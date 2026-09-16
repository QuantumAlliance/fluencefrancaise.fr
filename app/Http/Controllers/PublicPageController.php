<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Settings;
use Illuminate\Support\Facades\View;

class PublicPageController extends Controller
{
    public function show($slug = null)
    {
        // 1. Construct the DB slug format
        $dbSlug = '/' . ($slug ?? '');
        
        // 2. Try to find the page
        $page = Page::where('slug', $dbSlug)
                    ->where('status', 'published')
                    ->first();

        // 3. If found, render the server-side Blade view
        if ($page) {
            // Generate structured data using graph-based schema
            $schemaService = new \App\Services\SchemaService();
            
            // Determine if it's an Article or WebPage
            $schemaType = $page->schema_type ?? 'webpage';
            $pageType = ($schemaType === 'article') ? 'Article' : 'WebPage';
            
            $graphData = $schemaService->generateArticleGraph([
                'type' => $pageType,
                'name' => $page->name,
                'title' => $page->seo_title,
                'description' => $page->meta_description,
                'url' => url($page->slug),
                'datePublished' => $page->created_at ? $page->created_at->toIso8601String() : null,
                'dateModified' => $page->updated_at ? $page->updated_at->toIso8601String() : null,
            ]);
            
            $structuredData = '<script type="application/ld+json">' . json_encode($graphData, JSON_UNESCAPED_SLASHES) . '</script>';

            $settings = Settings::pluck('value', 'key')->toArray();
            $siteName = $settings['site_name'] ?? 'FocusFrame';
            
            // Calculate read time
            $wordCount = str_word_count(strip_tags($page->content));
            $minutes = ceil($wordCount / 200);
            $readTime = $minutes < 1 ? 'Less than a minute' : $minutes . ' min read';

            return view('pages.show', compact('page', 'settings', 'siteName', 'structuredData', 'readTime'));
        }

        // 4. Handle Books page specifically for SSR/SEO
        if ($slug === 'books') {
            $books = \App\Models\Book::where('is_active', true)->orderBy('created_at', 'desc')->get();
            $settings = Settings::pluck('value', 'key')->toArray();
            
            $pageData = [
                'name' => 'Our Books',
                'seo_title' => $settings['books_page_seo_title'] ?? 'Our Books',
                'meta_description' => $settings['books_page_meta_description'] ?? 'Explore our collection of books and educational resources.',
                'no_index' => ($settings['books_page_no_index'] ?? '0') === '1',
                'no_follow' => ($settings['books_page_no_follow'] ?? '0') === '1',
            ];
            
            return view('books', compact('books', 'settings', 'pageData'));
        }

        // 5. If homepage (no slug) and no page exists, render homepage Blade view
        if (empty($slug)) {
            $settings = Settings::pluck('value', 'key')->toArray();
            return view('homepage', compact('settings'));
        }

        // 6. Fallback: Render the Vue SPA (for SPA routes like login, register, books, etc.)
        return view('app');
    }
}
