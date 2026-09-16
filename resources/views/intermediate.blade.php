<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    @endphp

    <title>B1 Intermediate French — {{ ($settings['site_name'] ?? 'Fluence Française') }}</title>
    <meta name="description" content="The B1 Intermediate level develops your ability to communicate more fluently and confidently — explain opinions, describe experiences, and handle common situations in French.">
    <meta name="robots" content="{{ $settings['robots'] ?? 'index, follow' }}">

    @include('partials.social-meta', [
        'ogType'       => 'article',
        'ogUrl'        => url('/intermediate'),
        'ogTitle'      => 'B1 Intermediate French — ' . ($settings['site_name'] ?? 'Fluence Française'),
        'ogDescription'=> 'Achieve confident, practical French at the B1 Intermediate level. Explain opinions, describe experiences, handle common situations and prepare for DELF/TCF.',
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

                    <!-- Left: copy -->
                    <div class="reveal">
                        <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> Intermediate French</span>
                        <h1 class="mt-5 text-[2.5rem] leading-[1.06] md:text-6xl font-semibold text-ink tracking-tight">
                            French that <span class="swish text-primary-600">works</span> for you
                        </h1>
                        <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                            The B1 level is where French becomes more serious. At Fluence Française you develop the ability to communicate more fluently and confidently in a variety of contexts — explaining your opinions, describing experiences, handling common situations and moving toward exam preparation.
                        </p>
                        <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                            B1 is also commonly connected to <strong class="text-ink">long-term residency goals</strong> in France and is a major step toward DELF, TCF and TEF exam readiness.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="/register" class="btn btn-primary btn-lg">Enroll at B1 <i class="fas fa-arrow-right text-xs"></i></a>
                            <a href="/our-courses" class="btn btn-ghost btn-lg">See all levels</a>
                        </div>
                    </div>

                    <!-- Right: level badge card -->
                    <div class="reveal" style="transition-delay:.1s">
                        <div class="relative">
                            <div class="absolute inset-0 rotate-3 rounded-5xl bg-primary-600/5"></div>
                            <div class="relative bg-[#002654] text-white rounded-5xl p-8 md:p-10 overflow-hidden">
                                <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                                <!-- Big level badge -->
                                <div class="flex items-center gap-5 mb-6">
                                    <span class="w-20 h-20 rounded-3xl bg-primary-600 text-white grid place-content-center shrink-0" style="font-family:'Urbanist',sans-serif">
                                        <span class="text-4xl font-semibold leading-none">B1</span>
                                    </span>
                                    <div>
                                        <p class="text-xl font-semibold leading-snug" style="font-family:'Urbanist',sans-serif">Intermediate</p>
                                        <p class="text-sm text-slate-300 mt-0.5">CEFR Level B1</p>
                                    </div>
                                </div>
                                <p class="text-lg text-slate-200 leading-relaxed italic">"B1 is where French becomes more serious — and more rewarding."</p>
                                <div class="mt-8 grid grid-cols-3 gap-4 text-center border-t border-white/10 pt-6">
                                    <div>
                                        <p class="text-3xl font-semibold text-white" style="font-family:'Urbanist',sans-serif">4</p>
                                        <p class="text-xs text-slate-300 mt-1">Core skills</p>
                                    </div>
                                    <div>
                                        <p class="text-3xl font-semibold text-white" style="font-family:'Urbanist',sans-serif">B1</p>
                                        <p class="text-xs text-slate-300 mt-1">CEFR level</p>
                                    </div>
                                    <div>
                                        <p class="text-3xl font-semibold text-[#EF4135]" style="font-family:'Urbanist',sans-serif">3</p>
                                        <p class="text-xs text-slate-300 mt-1">Exams linked</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ===== WHAT YOU WILL LEARN (card-glow grid) ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> What you will learn</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Goals and objectives at B1</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">Every skill is trained with structure — not guesswork. Here is what B1 students work on at Fluence Française.</p>
                </div>

                @php
                $goals = [
                    ['fa-comments','Fluent Communication','Engage in more complex conversations and express opinions on familiar topics with growing confidence.'],
                    ['fa-book-open','Expanded Vocabulary','Broaden your vocabulary to include more abstract and technical terms needed for real-life and exam situations.'],
                    ['fa-spell-check','Advanced Grammar','Learn advanced grammatical structures, including the subjunctive mood and conditional sentences, and use them naturally.'],
                    ['fa-headphones','Listening & Speaking','Understand clearer spoken French and speak with more confidence across a wider range of situations.'],
                    ['fa-pen-nib','Reading & Writing','Read and analyse longer texts such as articles and structured passages, and write coherent paragraphs and short essays.'],
                    ['fa-graduation-cap','Exam Readiness','B1 is a major step toward DELF B1, TCF and TEF preparation — the level expected for residency and integration in France.'],
                ];
                @endphp

                <div class="mt-12 grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($goals as $i => $g)
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-7" style="transition-delay:{{ $i * 70 }}ms">
                        <span class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-lg"><i class="fas {{ $g[0] }}"></i></span>
                        <h3 class="mt-5 text-xl font-bold text-ink">{{ $g[1] }}</h3>
                        <p class="mt-2 text-slate-600 leading-relaxed">{{ $g[2] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ===== UPON COMPLETION CHECKLIST ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-start">

                    <!-- Left: upon completion checklist -->
                    <div class="reveal">
                        <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> Upon completion</span>
                        <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">At the end of B1, you will be able to</h2>
                        <p class="mt-4 text-slate-600 leading-relaxed">These are the real-world abilities you build through structured practice at the B1 level.</p>

                        @php
                        $outcomes = [
                            'Explain your opinions and give reasons for your views',
                            'Describe experiences, events and personal plans in detail',
                            'Understand clearer conversations and longer spoken passages',
                            'Write structured paragraphs, messages and short essays',
                            'Handle common situations — travel, work, daily life — with confidence',
                            'Speak with more confidence and natural flow',
                            'Understand longer reading passages and identify key information',
                            'Participate in discussions on a wider range of topics',
                            'Use a broad range of vocabulary and more complex grammatical structures',
                            'Understand and produce detailed written and spoken communication',
                        ];
                        @endphp

                        <div class="mt-8 grid sm:grid-cols-2 gap-x-8 gap-y-3.5">
                            @foreach($outcomes as $outcome)
                            <p class="flex items-start gap-2.5 text-slate-700">
                                <i class="fas fa-check text-primary-600 text-xs mt-1.5 shrink-0"></i>
                                <span>{{ $outcome }}</span>
                            </p>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right: accent note + enroll prompt -->
                    <div class="reveal" style="transition-delay:.1s">
                        <!-- Residency note -->
                        <div class="rounded-4xl bg-primary-50 border border-primary-100 p-7 mb-6">
                            <div class="flex items-start gap-4">
                                <span class="w-11 h-11 rounded-xl bg-primary-600 text-white grid place-content-center shrink-0 text-base"><i class="fas fa-flag"></i></span>
                                <div>
                                    <h3 class="text-lg font-bold text-ink">Residency & Integration</h3>
                                    <p class="mt-2 text-slate-600 leading-relaxed">B1 is the level commonly required for long-term residency and integration in France. Reaching it with Fluence Française means you arrive with real language ability — not just a certificate.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Exam note -->
                        <div class="rounded-4xl bg-white border border-slate-100 shadow-card p-7 mb-6">
                            <div class="flex items-start gap-4">
                                <span class="w-11 h-11 rounded-xl bg-accent-soft text-accent grid place-content-center shrink-0 text-base"><i class="fas fa-file-alt"></i></span>
                                <div>
                                    <h3 class="text-lg font-bold text-ink">Exam Preparation</h3>
                                    <p class="mt-2 text-slate-600 leading-relaxed">B1 is a major step toward exam preparation for DELF B1, TCF and TEF. Once you complete this level you are in a strong position to begin focused exam work.</p>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <span class="text-sm font-medium text-primary-700 bg-primary-50 px-3 py-1.5 rounded-full">DELF B1</span>
                                        <span class="text-sm font-medium text-primary-700 bg-primary-50 px-3 py-1.5 rounded-full">TCF</span>
                                        <span class="text-sm font-medium text-primary-700 bg-primary-50 px-3 py-1.5 rounded-full">TEF</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Inline enroll -->
                        <div class="rounded-4xl bg-primary-600 text-white p-7 relative overflow-hidden">
                            <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                            <p class="text-lg font-semibold leading-snug" style="font-family:'Urbanist',sans-serif">Ready to reach B1?</p>
                            <p class="mt-2 text-primary-100 text-sm leading-relaxed">Join Fluence Française and move through B1 with real structure, live teacher support and clear goals.</p>
                            <a href="/register" class="btn btn-onnavy btn-md mt-5">Enroll Now <i class="fas fa-arrow-right text-xs"></i></a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ===== LEVEL NAV ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10 reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> The learning path</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Where B1 sits in the journey</h2>
                    <p class="mt-4 text-slate-600 max-w-xl mx-auto">Fluence Française guides you level by level from A1 to B2. Each level builds on the last — no guesswork, no skipping foundations.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-5 max-w-4xl mx-auto">
                    <!-- Prev: A2 Elementary -->
                    <a href="/elementary" class="reveal card-glow group block bg-white rounded-3xl border border-slate-100 p-6 text-center shadow-card" style="transition-delay:0ms">
                        <span class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center font-bold text-lg mx-auto mb-4" style="font-family:'Urbanist',sans-serif">A2</span>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Previous level</p>
                        <p class="text-lg font-bold text-ink group-hover:text-primary-600 transition-colors">Elementary</p>
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">Practical everyday French — routine, work, family and travel.</p>
                        <span class="inline-flex items-center gap-1.5 mt-4 text-sm font-bold text-primary-600"><i class="fas fa-arrow-left text-xs"></i> A2 Elementary</span>
                    </a>

                    <!-- Current: B1 -->
                    <div class="reveal card-glow bg-[#002654] text-white rounded-3xl p-6 text-center relative overflow-hidden" style="transition-delay:70ms">
                        <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                        <span class="w-12 h-12 rounded-2xl bg-primary-600 text-white grid place-content-center font-bold text-lg mx-auto mb-4 mt-2" style="font-family:'Urbanist',sans-serif">B1</span>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-300 mb-1">Current level</p>
                        <p class="text-lg font-bold text-white">Intermediate</p>
                        <p class="mt-2 text-sm text-slate-300 leading-relaxed">Opinions, experiences, confidence — serious French begins here.</p>
                        <span class="inline-flex items-center gap-1.5 mt-4 text-sm font-bold text-[#EF4135]"><i class="fas fa-map-marker-alt text-xs"></i> You are here</span>
                    </div>

                    <!-- Next: B2 Upper-Intermediate -->
                    <a href="/upper-intermediate" class="reveal card-glow group block bg-white rounded-3xl border border-slate-100 p-6 text-center shadow-card" style="transition-delay:140ms">
                        <span class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center font-bold text-lg mx-auto mb-4" style="font-family:'Urbanist',sans-serif">B2</span>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Next level</p>
                        <p class="text-lg font-bold text-ink group-hover:text-primary-600 transition-colors">Upper-Intermediate</p>
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">Stronger control — arguments, formal communication, exam-level performance.</p>
                        <span class="inline-flex items-center gap-1.5 mt-4 text-sm font-bold text-primary-600">B2 Upper-Intermediate <i class="fas fa-arrow-right text-xs"></i></span>
                    </a>
                </div>

                <div class="text-center mt-10 reveal">
                    <a href="/our-courses" class="btn btn-outline btn-md">View the full course path <i class="fas fa-arrow-right text-xs"></i></a>
                </div>
            </div>
        </section>

        <!-- ===== FINAL CTA BAND ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal rounded-5xl bg-primary-600 text-white text-center px-6 py-14 md:py-16 relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1.5" style="background:linear-gradient(90deg,#003d7a 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>
                    <div class="absolute -top-24 -right-16 w-80 h-80 rounded-full bg-white/5 blur-3xl pointer-events-none"></div>
                    <h2 class="text-3xl md:text-5xl font-semibold leading-tight max-w-2xl mx-auto">
                        Start your B1 journey with Fluence Française
                    </h2>
                    <p class="mt-4 text-lg text-primary-100 max-w-xl mx-auto">Structured live classes, real teacher guidance and a clear path from B1 to exam-ready French.</p>
                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        <a href="/register" class="btn btn-onnavy btn-lg">Enroll Now</a>
                        <a href="/contact-us" class="btn btn-lg" style="box-shadow:inset 0 0 0 2px #fff;color:#fff;background:transparent">Contact Us</a>
                    </div>
                </div>
            </div>
        </section>

        @include('partials.footer')
    </div>
</body>
</html>
