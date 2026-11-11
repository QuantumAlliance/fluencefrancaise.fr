<!DOCTYPE html>
@php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    
    // Determine language based on current URL prefix
    $isFr = request()->is('fr*');
    
    // Page metadata
    $title = $isFr 
        ? "TCF IRN 2026 : format, notation et préparation en ligne"
        : "TCF IRN 2026: Format, Scoring and Online Preparation";
        
    $metaDesc = $isFr
        ? "TCF IRN 2026 : 4 épreuves, 1 h 35, niveau B2 pour la naturalisation. Format détaillé, pièges à éviter et préparation en ligne avec un tuteur."
        : "TCF IRN in 2026: 4 sections, 1 h 35, and B2 now required for French citizenship. Full format, common traps and live online prep with a tutor.";
        
    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/tcf-irn/' : 'https://fluencefrancaise.fr/en/tcf-irn/';
    $frUrl = 'https://fluencefrancaise.fr/fr/tcf-irn/';
    $enUrl = 'https://fluencefrancaise.fr/en/tcf-irn/';
    
    // FAQ Data
    $faqs = $isFr ? [
        [
            'q' => "Le TCF IRN est-il accepté pour la naturalisation ?",
            'a' => "Oui. L'attestation doit avoir moins de 2 ans et montrer un niveau global B2, selon les règles de notation du TCF IRN. Pour les dossiers déposés avant le 1er janvier 2026, l'ancien niveau B1 s'applique."
        ],
        [
            'q' => "Combien de temps l'attestation est-elle valable ?",
            'a' => "2 ans à compter de la délivrance de votre attestation. Si votre calendrier est incertain, regardez aussi le <a href=\"/fr/delf-b2\" class=\"text-primary-600 underline font-semibold\">DELF B2</a> : c'est un diplôme, et il n'expire jamais."
        ],
        [
            'q' => "Quelle différence entre le TCF IRN et le TCF tout public ?",
            'a' => "Le TCF IRN est la version conçue pour l'intégration, la résidence et la nationalité en France. Il évalue de A1 à B2, les niveaux demandés pour ces démarches. Le TCF tout public sert surtout aux études et au travail."
        ],
        [
            'q' => "Combien de temps faut-il pour passer de B1 à B2 ?",
            'a' => "Il n'existe pas de durée universelle. Tout dépend de votre écart, épreuve par épreuve : un candidat B2 à l'écrit et B1 à l'oral n'a pas le même chemin qu'un candidat B1 partout. Votre premier cours sert à l'estimer."
        ],
        [
            'q' => "Peut-on passer le TCF IRN en ligne ?",
            'a' => "Non. Le test se passe dans un centre agréé. La préparation, elle, se fait entièrement en ligne, en direct avec votre tuteur."
        ],
        [
            'q' => "Peut-on repasser le TCF IRN ?",
            'a' => "Oui, et chaque tentative est payante. Votre centre vous indique quand vous pouvez vous réinscrire. Mieux vaut s'inscrire quand vos examens blancs atteignent le niveau visé dans les 4 épreuves."
        ],
        [
            'q' => "Fluence Française fait-elle passer le TCF IRN ?",
            'a' => "Non. Nous préparons au test ; l'inscription se fait auprès d'un centre agréé par France Éducation international."
        ]
    ] : [
        [
            'q' => "Is the TCF IRN accepted for French citizenship?",
            'a' => "Yes. Your results must be under 2 years old and show an overall B2 level under the TCF IRN scoring rules. Applications filed before 1 January 2026 are judged on the old B1 level."
        ],
        [
            'q' => "How long are TCF IRN results valid?",
            'a' => "2 years from the date your attestation is issued. If your timeline is uncertain, look at the <a href=\"/en/delf-b2\" class=\"text-primary-600 underline font-semibold\">DELF B2</a> too: it's a diploma, and it never expires."
        ],
        [
            'q' => "What's the difference between the TCF IRN and the TCF Tout Public?",
            'a' => "The TCF IRN is the version built for integration, residence and citizenship in France. It tests A1 to B2, the levels those applications ask for. The TCF Tout Public is mainly used for study and work."
        ],
        [
            'q' => "How long does it take to go from B1 to B2?",
            'a' => "There's no universal answer. It depends on your gap in each section: someone at B2 in writing and B1 in speaking has a shorter road than someone at B1 everywhere. Your first class gives you an estimate."
        ],
        [
            'q' => "Can I take the TCF IRN online?",
            'a' => "No. You take it at an approved test centre. The preparation is fully online, live with your tutor."
        ],
        [
            'q' => "Can I retake the TCF IRN?",
            'a' => "Yes, and each attempt costs a new fee. Your test centre tells you when you can re-register. It's better to book once your mock exams hit the target level in all 4 sections."
        ],
        [
            'q' => "Does Fluence Française run the TCF IRN?",
            'a' => "No. We prepare you for it. You register with a test centre approved by France Éducation international."
        ]
    ];

    // Structured Data JSON-LD
    $breadcrumbSchema = [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => $isFr ? 'Accueil' : 'Home',
                'item' => $isFr ? 'https://fluencefrancaise.fr/fr/' : 'https://fluencefrancaise.fr/en/'
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $isFr ? 'Examens' : 'Exams',
                'item' => $canonicalUrl
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => 'TCF IRN',
                'item' => $canonicalUrl
            ]
        ]
    ];

    $courseSchema = [
        '@type' => 'Course',
        'name' => $title,
        'description' => $metaDesc,
        'courseMode' => 'online',
        'provider' => [
            '@type' => 'EducationalOrganization',
            'name' => $siteName,
            'url' => $canonicalUrl
        ]
    ];

    $faqSchema = [
        '@type' => 'FAQPage',
        'mainEntity' => array_map(function($f) {
            return [
                '@type' => 'Question',
                'name' => $f['q'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($f['a'])
                ]
            ];
        }, $faqs)
    ];

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            $breadcrumbSchema,
            $courseSchema,
            $faqSchema
        ]
    ];
