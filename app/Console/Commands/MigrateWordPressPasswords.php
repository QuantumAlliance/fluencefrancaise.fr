<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Utils\WordPressPassword;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateWordPressPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wordpress:migrate-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate WordPress passwords from password column to wordpress_password column';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting WordPress password migration...');

        // Get all users
        $users = User::all();
        $migrated = 0;
        $skipped = 0;

        foreach ($users as $user) {
            // Get raw password from database
            $passwordHash = DB::table('users')
                ->where('id', $user->id)
                ->value('password');

            // Check if it's WordPress format
            if (!empty($passwordHash) && WordPressPassword::isWordPressFormat($passwordHash)) {
                // Check if wordpress_password is already set
                $wpPassword = DB::table('users')
                    ->where('id', $user->id)
                    ->value('wordpress_password');

                if (empty($wpPassword)) {
                    // Migrate to wordpress_password column
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['wordpress_password' => $passwordHash]);

                    $migrated++;
                    $this->line("Migrated password for user: {$user->email}");
                } else {
                    $skipped++;
                    $this->line("Skipped user {$user->email} - wordpress_password already set");
                }
            } else {
                $skipped++;
            }
        }

        $this->info("Migration completed!");
        $this->info("Migrated: {$migrated} users");
        $this->info("Skipped: {$skipped} users");

        return 0;
    }
}

