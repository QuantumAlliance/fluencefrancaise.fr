<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SetUserPassword extends Command
{
    protected $signature = 'user:set-password {email} {password}';
    protected $description = 'Set a user password for testing';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User not found: {$email}");
            return 1;
        }

        // Set both Laravel and WordPress passwords
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password' => Hash::make($password),
                'wordpress_password' => null
            ]);

        $this->info("Password set successfully for: {$email}");
        return 0;
    }
}

