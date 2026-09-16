<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Keys read by resources/views — see app.blade.php and partials/footer,
     * custom-scripts-head, custom-scripts-body. Every view guards with ?? so
     * missing keys degrade gracefully; these are seeded so the local site shows
     * real content rather than fallbacks.
     */
    public function run(): void
    {
        $footer = [
            'contact' => [
                'email' => 'contact@fluencefrancaise.com',
                'phone' => '+33 1 23 45 67 89',
                'address' => 'Online · Based in France',
            ],
            'social' => [
                'facebook' => '#',
                'twitter' => '#',
                'instagram' => '#',
                'linkedin' => '#',
            ],
            'copyrightText' => '© {year} {siteName}. All rights reserved.',
        ];

        $settings = [
            'site_name' => 'Fluence Française',
            'site_url' => 'http://localhost:5174',
            // The one real logo present in storage/app/public/settings.
            'site_logo' => 'settings/oA4patNOsexTSpMnWZOkXRru0ibIW0x5LybGnMim.png',
            'robots' => 'noindex, nofollow',
            'footer_settings' => json_encode($footer),
            // Empty array, not null: the partials json_decode() this value.
            'custom_scripts' => json_encode([]),
            'student_portal_maintenance_mode' => '0',
            'student_portal_maintenance_message' => 'The student portal is briefly offline for maintenance.',
        ];

        foreach ($settings as $key => $value) {
            Settings::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
