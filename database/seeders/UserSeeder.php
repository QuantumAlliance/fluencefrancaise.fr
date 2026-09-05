<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Local-only accounts. Password for every one is "password".
     *
     * user_type values match those checked in AdminController: student, tutor,
     * admin, super_admin. The password cast on the User model is 'hashed', so the
     * plain string below is hashed on save.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'username' => 'admin',
                'email' => 'admin@fluencefrancaise.local',
                'user_type' => 'super_admin',
                'payment_confirmed' => true,
            ],
            [
                'name' => 'Tutor User',
                'first_name' => 'Tutor',
                'last_name' => 'User',
                'username' => 'tutor',
                'email' => 'tutor@fluencefrancaise.local',
                'user_type' => 'tutor',
                'title' => 'Professeure de français',
                'biography' => 'Teaches A1 through B2, DELF and TCF preparation.',
                'payment_confirmed' => true,
            ],
            [
                'name' => 'Student User',
                'first_name' => 'Student',
                'last_name' => 'User',
                'username' => 'student',
                'email' => 'student@fluencefrancaise.local',
                'user_type' => 'student',
                'payment_confirmed' => true,
            ],
        ];

        foreach ($users as $attributes) {
            User::updateOrCreate(
                ['email' => $attributes['email']],
                $attributes + [
                    'password' => 'password',
                    'timezone' => 'Europe/Paris',
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
