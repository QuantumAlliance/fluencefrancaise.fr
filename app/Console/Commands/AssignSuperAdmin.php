<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AssignSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-super-admin {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign super admin role to a user by email and password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Check if user already exists
        $user = User::where('email', $email)->first();

        if ($user) {
            // Update existing user
            $user->update([
                'user_type' => 'super_admin',
                'password' => Hash::make($password),
            ]);

            $this->info("User with email {$email} has been updated to super admin.");
        } else {
            // Create new user
            $username = explode('@', $email)[0] . '_' . time();
            $nameParts = explode('@', $email)[0];
            $firstName = explode('.', $nameParts)[0] ?? 'Admin';
            $lastName = explode('.', $nameParts)[1] ?? 'User';

            $user = User::create([
                'name' => ucfirst($firstName) . ' ' . ucfirst($lastName),
                'first_name' => ucfirst($firstName),
                'last_name' => ucfirst($lastName),
                'username' => $username,
                'email' => $email,
                'password' => Hash::make($password),
                'user_type' => 'super_admin',
            ]);

            $this->info("New super admin user created successfully!");
        }

        $this->line("Email: {$email}");
        $this->line("User Type: super_admin");
        $this->line("User ID: {$user->id}");

        return 0;
    }
}