@endphp
<html lang="{{ $isFr ? 'fr' : 'en' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="robots" content="{{ $settings['robots'] ?? 'index, follow' }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="fr" href="{{ $frUrl }}">
    <link rel="alternate" hreflang="en" href="{{ $enUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $enUrl }}">

    @include('partials.social-meta', [
        'ogType' => 'website',
        'ogUrl' => $canonicalUrl,
        'ogTitle' => $title,
        'ogDescription' => $metaDesc,
        'ogSiteName' => $siteName,
        'ogImage' => isset($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : null,
    ])

    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    @include('partials.favicon')
    @include('partials.theme')
    @include('partials.custom-scripts-head')
</head>
<body class="antialiased bg-white text-ink">
    @include('partials.custom-scripts-body')

    <div class="landing-page">
        @include('partials.header')

        <!-- BREADCRUMB -->
        <div class="pt-28 md:pt-36 pb-3 bg-white border-b border-slate-100">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <nav aria-label="Breadcrumb" class="text-xs text-slate-500 font-medium flex items-center gap-2">
                    <a href="{{ $isFr ? '/fr' : '/en' }}" class="hover:text-primary-600 transition-colors">{{ $isFr ? 'Accueil' : 'Home' }}</a>
                    <span class="text-slate-300">›</span>
                    <span class="text-slate-600">{{ $isFr ? 'Examens' : 'Exams' }}</span>
                    <span class="text-slate-300">›</span>
                    <span class="text-primary-600 font-semibold">TCF IRN</span>
                </nav>
            </div>
        </div>

        <!-- ============ SECTION 1: HERO ============ -->
        <section class="relative overflow-hidden bg-white pt-10 md:pt-16 pb-16 md:pb-24">
            <div class="hero-aurora-strong"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="max-w-3xl mx-auto text-center reveal">
                    <div class="inline-flex flex-col items-center justify-center gap-1.5 mb-3">
                        <span class="text-xs sm:text-[13px] font-bold tracking-[0.22em] uppercase text-primary-600">{{ $isFr ? 'EXAMEN OFFICIEL' : 'OFFICIAL EXAM' }}</span>
                        <span class="tricolor my-1" style="height:3.5px;width:48px;"><i></i><i></i><i></i></span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-semibold text-ink tracking-tight leading-tight">
                        {{ $isFr ? 'TCF IRN : préparez exactement le niveau exigé' : 'TCF IRN: Prepare for the Exact Level You Need' }}
                    </h1>

                    <p class="mt-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        {{ $isFr ? 'Mis à jour le 6 octobre 2026' : 'Updated 6 October 2026' }}
                    </p>

                    <p class="mt-6 text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
                        {{ $isFr 
                            ? 'Le TCF IRN mesure chaque compétence séparément : nous travaillons celles qui vous séparent du niveau exigé par votre dossier.' 
                            : 'The TCF IRN scores each skill separately, so we train the skills standing between you and the level your application requires.' 
                        }}
                    </p>

                    <div class="mt-8 flex flex-col items-center gap-3">
                        <a href="/register" class="btn btn-primary btn-lg">
                            {{ $isFr ? "S'inscrire" : 'Enroll now' }} <i class="fas fa-arrow-right text-xs ml-1"></i>
                        </a>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium">
                            {{ $isFr 
                                ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' 
                                : 'Live classes on Google Meet · 1:1 or small group · Student portal included' 
                            }}
                        </p>
                    </div>

                    <!-- 3 FACTS STRIP -->
                    <div class="mt-12 grid sm:grid-cols-3 gap-4 pt-8 border-t border-slate-100 text-left">
                        <div class="flex items-center gap-3 bg-mist rounded-2xl p-4 border border-slate-100">
                            <span class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 grid place-content-center shrink-0"><i class="fas fa-check"></i></span>
                            <span class="text-sm font-semibold text-ink">{{ $isFr ? '4 compétences travaillées' : 'All 4 skills covered' }}</span>
                        </div>
                        <div class="flex items-center gap-3 bg-mist rounded-2xl p-4 border border-slate-100">
                            <span class="w-10 h-10 rounded-xl bg-accent-soft text-[#EF4135] grid place-content-center shrink-0"><i class="fas fa-laptop"></i></span>
                            <span class="text-sm font-semibold text-ink">{{ $isFr ? 'Examens blancs dans votre espace élève' : 'Mock exams in your student portal' }}</span>
                        </div>
                        <div class="flex items-center gap-3 bg-mist rounded-2xl p-4 border border-slate-100">
                            <span class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 grid place-content-center shrink-0"><i class="fas fa-pen-nib"></i></span>
                            <span class="text-sm font-semibold text-ink">{{ $isFr ? 'Notes de cours après chaque séance' : 'Class notes after every session' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 2: AT A GLANCE ============ -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> {{ $isFr ? 'Vue d\'ensemble' : 'Overview' }}</span>
                    <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink">
                        {{ $isFr ? 'Le TCF IRN en bref' : 'The TCF IRN at a glance' }}
                    </h2>
                    <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                        {{ $isFr 
                            ? 'Le TCF IRN (Test de connaissance du français – Intégration, Résidence et Nationalité) est accepté pour la carte de séjour pluriannuelle, la carte de résident et la naturalisation. Il dure 1 h 35 et compte 4 épreuves. Son attestation est valable 2 ans.'
                            : 'The TCF IRN (Test de connaissance du français – Intégration, Résidence et Nationalité) is a French test accepted for a multi-year residence permit, a 10-year resident card and French citizenship. It takes 1 h 35 and has 4 sections. Your results are valid for 2 years.'
                        }}
                    </p>

                    <!-- Summary Table (no header row) -->
                    <div class="mt-8 bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-card">
                        <table class="w-full text-left text-sm">
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink bg-slate-50/70 w-1/3">{{ $isFr ? 'Pour qui' : "Who it's for" }}</td>
                                    <td class="py-4 px-6 text-slate-700">
                                        {{ $isFr 
                                            ? "Demandeurs d'une carte pluriannuelle, d'une carte de résident ou de la nationalité française"
                                            : "Anyone applying for a carte de séjour pluriannuelle (multi-year permit), a carte de résident (10-year card) or French citizenship"
                                        }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink bg-slate-50/70">{{ $isFr ? 'Niveaux évalués' : 'Levels tested' }}</td>
                                    <td class="py-4 px-6 font-semibold text-primary-600">A1 {{ $isFr ? 'à' : 'to' }} B2</td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink bg-slate-50/70">{{ $isFr ? 'Épreuves' : 'Sections' }}</td>
                                    <td class="py-4 px-6 text-slate-700">
                                        {{ $isFr 
                                            ? 'Compréhension orale, compréhension écrite, expression écrite, expression orale'
                                            : 'Listening, reading, writing, speaking'
                                        }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink bg-slate-50/70">{{ $isFr ? 'Durée' : 'Duration' }}</td>
                                    <td class="py-4 px-6 text-slate-700 font-semibold">{{ $isFr ? '1 h 35 au total' : '1 h 35 in total' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink bg-slate-50/70">{{ $isFr ? 'Validité' : 'Validity' }}</td>
                                    <td class="py-4 px-6 text-slate-700">
                                        {{ $isFr 
                                            ? '2 ans à compter de la délivrance de l\'attestation'
                                            : '2 years from the date the attestation is issued'
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 3: GAP ANALYSIS ============ -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> {{ $isFr ? 'Stratégie' : 'Strategy' }}</span>
                    <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink">
                        {{ $isFr ? 'Votre écart n\'est jamais le même partout' : 'Your gap is never the same in every skill' }}
                    </h2>
                    
                    <div class="mt-6 space-y-5 text-lg text-slate-600 leading-relaxed">
                        <p>
                            {{ $isFr
                                ? "Votre attestation donne un niveau pour chaque compétence. Pour la naturalisation, l'attestation doit indiquer un niveau global B2, calculé à partir des 4 compétences selon les règles de notation du TCF IRN."
                                : "Your results show a level for each skill. For citizenship, the attestation must show an overall B2 level, calculated from all 4 skills under the TCF IRN scoring rules."
                            }}
                        </p>
                        <p>
                            {{ $isFr
                                ? "Or l'écart est rarement uniforme. On peut lire au niveau B2 et parler au niveau B1. On peut tout comprendre à l'oral et perdre ses moyens devant une page blanche."
                                : "And the gap is rarely even. Plenty of people read at B2 and speak at B1. Others understand everything they hear, then freeze in front of a blank page."
                            }}
                        </p>
                        <div class="bg-primary-50/70 border-l-4 border-primary-600 rounded-2xl p-6 text-ink font-medium">
                            {{ $isFr
                                ? "Repasser le test, c'est repayer l'inscription et attendre une nouvelle session. Notre préparation commence donc par mesurer votre niveau dans chacune des 4 épreuves. Les heures vont ensuite là où l'écart est le plus grand."
                                : "A retake means paying the fee again and waiting for another session. So we start by measuring your level in each of the 4 sections. Then your hours go where the gap is widest."
                            }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 4: THE 4 SECTIONS ============ -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> {{ $isFr ? 'Structure' : 'Structure' }}</span>
                    <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink">
                        {{ $isFr ? 'Format du TCF IRN : les 4 épreuves' : 'TCF IRN format: the 4 sections' }}
                    </h2>
                    <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                        {{ $isFr
                            ? "Le TCF IRN comprend 4 épreuves obligatoires, passées le même jour. Les 2 épreuves de compréhension sont des questionnaires à choix multiple. Les 2 épreuves d'expression sont évaluées par des correcteurs et des examinateurs. Ce format, en vigueur depuis le 12 mai 2025, évalue jusqu'au niveau B2."
                            : "The TCF IRN has 4 compulsory sections, all taken on the same day. Listening and reading are multiple choice. Writing and speaking are marked by examiners. This format has applied since 12 May 2025 and tests up to B2."
                        }}
                    </p>

                    <!-- Table Format -->
                    <div class="mt-8 bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-card">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="py-4 px-6 font-bold text-ink">{{ $isFr ? 'Épreuve' : 'Section' }}</th>
                                    <th class="py-4 px-6 font-bold text-ink">{{ $isFr ? 'Format' : 'Format' }}</th>
                                    <th class="py-4 px-6 font-bold text-ink text-right">{{ $isFr ? 'Durée' : 'Time' }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink flex items-center gap-3">
                                        <i class="fas fa-headphones text-primary-600"></i>
                                        <span>{{ $isFr ? 'Compréhension orale' : 'Listening' }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-slate-600">{{ $isFr ? '25 questions à choix multiple' : '25 multiple-choice questions' }}</td>
                                    <td class="py-4 px-6 text-slate-700 font-semibold text-right">20 min</td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink flex items-center gap-3">
                                        <i class="fas fa-book-open text-primary-600"></i>
                                        <span>{{ $isFr ? 'Compréhension écrite' : 'Reading' }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-slate-600">{{ $isFr ? '25 questions à choix multiple' : '25 multiple-choice questions' }}</td>
                                    <td class="py-4 px-6 text-slate-700 font-semibold text-right">35 min</td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink flex items-center gap-3">
                                        <i class="fas fa-pen-nib text-[#EF4135]"></i>
                                        <span>{{ $isFr ? 'Expression écrite' : 'Writing' }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-slate-600">{{ $isFr ? '3 exercices' : '3 tasks' }}</td>
                                    <td class="py-4 px-6 text-slate-700 font-semibold text-right">30 min</td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink flex items-center gap-3">
                                        <i class="fas fa-comments text-[#EF4135]"></i>
                                        <span>{{ $isFr ? 'Expression orale' : 'Speaking' }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-slate-600">{{ $isFr ? 'Entretien individuel avec un examinateur' : '1-to-1 interview with an examiner' }}</td>
                                    <td class="py-4 px-6 text-slate-700 font-semibold text-right">10 min</td>
                                </tr>
                                <tr class="bg-primary-50/50 font-bold">
                                    <td class="py-4 px-6 text-primary-900" colspan="2">{{ $isFr ? 'Total' : 'Total' }}</td>
                                    <td class="py-4 px-6 text-primary-900 text-right">1 h 35</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 5: TRAPS & POINTS LOSS ============ -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center reveal mb-12">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> {{ $isFr ? 'Pièges d\'examen' : 'Common Traps' }}</span>
                    <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink">
                        {{ $isFr ? 'Où les candidats perdent des points au TCF IRN' : 'Where TCF IRN candidates lose points' }}
                    </h2>
                </div>

                <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                    <!-- Trap 1 -->
                    <div class="card-glow bg-white rounded-3xl border border-slate-100 p-7 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-10 h-10 rounded-xl bg-accent-soft text-[#EF4135] grid place-content-center shrink-0"><i class="fas fa-clock"></i></span>
                            <h3 class="text-xl font-bold text-ink">{{ $isFr ? 'Le chrono, plus que la grammaire' : 'The clock, more than the grammar' }}</h3>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            {{ $isFr 
                                ? "En compréhension écrite, vous avez 35 minutes pour 25 questions. Cela fait 84 secondes par question, lecture comprise. Bloquer 3 minutes sur une question, c'est utiliser le temps de 2."
                                : "Reading gives you 35 minutes for 25 questions. That's 84 seconds a question, reading included. Spend 3 minutes stuck on 1 question, and you've used the time of 2."
                            }}
                        </p>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-start gap-2 text-sm text-primary-700 font-medium">
                            <i class="fas fa-circle-check mt-1 text-primary-600 shrink-0"></i>
                            <span><strong>{{ $isFr ? 'Ce que nous faisons :' : 'What we do:' }}</strong> {{ $isFr ? 'des séries chronométrées, jusqu\'à ce que le rythme devienne automatique.' : 'timed drills until the pace is automatic.' }}</span>
                        </div>
                    </div>

                    <!-- Trap 2 -->
                    <div class="card-glow bg-white rounded-3xl border border-slate-100 p-7 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 grid place-content-center shrink-0"><i class="fas fa-volume-high"></i></span>
                            <h3 class="text-xl font-bold text-ink">{{ $isFr ? 'L\'oreille habituée au français lent' : 'Ears trained on slow French' }}</h3>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            {{ $isFr 
                                ? "Les documents audio du test sont courts et parlés à vitesse naturelle. Les méthodes d'apprentissage, elles, parlent souvent lentement. L'écart se découvre le jour J."
                                : "The audio is short and spoken at natural speed. Most courses speak slowly. People find out the difference on exam day."
                            }}
                        </p>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-start gap-2 text-sm text-primary-700 font-medium">
                            <i class="fas fa-circle-check mt-1 text-primary-600 shrink-0"></i>
                            <span><strong>{{ $isFr ? 'Ce que nous faisons :' : 'What we do:' }}</strong> {{ $isFr ? 'des exercices d\'écoute à vitesse native dans votre espace élève, dès la première semaine.' : 'native-speed listening practice in your student portal from week 1.' }}</span>
                        </div>
                    </div>

                    <!-- Trap 3 -->
                    <div class="card-glow bg-white rounded-3xl border border-slate-100 p-7 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 grid place-content-center shrink-0"><i class="fas fa-file-lines"></i></span>
                            <h3 class="text-xl font-bold text-ink">{{ $isFr ? '3 textes en 30 minutes' : '3 texts in 30 minutes' }}</h3>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            {{ $isFr 
                                ? "L'expression écrite demande 3 productions en 30 minutes. Le piège classique : soigner la première et bâcler la dernière. Le correcteur, lui, lit les 3."
                                : "The writing section asks for 3 pieces in 30 minutes. The classic trap: polishing the first and rushing the last. The examiner reads all 3."
                            }}
                        </p>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-start gap-2 text-sm text-primary-700 font-medium">
                            <i class="fas fa-circle-check mt-1 text-primary-600 shrink-0"></i>
                            <span><strong>{{ $isFr ? 'Ce que nous faisons :' : 'What we do:' }}</strong> {{ $isFr ? 'un plan de temps par exercice et des structures de phrases à adapter, pas à réciter.' : 'a time plan for each task and sentence structures you adapt rather than memorise.' }}</span>
                        </div>
                    </div>

                    <!-- Trap 4 -->
                    <div class="card-glow bg-white rounded-3xl border border-slate-100 p-7 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-10 h-10 rounded-xl bg-accent-soft text-[#EF4135] grid place-content-center shrink-0"><i class="fas fa-comment-slash"></i></span>
                            <h3 class="text-xl font-bold text-ink">{{ $isFr ? 'Des réponses trop courtes à l\'oral' : 'Answers that are too short' }}</h3>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            {{ $isFr 
                                ? "En 10 minutes, l'examinateur doit entendre ce qui fait un B2 : argumenter, nuancer, illustrer. Une réponse de 2 phrases ne lui en laisse pas l'occasion."
                                : "In 10 minutes, the examiner needs to hear B2 skills: arguing, nuancing, giving examples. A 2-sentence answer gives them nothing to mark."
                            }}
                        </p>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-start gap-2 text-sm text-primary-700 font-medium">
                            <i class="fas fa-circle-check mt-1 text-primary-600 shrink-0"></i>
                            <span><strong>{{ $isFr ? 'Ce que nous faisons :' : 'What we do:' }}</strong> {{ $isFr ? 'une méthode simple pour chaque réponse — une idée, un exemple, une nuance.' : 'a simple pattern for every answer — 1 idea, 1 example, 1 nuance.' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 6: HOW WE PREPARE YOU ============ -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> {{ $isFr ? 'Méthode' : 'Methodology' }}</span>
                    <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink">
                        {{ $isFr ? 'Notre préparation au TCF IRN' : 'How we prepare you for the TCF IRN' }}
                    </h2>

                    @php
                        $prepSteps = $isFr ? [
                            "<strong>Votre niveau, épreuve par épreuve.</strong> Dès le premier cours, votre tuteur situe chaque compétence face au niveau exigé par votre démarche.",
                            "<strong>Un plan construit sur votre écart.</strong> Les heures vont aux épreuves où vous êtes le plus loin du but. Pas de programme générique.",
                            "<strong>Des cours en direct, 2 ou 4 h par semaine.</strong> En particulier ou en mini-groupe de 2 à 5 personnes, sur Google Meet. Vous recevez les notes après chaque séance.",
                            "<strong>Des examens blancs au chrono.</strong> Votre espace élève contient des tests au format de l'examen et des banques d'exercices par compétence. Chaque devoir reçoit un retour."
                        ] : [
                            "<strong>Your level, section by section.</strong> In your first class, your tutor places each skill against the level your application needs.",
                            "<strong>A plan built on your gap.</strong> Your hours go to the sections where you're furthest from the target. No generic syllabus.",
                            "<strong>Live classes, 2 or 4 hours a week.</strong> 1:1 or in a group of 2–5, on Google Meet. You get the class notes after every session.",
                            "<strong>Timed mock exams.</strong> Your student portal has exam-format tests and practice banks for each skill. Every piece of homework gets feedback."
                        ];
                    @endphp

                    <div class="mt-8 space-y-4">
                        @foreach($prepSteps as $idx => $step)
                        <div class="flex items-start gap-4 bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                            <span class="w-8 h-8 rounded-full bg-primary-600 text-white grid place-content-center text-sm font-bold shrink-0 mt-0.5">{{ $idx + 1 }}</span>
                            <p class="text-slate-700 leading-relaxed">{!! $step !!}</p>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-10 text-center flex flex-col items-center gap-2">
                        <a href="/register" class="btn btn-primary btn-lg">
                            {{ $isFr ? "S'inscrire" : 'Enroll now' }} <i class="fas fa-arrow-right text-xs ml-1"></i>
                        </a>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium">
                            {{ $isFr 
                                ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' 
                                : 'Live classes on Google Meet · 1:1 or small group · Student portal included' 
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 7: REQUIRED LEVELS ============ -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> {{ $isFr ? 'Exigences officielles' : 'Requirements' }}</span>
                    <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink">
                        {{ $isFr ? 'Quel niveau viser au TCF IRN ?' : 'What TCF IRN level do you need?' }}
                    </h2>
                    <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                        {{ $isFr
                            ? "Tout dépend de votre démarche : A2 pour une première carte de séjour pluriannuelle, B1 pour la carte de résident, B2 pour la naturalisation. Ces niveaux s'appliquent depuis le 1er janvier 2026. Vérifiez celui de votre dossier avant de vous inscrire au test."
                            : "It depends on your application: A2 for a first multi-year residence permit, B1 for the 10-year resident card, B2 for citizenship. These levels have applied since 1 January 2026. Check which one applies to you before you book the test."
                        }}
                    </p>

                    <!-- Table -->
                    <div class="mt-8 bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-card">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="py-4 px-6 font-bold text-ink">{{ $isFr ? 'Démarche' : 'Application' }}</th>
                                    <th class="py-4 px-6 font-bold text-ink">{{ $isFr ? 'Niveau exigé' : 'Level required' }}</th>
                                    <th class="py-4 px-6 font-bold text-ink text-right">{{ $isFr ? 'En savoir plus' : 'Read more' }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink">{{ $isFr ? 'Carte de séjour pluriannuelle' : 'Multi-year residence permit' }}</td>
                                    <td class="py-4 px-6 font-bold text-primary-600">A2</td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ $isFr ? '/fr/titre-de-sejour-niveau-francais' : '/en/french-residence-permit-language' }}" class="text-primary-600 underline font-semibold hover:text-primary-700 inline-flex items-center gap-1">
                                            <span>{{ $isFr ? 'Niveau de français pour un titre de séjour' : 'French level for residence permits' }}</span>
                                            <i class="fas fa-arrow-right text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink">{{ $isFr ? 'Carte de résident' : '10-year resident card' }}</td>
                                    <td class="py-4 px-6 font-bold text-primary-600">B1</td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ $isFr ? '/fr/titre-de-sejour-niveau-francais' : '/en/french-residence-permit-language' }}" class="text-primary-600 underline font-semibold hover:text-primary-700 inline-flex items-center gap-1">
                                            <span>{{ $isFr ? 'Niveau de français pour un titre de séjour' : 'French level for residence permits' }}</span>
                                            <i class="fas fa-arrow-right text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink">{{ $isFr ? 'Naturalisation' : 'French citizenship' }}</td>
                                    <td class="py-4 px-6 font-bold text-[#EF4135]">B2</td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ $isFr ? '/fr/test-francais-naturalisation' : '/en/french-citizenship-language-test' }}" class="text-primary-600 underline font-semibold hover:text-primary-700 inline-flex items-center gap-1">
                                            <span>{{ $isFr ? 'Test de français pour la naturalisation' : 'The French citizenship language test' }}</span>
                                            <i class="fas fa-arrow-right text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="mt-6 text-sm text-slate-600 leading-relaxed">
                        @if($isFr)
                            Depuis 2026, un <a href="/fr/examen-civique" class="text-primary-600 underline font-semibold hover:text-primary-700">examen civique</a> est aussi exigé pour la naturalisation par décret et pour les titres de séjour concernés par les règles de 2026. Vous hésitez encore sur le test ? <a href="/fr/tcf-tef-ou-delf" class="text-primary-600 underline font-semibold hover:text-primary-700">Comparez le TCF, le TEF et le DELF</a>.
                        @else
                            Citizenship by decree and the residence permit applications covered by the 2026 rules also need the <a href="/en/french-civic-exam" class="text-primary-600 underline font-semibold hover:text-primary-700">French civic exam</a>. Not sure which test to take? <a href="/en/tcf-vs-tef-vs-delf" class="text-primary-600 underline font-semibold hover:text-primary-700">Compare the TCF, TEF and DELF</a>.
                        @endif
                    </p>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 8: FAQ ============ -->
        <section id="faq" class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                <div class="text-center mb-12 reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> FAQ</span>
                    <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink">
                        {{ $isFr ? 'Questions fréquentes sur le TCF IRN' : 'TCF IRN FAQ' }}
                    </h2>
                </div>

                <div class="space-y-3 reveal">
                    @foreach($faqs as $item)
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                        <button class="faq-toggle w-full px-6 py-5 flex justify-between items-center text-left gap-4 cursor-pointer">
                            <span class="font-bold text-ink text-base">{{ $item['q'] }}</span>
                            <i class="fas fa-plus text-primary-600 text-sm transition-transform duration-300 shrink-0"></i>
                        </button>
                        <div class="faq-content hidden px-6 pb-6 text-slate-600 leading-relaxed text-sm">
                            {!! $item['a'] !!}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ============ SECTION 9: FINAL CTA & SOURCES ============ -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal rounded-5xl bg-primary-600 text-white text-center px-6 py-14 md:py-16 relative overflow-hidden max-w-4xl mx-auto shadow-lift">
                    <div class="absolute inset-x-0 bottom-0 h-1.5" style="background:linear-gradient(90deg,#003d7a 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>

                    <h2 class="text-3xl md:text-5xl font-semibold leading-tight max-w-2xl mx-auto">
                        {{ $isFr ? 'Votre niveau. Le niveau exigé. Nous comblons l\'écart.' : 'Your level. The required level. We close the gap.' }}
                    </h2>

                    <p class="mt-4 text-lg text-primary-100 max-w-xl mx-auto">
                        {{ $isFr 
                            ? 'Votre premier cours fixe votre niveau dans chaque épreuve et le plan pour atteindre celui de votre dossier. Vous choisissez le rythme : 2 ou 4 heures par semaine.' 
                            : 'Your first class sets your level in each section and the plan to reach the one your application needs. You choose the pace: 2 or 4 hours a week.' 
                        }}
                    </p>

                    <div class="mt-8 flex flex-col items-center gap-3">
                        <a href="/register" class="btn btn-onnavy btn-lg">
                            {{ $isFr ? "S'inscrire" : 'Enroll now' }}
                        </a>
                        <p class="text-xs sm:text-sm text-primary-100 font-medium">
                            {{ $isFr 
                                ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' 
                                : 'Live classes on Google Meet · 1:1 or small group · Student portal included' 
                            }}
                        </p>
                    </div>

                    <!-- Referral note -->
                    <div class="mt-8 pt-6 border-t border-white/20 text-xs sm:text-sm text-primary-100">
                        @if($isFr)
                            Vous venez à deux ? 50 € de remise par mois, pour vous et pour votre ami. <a href="/fr/contact#parrainage" class="text-white underline font-bold hover:text-accent">Contactez-nous</a>
                        @else
                            Coming with a friend? €50 off per month, for you and for them. <a href="/en/contact#referral" class="text-white underline font-bold hover:text-accent">Contact us</a>
                        @endif
                    </div>
                </div>

                <!-- Official Sources Line -->
                <div class="mt-12 text-center text-xs text-slate-400 max-w-3xl mx-auto">
                    <p>
                        <strong>{{ $isFr ? 'Sources :' : 'Sources:' }}</strong>
                        <a href="https://www.france-education-international.fr/" target="_blank" rel="noopener noreferrer" class="underline hover:text-slate-600">France Éducation international – TCF IRN</a>
                        ·
                        <a href="https://www.service-public.gouv.fr/" target="_blank" rel="noopener noreferrer" class="underline hover:text-slate-600">Service-public.fr – {{ $isFr ? 'naturalisation' : 'citizenship' }}</a>
                        ·
                        <a href="https://www.service-public.gouv.fr/" target="_blank" rel="noopener noreferrer" class="underline hover:text-slate-600">Service-public.fr – {{ $isFr ? 'cartes de séjour' : 'residence cards' }}</a>
                    </p>
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
                if (icon) icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(45deg)';
            });
        });
    </script>
</body>
</html>

