<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $ogType ?? 'website' }}" />
    <meta property="og:url" content="{{ $ogUrl ?? url()->current() }}" />
    <meta property="og:title" content="{{ $ogTitle ?? '' }}" />
    <meta property="og:description" content="{{ $ogDescription ?? '' }}" />
    <meta property="og:site_name" content="{{ $ogSiteName ?? 'FocusFrame' }}" />
    <meta property="og:locale" content="en_US" />
    @if(isset($ogImage) && $ogImage)<meta property="og:image" content="{{ $ogImage }}" />
    @endif
@if(isset($updatedAt) && $updatedAt)<meta property="og:updated_time" content="{{ $updatedAt }}" />
    @endif
@if(isset($publishedAt) && $publishedAt)<meta property="article:published_time" content="{{ $publishedAt }}" />
    @endif
@if(isset($updatedAt) && $updatedAt && ($ogType ?? 'website') === 'article')<meta property="article:modified_time" content="{{ $updatedAt }}" />
    @endif
<!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ $ogUrl ?? url()->current() }}" />
    <meta name="twitter:title" content="{{ $ogTitle ?? '' }}" />
    <meta name="twitter:description" content="{{ $ogDescription ?? '' }}" />
    @if(isset($ogImage) && $ogImage)<meta name="twitter:image" content="{{ $ogImage }}" />
    @endif
@if(isset($readTime))<meta name="twitter:label1" content="Time to read" />
    <meta name="twitter:data1" content="{{ $readTime }}" />
    @endif
