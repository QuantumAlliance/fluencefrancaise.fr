<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    @endphp

    <title>A2 Elementary French — {{ ($settings['site_name'] ?? 'Fluence Française') }}</title>
    <meta name="description" content="The A2 Elementary level builds on your A1 foundations. Expand vocabulary, master past and future tenses, and communicate confidently in everyday French situations.">
    <meta name="robots" content="{{ $settings['robots'] ?? 'index, follow' }}">

    @include('partials.social-meta', [
        'ogType'       => 'article',
        'ogUrl'        => url('/elementary'),
        'ogTitle'      => 'A2 Elementary French — ' . ($settings['site_name'] ?? 'Fluence Française'),
        'ogDescription'=> 'Advance your French with our A2 Elementary course. Speak about routine, family, work and travel — and build the vocabulary that matters for daily life and integration.',
        'ogSiteName'   => $settings['site_name'] ?? 'Fluence Française',
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
                        <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> Practical Everyday French</span>
                        <h1 class="mt-5 text-[2.5rem] leading-[1.06] md:text-6xl font-semibold text-ink tracking-tight">
                            Build the French you need for <span class="swish text-primary-600">real life</span>
                        </h1>
                        <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                            A2 moves you from basic French to more useful daily communication. You will expand your vocabulary, master past and future structures, and handle everyday situations — from shopping and directions to conversations about family, work and travel.
                        </p>
                        <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                            At <strong class="text-ink">Fluence Française</strong>, A2 is taught with the same structured, teacher-led approach as every level — every lesson connects grammar, vocabulary and the four core skills you need to move forward with confidence.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="/register" class="btn btn-primary btn-lg">Enroll in A2 <i class="fas fa-arrow-right text-xs"></i></a>
                            <a href="/our-courses" class="btn btn-ghost btn-lg">See all levels</a>
                        </div>
                    </div>

                    <div class="reveal" style="transition-delay:.1s">
                        <div class="relative">
                            <div class="absolute inset-0 rotate-3 rounded-5xl bg-primary-600/5"></div>
                            <div class="relative bg-[#002654] text-white rounded-5xl p-8 md:p-10 overflow-hidden">
                                <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                                <!-- Level badge -->
                                <div class="flex items-center gap-5 mb-7">
                                    <span class="w-20 h-20 rounded-3xl bg-white/10 border border-white/20 grid place-content-center text-5xl font-semibold text-white shrink-0" style="font-family:'Urbanist',sans-serif">A2</span>
                                    <div>
                                        <p class="text-xs font-bold tracking-widest uppercase text-primary-300 mb-1">CEFR Level</p>
                                        <p class="text-2xl font-semibold leading-tight" style="font-family:'Urbanist',sans-serif">Elementary</p>
                                        <p class="text-sm text-slate-300 mt-0.5">Practical Everyday French</p>
                                    </div>
                                </div>
                                <p class="text-base text-slate-300 leading-relaxed border-t border-white/10 pt-6">
                                    "A2 is not just the next step — it is the level where French starts to feel useful. Real conversations, real vocabulary, real progress."
                                </p>
                                <div class="mt-6 grid grid-cols-3 gap-4 text-center">
                                    <div><p class="text-3xl font-semibold text-white" style="font-family:'Urbanist',sans-serif">4</p><p class="text-xs text-slate-300 mt-1">Core skills</p></div>
                                    <div><p class="text-3xl font-semibold text-white" style="font-family:'Urbanist',sans-serif">A1→</p><p class="text-xs text-slate-300 mt-1">Builds on A1</p></div>
                                    <div><p class="text-3xl font-semibold text-[#EF4135]" style="font-family:'Urbanist',sans-serif">B1</p><p class="text-xs text-slate-300 mt-1">Next step</p></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ===== LEVEL NAV ===== -->
        <section class="bg-mist border-y border-slate-100 py-5">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <a href="/beginner" class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary-600 transition-colors">
                        <i class="fas fa-arrow-left text-xs"></i>
                        <span>A1 — Beginner</span>
                    </a>
                    <a href="/our-courses" class="flex items-center gap-1.5 text-sm font-bold text-primary-600 hover:text-primary-800 transition-colors">
                        <i class="fas fa-layer-group text-xs"></i>
                        <span>All Levels</span>
                    </a>
                    <a href="/intermediate" class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary-600 transition-colors">
                        <span>B1 — Intermediate</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- ===== WHAT YOU'LL LEARN ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> What you'll learn</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Goals &amp; objectives at A2</h2>
                    <p class="mt-4 text-lg text-slate-600 leading-relaxed">The A2 level builds on the basics learned in A1. You will expand your vocabulary and start to understand and use more complex grammatical structures across all four skills.</p>
                </div>

                @php $goals = [
                    ['fa-comments','Enhanced Communication','Communicate more effectively in everyday situations — shopping, ordering food, asking for directions, and handling familiar social exchanges with confidence.'],
                    ['fa-book-open','Vocabulary Expansion','Learn additional vocabulary related to common topics such as family, hobbies, work, travel and routine daily life. Build a stronger word bank you can actually use.'],
                    ['fa-spell-check','Grammar &amp; Structure','Master the use of past tense verbs (passé composé, imparfait basics) and future structures, along with more complex sentence patterns.'],
                    ['fa-ear-listen','Listening &amp; Speaking','Understand simple spoken French and participate in conversations about familiar topics — routine, work, family and travel — with growing fluency.'],
                    ['fa-pen-to-square','Reading &amp; Writing','Read short texts and notices. Write short messages, paragraphs and simple personal communications with clarity and accuracy.'],
                    ['fa-globe','Everyday &amp; Integration','Develop the practical French needed for daily integration — important for those with residency goals or settling into French-speaking communities.'],
                ]; @endphp

                <div class="mt-12 grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($goals as $i => $g)
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-7 shadow-card" style="transition-delay:{{ $i*70 }}ms">
                        <span class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-lg"><i class="fas {{ $g[0] }}"></i></span>
                        <h3 class="mt-5 text-xl font-bold text-ink">{!! $g[1] !!}</h3>
                        <p class="mt-2 text-slate-600 leading-relaxed">{!! $g[2] !!}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ===== UPON COMPLETION ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-start">

                    <div class="reveal">
                        <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> By the end of A2</span>
                        <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Upon completion, you will be able to</h2>
                        <p class="mt-4 text-lg text-slate-600 leading-relaxed">A2 equips you with the practical language skills to handle everyday French situations and take a confident step toward B1.</p>
                        <div class="mt-8 space-y-4">
                            @php $outcomes = [
                                'Speak about routine, work, family and travel in French',
                                'Handle everyday interactions with ease',
                                'Understand simple spoken French in familiar contexts',
                                'Use and understand a wider range of vocabulary',
                                'Read short texts, notices and simple written material',
                                'Write short messages, paragraphs and personal communications',
                                'Use past and future structures correctly',
                                'Build stronger vocabulary for daily use and integration',
                            ]; @endphp
                            @foreach($outcomes as $i => $outcome)
                            <div class="reveal flex items-start gap-3" style="transition-delay:{{ $i*55 }}ms">
                                <span class="w-6 h-6 rounded-full bg-primary-600 text-white grid place-content-center shrink-0 mt-0.5"><i class="fas fa-check text-[10px]"></i></span>
                                <span class="text-slate-700 leading-relaxed">{{ $outcome }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="reveal" style="transition-delay:.12s">
                        <!-- Why A2 matters card -->
                        <div class="rounded-4xl bg-[#002654] text-white p-8 md:p-10 overflow-hidden relative">
                            <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                            <div class="absolute -bottom-16 -right-10 w-56 h-56 rounded-full bg-[#EF4135]/20 blur-3xl pointer-events-none"></div>
                            <div class="relative">
                                <span class="eyebrow text-primary-300"><span class="tricolor"><i></i><i></i><i></i></span> Why A2 matters</span>
                                <h3 class="mt-4 text-2xl font-semibold leading-snug" style="font-family:'Urbanist',sans-serif">More than a language milestone</h3>
                                <p class="mt-4 text-slate-300 leading-relaxed">A2 is an important level for integration and residency-related goals in France. It represents the threshold at which French becomes genuinely functional — not just studied.</p>
                                <p class="mt-4 text-slate-300 leading-relaxed">Completing A2 with <strong class="text-white">Fluence Française</strong> means you leave with a real ability to use the language, not just knowledge of grammar rules.</p>
                                <div class="mt-7 pt-6 border-t border-white/10">
                                    <p class="text-sm text-slate-400 italic border-l-2 border-[#EF4135] pl-4">An important level for integration and residency-related goals in France.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Enroll prompt -->
                        <div class="mt-5 rounded-3xl bg-white border border-slate-100 shadow-card p-7">
                            <p class="font-bold text-ink text-lg">Ready to start A2?</p>
                            <p class="mt-2 text-slate-600">Join a live online class guided by real teachers. Move at a structured pace with assessments at each stage.</p>
                            <div class="mt-5 flex flex-wrap gap-3">
                                <a href="/register" class="btn btn-primary btn-md">Enroll Now <i class="fas fa-arrow-right text-xs"></i></a>
                                <a href="/our-courses" class="btn btn-outline btn-md">See all levels</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ===== HOW WE TEACH A2 ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> Our approach at A2</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Structured. Practical. <span class="swish text-primary-600">Teacher-led.</span></h2>
                    <p class="mt-4 text-lg text-slate-600 leading-relaxed">At every level, including A2, Fluence Française teaches with a method that connects all four skills — so progress is real, not assumed.</p>
                </div>

                @php $pillars = [
                    ['fa-layer-group','Structured Progression','A2 begins only once A1 foundations are solid. We use a level assessment to confirm readiness — so you build on what you know, not what we assume.'],
                    ['fa-people-arrows','All 4 Skills, Every Lesson','Speaking, listening, reading and writing are all trained at A2. You\'ll practice grammar in context — not in isolation.'],
                    ['fa-chalkboard-user','Live, Teacher-Led Classes','Every A2 lesson is guided by a real teacher online. You get feedback, accountability and a structured path forward — not an app left to your own devices.'],
                ]; @endphp

                <div class="mt-12 grid md:grid-cols-3 gap-5">
                    @foreach($pillars as $i => $p)
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-7 shadow-card" style="transition-delay:{{ $i*70 }}ms">
                        <span class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-lg"><i class="fas {{ $p[0] }}"></i></span>
                        <h3 class="mt-5 text-xl font-bold text-ink">{{ $p[1] }}</h3>
                        <p class="mt-2 text-slate-600 leading-relaxed">{{ $p[2] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ===== LEVEL NAVIGATOR ===== -->
        <section class="py-16 md:py-20 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-xl mx-auto reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> Explore the full path</span>
                    <h2 class="mt-4 text-2xl md:text-3xl font-semibold text-ink">See where A2 fits in your journey</h2>
                </div>
                <div class="mt-10 grid sm:grid-cols-3 gap-5 max-w-3xl mx-auto">
                    <a href="/beginner" class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-6 text-center shadow-card group" style="transition-delay:0ms">
                        <span class="w-14 h-14 mx-auto rounded-2xl bg-slate-50 text-slate-500 group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors grid place-content-center text-2xl font-bold" style="font-family:'Urbanist',sans-serif">A1</span>
                        <p class="mt-3 font-bold text-ink">Beginner</p>
                        <p class="mt-1 text-sm text-slate-500">Foundation Level</p>
                        <p class="mt-3 text-xs font-bold text-slate-400 flex items-center justify-center gap-1"><i class="fas fa-arrow-left text-[10px]"></i> Previous</p>
                    </a>
                    <div class="reveal card-glow rounded-3xl border-2 border-primary-600 bg-primary-600 p-6 text-center shadow-lift" style="transition-delay:70ms">
                        <span class="w-14 h-14 mx-auto rounded-2xl bg-white/20 text-white grid place-content-center text-2xl font-bold" style="font-family:'Urbanist',sans-serif">A2</span>
                        <p class="mt-3 font-bold text-white">Elementary</p>
                        <p class="mt-1 text-sm text-primary-200">You are here</p>
                        <p class="mt-3 text-xs font-bold text-white/60 flex items-center justify-center gap-1"><i class="fas fa-map-marker-alt text-[10px]"></i> Current</p>
                    </div>
                    <a href="/intermediate" class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-6 text-center shadow-card group" style="transition-delay:140ms">
                        <span class="w-14 h-14 mx-auto rounded-2xl bg-slate-50 text-slate-500 group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors grid place-content-center text-2xl font-bold" style="font-family:'Urbanist',sans-serif">B1</span>
                        <p class="mt-3 font-bold text-ink">Intermediate</p>
                        <p class="mt-1 text-sm text-slate-500">Next Step Up</p>
                        <p class="mt-3 text-xs font-bold text-primary-600 flex items-center justify-center gap-1">Next <i class="fas fa-arrow-right text-[10px]"></i></p>
                    </a>
                </div>
            </div>
        </section>

        <!-- ===== FINAL CTA BAND ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal rounded-5xl bg-primary-600 text-white text-center px-6 py-14 md:py-16 relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1.5" style="background:linear-gradient(90deg,#003d7a 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>
                    <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full bg-white/5 blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-16 -right-10 w-64 h-64 rounded-full bg-[#EF4135]/20 blur-3xl pointer-events-none"></div>
                    <div class="relative">
                        <span class="eyebrow justify-center text-primary-200"><span class="tricolor"><i></i><i></i><i></i></span> Start your A2 journey</span>
                        <h2 class="mt-5 text-3xl md:text-5xl font-semibold leading-tight max-w-2xl mx-auto">Ready to move beyond the basics?</h2>
                        <p class="mt-4 text-lg text-primary-100 max-w-xl mx-auto">Join {{ $settings['site_name'] ?? 'Fluence Française' }} and develop the practical, everyday French that opens doors — to conversation, to community, and to the next level.</p>
                        <div class="mt-8 flex flex-wrap justify-center gap-4">
                            <a href="/register" class="btn btn-onnavy btn-lg">Enroll in A2 Now</a>
                            <a href="/contact-us" class="btn btn-lg" style="box-shadow:inset 0 0 0 2px #fff;color:#fff;background:transparent">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('partials.footer')
    </div>
</body>
</html>
