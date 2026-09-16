<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php $settings = \App\Models\Settings::pluck('value', 'key')->toArray(); @endphp
    <title>B2 Upper-Intermediate French — {{ ($settings['site_name'] ?? 'Fluence Française') }}</title>
    <meta name="description" content="The B2 level prepares you for advanced communication in French, focusing on a deeper understanding of the language and its nuances.">
    <meta name="robots" content="{{ $settings['robots'] ?? 'index, follow' }}">
    @include('partials.social-meta', [
        'ogType'       => 'article',
        'ogUrl'        => url('/upper-intermediate'),
        'ogTitle'      => 'B2 Upper-Intermediate French — ' . ($settings['site_name'] ?? 'Fluence Française'),
        'ogDescription'=> 'Master advanced French communication at B2: clear arguments, complex reading, structured writing and exam-level performance.',
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

                    <!-- Left: text -->
                    <div class="reveal">
                        <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> Upper-Intermediate French</span>
                        <h1 class="mt-5 text-[2.5rem] leading-[1.06] md:text-6xl font-semibold text-ink tracking-tight">
                            Take your French to a <span class="swish text-primary-600">higher level</span>
                        </h1>
                        <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                            B2 is the level where students need stronger control of French. You move beyond getting by and start communicating with clarity — arguing a point, reading complex texts, writing with structure, and understanding faster, more natural speech.
                        </p>
                        <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                            B2 is the benchmark widely expected for <strong class="text-ink">French citizenship</strong>, advanced academic studies and stronger professional communication — and it is the gateway to serious exam preparation with Fluence Française.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="/register" class="btn btn-primary btn-lg">Enroll in B2 <i class="fas fa-arrow-right text-xs"></i></a>
                            <a href="/our-courses" class="btn btn-ghost btn-lg">View all levels</a>
                        </div>
                    </div>

                    <!-- Right: level badge card -->
                    <div class="reveal" style="transition-delay:.1s">
                        <div class="relative">
                            <div class="absolute inset-0 rotate-3 rounded-5xl bg-primary-600/5"></div>
                            <div class="relative bg-[#002654] text-white rounded-5xl p-8 md:p-10 overflow-hidden">
                                <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                                <!-- Big B2 badge -->
                                <div class="flex items-center gap-5 mb-6">
                                    <span class="w-20 h-20 rounded-3xl bg-primary-600 grid place-content-center text-white text-4xl font-semibold shrink-0" style="font-family:'Urbanist',sans-serif">B2</span>
                                    <div>
                                        <p class="text-xs font-bold tracking-widest uppercase text-slate-300">CEFR Level</p>
                                        <p class="text-2xl font-semibold leading-tight" style="font-family:'Urbanist',sans-serif">Upper-Intermediate</p>
                                    </div>
                                </div>
                                <p class="text-slate-300 leading-relaxed text-sm">
                                    At B2 you can engage in sophisticated conversations, present detailed opinions, handle formal and informal contexts and perform at an exam-ready standard.
                                </p>
                                <div class="mt-7 grid grid-cols-3 gap-4 text-center border-t border-white/10 pt-6">
                                    <div>
                                        <p class="text-3xl font-semibold text-white" style="font-family:'Urbanist',sans-serif">4</p>
                                        <p class="text-xs text-slate-300 mt-1">Core skills</p>
                                    </div>
                                    <div>
                                        <p class="text-3xl font-semibold text-[#EF4135]" style="font-family:'Urbanist',sans-serif">B2</p>
                                        <p class="text-xs text-slate-300 mt-1">CEFR level</p>
                                    </div>
                                    <div>
                                        <p class="text-3xl font-semibold text-white" style="font-family:'Urbanist',sans-serif">4</p>
                                        <p class="text-xs text-slate-300 mt-1">Exams covered</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ===== LEVEL NAV ===== -->
        <section class="bg-mist py-5 border-y border-slate-100">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <a href="/intermediate" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-primary-600 transition-colors">
                        <i class="fas fa-arrow-left text-xs"></i> B1 Intermediate
                    </a>
                    <div class="flex flex-wrap gap-2 text-xs font-bold uppercase tracking-wide">
                        <span class="px-3 py-1.5 rounded-full bg-primary-50 text-primary-500">A1</span>
                        <span class="px-3 py-1.5 rounded-full bg-primary-50 text-primary-500">A2</span>
                        <span class="px-3 py-1.5 rounded-full bg-primary-50 text-primary-500">B1</span>
                        <span class="px-3 py-1.5 rounded-full bg-primary-600 text-white shadow-soft">B2 ← You are here</span>
                    </div>
                    <a href="/our-courses" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-primary-600 transition-colors">
                        All courses <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- ===== GOALS & OBJECTIVES ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> Goals &amp; objectives</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">What you will work on at B2</h2>
                    <p class="mt-3 text-slate-600 leading-relaxed">Each goal below is built into every lesson — across grammar, vocabulary, speaking, listening, reading and writing.</p>
                </div>

                @php $goals = [
                    ['fa-comments','Advanced Communication','Discuss complex topics, give presentations and debate in French with confidence and clarity.'],
                    ['fa-book-open-reader','Vocabulary Mastery','Achieve a comprehensive vocabulary including idiomatic expressions and specialised terminology.'],
                    ['fa-pen-nib','Grammar Proficiency','Master advanced grammar: passive voice, complex clauses, nuanced verb tenses and register shifts.'],
                    ['fa-headphones','Listening &amp; Speaking','Develop high-level listening and speaking skills for professional, academic and exam contexts.'],
                    ['fa-file-lines','Reading &amp; Writing','Read and critique advanced texts — novels, academic papers — and produce well-structured, detailed writing.'],
                    ['fa-trophy','Exam-Level Performance','Build the speed, stamina and strategy to perform at the standard expected by DELF B2, TEF and TCF.'],
                ]; @endphp

                <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($goals as $i => $g)
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-7 shadow-card" style="transition-delay:{{ $i * 70 }}ms">
                        <span class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-lg">
                            <i class="fas {{ $g[0] }}"></i>
                        </span>
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

                    <!-- Left: checklist -->
                    <div class="reveal">
                        <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> Upon completion</span>
                        <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">
                            What you will be able to <span class="swish text-primary-600">do</span>
                        </h2>
                        <p class="mt-4 text-slate-600 leading-relaxed">
                            Completing B2 means you leave with real, measurable ability across all four skills — not just knowledge of the rules.
                        </p>

                        @php $outcomes = [
                            'Engage in sophisticated conversations and debates',
                            'Use advanced vocabulary and complex grammar with confidence',
                            'Understand and produce high-level written and spoken French suitable for academic and professional settings',
                            'Construct clear, well-structured arguments and present detailed opinions',
                            'Handle both formal and informal registers naturally',
                            'Complete longer listening tasks and comprehend complex reading passages',
                            'Write structured essays, reports and formal correspondence',
                            'Speak more naturally and fluently with near-native rhythm',
                        ]; @endphp

                        <ul class="mt-8 space-y-3">
                            @foreach($outcomes as $o)
                            <li class="flex items-start gap-3 text-slate-700 leading-relaxed">
                                <span class="mt-1 w-5 h-5 rounded-full bg-primary-600 text-white grid place-content-center shrink-0 text-[10px]">
                                    <i class="fas fa-check"></i>
                                </span>
                                {{ $o }}
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Right: B2 descriptors highlight + exam nudge -->
                    <div class="reveal space-y-5" style="transition-delay:.1s">

                        <!-- Descriptor tags card -->
                        <div class="bg-white rounded-4xl border border-slate-100 p-7 shadow-card">
                            <h3 class="text-lg font-bold text-ink mb-4">B2 skills at a glance</h3>
                            @php $tags = [
                                'Clear arguments','Detailed opinions','Formal communication',
                                'Informal communication','Longer listening tasks','Complex reading passages',
                                'Structured writing','More natural speaking','Exam-level performance',
                            ]; @endphp
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                <span class="text-sm font-medium text-primary-700 bg-primary-50 px-3 py-1.5 rounded-full">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Citizenship / exam nudge -->
                        <div class="relative overflow-hidden bg-[#002654] text-white rounded-4xl p-7">
                            <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                            <p class="text-xs font-bold tracking-widest uppercase text-slate-300 mb-3">Why B2 matters</p>
                            <p class="text-slate-200 leading-relaxed text-sm">
                                B2 is often the level expected for <strong class="text-white">French citizenship</strong>, entry to advanced academic programmes and stronger professional roles. It is also the standard at which DELF B2, TEF and TCF become realistic goals — and where Fluence Française begins focused exam-track preparation.
                            </p>
                            <a href="/tef-tcf" class="inline-flex items-center gap-2 mt-5 text-[#8eb8e1] font-bold text-sm hover:text-white hover:gap-3 transition-all">
                                Explore TEF / TCF preparation <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>

                        <!-- Enroll CTA -->
                        <div class="bg-primary-50 rounded-3xl p-6 border border-primary-100">
                            <p class="font-bold text-ink mb-1">Ready to start B2?</p>
                            <p class="text-sm text-slate-600 mb-4">Join Fluence Française and progress with structure, live teaching and real accountability.</p>
                            <a href="/register" class="btn btn-primary btn-md w-full justify-center">Enroll Now <i class="fas fa-arrow-right text-xs"></i></a>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- ===== FOUR SKILLS DEEP-DIVE ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> How we build B2</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Every skill trained, every lesson</h2>
                    <p class="mt-3 text-slate-600">At B2 all four skills become inseparable. Our lessons are designed so each one reinforces the others.</p>
                </div>

                @php $skills = [
                    ['fa-microphone-lines','Speaking','Structured oral production: present arguments, participate in debates, handle formal and informal registers and express nuanced opinions.'],
                    ['fa-ear-listen','Listening','Extended listening tasks covering authentic speech — news, lectures, conversations — with comprehension strategies for speed and accent variety.'],
                    ['fa-book-open','Reading','Longer, more complex passages: articles, essays, reports and literary excerpts. Focus on inference, vocabulary in context and critical reading.'],
                    ['fa-pencil','Writing','Produce well-structured essays, formal letters, opinion pieces and summaries. Develop cohesion, range of expression and grammatical accuracy.'],
                ]; @endphp

                <div class="mt-12 grid sm:grid-cols-2 gap-5">
                    @foreach($skills as $i => $sk)
                    <div class="reveal card-glow bg-white rounded-4xl border border-slate-100 p-8 shadow-card" style="transition-delay:{{ $i * 70 }}ms">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-lg shrink-0">
                                <i class="fas {{ $sk[0] }}"></i>
                            </span>
                            <h3 class="text-2xl font-bold text-ink">{{ $sk[1] }}</h3>
                        </div>
                        <p class="text-slate-600 leading-relaxed">{{ $sk[2] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ===== EXAM READINESS NUDGE ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal relative overflow-hidden rounded-5xl bg-white border border-slate-100 shadow-lift p-8 md:p-14">
                    <div class="grid md:grid-cols-2 gap-10 items-center">
                        <div>
                            <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> B2 &amp; exam preparation</span>
                            <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink leading-tight">
                                Completing B2 means you're <span class="text-[#EF4135]">exam-ready</span>
                            </h2>
                            <p class="mt-5 text-slate-600 leading-relaxed">
                                Once you complete B2 at Fluence Française, you have the language foundation to move directly into exam-specific preparation — timed practice, speaking simulations, writing correction, mock tests and exam strategy for DELF B2, TEF and TCF.
                            </p>
                            <div class="mt-7 flex flex-wrap gap-4">
                                <a href="/tef-tcf" class="btn btn-accent btn-md">Explore TEF / TCF prep <i class="fas fa-arrow-right text-xs"></i></a>
                                <a href="/our-courses" class="btn btn-outline btn-md">View all levels</a>
                            </div>
                        </div>
                        <div class="space-y-3">
                            @php $examPoints = [
                                'DELF / DALF practice tests',
                                'TCF / TEF-style questions',
                                'Timed exercises under exam conditions',
                                'Speaking simulation and feedback',
                                'Structured writing correction',
                                'Mock tests and exam strategy',
                            ]; @endphp
                            @foreach($examPoints as $ep)
                            <p class="flex items-center gap-3 bg-mist rounded-2xl px-4 py-3 text-slate-700 font-medium">
                                <i class="fas fa-check text-primary-600 text-xs shrink-0"></i>{{ $ep }}
                            </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== LEVEL NAVIGATION ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-xl mx-auto reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> The full path</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] font-semibold text-ink leading-tight">Where B2 fits in the journey</h2>
                    <p class="mt-3 text-slate-600">B2 is the top of the general programme — the level that unlocks exam preparation and real-world French.</p>
                </div>
                <div class="mt-12 grid sm:grid-cols-3 gap-5 reveal" style="transition-delay:.07s">
                    <!-- Previous level -->
                    <a href="/intermediate" class="card-glow block bg-white rounded-3xl border border-slate-100 p-6 shadow-card text-center group">
                        <span class="w-14 h-14 rounded-2xl bg-primary-50 text-primary-400 grid place-content-center text-2xl font-bold mx-auto" style="font-family:'Urbanist',sans-serif">B1</span>
                        <p class="mt-3 font-bold text-ink">Intermediate</p>
                        <p class="mt-1 text-sm text-slate-500">The level before B2</p>
                        <p class="mt-4 text-xs font-bold text-primary-600 group-hover:gap-2 inline-flex items-center gap-1 transition-all">
                            <i class="fas fa-arrow-left text-[10px]"></i> Go to B1
                        </p>
                    </a>
                    <!-- Current level -->
                    <div class="bg-[#002654] text-white rounded-3xl p-6 text-center relative overflow-hidden shadow-lift">
                        <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                        <span class="w-14 h-14 rounded-2xl bg-primary-600 text-white grid place-content-center text-2xl font-bold mx-auto" style="font-family:'Urbanist',sans-serif">B2</span>
                        <p class="mt-3 font-bold text-white">Upper-Intermediate</p>
                        <p class="mt-1 text-sm text-slate-300">You are here</p>
                        <span class="mt-4 inline-block text-xs font-bold text-[#EF4135] uppercase tracking-widest">Current level</span>
                    </div>
                    <!-- All courses -->
                    <a href="/our-courses" class="card-glow block bg-white rounded-3xl border border-slate-100 p-6 shadow-card text-center group">
                        <span class="w-14 h-14 rounded-2xl bg-accent-soft text-accent grid place-content-center text-2xl font-bold mx-auto" style="font-family:'Urbanist',sans-serif; color:#EF4135; background:#fde8e6">
                            <i class="fas fa-list-ul text-lg"></i>
                        </span>
                        <p class="mt-3 font-bold text-ink">All Courses</p>
                        <p class="mt-1 text-sm text-slate-500">A1 → B2 overview</p>
                        <p class="mt-4 text-xs font-bold text-primary-600 group-hover:gap-2 inline-flex items-center gap-1 transition-all">
                            Explore <i class="fas fa-arrow-right text-[10px]"></i>
                        </p>
                    </a>
                </div>
            </div>
        </section>

        <!-- ===== FINAL CTA BAND ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal rounded-5xl bg-primary-600 text-white text-center px-6 py-14 md:py-16 relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1.5" style="background:linear-gradient(90deg,#003d7a 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>
                    <span class="eyebrow justify-center text-primary-200 mb-4"><span class="tricolor"><i></i><i></i><i></i></span> Ready to reach B2?</span>
                    <h2 class="mt-4 text-3xl md:text-5xl font-semibold leading-tight max-w-2xl mx-auto">
                        Start your B2 journey with Fluence Française
                    </h2>
                    <p class="mt-4 text-lg text-primary-100 max-w-xl mx-auto">
                        Live classes, structured progression, real teachers — and a clear path from B2 to exam-ready.
                    </p>
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
