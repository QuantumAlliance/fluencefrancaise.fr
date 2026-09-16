<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Settings;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        // Find page by slug
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Get site settings for layout
        $siteName = Settings::where('key', 'site_name')->value('value') ?? 'FocusFrame';
        $siteUrl = Settings::where('key', 'site_url')->value('value') ?? '';

        // Generate structured data for the page
        $schemaService = new \App\Services\SchemaService();
        $schemaType = $page->schema_type ?? 'article';
        
        $structuredData = $schemaService->generateScript($schemaType, [
            'title' => $page->seo_title ?? $page->name,
            'name' => $page->name,
            'description' => $page->meta_description ?? '',
            'url' => url($page->slug),
            'datePublished' => $page->created_at ? $page->created_at->toIso8601String() : null,
            'dateModified' => $page->updated_at ? $page->updated_at->toIso8601String() : null,
        ]);

        return view('pages.show', compact('page', 'siteName', 'siteUrl', 'structuredData'));
    }
}
