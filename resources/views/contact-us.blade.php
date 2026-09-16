<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
        $footerData = isset($settings['footer_settings']) ? json_decode($settings['footer_settings'], true) : null;
        $contactEmail = $footerData['contact']['email'] ?? 'contact@fluencefrancaise.com';
        $contactPhone = $footerData['contact']['phone'] ?? '+33 1 23 45 67 89';
        $contactPhoneTel = preg_replace('/[^0-9+]/', '', $contactPhone);
        $contactAddress = $footerData['contact']['address'] ?? 'Online · Based in France';
    @endphp

    <!-- SEO Meta Tags -->
    <title>Contact Us — {{ ($settings['site_name'] ?? 'Fluence Française') }}</title>
    <meta name="description" content="Get in touch with Fluence Française. Ask about levels, pricing, the referral offer or how to start your DELF, DALF, TCF or TEF preparation.">
    <meta name="robots" content="{{ $settings['robots'] ?? 'index, follow' }}">

    <!-- Social Media Meta Tags -->
    @include('partials.social-meta', [
        'ogType' => 'website',
        'ogUrl' => url('/contact-us'),
        'ogTitle' => 'Contact Us — ' . ($settings['site_name'] ?? 'Fluence Française'),
        'ogDescription' => 'Get in touch with Fluence Française about levels, pricing and exam preparation.',
        'ogSiteName' => $settings['site_name'] ?? 'Fluence Française',
    ])

    @include('partials.favicon')
    @include('partials.theme')
    @include('partials.custom-scripts-head')
</head>
<body class="antialiased bg-white">
    @include('partials.custom-scripts-body')
    <div class="landing-page">
        @include('partials.header')

        <!-- ===== HERO ===== -->
        <section class="relative overflow-hidden bg-mist pt-32 md:pt-40 pb-14 md:pb-20">
            <div class="hero-aurora"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative text-center max-w-3xl">
                <span class="eyebrow justify-center reveal"><span class="tricolor"><i></i><i></i><i></i></span> Contact us</span>
                <h1 class="reveal mt-5 text-[2.6rem] leading-[1.05] md:text-6xl font-semibold text-ink tracking-tight">Let's talk about your <span class="swish text-primary-600">French</span></h1>
                <p class="reveal mt-6 text-lg text-slate-600 leading-relaxed">Questions about levels, pricing or the referral offer? Tell us your exam goal and current level — we'll help you choose where to start.</p>
            </div>
        </section>

        <!-- ===== CONTACT METHODS ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                <div class="grid md:grid-cols-3 gap-5">
                    <!-- Email -->
                    <a href="mailto:{{ $contactEmail }}" class="reveal card-glow group bg-mist rounded-4xl border border-slate-100 p-8 block">
                        <span class="w-14 h-14 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-xl group-hover:bg-primary-600 group-hover:text-white transition"><i class="fas fa-envelope"></i></span>
                        <h3 class="mt-6 text-xl font-bold text-ink">Email us</h3>
                        <p class="mt-2 text-sm text-slate-500">Our team is here to help.</p>
                        <p class="mt-4 text-primary-600 font-bold break-words">{{ $contactEmail }}</p>
                    </a>
                    <!-- Phone -->
                    <a href="tel:{{ $contactPhoneTel }}" class="reveal card-glow group bg-mist rounded-4xl border border-slate-100 p-8 block" style="transition-delay:.07s">
                        <span class="w-14 h-14 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-xl group-hover:bg-primary-600 group-hover:text-white transition"><i class="fas fa-phone"></i></span>
                        <h3 class="mt-6 text-xl font-bold text-ink">Call us</h3>
                        <p class="mt-2 text-sm text-slate-500">Mon–Fri, 9am to 6pm.</p>
                        <p class="mt-4 text-primary-600 font-bold">{{ $contactPhone }}</p>
                    </a>
                    <!-- Location / Enroll -->
                    <div class="reveal bg-[#002654] text-white rounded-4xl p-8 relative overflow-hidden" style="transition-delay:.14s">
                        <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                        <span class="w-14 h-14 rounded-2xl bg-white/10 text-white grid place-content-center text-xl"><i class="fas fa-graduation-cap"></i></span>
                        <h3 class="mt-6 text-xl font-bold">Ready to start?</h3>
                        <p class="mt-2 text-sm text-slate-300">Skip the wait and enroll online today.</p>
                        <a href="/register" class="btn btn-onnavy btn-md mt-5 w-full">Enroll Now</a>
                        <p class="mt-5 text-xs text-slate-400 flex items-center gap-2"><i class="fas fa-location-dot"></i> {{ $contactAddress }}</p>
                    </div>
                </div>

                <!-- reassurance row -->
                <div class="mt-10 grid sm:grid-cols-3 gap-4 reveal">
                    @php $points = [['fa-clock','Fast replies','We usually answer within one business day.'],['fa-comments','Real guidance','Speak with teachers, not a chatbot.'],['fa-gift','Referral offer','Ask how to get €50 off every month.']]; @endphp
                    @foreach($points as $p)
                    <div class="flex items-start gap-3">
                        <span class="w-10 h-10 rounded-xl bg-accent-soft text-[#EF4135] grid place-content-center shrink-0"><i class="fas {{ $p[0] }}"></i></span>
                        <div><p class="font-bold text-ink text-sm">{{ $p[1] }}</p><p class="text-sm text-slate-500">{{ $p[2] }}</p></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        @include('partials.footer')
    </div>
</body>
</html>
