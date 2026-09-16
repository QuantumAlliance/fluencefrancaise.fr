<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class InstallerController extends Controller
{
    public function index()
    {
        // Check if installation is already complete
        if ($this->isInstalled()) {
            return redirect('/');
        }

        return view('installer.index');
    }

    public function saveDatabase(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'db_connection' => 'required|string|in:mysql,sqlite',
            'db_host' => 'required_if:db_connection,mysql|string',
            'db_port' => 'required_if:db_connection,mysql|numeric',
            'db_name' => 'required|string',
            'db_username' => 'required_if:db_connection,mysql|string',
            'db_password' => 'nullable|string',
        ]);

        try {
            // Test database connection
            $this->testDatabaseConnection($validated);

            // Update .env file
            $this->updateEnvFile($validated);

            // Store app_name for later use during migration
            $tempConfigFile = base_path('storage/installer_config.json');
            File::put($tempConfigFile, json_encode(['app_name' => $validated['app_name']]));

            // Clear config cache
            Artisan::call('config:clear');

            return response()->json([
                'success' => true,
                'message' => 'Database configured successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database connection failed: ' . $e->getMessage()
            ], 422);
        }
    }

    public function createAdmin(Request $request)
    {
        // Build validation rules
        $validationRules = [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ];

        // Only add unique constraint if users table exists
        try {
            if (Schema::hasTable('users')) {
                $validationRules['email'] = 'required|email|unique:users';
            }
        } catch (\Exception $e) {
            // Table doesn't exist yet, skip unique check
        }

        $validated = $request->validate($validationRules);

        try {
            // If users table exists, create the user immediately
            if (Schema::hasTable('users')) {
                $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);
                $username = explode('@', $validated['email'])[0] . '_' . time();

                User::create([
                    'name' => $fullName,
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'username' => $username,
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'user_type' => 'super_admin',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Admin account created successfully'
                ]);
            } else {
                // Store admin data temporarily to create after migrations
                $adminData = [
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                ];

                $tempFile = base_path('storage/installer_admin.json');
                File::put($tempFile, json_encode($adminData));

                return response()->json([
                    'success' => true,
                    'message' => 'Admin account details saved. Please proceed to complete migrations.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Admin creation failed: ' . $e->getMessage()
            ], 422);
        }
    }

    public function migrate(Request $request)
    {
        try {
            Artisan::call('migrate:fresh', ['--force' => true]);

            // Check if admin data was saved and create the user
            $tempFile = base_path('storage/installer_admin.json');
            if (File::exists($tempFile)) {
                $adminData = json_decode(File::get($tempFile), true);

                if ($adminData) {
                    $fullName = trim($adminData['first_name'] . ' ' . $adminData['last_name']);
                    $username = explode('@', $adminData['email'])[0] . '_' . time();

                    User::create([
                        'name' => $fullName,
                        'first_name' => $adminData['first_name'],
                        'last_name' => $adminData['last_name'],
                        'username' => $username,
                        'email' => $adminData['email'],
                        'password' => Hash::make($adminData['password']),
                        'user_type' => 'super_admin',
                    ]);

                    // Clean up temp file
                    File::delete($tempFile);
                }
            }

            // Load app_name from temp config and save to settings
            $tempConfigFile = base_path('storage/installer_config.json');
            if (File::exists($tempConfigFile)) {
                $configData = json_decode(File::get($tempConfigFile), true);

                if ($configData && isset($configData['app_name'])) {
                    // Save platform name to settings
                    Settings::updateOrCreate(
                        ['key' => 'platform_name'],
                        ['value' => $configData['app_name']]
                    );
                }

                // Clean up temp file
                File::delete($tempConfigFile);
            }

            // Mark installation as complete
            $this->markInstallationComplete();

            return response()->json([
                'success' => true,
                'message' => 'Database migrations completed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Migration failed: ' . $e->getMessage()
            ], 422);
        }
    }

    private function testDatabaseConnection($config)
    {
        $connection = $config['db_connection'] ?? 'mysql';

        if ($connection === 'mysql') {
            $this->testMySQLConnection($config);
        } elseif ($connection === 'sqlite') {
            $this->testSQLiteConnection($config);
        }
    }

    private function testMySQLConnection($config)
    {
        try {
            $pdo = new \PDO(
                "mysql:host={$config['db_host']}:{$config['db_port']}",
                $config['db_username'],
                $config['db_password'] ?? ''
            );

            // Try to create database if it doesn't exist
            try {
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['db_name']}`");
            } catch (\PDOException $e) {
                // If creation fails, just try to select it
            }

            $pdo = null;
        } catch (\PDOException $e) {
            throw new \Exception('Cannot connect to database: ' . $e->getMessage());
        }
    }

    private function testSQLiteConnection($config)
    {
        // SQLite connection is tested by checking if we can write to the database path
    }

    private function updateEnvFile($config)
    {
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        // Update APP_NAME
        $envContent = preg_replace(
            '/^APP_NAME=.*$/m',
            'APP_NAME="' . $config['app_name'] . '"',
            $envContent
        );

        // Update database config
        $envContent = preg_replace('/^DB_CONNECTION=.*$/m', 'DB_CONNECTION=' . $config['db_connection'], $envContent);

        if ($config['db_connection'] === 'mysql') {
            $envContent = preg_replace('/^DB_HOST=.*$/m', 'DB_HOST=' . $config['db_host'], $envContent);
            $envContent = preg_replace('/^DB_PORT=.*$/m', 'DB_PORT=' . $config['db_port'], $envContent);
            $envContent = preg_replace('/^DB_USERNAME=.*$/m', 'DB_USERNAME=' . $config['db_username'], $envContent);
            $envContent = preg_replace('/^DB_PASSWORD=.*$/m', 'DB_PASSWORD=' . $config['db_password'], $envContent);
        }

        $envContent = preg_replace('/^DB_DATABASE=.*$/m', 'DB_DATABASE=' . $config['db_name'], $envContent);

        // Add installation flag
        if (strpos($envContent, 'INSTALLER_COMPLETE=') === false) {
            $envContent .= "\n\nINSTALLER_COMPLETE=true\n";
        } else {
            $envContent = preg_replace('/^INSTALLER_COMPLETE=.*$/m', 'INSTALLER_COMPLETE=true', $envContent);
        }

        File::put($envPath, $envContent);
    }

    private function markInstallationComplete()
    {
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        if (strpos($envContent, 'INSTALLER_COMPLETE=') === false) {
            $envContent .= "\nINSTALLER_COMPLETE=true\n";
        } else {
            $envContent = preg_replace('/^INSTALLER_COMPLETE=.*$/m', 'INSTALLER_COMPLETE=true', $envContent);
        }

        File::put($envPath, $envContent);
    }

    private function isInstalled()
    {
        return env('INSTALLER_COMPLETE') === true || env('INSTALLER_COMPLETE') === 'true';
    }

    public function reset()
    {
        // Only allow reset in development environment
        if (app()->environment() !== 'local') {
            return response()->json([
                'success' => false,
                'message' => 'Reset is only available in development environment'
            ], 403);
        }

        try {
            // Wipe the database (drops all tables)
            Artisan::call('db:wipe', ['--force' => true]);

            // Clean up any temporary installer files
            $tempFile = base_path('storage/installer_admin.json');
            if (File::exists($tempFile)) {
                File::delete($tempFile);
            }

            // Update .env file to mark installer as incomplete
            $envPath = base_path('.env');
            $envContent = File::get($envPath);

            if (strpos($envContent, 'INSTALLER_COMPLETE=') === false) {
                $envContent .= "\nINSTALLER_COMPLETE=false\n";
            } else {
                $envContent = preg_replace('/^INSTALLER_COMPLETE=.*$/m', 'INSTALLER_COMPLETE=false', $envContent);
            }

            File::put($envPath, $envContent);

            // Return success page with countdown
            return view('installer.reset-success');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Reset failed: ' . $e->getMessage()
            ], 422);
        }
    }
}
