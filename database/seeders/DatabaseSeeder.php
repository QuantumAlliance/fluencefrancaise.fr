<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Local development data only — see database/README.md. Every seeder uses
     * updateOrCreate keyed on a natural column, so re-running is idempotent and
     * will not duplicate rows.
     */
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            UserSeeder::class,
            CatalogueSeeder::class,
            PageSeeder::class,
        ]);
    }
}
