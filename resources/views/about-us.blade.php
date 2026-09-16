<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    @endphp

    <!-- SEO Meta Tags -->
    <title>About Us — {{ ($settings['site_name'] ?? 'Fluence Française') }}</title>
    <meta name="description" content="Fluence Française is built for serious learners preparing for DELF, DALF, TCF and TEF — structured, practical, teacher-led live online French classes.">
    <meta name="robots" content="{{ $settings['robots'] ?? 'index, follow' }}">

    <!-- Social Media Meta Tags -->
    @include('partials.social-meta', [
        'ogType' => 'article',
        'ogUrl' => url('/about-us'),
        'ogTitle' => 'About Us — ' . ($settings['site_name'] ?? 'Fluence Française'),
        'ogDescription' => 'Structured, practical, teacher-led live online French classes for DELF, DALF, TCF and TEF.',
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
        <section class="relative overflow-hidden bg-white pt-32 md:pt-40 pb-14 md:pb-20">
            <div class="hero-aurora"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div class="reveal">
                        <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> About us</span>
                        <h1 class="mt-5 text-[2.5rem] leading-[1.06] md:text-6xl font-semibold text-ink tracking-tight">More than casual French <span class="swish text-primary-600">lessons</span></h1>
                        <p class="mt-6 text-lg text-slate-600 leading-relaxed">Fluence Française was built for students who need more than casual French lessons. We focus on serious preparation for <strong class="text-ink">DELF, DALF, TCF and TEF</strong>, with live online classes designed to take students from their current level toward strong <strong class="text-ink">B1/B2 exam readiness</strong>.</p>
                        <p class="mt-4 text-lg text-slate-600 leading-relaxed">Our approach is structured, practical and teacher-led: every lesson connects grammar, vocabulary, speaking, listening, reading, writing and the exam skills students need to improve with confidence.</p>
                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="/register" class="btn btn-primary btn-lg">Start Learning <i class="fas fa-arrow-right text-xs"></i></a>
                            <a href="/our-courses" class="btn btn-ghost btn-lg">See the course path</a>
                        </div>
                    </div>
                    <div class="reveal" style="transition-delay:.1s">
                        <div class="relative">
                            <div class="absolute inset-0 rotate-3 rounded-5xl bg-primary-600/5"></div>
                            <div class="relative bg-[#002654] text-white rounded-5xl p-8 md:p-10 overflow-hidden">
                                <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                                <p class="text-2xl md:text-3xl font-semibold leading-snug" style="font-family:'Urbanist',sans-serif">"Every level has structure. Every student gets support. Every step has a purpose."</p>
                                <div class="mt-8 grid grid-cols-3 gap-4 text-center">
                                    <div><p class="text-4xl font-semibold text-white" style="font-family:'Urbanist',sans-serif">4</p><p class="text-xs text-slate-300 mt-1">Core skills</p></div>
                                    <div><p class="text-4xl font-semibold text-white" style="font-family:'Urbanist',sans-serif">A1–B2</p><p class="text-xs text-slate-300 mt-1">Levels</p></div>
                                    <div><p class="text-4xl font-semibold text-[#EF4135]" style="font-family:'Urbanist',sans-serif">4</p><p class="text-xs text-slate-300 mt-1">Exams covered</p></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== PILLARS ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> How we teach</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Structured, practical, teacher-led</h2>
                </div>
                @php $pillars = [
                    ['fa-diagram-project','Structured','Clear levels from A1 to B2, with assessments before you move forward — so progress is real, not assumed.'],
                    ['fa-people-arrows','Practical','Every lesson connects grammar and vocabulary to speaking, listening, reading and writing you can actually use.'],
                    ['fa-chalkboard-user','Teacher-led','Live online guidance and accountability from real teachers — not an app left to figure out alone.'],
                ]; @endphp
                <div class="mt-12 grid md:grid-cols-3 gap-5">
                    @foreach($pillars as $i => $p)
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-7" style="transition-delay:{{ $i*70 }}ms">
                        <span class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-lg"><i class="fas {{ $p[0] }}"></i></span>
                        <h3 class="mt-5 text-xl font-bold text-ink">{{ $p[1] }}</h3>
                        <p class="mt-2 text-slate-600 leading-relaxed">{{ $p[2] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ===== CTA ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal rounded-5xl bg-primary-600 text-white text-center px-6 py-14 md:py-16 relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1.5" style="background:linear-gradient(90deg,#003d7a 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>
                    <h2 class="text-3xl md:text-5xl font-semibold leading-tight max-w-2xl mx-auto">Start your French journey with us</h2>
                    <p class="mt-4 text-lg text-primary-100 max-w-xl mx-auto">Join {{ $settings['site_name'] ?? 'Fluence Française' }} and reach your B1 / B2 with real structure and support.</p>
                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        <a href="/register" class="btn btn-onnavy btn-lg">Start Learning Now</a>
                        <a href="/contact-us" class="btn btn-lg" style="box-shadow:inset 0 0 0 2px #fff;color:#fff;background:transparent">Contact Us</a>
                    </div>
                </div>
            </div>
        </section>

        @include('partials.footer')
    </div>
</body>
</html>
