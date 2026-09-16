<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\WordPressPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login - POST /api/auth/login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Get raw passwords from database to bypass Eloquent 'hashed' cast
        // Check wordpress_password column first (where WordPress passwords are stored)
        // Then fall back to password column (for Laravel passwords)
        $passwordData = \Illuminate\Support\Facades\DB::table('users')
            ->where('id', $user->id)
            ->select('password', 'wordpress_password')
            ->first();

        $wordPressPasswordHash = $passwordData->wordpress_password ?? null;
        $laravelPasswordHash = $passwordData->password ?? null;

        // Check password - handle both Laravel and WordPress formats
        $passwordValid = false;
        
        \Log::info('Login attempt', [
            'email' => $validated['email'],
            'has_wordpress_password' => !empty($wordPressPasswordHash),
            'has_laravel_password' => !empty($laravelPasswordHash),
        ]);
        
        // First, try WordPress password if it exists
        if (!empty($wordPressPasswordHash)) {
            \Log::info('Checking WordPress password', [
                'hash_prefix' => substr($wordPressPasswordHash, 0, 30) . '...',
                'hash_length' => strlen($wordPressPasswordHash)
            ]);
            
            if (WordPressPassword::verify($validated['password'], $wordPressPasswordHash)) {
                $passwordValid = true;
                \Log::info('WordPress password verified successfully');
                // Optionally convert to Laravel format on successful login for future logins
                // Use DB::table to bypass Eloquent cast
                \Illuminate\Support\Facades\DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'password' => Hash::make($validated['password']),
                        'wordpress_password' => null // Clear WordPress password after conversion
                    ]);
                $user->refresh();
            } else {
                \Log::warning('WordPress password verification failed', [
                    'email' => $validated['email'],
                    'hash_prefix' => substr($wordPressPasswordHash, 0, 30) . '...',
                    'password_length' => strlen($validated['password'])
                ]);
            }
        }
        
        // If WordPress password check failed or doesn't exist, try Laravel password
        if (!$passwordValid && !empty($laravelPasswordHash)) {
            $isWordPressFormat = WordPressPassword::isWordPressFormat($laravelPasswordHash);
            
            // Only check Laravel password if it's not WordPress format
            // (WordPress format in password column means it was stored incorrectly)
            if (!$isWordPressFormat) {
                try {
                    if (Hash::check($validated['password'], $laravelPasswordHash)) {
                        $passwordValid = true;
                        \Log::info('Laravel password verified successfully');
                    }
                } catch (\Exception $e) {
                    \Log::warning('Hash::check failed, trying password_verify', ['error' => $e->getMessage()]);
                    // Fallback to password_verify
                    if (password_verify($validated['password'], $laravelPasswordHash)) {
                        $passwordValid = true;
                        \Log::info('Laravel password verified with password_verify');
                    }
                }
            } else {
                // If password column has WordPress format, try to verify it
                // This handles legacy imports that stored WordPress passwords in password column
                \Log::info('Found WordPress format in password column, attempting verification');
                if (WordPressPassword::verify($validated['password'], $laravelPasswordHash)) {
                    $passwordValid = true;
                    \Log::info('WordPress password from password column verified successfully');
                    // Convert to Laravel format
                    \Illuminate\Support\Facades\DB::table('users')
                        ->where('id', $user->id)
                        ->update(['password' => Hash::make($validated['password'])]);
                    $user->refresh();
                }
            }
        }

        if (!$passwordValid) {
            // Provide more helpful error message
            $hasWordPressPassword = !empty($wordPressPasswordHash);
            $errorMessage = 'The provided credentials are incorrect.';

            if ($hasWordPressPassword) {
                $errorMessage .= ' Please use your WordPress password. If you have forgotten it, please contact support to reset your password.';
            }

            \Log::warning('Login failed', [
                'email' => $validated['email'],
                'has_wordpress_password' => $hasWordPressPassword,
                'has_laravel_password' => !empty($laravelPasswordHash)
            ]);

            throw ValidationException::withMessages([
                'email' => [$errorMessage],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // Also log the user into Laravel's session for SSR pages (homepage, etc.)
        Auth::guard('web')->login($user, true);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'data' => ['user' => $user, 'token' => $token],
            'message' => 'Login successful',
        ]);
    }

    /**
     * Register - POST /api/auth/register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Generate username from email (without domain)
        $username = explode('@', $validated['email'])[0] . '_' . time();

        // Create full name from first_name and last_name for the 'name' field
        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

        $user = User::create([
            'name' => $fullName, // Required field in users table
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => 'student', // Default role for new registration
        ]);

        // Don't send welcome email here - it will be sent after payment success
        // Registration form includes enrollment + payment, so email will be sent after purchase

        $token = $user->createToken('auth_token')->plainTextToken;

        // Also log the user into Laravel's session for SSR pages (homepage, etc.)
        Auth::guard('web')->login($user, true);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'data' => ['user' => $user, 'token' => $token],
            'message' => 'Registration successful',
        ], 201);
    }

    /**
     * Get current user - GET /api/auth/me
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
            'message' => 'Current user',
        ]);
    }

    /**
     * Logout - POST /api/auth/logout
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user && method_exists($user, 'currentAccessToken')) {
            $token = $user->currentAccessToken();
            if ($token) {
                $token->delete();
            }
        }

        // Also log out from Laravel's session
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }

    /**
     * Forgot password - POST /api/auth/forgot-password
     */
    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            $user = User::where('email', $validated['email'])->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            // Generate password reset token
            $token = \Illuminate\Support\Str::random(64);
            
            // Store token in database
            \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => \Illuminate\Support\Facades\Hash::make($token),
                    'created_at' => now(),
                ]
            );

            // Build reset URL
            $frontendUrl = rtrim(config('app.url'), '/');
            $resetUrl = $frontendUrl . '/reset-password?token=' . $token . '&email=' . urlencode($user->email);

            // Try to send via Google OAuth first
            $emailSent = false;
            $appName = \App\Models\Settings::where('key', 'site_name')->value('value') ?? config('app.name', 'FocusFrame');

            // Check if Google OAuth is configured
            $googleClientId = env('GOOGLE_CLIENT_ID') ?: \App\Models\Settings::where('key', 'google_client_id')->value('value');
            if ($googleClientId) {
                $emailBody = "
                    <html><body>
                    <p>Hello {$user->name},</p>
                    <p>You requested to reset your password. Click the link below to reset it:</p>
                    <p><a href=\"{$resetUrl}\" style=\"background-color: #cb8e4f; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;\">Reset Password</a></p>
                    <p>Or copy and paste this URL into your browser:</p>
                    <p>{$resetUrl}</p>
                    <p>This link will expire in 10 minutes.</p>
                    <p>If you did not request a password reset, please ignore this email.</p>
                    </body></html>";

                $emailSent = \App\Services\GoogleMailService::sendEmail(
                    $user->email, 
                    "{$appName} - Reset Your Password", 
                    $emailBody
                );
            }

            if ($emailSent) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset link sent to your email address.',
                ]);
            }

            // Fallback to SMTP if Google OAuth not available or failed
            if (!$emailSent) {
                // Configure SMTP settings dynamically from .env
                $envPath = base_path('.env');
                $envContent = File::get($envPath);
                
                $smtpHost = '';
                $smtpPort = 587;
                $smtpUsername = '';
                $smtpPassword = '';
                $fromEmail = '';
                $fromName = '';
                $encryption = 'tls';
                
                // Try custom MAIL_SMTP_* variables first
                if (preg_match('/^MAIL_SMTP_HOST=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $smtpHost = trim($matches[1], ' "\'');
                }
                if (preg_match('/^MAIL_SMTP_PORT=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $smtpPort = (int)trim($matches[1], ' "\'') ?: 587;
                }
                if (preg_match('/^MAIL_SMTP_USERNAME=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $smtpUsername = trim($matches[1], ' "\'');
                }
                if (preg_match('/^MAIL_SMTP_PASSWORD=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $smtpPassword = trim($matches[1], ' "\'');
                }
                
                // Auto-detect Gmail SMTP if port 587 and credentials exist
                if (!$smtpHost && $smtpPort == 587 && !empty($smtpUsername) && !empty($smtpPassword)) {
                    $smtpHost = 'smtp.gmail.com';
                }
                
                if (preg_match('/^MAIL_FROM_ADDRESS=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $fromEmail = trim($matches[1], ' "\'');
                }
                if (preg_match('/^MAIL_FROM_NAME=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $fromName = trim($matches[1], ' "\'');
                }
                
                if ($smtpHost && $smtpUsername) {
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.host' => $smtpHost,
                        'mail.mailers.smtp.port' => $smtpPort,
                        'mail.mailers.smtp.username' => $smtpUsername,
                        'mail.mailers.smtp.password' => $smtpPassword,
                        'mail.mailers.smtp.encryption' => $smtpPort == 465 ? 'ssl' : 'tls',
                        'mail.from.address' => $fromEmail ?: $smtpUsername,
                        'mail.from.name' => $fromName ?: config('app.name', 'Laravel'),
                    ]);
                }
                
                // Use Laravel's Password facade to send reset email
                $status = Password::sendResetLink(
                    $request->only('email')
                );

                if ($status === Password::RESET_LINK_SENT) {
        return response()->json([
            'success' => true,
                        'message' => 'Password reset link sent to your email address.',
        ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unable to send password reset link. Please check your email configuration.',
                    ], 500);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Password reset failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send reset link: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reset password - POST /api/auth/reset-password
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            // Check if token exists and is valid
            $passwordReset = DB::table('password_reset_tokens')
                ->where('email', $validated['email'])
                ->first();

            if (!$passwordReset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired reset token.',
                ], 400);
            }

            // Check if token is expired (10 minutes)
            $createdAt = \Carbon\Carbon::parse($passwordReset->created_at);
            if (now()->diffInMinutes($createdAt) > 10) {
                DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'Reset token has expired. Please request a new password reset.',
                ], 400);
            }

            // Verify the token
            if (!Hash::check($validated['token'], $passwordReset->token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid reset token.',
                ], 400);
            }

            // Find user and update password
            $user = User::where('email', $validated['email'])->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            // Update password
            $user->password = Hash::make($validated['password']);
            $user->wordpress_password = null; // Clear WordPress password after reset
            $user->save();

            // Delete the reset token
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

            return response()->json([
                'success' => true,
                'message' => 'Password has been reset successfully.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Password reset failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password: ' . $e->getMessage(),
            ], 500);
        }
    }
}
