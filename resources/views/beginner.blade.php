<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    @endphp

    <title>A1 Beginner French — {{ ($settings['site_name'] ?? 'Fluence Française') }}</title>
    <meta name="description" content="The A1 level is designed for absolute beginners. Learn the basics of French — from introducing yourself to understanding simple conversations and reading short texts.">
    <meta name="robots" content="{{ $settings['robots'] ?? 'index, follow' }}">

    @include('partials.social-meta', [
        'ogType'       => 'article',
        'ogUrl'        => url('/beginner'),
        'ogTitle'      => 'A1 Beginner French — ' . ($settings['site_name'] ?? 'Fluence Française'),
        'ogDescription'=> 'Start your French journey with our A1 Beginner course. Build the foundations of French communication, vocabulary and grammar with live online classes.',
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
                <div class="text-center max-w-3xl mx-auto">
                    <div class="reveal">
                        <span class="inline-grid place-content-center w-16 h-16 rounded-2xl bg-primary-600 text-white text-2xl font-bold mb-5" style="font-family:'Urbanist',sans-serif">A1</span>
                    </div>
                    <span class="eyebrow justify-center reveal"><span class="tricolor"><i></i><i></i><i></i></span> Beginner Foundation</span>
                    <h1 class="reveal mt-5 text-[2.6rem] leading-[1.05] md:text-6xl font-semibold text-ink tracking-tight">Build your French from the <span class="swish text-primary-600">ground up</span></h1>
                    <p class="reveal mt-6 text-lg text-slate-600 leading-relaxed">
                        The A1 level is designed for absolute beginners who have no prior knowledge of French. At Fluence Française, we guide you through the basics — from the alphabet to fundamental vocabulary and simple sentence structures — so you can understand and use French in real situations from day one.
                    </p>
                    <div class="reveal mt-8 flex flex-wrap justify-center gap-4">
                        <a href="/register" class="btn btn-primary btn-lg">Enroll Now <i class="fas fa-arrow-right text-xs"></i></a>
                        <a href="/our-courses" class="btn btn-ghost btn-lg">See all levels</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== WHAT YOU WILL LEARN ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> A1 curriculum</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">What you will learn at A1</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">A1 is where students begin building the structure of French. Every topic is chosen to give you a real, usable foundation.</p>
                </div>

                @php $skills = [
                    ['fa-user','Basic Communication','Learn to introduce yourself, ask and answer simple questions, and handle brief everyday exchanges.'],
                    ['fa-book-open','Essential Vocabulary','Acquire the key words and phrases you need for everyday situations — family, numbers, time, places and more.'],
                    ['fa-spell-check','Grammar Foundations','Understand the basic grammatical structures of French, including present-tense verbs and simple sentence formation.'],
                    ['fa-ear-listen','Listening & Speaking','Develop the listening and speaking skills needed to understand simple conversations and participate with confidence.'],
                    ['fa-sun','Daily Life French','Talk about your day, your routine, your preferences and the world around you using practical, natural French.'],
                    ['fa-file-lines','Reading & Writing','Read short texts and notices; write simple sentences, messages and short descriptions.'],
                ]; @endphp

                <div class="mt-12 grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($skills as $i => $sk)
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-7 shadow-card" style="transition-delay:{{ $i*70 }}ms">
                        <span class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-lg shrink-0">
                            <i class="fas {{ $sk[0] }}"></i>
                        </span>
                        <h3 class="mt-5 text-xl font-bold text-ink">{{ $sk[1] }}</h3>
                        <p class="mt-2 text-slate-600 leading-relaxed">{{ $sk[2] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ===== GOALS & OBJECTIVES CHECKLIST ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-start">

                    <div class="reveal">
                        <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> Goals &amp; objectives</span>
                        <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">By the end of A1 you will be able to…</h2>
                        <p class="mt-4 text-slate-600 leading-relaxed">These are the core competencies Fluence Française teaches and assesses at the A1 level. Progress is confirmed by assessment before you move to A2.</p>
                        <div class="mt-8 space-y-3.5">
                            @php $objectives = [
                                'Introduce yourself and others in French',
                                'Ask and answer simple questions',
                                'Use basic verbs in the present tense',
                                'Understand simple spoken conversations',
                                'Talk about daily life, routines and preferences',
                                'Read and understand short texts and notices',
                                'Write simple sentences and short messages',
                            ]; @endphp
                            @foreach($objectives as $obj)
                            <div class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-full bg-primary-50 text-primary-600 grid place-content-center shrink-0 mt-0.5">
                                    <i class="fas fa-check text-xs"></i>
                                </span>
                                <span class="text-slate-600 leading-relaxed">{{ $obj }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="reveal" style="transition-delay:140ms">
                        <div class="relative">
                            <div class="absolute inset-0 rotate-2 rounded-5xl bg-primary-600/5"></div>
                            <div class="relative bg-[#002654] text-white rounded-5xl p-8 md:p-10 overflow-hidden">
                                <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                                <p class="text-xl font-semibold leading-snug text-slate-200" style="font-family:'Urbanist',sans-serif">A note on A1</p>
                                <p class="mt-4 text-2xl md:text-3xl font-semibold leading-snug" style="font-family:'Urbanist',sans-serif">"A1 is not about speaking perfectly — it is about building the base properly."</p>
                                <p class="mt-6 text-slate-300 leading-relaxed">Every strong French speaker started here. The goal at A1 is clarity of foundation, not fluency. With the right structure now, every level after this becomes easier.</p>
                                <div class="mt-8 pt-6 border-t border-white/10 grid grid-cols-2 gap-4 text-center">
                                    <div>
                                        <p class="text-3xl font-semibold text-white" style="font-family:'Urbanist',sans-serif">7</p>
                                        <p class="text-xs text-slate-300 mt-1">Core objectives</p>
                                    </div>
                                    <div>
                                        <p class="text-3xl font-semibold text-[#EF4135]" style="font-family:'Urbanist',sans-serif">Live</p>
                                        <p class="text-xs text-slate-300 mt-1">Online classes</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ===== HOW A1 FITS THE PATH ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> Your learning path</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">How A1 fits the bigger <span class="swish text-primary-600">picture</span></h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">Fluence Française takes students from A1 all the way to B2 exam readiness. A1 is step one of a deliberate, structured journey.</p>
                </div>

                @php $path = [
                    ['code'=>'A1','label'=>'Beginner Foundation','desc'=>'You are here. Build the core structures of French — pronunciation, basic grammar, essential vocabulary and simple communication.','active'=>true,'url'=>'/beginner'],
                    ['code'=>'A2','label'=>'Practical Everyday French','desc'=>'Move into more useful daily communication — routine, work, family and travel. Important for integration and residency goals.','active'=>false,'url'=>'/elementary'],
                    ['code'=>'B1','label'=>'Intermediate French','desc'=>'Express opinions, describe experiences and handle more complex situations. A major step toward exam preparation.','active'=>false,'url'=>'/intermediate'],
                    ['code'=>'B2','label'=>'Upper-Intermediate French','desc'=>'Exam-level performance — structured writing, longer listening, formal communication and full DELF/DALF/TCF/TEF readiness.','active'=>false,'url'=>'/upper-intermediate'],
                ]; @endphp

                <div class="mt-12 grid md:grid-cols-2 xl:grid-cols-4 gap-5">
                    @foreach($path as $i => $step)
                    <div class="reveal card-glow rounded-3xl border p-6 {{ $step['active'] ? 'bg-primary-600 border-primary-600 text-white' : 'bg-white border-slate-100' }}" style="transition-delay:{{ $i*70 }}ms">
                        <span class="inline-grid place-content-center w-12 h-12 rounded-2xl font-bold text-lg shrink-0 {{ $step['active'] ? 'bg-white text-primary-600' : 'bg-primary-50 text-primary-600' }}" style="font-family:'Urbanist',sans-serif">{{ $step['code'] }}</span>
                        <h3 class="mt-4 text-lg font-bold {{ $step['active'] ? 'text-white' : 'text-ink' }}">{{ $step['label'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed {{ $step['active'] ? 'text-primary-100' : 'text-slate-500' }}">{{ $step['desc'] }}</p>
                        @if(!$step['active'])
                        <a href="{{ $step['url'] }}" class="inline-flex items-center gap-1.5 mt-4 text-sm font-bold text-primary-600 hover:gap-2.5 transition-all">
                            Explore {{ $step['code'] }} <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                        @else
                        <span class="inline-flex items-center gap-1.5 mt-4 text-sm font-bold text-white/80">
                            <i class="fas fa-map-pin text-xs"></i> Current level
                        </span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ===== LEVEL NAVIGATION ===== -->
        <section class="py-12 md:py-16 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal flex flex-col sm:flex-row items-center justify-between gap-6 rounded-4xl border border-slate-100 bg-mist px-8 py-7">
                    <a href="/our-courses" class="flex items-center gap-3 text-slate-500 hover:text-primary-600 transition-colors font-semibold">
                        <i class="fas fa-arrow-left text-sm"></i>
                        <span>All levels overview</span>
                    </a>
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-primary-600 text-white grid place-content-center font-bold text-sm" style="font-family:'Urbanist',sans-serif">A1</span>
                        <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
                        <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 grid place-content-center font-bold text-sm" style="font-family:'Urbanist',sans-serif">A2</span>
                        <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
                        <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 grid place-content-center font-bold text-sm" style="font-family:'Urbanist',sans-serif">B1</span>
                        <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
                        <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 grid place-content-center font-bold text-sm" style="font-family:'Urbanist',sans-serif">B2</span>
                    </div>
                    <a href="/elementary" class="flex items-center gap-3 text-primary-600 hover:text-primary-700 transition-colors font-semibold">
                        <span>Next: A2 Elementary</span>
                        <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- ===== FINAL CTA BAND ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal rounded-5xl bg-primary-600 text-white text-center px-6 py-14 md:py-16 relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1.5" style="background:linear-gradient(90deg,#003d7a 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>
                    <span class="inline-grid place-content-center w-14 h-14 rounded-2xl bg-white/15 text-white text-xl font-bold mb-5" style="font-family:'Urbanist',sans-serif">A1</span>
                    <h2 class="text-3xl md:text-5xl font-semibold leading-tight max-w-2xl mx-auto">Ready to start your French journey?</h2>
                    <p class="mt-4 text-lg text-primary-100 max-w-xl mx-auto">Join Fluence Française and build your French from the ground up — with structure, live guidance and a clear path from A1 to exam readiness.</p>
                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        <a href="/register" class="btn btn-onnavy btn-lg">Enroll in A1 Now</a>
                        <a href="/contact-us" class="btn btn-lg" style="box-shadow:inset 0 0 0 2px #fff;color:#fff;background:transparent">Contact Us</a>
                    </div>
                </div>
            </div>
        </section>

        @include('partials.footer')
    </div>
</body>
</html>
