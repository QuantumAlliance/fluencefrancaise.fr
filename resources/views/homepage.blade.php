<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta Tags -->
    <title>Fluence Française: TCF, TEF, DELF & fide Prep Online</title>
    <meta name="description" content="Live online French classes with a tutor: TCF IRN, TEF IRN, DELF and fide test prep, civic exam included. For France and Switzerland, from A1 to B2.">
    <meta name="robots" content="{{ $settings['robots'] ?? 'index, follow' }}">
    <link rel="canonical" href="https://fluencefrancaise.fr/en/">
    <link rel="alternate" hreflang="fr" href="https://fluencefrancaise.fr/">
    <link rel="alternate" hreflang="en" href="https://fluencefrancaise.fr/en/">
    <link rel="alternate" hreflang="x-default" href="https://fluencefrancaise.fr/en/">

    <!-- Social Media Meta Tags -->
    @include('partials.social-meta', [
        'ogType' => 'website',
        'ogUrl' => 'https://fluencefrancaise.fr/en/',
        'ogTitle' => 'Fluence Française: TCF, TEF, DELF & fide Prep Online',
        'ogDescription' => 'Live online French classes with a tutor: TCF IRN, TEF IRN, DELF and fide test prep, civic exam included. For France and Switzerland, from A1 to B2.',
        'ogSiteName' => $settings['site_name'] ?? 'Fluence Française',
        'ogImage' => isset($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : null,
    ])

    <!-- Schema.org Structured Data (EducationalOrganization + FAQPage) -->
    @php
        $footerSettings = json_decode($settings['footer_settings'] ?? '{}', true);
        $socialLinks = array_values(array_filter($footerSettings['social'] ?? []));

        $faqsData = [
            [
                'question' => 'Are the classes online?',
                'answer' => 'Yes. Every class runs live on Google Meet with your tutor. Only the exam itself is taken at an approved centre.'
            ],
            [
                'question' => 'Which exams do you prepare for?',
                'answer' => 'The TCF IRN, TEF IRN, DELF (A1 to B2) and fide test. Civic-exam prep is included in every plan at no extra cost. Not sure which one? Compare the TCF, TEF and DELF at https://fluencefrancaise.fr/en/tcf-vs-tef-vs-delf/ .'
            ],
            [
                'question' => 'Do you prepare for Swiss applications?',
                'answer' => 'Yes. We prepare the fide test and the DELF for the C permit and Swiss citizenship, with Swiss scenarios and the vocabulary of French-speaking Switzerland.'
            ],
            [
                'question' => 'Which level should I start from?',
                'answer' => 'Your first class decides: your tutor measures your 4 skills against the level your application requires. If you\'re starting from zero, you go straight into the A1 programme.'
            ],
            [
                'question' => 'Do students take assessments?',
                'answer' => 'Yes. Regular mock exams in your exam\'s format show your progress and tell you when to book the test.'
            ],
            [
                'question' => 'Does Fluence Française run the exams?',
                'answer' => 'No. We\'re an independent prep school. You register for the exam with an approved centre.'
            ]
        ];

        $faqSchema = [
            '@type' => 'FAQPage',
            'mainEntity' => array_map(function ($f) {
                return [
                    '@type' => 'Question',
                    'name' => $f['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $f['answer']
                    ]
                ];
            }, $faqsData)
        ];

        $orgSchema = [
            '@type' => 'EducationalOrganization',
            'name' => $settings['site_name'] ?? 'Fluence Française',
            'url' => 'https://fluencefrancaise.fr/en/',
            'description' => 'Live online French classes with a tutor: TCF IRN, TEF IRN, DELF and fide test prep, civic exam included. For France and Switzerland, from A1 to B2.',
            'logo' => isset($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : null,
            'sameAs' => $socialLinks,
        ];

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@graph' => [
                $orgSchema,
                $faqSchema
            ]
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    @include('partials.favicon')

    <!-- Shared tricolor theme (fonts, tailwind config, components) -->
    @include('partials.theme')

    @if(isset($settings['custom_scripts']))
        @php
            $scripts = json_decode($settings['custom_scripts'], true);
            $hasHeadScripts = false;
            if (is_array($scripts)) {
                foreach ($scripts as $script) {
                    $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                    $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                    if (!empty(trim($scriptCode)) && $placement === 'head') { $hasHeadScripts = true; break; }
                }
            }
        @endphp
        @if($hasHeadScripts)
            @php
                foreach ($scripts as $script) {
                    $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                    $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                    if (!empty(trim($scriptCode)) && $placement === 'head') {
                        $trimmedCode = trim($scriptCode);
                        echo (stripos($trimmedCode, '<script') === 0) ? $scriptCode : '<script>' . "\n" . $scriptCode . "\n" . '</script>';
                    }
                }
            @endphp
        @endif
    @endif

    @php
        $skills = [
            [
                'icon' => 'fa-comments',
                'title' => 'Speaking',
                'fr' => 'Expression orale',
                'desc' => 'Argue, nuance and handle an interview without hunting for words.'
            ],
            [
                'icon' => 'fa-headphones',
                'title' => 'Listening',
                'fr' => 'Compréhension orale',
                'desc' => 'French at native speed, like the test audio.'
            ],
            [
                'icon' => 'fa-book-open',
                'title' => 'Reading',
                'fr' => 'Compréhension écrite',
                'desc' => 'Read fast and accurately, at the pace of the exam and the civic exam.'
            ],
            [
                'icon' => 'fa-pen-nib',
                'title' => 'Writing',
                'fr' => 'Expression écrite',
                'desc' => 'Structured texts that answer the whole task.'
            ],
            [
                'icon' => 'fa-award',
                'title' => 'Exam preparation',
                'fr' => 'Préparation aux examens',
                'desc' => 'TCF IRN, TEF IRN, DELF, fide test and civic exam, in the real format.'
            ],
        ];

        $taglines = [
            [
                'title' => 'Every gap is measured',
                'desc' => 'In your first class, your 4 skills are placed against the required level.'
            ],
            [
                'title' => 'Every hour has a target',
                'desc' => 'Your hours go where you\'re furthest from the goal.'
            ],
            [
                'title' => 'Every step is proven',
                'desc' => 'Regular mock exams, so you know when you\'re ready.'
            ],
        ];

        $tiers = [
            [
                'name' => 'Standard one-on-one (4 hours a week)',
                'symbol' => '€',
                'price' => '299',
                'cadence' => '/ month',
                'badge' => 'Recommended',
                'popular' => true,
                'blurb' => 'Tailored classes, built around your gap and your exam or application date.',
                'features' => [
                    'Flexible scheduling',
                    '4 hours a week: 1 hour on 4 days or 2 hours on 2 days',
                    'First class set up 2–5 days after you enroll',
                    'A plan built on your goal: TCF IRN, TEF IRN, DELF or fide test',
                    'A tutor just for you',
                ],
                'icon' => 'fa-user',
                'batch_full' => false,
                'enroll_url' => '/register',
            ],
            [
                'name' => 'Small group (4 hours a week)',
                'symbol' => '€',
                'price' => '249',
                'cadence' => '/ month',
                'badge' => 'Batch full',
                'popular' => false,
                'blurb' => '2–5 people at the same level: small-group attention at a lower cost.',
                'features' => [
                    'Groups of 2–5 people',
                    '4 hours a week: 1 hour on 4 days or 2 hours on 2 days',
                    'Groups open by level, A1 to B2',
                    'Individual attention within the group',
                    'Interactive learning',
                ],
                'icon' => 'fa-users',
                'batch_full' => true,
                'batch_full_text' => 'This batch is full. Get notified about the next batch',
                'batch_full_url' => '/en/contact/',
            ],
            [
                'name' => '2-hour one-on-one (2 hours a week)',
                'symbol' => '€',
                'price' => '199',
                'cadence' => '/ month',
                'badge' => null,
                'popular' => false,
                'blurb' => 'For a busy schedule: a gentler pace, without losing momentum.',
                'features' => [
                    'Flexible scheduling',
                    '2 hours a week: 1 hour on 2 days or 2 hours on 1 day',
                    'Built for busy schedules',
                    'A more gradual pace',
                    'Personal attention',
                ],
                'icon' => 'fa-clock',
                'batch_full' => false,
                'enroll_url' => '/register',
            ],
        ];

        $resourceCards = [
            ['fa-book-open', 'Free ebooks & study materials', 'PDFs for every level'],
            ['fa-pencil', 'Class notes', 'A recap after every session'],
            ['fa-headphones', 'Reading & listening', 'Texts and audio at native speed'],
            ['fa-layer-group', 'Practice banks', 'Questions sorted by skill'],
            ['fa-clock', 'Mock exams', 'TCF, TEF, DELF and fide format, timed'],
            ['fa-globe', 'A1–B2 resources', 'Sorted by CEFR level'],
            ['fa-desktop', 'Student portal', 'All your materials in one place'],
        ];
    @endphp
</head>
<body class="antialiased bg-white">
    @if(isset($settings['custom_scripts']))
        @php
            $scripts = json_decode($settings['custom_scripts'], true);
            $hasBodyScripts = false;
            if (is_array($scripts)) {
                foreach ($scripts as $script) {
                    $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                    $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                    if (!empty(trim($scriptCode)) && $placement === 'body') { $hasBodyScripts = true; break; }
                }
            }
        @endphp
        @if($hasBodyScripts)
            @php
                foreach ($scripts as $script) {
                    $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                    $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                    if (!empty(trim($scriptCode)) && $placement === 'body') {
                        $trimmedCode = trim($scriptCode);
                        echo (stripos($trimmedCode, '<script') === 0) ? $scriptCode : '<script>' . "\n" . $scriptCode . "\n" . '</script>';
                    }
                }
            @endphp
        @endif
    @endif

    <div class="landing-page">
        @include('partials.header')

        <!-- ============ SECTION 1: HERO ============ -->
        <section id="home" class="relative overflow-hidden bg-white pt-32 md:pt-40 pb-16 md:pb-24">
            <div class="hero-aurora-strong"></div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="max-w-3xl mx-auto text-center">
                    <div class="reveal">
                        <div class="inline-flex flex-col items-center justify-center gap-1.5 mb-2">
                            <span class="text-xs sm:text-[13px] font-bold tracking-[0.22em] uppercase text-primary-600">LIVE ONLINE CLASSES</span>
                            <span class="tricolor my-1" style="height:3.5px;width:48px;"><i></i><i></i><i></i></span>
                            <span class="text-xs sm:text-sm font-semibold tracking-wide text-primary-700/85">TCF IRN · TEF IRN · DELF · fide test · Civic exam</span>
                        </div>
                        <h1 class="mt-5 text-[2.7rem] leading-[1.04] md:text-6xl lg:text-7xl font-semibold text-ink tracking-tight">
                            French classes for the life you're building in <span class="swish text-primary-600">France or Switzerland</span>.
                        </h1>
                        <p class="mt-6 text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
                            Residence card, citizenship, C permit: every application requires a specific level. We measure yours in your first class, then close the gap, live with a tutor.
                        </p>
                        <div class="mt-9 flex flex-wrap items-center justify-center gap-4">
                            <a href="/register" class="btn btn-primary btn-lg">Enroll now <i class="fas fa-arrow-right text-xs"></i></a>
                            <a href="#pricing" class="btn btn-ghost btn-lg">See pricing</a>
                        </div>
                        <div class="mt-10 flex flex-wrap justify-center gap-x-8 gap-y-3 text-sm text-slate-600">
                            <span class="inline-flex items-center gap-2"><i class="fas fa-user-group text-primary-600"></i> 1:1 or groups of 2–5</span>
                            <span class="inline-flex items-center gap-2"><i class="fas fa-video text-primary-600"></i> Live on Google Meet</span>
                            <span class="inline-flex items-center gap-2"><i class="fas fa-route text-primary-600"></i> A1 → B2 + TCF, TEF, DELF, fide</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 2: WHAT WE DO ============ -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> What we do</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">From basic French to the level your <span class="swish text-primary-600">application requires</span></h2>
                    <p class="mt-5 text-lg text-slate-600 leading-relaxed">Whether you're starting from zero or close to B2, every class serves one goal: the level your residence card, citizenship or permit requires.</p>
                </div>

                <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($skills as $i => $s)
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-7 {{ $i === 4 ? 'lg:col-span-1 sm:col-span-2' : '' }}" style="transition-delay:{{ $i * 60 }}ms">
                        <span class="w-12 h-12 rounded-2xl grid place-content-center {{ $i === 4 ? 'bg-[#EF4135] text-white' : 'bg-primary-50 text-primary-600' }} text-lg"><i class="fas {{ $s['icon'] }}"></i></span>
                        <h3 class="mt-5 text-xl font-bold text-ink">{{ $s['title'] }}</h3>
                        @if(!empty($s['fr']))<p class="mt-1 text-sm font-semibold italic text-primary-600">{{ $s['fr'] }}</p>@endif
                        <p class="mt-2 text-slate-600 leading-relaxed">{{ $s['desc'] }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="mt-12 grid sm:grid-cols-3 gap-6 reveal">
                    @foreach($taglines as $t)
                    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                        <p class="text-ink font-bold text-base"><i class="fas fa-check-circle text-primary-600 mr-2"></i>{{ $t['title'] }}</p>
                        <p class="mt-2 text-sm text-slate-600 leading-relaxed">{{ $t['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ============ SECTION 3: PROGRAMS & PRICING ============ -->
        <section id="pricing" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> Programs &amp; pricing</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Choose the format that fits your schedule</h2>
                </div>

                <!-- Mobile swipe hint -->
                <div class="lg:hidden flex justify-center mt-6 -mb-4">
                    <dotlottie-player
                        src="/images/Swiper-Gesture-Left.json"
                        background="transparent"
                        speed="1"
                        style="width: 110px; height: 110px;"
                        loop
                        autoplay>
                    </dotlottie-player>
                </div>

                <div class="mt-12 flex overflow-x-auto snap-x snap-mandatory gap-6 px-4 -mx-4 pb-4 sm:px-6 sm:-mx-6 lg:grid lg:grid-cols-3 lg:overflow-visible lg:mx-0 lg:px-0 lg:pb-0 items-stretch no-scrollbar">
                    @foreach($tiers as $i => $t)
                    <div class="reveal relative flex flex-col rounded-4xl p-8 w-[85%] sm:w-[60%] lg:w-auto lg:min-w-0 snap-center shrink-0 {{ $t['popular'] ? 'bg-[#002654] text-white shadow-lift lg:-mt-4 lg:mb-4' : 'bg-white text-ink border border-slate-100 shadow-card' }}" style="transition-delay:{{ $i*70 }}ms">
                        @if(!empty($t['badge']))
                            <span class="absolute -top-3 left-8 bg-[#EF4135] text-white text-[11px] font-bold uppercase tracking-wider px-4 py-1.5 rounded-full shadow">{{ $t['badge'] }}</span>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="w-11 h-11 rounded-2xl grid place-content-center {{ $t['popular'] ? 'bg-white/15 text-white' : 'bg-primary-50 text-primary-600' }}"><i class="fas {{ $t['icon'] }}"></i></span>
                            <h3 class="text-xl font-bold {{ $t['popular'] ? 'text-white' : 'text-ink' }}">{{ $t['name'] }}</h3>
                        </div>
                        <div class="mt-6 flex items-end gap-1">
                            <span class="text-5xl font-semibold {{ $t['popular'] ? 'text-white' : 'text-ink' }}" style="font-family:'Urbanist',sans-serif">{{ $t['symbol'] }}{{ $t['price'] }}</span>
                            <span class="{{ $t['popular'] ? 'text-slate-300' : 'text-slate-400' }} font-medium mb-2">{{ $t['cadence'] }}</span>
                        </div>
                        <p class="mt-4 text-sm leading-relaxed {{ $t['popular'] ? 'text-slate-300' : 'text-slate-600' }}">{{ $t['blurb'] }}</p>

                        <ul class="mt-6 space-y-3 text-sm flex-1">
                            @foreach($t['features'] as $f)
                            <li class="flex items-start gap-3">
                                <i class="fas fa-check mt-0.5 {{ $t['popular'] ? 'text-[#EF4135]' : 'text-primary-600' }}"></i>
                                <span class="{{ $t['popular'] ? 'text-slate-200' : 'text-slate-600' }}">{{ $f }}</span>
                            </li>
                            @endforeach
                        </ul>

                        @if($t['batch_full'])
                            <div class="mt-8 pt-4 flex flex-col gap-2">
                                <p class="text-sm text-slate-500 font-normal">This batch is full.</p>
                                <a href="{{ $t['batch_full_url'] ?? '/en/contact/' }}" class="flex items-center justify-between text-primary-600 hover:text-primary-700 font-semibold text-sm group transition-colors py-1">
                                    <span>Get notified about the next batch</span>
                                    <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                                </a>
                            </div>
                        @else
                            <a href="{{ $t['enroll_url'] ?? '/register' }}" class="btn btn-lg mt-8 w-full {{ $t['popular'] ? 'btn-onnavy' : 'btn-primary' }}">Enroll now</a>
                        @endif
                    </div>
                    @endforeach
                </div>
                <p class="mt-8 text-center text-sm text-slate-600 max-w-2xl mx-auto">All plans include live teaching, feedback on every assignment, full student-portal access and civic-exam prep.</p>
            </div>
        </section>

        <!-- ============ SECTION 4: RESOURCE CARDS & EVERYTHING YOU NEED BETWEEN CLASSES ============ -->
        <section id="portal" class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div class="reveal order-2 lg:order-1">
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($resourceCards as $i => $p)
                            @if($loop->last)
                            <div class="card-glow group bg-white rounded-2xl border border-slate-100 p-5 hover:border-primary-200 col-span-2 flex items-center gap-4" style="transition-delay:{{ $i * 55 }}ms">
                                <span class="w-11 h-11 rounded-xl grid place-content-center text-lg transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-6 bg-primary-50 text-primary-600 shrink-0">
                                    <i class="fas {{ $p[0] }}"></i>
                                </span>
                                <div>
                                    <p class="font-bold text-ink text-sm leading-snug">{{ $p[1] }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500 leading-snug">{{ $p[2] }}</p>
                                </div>
                            </div>
                            @else
                            <div class="card-glow group bg-white rounded-2xl border border-slate-100 p-5 hover:border-primary-200" style="transition-delay:{{ $i * 55 }}ms">
                                <span class="w-11 h-11 rounded-xl grid place-content-center text-lg transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-6 {{ $i % 2 ? 'bg-accent-soft text-[#EF4135]' : 'bg-primary-50 text-primary-600' }}">
                                    <i class="fas {{ $p[0] }}"></i>
                                </span>
                                <p class="mt-3 font-bold text-ink text-sm leading-snug">{{ $p[1] }}</p>
                                <p class="mt-1 text-xs text-slate-500 leading-snug">{{ $p[2] }}</p>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="reveal order-1 lg:order-2" style="transition-delay:.1s">
                        <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> Student portal</span>
                        <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Everything you need between classes</h2>
                        <div class="mt-5 rounded-2xl border-l-4 border-primary-600 bg-primary-50/70 p-5 shadow-sm">
                            <p class="text-lg text-slate-700 leading-relaxed">Your learning keeps going after class. Every student gets the full resource library: class notes, native-speed practice, practice banks and mock exams.</p>
                        </div>
                        <div class="mt-6 rounded-2xl bg-white border border-primary-100 p-5 flex gap-4">
                            <span class="w-10 h-10 rounded-xl bg-accent-soft text-[#EF4135] grid place-content-center shrink-0"><i class="fas fa-star"></i></span>
                            <p class="text-sm text-slate-600">After 6 months of enrolment, you get free access to <strong class="text-ink">PrepMyFuture</strong> for even more practice.</p>
                        </div>
                        <a href="/register" class="btn btn-primary btn-lg mt-7">Enroll now <i class="fas fa-arrow-right text-xs"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 5: REFERRAL OFFER ============ -->
        <section id="referral" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal relative overflow-hidden rounded-5xl bg-[#002654] text-white p-8 md:p-14">
                    <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                    <div class="absolute -bottom-24 -right-16 w-80 h-80 rounded-full bg-[#EF4135]/20 blur-3xl"></div>
                    <div class="relative grid md:grid-cols-2 gap-10 items-center">
                        <div>
                            <span class="eyebrow text-[#8eb8e1]"><span class="tricolor"><i></i><i></i><i></i></span> Referral offer</span>
                            <h2 class="mt-4 text-3xl md:text-5xl font-semibold leading-tight">€50 off every month — for you <span class="text-[#EF4135]">and</span> your friend</h2>
                            <p class="mt-5 text-lg text-slate-300 leading-relaxed max-w-lg">Refer a friend and you both get €50 off, every month you're enrolled.</p>
                            <a href="/en/contact/#referral" class="btn btn-onnavy btn-lg mt-8">Contact us to refer</a>
                        </div>
                        <div class="md:justify-self-end w-full max-w-sm bg-white/5 backdrop-blur-sm border border-white/10 rounded-4xl p-7">
                            <p class="text-7xl font-semibold text-white" style="font-family:'Urbanist',sans-serif">€50<span class="text-2xl text-slate-300 align-top">/mo</span></p>
                            <p class="mt-3 text-slate-300">The discount applies to both of you, every month of enrolment.</p>
                            <p class="mt-6 text-xs text-slate-400 border-t border-white/10 pt-4">Valid once per student account, not combinable with other offers.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 6: WHO THIS IS FOR ============ -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> Who this is for</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Fluence Française is for you if…</h2>
                </div>
                <div class="mt-12 grid md:grid-cols-2 gap-4 max-w-4xl mx-auto">
                    @php
                        $who = [
                            "You're applying for a <a href=\"/en/french-residence-permit-language/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">multi-year residence permit or a 10-year resident card</a> and need to prove A2 or B1.",
                            "You're going for <a href=\"/en/french-citizenship-language-test/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">French citizenship</a> : B2 spoken and written and, for citizenship by decree, the <a href=\"/en/french-civic-exam/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">civic exam</a>.",
                            "You live in French-speaking Switzerland and are preparing your <a href=\"/en/c-permit-switzerland/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">C permit</a> or <a href=\"/en/swiss-citizenship/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">Swiss citizenship</a>.",
                            "You're preparing for the <a href=\"/en/tcf-irn/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">TCF IRN</a>, <a href=\"/en/tef-irn/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">TEF IRN</a>, <a href=\"/en/delf/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">DELF</a> or <a href=\"/en/fide-test/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">fide test</a>.",
                            "You're tired of apps, irregular tutors and lessons with no clear thread.",
                            "You want live classes, real follow-up, and someone who tells you where you stand.",
                        ];
                    @endphp
                    @foreach($who as $i => $w)
                    <div class="reveal card-glow flex items-start gap-4 bg-white rounded-2xl border border-slate-100 p-5" style="transition-delay:{{ $i*50 }}ms">
                        <span class="w-8 h-8 rounded-full bg-primary-600 text-white grid place-content-center text-sm font-bold shrink-0">{{ $i+1 }}</span>
                        <p class="text-ink font-medium leading-relaxed">{!! $w !!}</p>
                    </div>
                    @endforeach
                </div>
                <div class="mt-10 text-center reveal">
                    <a href="/register" class="btn btn-primary btn-lg">Enroll now <i class="fas fa-arrow-right text-xs"></i></a>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 7: FAQ ============ -->
        <section id="faq" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                <div class="text-center mb-12 reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> FAQ</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Questions, answered</h2>
                </div>
                @php
                    $faqItems = [
                        [
                            'Are the classes online?',
                            'Yes. Every class runs live on Google Meet with your tutor. Only the exam itself is taken at an approved centre.'
                        ],
                        [
                            'Which exams do you prepare for?',
                            'The TCF IRN, TEF IRN, DELF (A1 to B2) and fide test. Civic-exam prep is included in every plan at no extra cost. Not sure which one? <a href="/en/tcf-vs-tef-vs-delf/" class="text-primary-600 underline font-semibold hover:text-primary-700">Compare the TCF, TEF and DELF</a>.'
                        ],
                        [
                            'Do you prepare for Swiss applications?',
                            'Yes. We prepare the fide test and the DELF for the C permit and Swiss citizenship, with Swiss scenarios and the vocabulary of French-speaking Switzerland.'
                        ],
                        [
                            'Which level should I start from?',
                            'Your first class decides: your tutor measures your 4 skills against the level your application requires. If you\'re starting from zero, you go straight into the A1 programme.'
                        ],
                        [
                            'Do students take assessments?',
                            'Yes. Regular mock exams in your exam\'s format show your progress and tell you when to book the test.'
                        ],
                        [
                            'Does Fluence Française run the exams?',
                            'No. We\'re an independent prep school. You register for the exam with an approved centre.'
                        ],
                    ];
                @endphp
                <div class="space-y-3 reveal">
                    @foreach($faqItems as $q)
                    <div class="bg-mist rounded-2xl border border-slate-100 overflow-hidden">
                        <button class="faq-toggle w-full px-6 py-5 flex justify-between items-center text-left gap-4">
                            <span class="font-bold text-ink">{{ $q[0] }}</span>
                            <i class="fas fa-plus text-primary-600 text-sm transition-transform duration-300 shrink-0"></i>
                        </button>
                        <div class="faq-content hidden px-6 pb-6 text-slate-600 leading-relaxed">{!! $q[1] !!}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ============ SECTION 8: FINAL CTA ============ -->
        <section class="pb-20 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal rounded-5xl bg-primary-600 text-white text-center px-6 py-14 md:py-16 relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1.5" style="background:linear-gradient(90deg,#003d7a 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>
                    <h2 class="text-3xl md:text-5xl font-semibold leading-tight max-w-2xl mx-auto">Your level. The required level. We close the gap.</h2>
                    <p class="mt-4 text-lg text-primary-100 max-w-xl mx-auto">Your first class sets your level and your plan. You choose the pace: 2 or 4 hours a week.</p>
                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        <a href="/register" class="btn btn-onnavy btn-lg">Enroll now</a>
                        <a href="/en/contact/" class="btn btn-lg btn-outline" style="box-shadow:inset 0 0 0 2px #fff;color:#fff;background:transparent">Talk to us</a>
                    </div>
                </div>
            </div>
        </section>

        @include('partials.footer')
    </div>

    <script>
        // FAQ accordion
        document.querySelectorAll('.faq-toggle').forEach(function (button) {
            button.addEventListener('click', function () {
                var content = this.nextElementSibling;
                var icon = this.querySelector('i');
                var isOpen = !content.classList.contains('hidden');
                content.classList.toggle('hidden');
                if (icon) icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(135deg)';
            });
        });
    </script>
</body>
</html>
