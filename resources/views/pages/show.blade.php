<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta Tags -->
    <title>{{ $page->seo_title ?? $page->name }} - {{ $siteName }}</title>
    @if($page->meta_description)
    <meta name="description" content="{{ $page->meta_description }}">
    @endif
    <!-- Social Media Meta Tags -->
    @include('partials.social-meta', [
        'ogType' => ($page->schema_type ?? 'article') === 'webpage' ? 'website' : 'article',
        'ogUrl' => url($page->slug),
        'ogTitle' => ($page->seo_title ?? $page->name) . ' - ' . ($siteName ?? 'FocusFrame'),
        'ogDescription' => $page->meta_description,
        'ogSiteName' => $siteName ?? 'FocusFrame',
        'ogImage' => isset($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : null,
        'publishedAt' => $page->created_at ? $page->created_at->toIso8601String() : null,
        'updatedAt' => $page->updated_at ? $page->updated_at->toIso8601String() : null,
        'readTime' => $readTime ?? null,
    ])
    <!-- Robots Meta Tags -->
    <meta name="robots" content="{{ $page->no_index ? 'noindex' : 'index' }}, {{ $page->no_follow ? 'nofollow' : 'follow' }}">
    <!-- Schema.org Structured Data for Rich Snippets -->
    @if(isset($structuredData) && $structuredData)
    {!! $structuredData !!}
    @endif

    @include('partials.favicon')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .page-content p {
            margin-bottom: 1rem;
            line-height: 1.75;
        }
        .page-content br + br {
            display: block;
            content: "";
            margin-top: 1rem;
        }
        .page-content h2 {
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .page-content h3 {
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }
        .page-content ul, .page-content ol {
            margin-bottom: 1rem;
        }
        .page-content img {
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
    </style>

    @php
        $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    @endphp
    @if(isset($settings['custom_scripts']))
        @php
            $scripts = json_decode($settings['custom_scripts'], true);
            $hasHeadScripts = false;
            if (is_array($scripts)) {
                foreach ($scripts as $script) {
                    $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                    $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                    if (!empty(trim($scriptCode)) && $placement === 'head') {
                        $hasHeadScripts = true;
                        break;
                    }
                }
            }
        @endphp
        @if($hasHeadScripts)
            <!-- Custom Scripts - Head -->
            @php
                foreach ($scripts as $script) {
                    $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                    $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                    if (!empty(trim($scriptCode)) && $placement === 'head') {
                        // Check if script already has <script> tags
                        $trimmedCode = trim($scriptCode);
                        if (stripos($trimmedCode, '<script') === 0) {
                            echo $scriptCode;
                        } else {
                            echo '<script>' . "\n" . $scriptCode . "\n" . '</script>';
                        }
                    }
                }
            @endphp
        @endif
    @endif
</head>
<body class="antialiased bg-gray-50 px-0">
    @if(isset($settings['custom_scripts']))
        @php
            $scripts = json_decode($settings['custom_scripts'], true);
            $hasBodyScripts = false;
            if (is_array($scripts)) {
                foreach ($scripts as $script) {
                    $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                    $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                    if (!empty(trim($scriptCode)) && $placement === 'body') {
                        $hasBodyScripts = true;
                        break;
                    }
                }
            }
        @endphp
        @if($hasBodyScripts)
            <!-- Custom Scripts - Body -->
            @php
                foreach ($scripts as $script) {
                    $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                    $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                    if (!empty(trim($scriptCode)) && $placement === 'body') {
                        // Check if script already has <script> tags
                        $trimmedCode = trim($scriptCode);
                        if (stripos($trimmedCode, '<script') === 0) {
                            echo $scriptCode;
                        } else {
                            echo '<script>' . "\n" . $scriptCode . "\n" . '</script>';
                        }
                    }
                }
            @endphp
        @endif
    @endif
    <!-- Header -->
    @include('partials.header')

    <!-- Page Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-44 pb-12">
        <article class="bg-white rounded-lg shadow-sm p-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-6">{{ $page->name }}</h1>

            <div class="text-gray-600 mb-8">
                Last updated: {{ $page->updated_at->format('F j, Y') }}
            </div>

            <div class="prose prose-lg max-w-none page-content">
                {!! $page->content !!}
            </div>
        </article>
    </main>

    <!-- Footer -->
    @include('partials.footer')
</body>
</html>
