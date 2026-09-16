<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Fluence Française - French Learning Platform. Learn French with our comprehensive courses, books, and educational resources.">
        <meta name="keywords" content="French learning, French courses, French books, language learning, education">
        @if(auth()->check() || request()->is('login') || request()->is('register') || request()->is('forgot-password') || request()->is('reset-password') || request()->is('admin*') || request()->is('student*') || request()->is('tutor*') || request()->is('payment*'))
        <meta name="robots" content="noindex, follow">
        @else
        <meta name="robots" content="index, follow">
        @endif
        <meta name="author" content="Fluence Française">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ \App\Models\Settings::where('key', 'site_name')->value('value') ?? 'Fluence Française' }}">
        
        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@fluencefrancaise">

        <title>{{ \App\Models\Settings::where('key', 'site_name')->value('value') ?? 'Fluence Française' }} - French Learning Platform</title>

        @include('partials.favicon')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Urbanist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @vite(['resources/js/app.js', 'resources/css/app.css'])

        @php
            $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
        @endphp
        @include('partials.custom-scripts-head')
    </head>
    <body class="antialiased bg-gray-50">
        @include('partials.custom-scripts-body')
        <div id="app">
            <!-- Initial loading spinner - shown until Vue app mounts -->
            <div id="initial-loader" style="display: flex; justify-content: center; align-items: center; min-height: 100vh; flex-direction: column; gap: 16px;">
                <div style="width: 48px; height: 48px; border: 4px solid #e5e7eb; border-top-color: #0055A4; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <p style="color: #6b7280; font-size: 14px;">Loading...</p>
            </div>
            <style>
                @keyframes spin {
                    to { transform: rotate(360deg); }
                }
            </style>
        </div>
    </body>
</html>
