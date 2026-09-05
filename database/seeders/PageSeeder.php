<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Routes resolve CMS pages by slug + status = 'published' (see the
     * Page::where('slug', ...)->where('status', 'published') lookups). Views read
     * $pageData['name'] and $pageData['seo_title'].
     */
    public function run(): void
    {
        $pages = [
            ['slug' => 'books', 'name' => 'Books', 'seo_title' => 'French Learning Books'],
            ['slug' => 'about-us', 'name' => 'About Us', 'seo_title' => 'About Fluence Française'],
            ['slug' => 'contact-us', 'name' => 'Contact Us', 'seo_title' => 'Contact Fluence Française'],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page + [
                    'status' => 'published',
                    'content' => '<p>Placeholder content for ' . e($page['name']) . '.</p>',
                    'meta_description' => $page['name'] . ' — Fluence Française.',
                    'no_index' => true,
                    'no_follow' => true,
                ]
            );
        }
    }
}
