<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Settings;

class GoogleMailService
{
    /**
     * Send email using Gmail API with automatic token refresh
     */
    public static function sendEmail($to, $subject, $body, $fromName = null, $fromEmail = null)
    {
        $accessToken = self::getAccessToken();
        
        if (!$accessToken) {
            Log::error('Google Mail Service: No access token available');
            return false;
        }

        $fromEmail = $fromEmail ?: env('GOOGLE_FROM_EMAIL') ?: Settings::where('key', 'google_from_email')->value('value');
        $fromName = $fromName ?: env('GOOGLE_FROM_NAME') ?: Settings::where('key', 'google_from_name')->value('value') ?: config('app.name');

        $emailMessage = "From: {$fromName} <{$fromEmail}>\r\n";
        $emailMessage .= "To: {$to}\r\n";
        $emailMessage .= "Subject: {$subject}\r\n";
        $emailMessage .= "Content-Type: text/html; charset=utf-8\r\n";
        $emailMessage .= "\r\n";
        $emailMessage .= $body;

        $rawMessage = base64_encode($emailMessage);
        $rawMessage = str_replace(['+', '/', '='], ['-', '_', ''], $rawMessage); // URL-safe base64

        $response = self::makeRequest($accessToken, $rawMessage);

        // If 401, refresh and retry
        if ($response->status() === 401) {
            Log::info('Google Mail Service: Token expired, attempting refresh...');
            $accessToken = self::refreshAccessToken();
            
            if ($accessToken) {
                $response = self::makeRequest($accessToken, $rawMessage);
            }
        }

        if ($response->successful()) {
            return true;
        }

        Log::error('Google Mail Service: Failed to send email', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);
        
        return false;
    }

    /**
     * Get access token from cache, database or env
     */
    public static function getAccessToken()
    {
        // Try Cache first (optional, but let's stick to DB/Env for consistency with existing code)
        $token = Settings::where('key', 'google_access_token')->value('value') ?: env('GOOGLE_ACCESS_TOKEN');
        return $token;
    }

    /**
     * Refresh the access token using refresh_token
     */
    public static function refreshAccessToken()
    {
        $clientId = env('GOOGLE_CLIENT_ID') ?: Settings::where('key', 'google_client_id')->value('value');
        $clientSecret = env('GOOGLE_CLIENT_SECRET') ?: Settings::where('key', 'google_client_secret')->value('value');
        $refreshToken = env('GOOGLE_REFRESH_TOKEN') ?: Settings::where('key', 'google_refresh_token')->value('value');

        if (!$clientId || !$clientSecret || !$refreshToken) {
            Log::error('Google Mail Service: Missing credentials for refresh', [
                'has_id' => !!$clientId,
                'has_secret' => !!$clientSecret,
                'has_refresh' => !!$refreshToken
            ]);
            return null;
        }

        $httpClient = config('app.env') === 'production' ? Http::asForm() : Http::withoutVerifying()->asForm();
        
        $response = $httpClient->post('https://oauth2.googleapis.com/token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $newAccessToken = $data['access_token'];

            // Update DB (Primary storage)
            Settings::updateOrCreate(['key' => 'google_access_token'], ['value' => $newAccessToken]);
            
            // Optional: Update .env (might be slow or cause restarts, but keeping it for now as user requested)
            self::updateEnv('GOOGLE_ACCESS_TOKEN', $newAccessToken);

            Log::info('Google Mail Service: Access token refreshed successfully');
            return $newAccessToken;
        }

        Log::error('Google Mail Service: Refresh failed', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);
        
        return null;
    }

    /**
     * Helper to make the Gmail API request
     */
    private static function makeRequest($token, $rawMessage)
    {
        $httpClient = config('app.env') === 'production' ? Http::withToken($token) : Http::withoutVerifying()->withToken($token);
        
        return $httpClient->post('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', [
            'raw' => $rawMessage,
        ]);
    }

    /**
     * Helper to update .env file
     */
    private static function updateEnv($key, $value)
    {
        try {
            $path = base_path('.env');
            if (!File::exists($path)) return;

            $content = File::get($path);
            $pattern = "/^{$key}=.*?(?:\r?\n|$)/m";
            $newline = (strpos($content, "\r\n") !== false) ? "\r\n" : "\n";
            
            $escapedValue = $value;
            if (preg_match('/[\s#"]/', $value)) {
                $escapedValue = '"' . str_replace(['"', '\\'], ['\"', '\\\\'], $value) . '"';
            }

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, "{$key}={$escapedValue}{$newline}", $content, 1);
            } else {
                if (substr($content, -1) !== "\n" && substr($content, -1) !== "\r\n") {
                    $content .= $newline;
                }
                $content .= "{$key}={$escapedValue}{$newline}";
            }

            File::put($path, $content);
            @\Artisan::call('config:clear');
        } catch (\Exception $e) {
            Log::warning('Google Mail Service: Failed to update .env', ['error' => $e->getMessage()]);
        }
    }
}
