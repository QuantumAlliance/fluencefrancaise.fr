<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- SEO Meta Tags -->
    <title>{{ $pageData['seo_title'] ?: ($pageData['name'] . ' | ' . ($settings['site_name'] ?? 'FocusFrame')) }}</title>
    @if($pageData['meta_description'])
    <meta name="description" content="{{ $pageData['meta_description'] }}">
    @endif
    @php
        $robots = [];
        $robots[] = $pageData['no_index'] ? 'noindex' : 'index';
        $robots[] = $pageData['no_follow'] ? 'nofollow' : 'follow';
        $robotsContent = implode(', ', $robots);
    @endphp
    <meta name="robots" content="{{ $robotsContent }}">

    <!-- Social Media Meta Tags -->
    @include('partials.social-meta', [
        'ogType' => 'website',
        'ogUrl' => url('/books'),
        'ogTitle' => $pageData['seo_title'] ?: ($pageData['name'] . ' | ' . ($settings['site_name'] ?? 'FocusFrame')),
        'ogDescription' => $pageData['meta_description'] ?? 'Explore our collection of books and resources.',
        'ogSiteName' => $settings['site_name'] ?? 'FocusFrame',
        'ogImage' => isset($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : null,
    ])

    <!-- Schema.org Structured Data -->
    @php
        $schemaService = new \App\Services\SchemaService();
        
        // Build book items for the collection
        $bookItems = [];
        foreach ($books as $index => $book) {
            $bookItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => [
                    '@type' => 'Book',
                    'name' => $book->title,
                    'image' => $book->cover_image ? asset('storage/' . $book->cover_image) : null,
                    'url' => url('/books'),
                ]
            ];
        }

        $graphData = $schemaService->generateCollectionGraph([
            'name' => $pageData['name'] ?? 'Our Books',
            'description' => $pageData['meta_description'] ?? 'Explore our collection of books and resources',
            'url' => url('/books'),
            'items' => $bookItems,
        ]);
    @endphp
    <script type="application/ld+json">
    {!! json_encode($graphData, JSON_UNESCAPED_SLASHES) !!}
    </script>

    @include('partials.favicon')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'brand': '#0055A4',
                        'brand-dark': '#003d7a',
                        'navy': '#002654',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <div id="app">
        <!-- Static Header -->
        @include('partials.header')

        <!-- Main Content -->
        <main class="pt-0 pb-16">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Page Header -->
                <div class="relative pt-40 pb-16 mb-12 rounded-3xl overflow-hidden bg-[#002654] text-white">
                    <div class="absolute inset-0 opacity-10">
                        <svg class="h-full w-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M0 0 L100 0 L100 100 L0 100 Z" />
                            <circle cx="20" cy="20" r="30" />
                            <circle cx="80" cy="80" r="40" />
                        </svg>
                    </div>
                    <div class="relative z-10 text-center px-4">
                        <h1 class="text-3xl md:text-4xl font-extrabold mb-6 tracking-tight">Our <span class="text-[#0055A4]">Curated</span> Books</h1>
                        <p class="text-xl text-gray-200 max-w-2xl mx-auto leading-relaxed">
                            Unlock a world of knowledge with our carefully selected collection of books and educational resources.
                        </p>
                    </div>
                </div>

                <!-- Books Grid -->
                @if(count($books) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($books as $book)
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden cursor-pointer transform hover:-translate-y-2">
                        <!-- Cover Image -->
                        <div class="aspect-[3/4] bg-gray-100 overflow-hidden relative">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 text-gray-400">
                                    <i class="fas fa-book text-6xl opacity-20"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-500 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <span class="bg-white text-gray-900 px-6 py-2 rounded-full font-bold shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                    Quick View
                                </span>
                            </div>
                        </div>

                        <!-- Book Info -->
                        <div class="p-6">
                            <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2 leading-tight group-hover:text-[#0055A4] transition-colors">
                                {{ $book->title }}
                            </h3>
                            <div class="w-10 h-1 bg-[#0055A4]/30 rounded-full group-hover:w-full transition-all duration-500"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="text-gray-500 text-lg mb-2">No books found</p>
                    <p class="text-gray-400 text-sm">We'll be adding more books soon!</p>
                </div>
                @endif
            </div>
        </main>

        <!-- Static Footer -->
        <!-- Static Footer -->
        @include('partials.footer')
    </div>
</body>
</html>
