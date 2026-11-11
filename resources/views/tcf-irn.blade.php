<!DOCTYPE html>
@php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';

    // Determine language based on current URL prefix
    $isFr = request()->is('fr*');

    // Page metadata according to Spec Page 02
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
            'a' => "2 ans à compter de la délivrance de votre attestation. Si votre calendrier est incertain, regardez aussi le DELF B2 : c'est un diplôme, et il n'expire jamais."
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
            'a' => "2 years from the date your attestation is issued. If your timeline is uncertain, look at the DELF B2 too: it's a diploma, and it never expires."
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
@endphp
<html lang="{{ $isFr ? 'fr' : 'en' }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="fr" href="{{ $frUrl }}">
    <link rel="alternate" hreflang="en" href="{{ $enUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $enUrl }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "Course",
          "name": "{{ $isFr ? 'Préparation au TCF IRN en ligne' : 'TCF IRN Online Preparation' }}",
          "description": "{{ $metaDesc }}",
          "provider": {
            "@type": "EducationalOrganization",
            "name": "Fluence Française",
            "url": "https://fluencefrancaise.fr"
          },
          "courseMode": "online",
          "inLanguage": ["fr", "en"]
        },
        {
          "@type": "BreadcrumbList",
          "itemListElement": [
            {
              "@type": "ListItem",
              "position": 1,
              "name": "{{ $isFr ? 'Accueil' : 'Home' }}",
              "item": "{{ $isFr ? 'https://fluencefrancaise.fr/fr/' : 'https://fluencefrancaise.fr/en/' }}"
            },
            {
              "@type": "ListItem",
              "position": 2,
              "name": "{{ $isFr ? 'Examens' : 'Exams' }}",
              "item": "{{ $isFr ? 'https://fluencefrancaise.fr/fr/#exams' : 'https://fluencefrancaise.fr/en/#exams' }}"
            },
            {
              "@type": "ListItem",
              "position": 3,
              "name": "TCF IRN",
              "item": "{{ $canonicalUrl }}"
            }
          ]
        },
        {
          "@type": "FAQPage",
          "mainEntity": [
            @foreach($faqs as $index => $faq)
                {
                  "@type": "Question",
                  "name": "{{ addslashes($faq['q']) }}",
                  "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "{{ addslashes($faq['a']) }}"
                  }
                }{{ $index < count($faqs) - 1 ? ',' : '' }}
            @endforeach
          ]
        }
      ]
    }
    </script>

    @include('partials.favicon')

    <!-- Shared tricolor theme (fonts, tailwind config, components, aurora) -->
    @include('partials.theme')

    @include('partials.custom-scripts-head')
</head>

<body class="antialiased bg-white text-ink">
    @include('partials.custom-scripts-body')

    <div class="landing-page min-h-screen flex flex-col">
        <!-- Top Site Header (Floating pill navbar) -->
        @include('partials.header')

        <main class="flex-grow">
            <!-- ============ SECTION 1: HERO (HOMEPAGE AURORA STYLE) ============ -->
            <section
                class="relative overflow-hidden bg-white pt-28 sm:pt-36 md:pt-40 pb-16 md:pb-20 border-b border-slate-100">
                <div class="hero-aurora-strong"></div>

                <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="max-w-3xl mx-auto text-center reveal">
                        <div class="mb-5 flex justify-center">
                            <nav aria-label="Breadcrumb"
                                class="text-xs text-slate-400 font-medium flex items-center gap-2">
                                <a href="{{ $isFr ? '/fr' : '/en' }}"
                                    class="hover:text-primary-600 transition-colors">{{ $isFr ? 'Accueil' : 'Home' }}</a>
                                <span class="text-slate-300">›</span>
                                <a href="{{ $isFr ? '/fr#exams' : '/en#exams' }}"
                                    class="hover:text-primary-600 transition-colors">{{ $isFr ? 'Examens' : 'Exams' }}</a>
                                <span class="text-slate-300">›</span>
                                <span class="text-slate-700 font-semibold">TCF IRN</span>
                            </nav>
                        </div>

                        <!-- Centered Eyebrow with Blue & Red Dashes (Image 1 Style) -->
                        <div class="inline-flex items-center justify-center gap-1.5 mb-5">
                            <div class="inline-flex items-center justify-center gap-1.5 mb-5">
                                <span class="inline-block w-3.5 h-[3px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[3px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span
                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                    {{ $isFr ? 'VOTRE PROCHAIN CHAPITRE COMMENCE PAR LA BONNE PRÉPARATION' : 'YOUR NEXT CHAPTER STARTS WITH THE RIGHT PREPARATION' }}
                                </span>
                            </div>

                            <!-- H1 Title with Attached French Tricolor Underline (Exact Image 1 Match) -->
                            <h1 <h1
                                class="text-3xl sm:text-5xl lg:text-[56px] font-bold text-[#0c1e38] tracking-medium leading-[1.30] sm:leading-[1.32]">
                                @if($isFr)
                                    TCF IRN : préparez exactement<br class="hidden sm:inline" />
                                    <span class="relative inline-block text-[#0055a4] mt-1 sm:mt-1.5 pb-2.5 sm:pb-3.5">
                                        le niveau exigé
                                        <span class="absolute left-0 right-0 bottom-0 h-[2.5px] sm:h-[3px] rounded-full"
                                            style="background: linear-gradient(90deg, #0055A4 0%, #0055A4 33.3%, #eaebf0 33.3%, #eaebf0 66.6%, #EF4135 66.6%, #EF4135 100%);"></span>
                                    </span>
                                @else
                                    TCF IRN: Prepare for the<br class="hidden sm:inline" />
                                    <span class="relative inline-block text-[#0055a4] mt-1 sm:mt-1.5 pb-2.5 sm:pb-3.5">
                                        Exact Level You Need
                                        <span class="absolute left-0 right-0 bottom-0 h-[2.5px] sm:h-[3px] rounded-full"
                                            style="background: linear-gradient(90deg, #0055A4 0%, #0055A4 33.3%, #eaebf0 33.3%, #eaebf0 66.6%, #EF4135 66.6%, #EF4135 100%);"></span>
                                    </span>
                                @endif
                            </h1>

                            <!-- Subhead -->
                            <p <p
                                class="mt-5 sm:mt-6 text-sm sm:text-base md:text-[17px] text-slate-600 max-w-2xl mx-auto leading-relaxed font-normal">
                                {{ $isFr
    ? 'Le TCF IRN mesure chaque compétence séparément : nous travaillons celles qui vous séparent du niveau exigé par votre dossier.'
    : 'The TCF IRN scores each skill separately, so we train the skills standing between you and the level your application requires.' 
                            }}
                            </p>

                            <!-- CTA Button & Under-button text -->
                            <div class="mt-8 flex flex-col items-center gap-3">
                                <div class="mt-8 flex flex-col items-center gap-3">
                                    <a href="/register"
                                        class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055a4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5">
                                        {{ $isFr ? "S'inscrire" : 'Enroll now' }}
                                        <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                                    </a>
                                    <p class="text-xs sm:text-[13px] text-slate-500 font-medium">
                                        {{ $isFr
    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
    : 'Live classes on Google Meet · 1:1 or small group · Student portal included' 
                                }}
                                    </p>
                                </div>

                                <!-- 2026 requirements / Updated date tag (Image 1 Style) -->
                                <div
                                    class="mt-6 flex items-center justify-center gap-2 text-xs font-semibold text-slate-400">
                                    <div
                                        class="mt-6 flex items-center justify-center gap-2 text-xs font-semibold text-slate-400">
                                        <span class="text-slate-400 text-[9px]">●</span>
                                        <span>{{ $isFr ? 'Exigences 2026' : '2026 requirements' }}</span>
                                        <span class="text-slate-300">/</span>
                                        <span>{{ $isFr ? 'Mis à jour le 11 novembre 2026' : 'Updated 11 November 2026' }}</span>
                                    </div>
                                </div>
                            </div>
            </section>

            <!-- ============ SECTION 2: THE TCF IRN AT A GLANCE ============ -->
            <!-- ============ SECTION 2: THE TCF IRN AT A GLANCE ============ -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-14 items-center">
                        <!-- Left Column: Title & Intro -->
                        <div class="lg:col-span-7 space-y-4 sm:space-y-5">
                            <div class="inline-flex items-center gap-1.5">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span
                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                    <span
                                        class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                        {{ $isFr ? "CONNAÎTRE L'EXAMEN" : 'KNOW YOUR EXAM' }}
                                    </span>
                            </div>

                            <h2
                                class="text-3xl sm:text-4xl md:text-[42px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                <h2
                                    class="text-3xl sm:text-4xl md:text-[42px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                    {{ $isFr ? 'Le TCF IRN en bref' : 'The TCF IRN at a glance' }}
                                </h2>

                                <p
                                    class="text-sm sm:text-[15px] md:text-base text-slate-500 leading-relaxed font-normal">
                                    {{ $isFr
    ? 'Le TCF IRN (Test de connaissance du français – Intégration, Résidence et Nationalité) est accepté pour la carte de séjour pluriannuelle, la carte de résident et la naturalisation. Il dure 1 h 35 et compte 4 épreuves. Son attestation est valable 2 ans.'
    : 'The TCF IRN (Test de connaissance du français – Intégration, Résidence et Nationalité) is a French test accepted for a multi-year residence permit, a 10-year resident card and French citizenship. It takes 1 h 35 and has 4 sections. Your results are valid for 2 years.'
    ? 'Le TCF IRN (Test de connaissance du français – Intégration, Résidence et Nationalité) est accepté pour la carte de séjour pluriannuelle, la carte de résident et la naturalisation. Il dure 1 h 35 et compte 4 épreuves. Son attestation est valable 2 ans.'
    : 'The TCF IRN (Test de connaissance du français – Intégration, Résidence et Nationalité) is a French test accepted for a multi-year residence permit, a 10-year resident card and French citizenship. It takes 1 h 35 and has 4 sections. Your results are valid for 2 years.'
                                }}
                                </p>

                                <!-- Trust Line (Matching Image 1) -->
                                <div
                                    class="pt-2 flex items-center gap-2.5 text-xs sm:text-[13px] text-slate-500 font-medium">
                                    <svg class="w-4 h-4 text-[#0055A4] shrink-0" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        <div
                                            class="pt-2 flex items-center gap-2.5 text-xs sm:text-[13px] text-slate-500 font-medium">
                                            <svg class="w-4 h-4 text-[#0055A4] shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            <span>{{ $isFr ? 'Préparation en direct en ligne. Confiance totale le jour de l\'examen.' : 'Live online preparation. Real exam-day confidence.' }}</span>
                                        </div>

                                        <!-- Official Guidelines Link -->
                                        <div class="pt-1">
                                            <a href="https://www.france-education-international.fr/article/tcf-integration-residence-et-nationalite"
                                                target="_blank" rel="noopener"
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0055a4] hover:text-[#002654] group transition-colors">
                                                <span>{{ $isFr ? 'Règlement officiel France Éducation international' : 'Official France Éducation international guidelines' }}</span>
                                                <i
                                                    class="fas fa-arrow-up-right-from-square text-[10px] group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                                <i
                                                    class="fas fa-arrow-up-right-from-square text-[10px] group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                            </a>
                                        </div>
                                </div>

                                <div class="lg:col-span-5">
                                    <div
                                        class="rounded-3xl bg-[#002654] text-white overflow-hidden shadow-xl shadow-blue-950/15 relative">
                                        <!-- Top French Tricolor Bar -->
                                        <div
                                            class="rounded-3xl bg-[#002654] text-white overflow-hidden border border-[#001f3f] relative">
                                            <div class="h-[3px] w-full flex">
                                                <span class="w-1/3 h-full bg-[#0055A4]"></span>
                                                <span class="w-1/3 h-full bg-white"></span>
                                                <span class="w-1/3 h-full bg-[#EF4135]"></span>
                                            </div>

                                            <!-- 2x2 Stats Grid with Cross Dividers -->
                                            <div class="grid grid-cols-2 divide-x divide-white/10">
                                                <!-- Left Column: Stat 1 (top) and Stat 3 (bottom) -->
                                                <div class="divide-y divide-white/10">
                                                    <!-- Stat 1: 4 Compulsory sections -->
                                                    <div class="p-6 sm:p-7 space-y-2">
                                                        <div class="text-blue-200/70">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="1.75"
                                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="1.75"
                                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                                </svg>
                                                        </div>
                                                        <div
                                                            class="text-3xl sm:text-4xl font-bold font-display text-white tracking-tight">
                                                            4</div>
                                                        <div
                                                            class="text-3xl sm:text-4xl font-bold font-display text-white tracking-tight">
                                                            4</div>
                                                        <div
                                                            class="text-xs sm:text-[13px] text-blue-200/80 font-normal">
                                                            {{ $isFr ? 'Épreuves obligatoires' : 'Compulsory sections' }}
                                                        </div>
                                                    </div>

                                                    <!-- Stat 3: A1–B2 Levels tested -->
                                                    <div class="p-6 sm:p-7 space-y-2">
                                                        <div class="text-blue-200/70">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="1.75"
                                                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="1.75"
                                                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                                </svg>
                                                        </div>
                                                        <div
                                                            class="text-3xl sm:text-4xl font-bold font-display text-white tracking-tight">
                                                            A1–B2</div>
                                                        <div
                                                            class="text-3xl sm:text-4xl font-bold font-display text-white tracking-tight">
                                                            A1–B2</div>
                                                        <div
                                                            class="text-xs sm:text-[13px] text-blue-200/80 font-normal">
                                                            {{ $isFr ? 'Niveaux évalués' : 'Levels tested' }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Right Column: Stat 2 (top) and Stat 4 (bottom) -->
                                                <div class="divide-y divide-white/10">
                                                    <!-- Stat 2: 1 h 35 Total test time -->
                                                    <div class="p-6 sm:p-7 space-y-2">
                                                        <div class="text-blue-200/70">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="1.75"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="1.75"
                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                        </div>
                                                        <div
                                                            class="text-3xl sm:text-4xl font-bold font-display text-white tracking-tight">
                                                            1 h 35</div>
                                                        <div
                                                            class="text-3xl sm:text-4xl font-bold font-display text-white tracking-tight">
                                                            1 h 35</div>
                                                        <div
                                                            class="text-xs sm:text-[13px] text-blue-200/80 font-normal">
                                                            {{ $isFr ? 'Durée totale du test' : 'Total test time' }}
                                                        </div>
                                                    </div>

                                                    <!-- Stat 4: 2 years Results validity -->
                                                    <div class="p-6 sm:p-7 space-y-2">
                                                        <div class="text-blue-200/70">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="1.75"
                                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="1.75"
                                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                                </svg>
                                                        </div>
                                                        <div
                                                            class="text-3xl sm:text-4xl font-bold font-display text-white tracking-tight">
                                                            2 {{ $isFr ? 'ans' : 'years' }}</div>
                                                        <div
                                                            class="text-3xl sm:text-4xl font-bold font-display text-white tracking-tight">
                                                            2 {{ $isFr ? 'ans' : 'years' }}</div>
                                                        <div
                                                            class="text-xs sm:text-[13px] text-blue-200/80 font-normal">
                                                            {{ $isFr ? "Validité des résultats" : 'Results validity' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Clean Summary Table (Alternating Zebra Striped Rows, Matching Image 1) -->
                                <div
                                    class="mt-10 sm:mt-12 bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl overflow-hidden">
                                    <div
                                        class="mt-10 sm:mt-12 bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl overflow-hidden">
                                        <div class="divide-y divide-slate-100">
                                            <!-- Row 1: Who it's for -->
                                            <div
                                                class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-[#f8fafc] items-center gap-2 sm:gap-4">
                                                <div
                                                    class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-[#f8fafc] items-center gap-2 sm:gap-4">
                                                    <div
                                                        class="sm:col-span-3 font-bold text-xs sm:text-sm text-[#10233e]">
                                                        {{ $isFr ? 'Pour qui' : "Who it's for" }}
                                                    </div>
                                                    <div
                                                        class="sm:col-span-9 text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                                        <div
                                                            class="sm:col-span-9 text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                                            {{ $isFr
    ? "Demandeurs d'une carte de séjour pluriannuelle, d'une carte de résident ou de la nationalité française"
    : "Anyone applying for a carte de séjour pluriannuelle (multi-year permit), a carte de résident (10-year card) or French citizenship"
    ? "Demandeurs d'une carte de séjour pluriannuelle, d'une carte de résident ou de la nationalité française"
    : "Anyone applying for a carte de séjour pluriannuelle (multi-year permit), a carte de résident (10-year card) or French citizenship"
                                    }}
                                                        </div>
                                                    </div>

                                                    <!-- Row 2: Levels tested -->
                                                    <div
                                                        class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-white items-center gap-2 sm:gap-4">
                                                        <div
                                                            class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-white items-center gap-2 sm:gap-4">
                                                            <div
                                                                class="sm:col-span-3 font-bold text-xs sm:text-sm text-[#10233e]">
                                                                {{ $isFr ? 'Niveaux évalués' : 'Levels tested' }}
                                                            </div>
                                                            <div
                                                                class="sm:col-span-9 text-xs sm:text-sm text-slate-600 font-normal">
                                                                A1 {{ $isFr ? 'à' : 'to' }} B2
                                                            </div>
                                                        </div>

                                                        <!-- Row 3: Sections -->
                                                        <div
                                                            class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-[#f8fafc] items-center gap-2 sm:gap-4">
                                                            <div
                                                                class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-[#f8fafc] items-center gap-2 sm:gap-4">
                                                                <div
                                                                    class="sm:col-span-3 font-bold text-xs sm:text-sm text-[#10233e]">
                                                                    {{ $isFr ? 'Épreuves' : 'Sections' }}
                                                                </div>
                                                                <div
                                                                    class="sm:col-span-9 text-xs sm:text-sm text-slate-600 font-normal">
                                                                    {{ $isFr
    ? 'Compréhension orale, compréhension écrite, expression écrite, expression orale'
    : 'Listening, reading, writing, speaking'
    ? 'Compréhension orale, compréhension écrite, expression écrite, expression orale'
    : 'Listening, reading, writing, speaking'
                                    }}
                                                                </div>
                                                            </div>

                                                            <!-- Row 4: Duration -->
                                                            <div
                                                                class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-white items-center gap-2 sm:gap-4">
                                                                <div
                                                                    class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-white items-center gap-2 sm:gap-4">
                                                                    <div
                                                                        class="sm:col-span-3 font-bold text-xs sm:text-sm text-[#10233e]">
                                                                        {{ $isFr ? 'Durée' : 'Duration' }}
                                                                    </div>
                                                                    <div
                                                                        class="sm:col-span-9 text-xs sm:text-sm text-slate-600 font-normal">
                                                                        {{ $isFr ? '1 h 35 au total' : '1 h 35 in total' }}
                                                                    </div>
                                                                </div>

                                                                <!-- Row 5: Validity -->
                                                                <div
                                                                    class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-[#f8fafc] items-center gap-2 sm:gap-4">
                                                                    <div
                                                                        class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-[#f8fafc] items-center gap-2 sm:gap-4">
                                                                        <div
                                                                            class="sm:col-span-3 font-bold text-xs sm:text-sm text-[#10233e]">
                                                                            {{ $isFr ? 'Validité' : 'Validity' }}
                                                                        </div>
                                                                        <div
                                                                            class="sm:col-span-9 text-xs sm:text-sm text-slate-600 font-normal">
                                                                            {{ $isFr
    ? "2 ans à compter de la délivrance de l'attestation"
    : '2 years from the date the attestation is issued'
    ? "2 ans à compter de la délivrance de l'attestation"
    : '2 years from the date the attestation is issued'
                                    }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
            </section>

            <!-- ============ SECTION 3: YOUR GAP IS NEVER THE SAME ============ -->
            <section class="py-16 sm:py-24 bg-[#f8fafc] border-b border-slate-100">
                <section class="py-16 sm:py-24 bg-[#f4f7fb] border-b border-slate-100">
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                            <!-- Left Column: Strategy & Gap explanation -->
                            <div class="lg:col-span-6 space-y-5">
                                <div
                                    class="inline-flex items-center gap-2 text-xs font-bold tracking-wider uppercase text-primary-600">
                                    <span class="w-2 h-2 rounded-full bg-[#0055a4]"></span>
                                    {{ $isFr ? 'Diagnostic personnalisé' : 'Diagnostic Strategy' }}
                                    <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-center">
                                        <div class="lg:col-span-6 space-y-4">
                                            <div class="inline-flex items-center gap-1.5">
                                                <span
                                                    class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                                <span
                                                    class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                                <span
                                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                                    {{ $isFr ? 'UNE MEILLEURE MÉTHODE' : 'A SMARTER WAY TO PREPARE' }}
                                                </span>
                                            </div>
                                            <h2
                                                class="text-2xl sm:text-4xl font-extrabold text-[#002654] tracking-tight">
                                                {{ $isFr ? "Votre écart n'est jamais le même partout" : 'Your gap is never the same in every skill' }}

                                                <h2
                                                    class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                                    {{ $isFr ? "Votre écart n'est jamais le même partout." : 'Your gap is never the same in every skill.' }}
                                                </h2>
                                                <p class="text-base text-slate-600 leading-relaxed font-normal">
                                                    {{ $isFr
    ? "Votre attestation donne un niveau pour chaque compétence. Pour la naturalisation, l'attestation doit indiquer un niveau global B2, calculé à partir des 4 compétences selon les règles de notation du TCF IRN."
    : "Your results show a level for each skill. For citizenship, the attestation must show an overall B2 level, calculated from all 4 skills under the TCF IRN scoring rules."
                                }}
                                                </p>
                                                <p class="text-base text-slate-600 leading-relaxed font-normal">
                                                    {{ $isFr
    ? "Or l'écart est rarement uniforme. On peut lire au niveau B2 et parler au niveau B1. On peut tout comprendre à l'oral et perdre ses moyens devant une page blanche."
    : "And the gap is rarely even. Plenty of people read at B2 and speak at B1. Others understand everything they hear, then freeze in front of a blank page."
                                }}
                                                </p>
                                                <div
                                                    class="bg-white rounded-2xl p-5 border-l-4 border-[#0055a4] border border-slate-200/80 shadow-sm text-sm text-[#002654] font-medium leading-relaxed">
                                                    {{ $isFr
    ? "Repasser le test, c'est repayer l'inscription et attendre une nouvelle session. Notre préparation commence donc par mesurer votre niveau dans chacune des 4 épreuves. Les heures vont ensuite là où l'écart est le plus grand."
    : "A retake means paying the fee again and waiting for another session. So we start by measuring your level in each of the 4 sections. Then your hours go where the gap is widest."
                                }}

                                                    <div
                                                        class="space-y-4 text-sm sm:text-[15px] text-slate-500 font-normal leading-relaxed pt-1">
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
                                                        <p>
                                                            {{ $isFr
    ? "Repasser le test, c'est repayer l'inscription et attendre une nouvelle session. Notre préparation commence donc par mesurer votre niveau dans chacune des 4 épreuves. Vos heures sont ensuite concentrées là où l'écart est le plus grand."
    : "A retake means paying the fee again and waiting for another session. So we start by measuring your level in each of the 4 sections. Then your hours go where the gap is widest."
                                    }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Right Column: Diagnostic Score Card Graphic -->
                                                <div class="lg:col-span-6">
                                                    <div
                                                        class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl shadow-slate-200/50">
                                                        <div
                                                            class="flex items-center justify-between pb-5 border-b border-slate-100 mb-6">
                                                            <div
                                                                class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8">
                                                                <div
                                                                    class="flex items-center gap-3.5 pb-6 border-b border-slate-100">
                                                                    <div
                                                                        class="w-10 h-10 rounded-xl bg-blue-50 text-[#0055a4] flex items-center justify-center shrink-0">
                                                                        <svg class="w-5 h-5" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="1.75"
                                                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                                        </svg>
                                                                    </div>
                                                                    <div>
                                                                        <div
                                                                            class="text-[11px] font-bold uppercase tracking-wider text-primary-600">
                                                                            {{ $isFr ? 'Évaluation diagnostique initiale' : 'Initial Diagnostic Evaluation' }}
                                                                        </div>
                                                                        <h4 class="text-base font-bold text-[#002654]">
                                                                            {{ $isFr ? 'Profil type candidat' : 'Candidate Diagnostic Profile' }}
                                                                        </h4>
                                                                        <h3
                                                                            class="font-bold text-[#10233e] text-base sm:text-[17px] leading-snug">
                                                                            {{ $isFr ? 'Votre point de départ' : 'Your starting point' }}
                                                                        </h3>
                                                                        <p class="text-xs text-slate-400 font-normal">
                                                                            {{ $isFr ? 'Un plan pour chaque compétence. Pas juste une moyenne.' : 'A plan for every skill. Not just an average.' }}
                                                                        </p>
                                                                    </div>
                                                                    <span
                                                                        class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#0055a4] border border-blue-100">
                                                                        {{ $isFr ? 'Objectif B2' : 'Target: B2' }}
                                                                    </span>
                                                                </div>

                                                                <!-- Skill Bars -->
                                                                <div class="space-y-4">
                                                                    <!-- Listening -->
                                                                    <div class="space-y-5 pt-6">
                                                                        <div>
                                                                            <div
                                                                                class="flex items-center justify-between text-xs font-semibold mb-1.5">
                                                                                <span
                                                                                    class="text-slate-700 flex items-center gap-2">
                                                                                    <i
                                                                                        class="fas fa-headphones text-primary-600 w-4"></i>
                                                                                    <div
                                                                                        class="flex items-center justify-between text-xs sm:text-[13px] font-semibold text-[#10233e] mb-2">
                                                                                        <span
                                                                                            class="flex items-center gap-2">
                                                                                            <svg class="w-4 h-4 text-[#0055A4]"
                                                                                                fill="none"
                                                                                                viewBox="0 0 24 24"
                                                                                                stroke="currentColor">
                                                                                                <path
                                                                                                    stroke-linecap="round"
                                                                                                    stroke-linejoin="round"
                                                                                                    stroke-width="1.75"
                                                                                                    d="M3 18v-6a9 9 0 0118 0v6M3 15h3a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-4zm18 0h-3a2 2 0 00-2 2v2a2 2 0 002 2h1a2 2 0 002-2v-4z" />
                                                                                            </svg>
                                                                                            {{ $isFr ? 'Compréhension orale' : 'Listening' }}
                                                                                        </span>
                                                                                        <span
                                                                                            class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">B2
                                                                                            (510/699)</span>
                                                                                        <span
                                                                                            class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50/80 text-[#0055A4]">B1</span>
                                                                                    </div>
                                                                                    <div
                                                                                        class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                                                                        <div class="bg-emerald-500 h-2.5 rounded-full"
                                                                                            style="width: 82%"></div>
                                                                                        <div
                                                                                            class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                                                                            <div class="bg-[#0055A4] h-2 rounded-full"
                                                                                                style="width: 65%">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- Reading -->
                                                                                    <div>
                                                                                        <div
                                                                                            class="flex items-center justify-between text-xs font-semibold mb-1.5">
                                                                                            <span
                                                                                                class="text-slate-700 flex items-center gap-2">
                                                                                                <i
                                                                                                    class="fas fa-book-open text-primary-600 w-4"></i>
                                                                                                <div
                                                                                                    class="flex items-center justify-between text-xs sm:text-[13px] font-semibold text-[#10233e] mb-2">
                                                                                                    <span
                                                                                                        class="flex items-center gap-2">
                                                                                                        <svg class="w-4 h-4 text-[#0055A4]"
                                                                                                            fill="none"
                                                                                                            viewBox="0 0 24 24"
                                                                                                            stroke="currentColor">
                                                                                                            <path
                                                                                                                stroke-linecap="round"
                                                                                                                stroke-linejoin="round"
                                                                                                                stroke-width="1.75"
                                                                                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                                                                        </svg>
                                                                                                        {{ $isFr ? 'Compréhension écrite' : 'Reading' }}
                                                                                                    </span>
                                                                                                    <span
                                                                                                        class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">B2
                                                                                                        (495/699)</span>
                                                                                                    <span
                                                                                                        class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50/80 text-[#0055A4]">B2</span>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                                                                                    <div class="bg-emerald-500 h-2.5 rounded-full"
                                                                                                        style="width: 78%">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                                                                                        <div class="bg-[#0055A4] h-2 rounded-full"
                                                                                                            style="width: 82%">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <!-- Writing (Gap) -->
                                                                                                <div>
                                                                                                    <div
                                                                                                        class="flex items-center justify-between text-xs font-semibold mb-1.5">
                                                                                                        <span
                                                                                                            class="text-slate-700 flex items-center gap-2">
                                                                                                            <i
                                                                                                                class="fas fa-pen-nib text-amber-600 w-4"></i>
                                                                                                            <div
                                                                                                                class="flex items-center justify-between text-xs sm:text-[13px] font-semibold text-[#10233e] mb-2">
                                                                                                                <span
                                                                                                                    class="flex items-center gap-2">
                                                                                                                    <svg class="w-4 h-4 text-[#0055A4]"
                                                                                                                        fill="none"
                                                                                                                        viewBox="0 0 24 24"
                                                                                                                        stroke="currentColor">
                                                                                                                        <path
                                                                                                                            stroke-linecap="round"
                                                                                                                            stroke-linejoin="round"
                                                                                                                            stroke-width="1.75"
                                                                                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                                                                    </svg>
                                                                                                                    {{ $isFr ? 'Expression écrite' : 'Writing' }}
                                                                                                                </span>
                                                                                                                <span
                                                                                                                    class="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">B1
                                                                                                                    (360/699)
                                                                                                                    ·
                                                                                                                    {{ $isFr ? 'Écart' : 'Gap' }}</span>
                                                                                                                <span
                                                                                                                    class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50/80 text-[#0055A4]">A2</span>
                                                                                                            </div>
                                                                                                            <div
                                                                                                                class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                                                                                                <div class="bg-amber-500 h-2.5 rounded-full"
                                                                                                                    style="width: 50%">
                                                                                                                </div>
                                                                                                                <div
                                                                                                                    class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                                                                                                    <div class="bg-[#0055A4] h-2 rounded-full"
                                                                                                                        style="width: 40%">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <!-- Speaking (Gap) -->
                                                                                                            <div>
                                                                                                                <div
                                                                                                                    class="flex items-center justify-between text-xs font-semibold mb-1.5">
                                                                                                                    <span
                                                                                                                        class="text-slate-700 flex items-center gap-2">
                                                                                                                        <i
                                                                                                                            class="fas fa-comments text-amber-600 w-4"></i>
                                                                                                                        <div
                                                                                                                            class="flex items-center justify-between text-xs sm:text-[13px] font-semibold text-[#10233e] mb-2">
                                                                                                                            <span
                                                                                                                                class="flex items-center gap-2">
                                                                                                                                <svg class="w-4 h-4 text-[#0055A4]"
                                                                                                                                    fill="none"
                                                                                                                                    viewBox="0 0 24 24"
                                                                                                                                    stroke="currentColor">
                                                                                                                                    <path
                                                                                                                                        stroke-linecap="round"
                                                                                                                                        stroke-linejoin="round"
                                                                                                                                        stroke-width="1.75"
                                                                                                                                        d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z" />
                                                                                                                                </svg>
                                                                                                                                {{ $isFr ? 'Expression orale' : 'Speaking' }}
                                                                                                                            </span>
                                                                                                                            <span
                                                                                                                                class="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">B1
                                                                                                                                (380/699)
                                                                                                                                ·
                                                                                                                                {{ $isFr ? 'Écart' : 'Gap' }}</span>
                                                                                                                            <span
                                                                                                                                class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50/80 text-[#0055A4]">B1</span>
                                                                                                                        </div>
                                                                                                                        <div
                                                                                                                            class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                                                                                                            <div class="bg-amber-500 h-2.5 rounded-full"
                                                                                                                                style="width: 54%">
                                                                                                                            </div>
                                                                                                                            <div
                                                                                                                                class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                                                                                                                <div class="bg-[#0055A4] h-2 rounded-full"
                                                                                                                                    style="width: 55%">
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <!-- Diagnostic note -->
                                                                                                                <div
                                                                                                                    class="mt-6 p-4 rounded-xl bg-amber-50/80 border border-amber-200/80 text-xs text-amber-900 flex items-start gap-3">
                                                                                                                    <i
                                                                                                                        class="fas fa-bullseye text-amber-600 text-sm mt-0.5 shrink-0"></i>
                                                                                                                    <div>
                                                                                                                        <strong>{{ $isFr ? 'Plan de cours personnalisé :' : 'Targeted Study Plan:' }}</strong>
                                                                                                                        {{ $isFr
    ? "70% du temps concentré sur l'expression écrite et orale pour sécuriser le B2 global sans perdre de temps sur les acquis."
    : "70% of tutoring hours focused on Writing & Speaking to bridge the gap to B2, with zero wasted time on skills you've already mastered."
                                        }}
                                                                                                                    </div>
                                                                                                                    <div
                                                                                                                        class="pt-5 mt-5 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-400 font-medium">
                                                                                                                        <span
                                                                                                                            class="inline-block w-1.5 h-1.5 rounded-full bg-[#EF4135] shrink-0"></span>
                                                                                                                        <span>{{ $isFr ? 'Votre objectif. Votre rythme. Votre plan personnalisé.' : 'Your target. Your pace. Your personal plan.' }}</span>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                    </div>
                                                                                                </div>
                </section>

                <!-- ============ SECTION 4: TCF IRN FORMAT (THE 4 SECTIONS) ============ -->
                <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                        <!-- Centered Heading -->
                        <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                            <div
                                class="inline-flex items-center gap-2 text-xs font-bold tracking-wider uppercase text-primary-600 mb-2">
                                <span class="w-2 h-2 rounded-full bg-[#0055a4]"></span>
                                {{ $isFr ? 'Structure des épreuves' : 'Exam Structure' }}
                                <div class="inline-flex items-center gap-1.5 mb-3">
                                    <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                    <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                    <span
                                        class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                        {{ $isFr ? 'STRUCTURE DES ÉPREUVES' : 'THE 4 SECTIONS' }}
                                    </span>
                                </div>
                                <h2 class="text-2xl sm:text-4xl font-extrabold text-[#002654] tracking-tight">
                                    <h2
                                        class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                        {{ $isFr ? 'Format du TCF IRN : les 4 épreuves' : 'TCF IRN format: the 4 sections' }}
                                    </h2>
                                    <p class="mt-4 text-base text-slate-600 leading-relaxed max-w-2xl mx-auto">
                                    <p
                                        class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto font-normal">
                                        {{ $isFr
    ? "Le TCF IRN comprend 4 épreuves obligatoires, passées le même jour. Les 2 épreuves de compréhension sont des questionnaires à choix multiple. Les 2 épreuves d'expression sont évaluées par des correcteurs et des examinateurs. Ce format, en vigueur depuis le 12 mai 2025, évalue jusqu'au niveau B2."
    : "The TCF IRN has 4 compulsory sections, all taken on the same day. Listening and reading are multiple choice. Writing and speaking are marked by examiners. This format has applied since 12 May 2025 and tests up to B2."
    ? "Le TCF IRN comprend 4 épreuves obligatoires, passées le même jour. Les 2 épreuves de compréhension sont des questionnaires à choix multiple. Les 2 épreuves d'expression sont évaluées par des correcteurs et des examinateurs. Ce format, en vigueur depuis le 12 mai 2025, évalue jusqu'au niveau B2."
    : "The TCF IRN has 4 compulsory sections, all taken on the same day. Listening and reading are multiple choice. Writing and speaking are marked by examiners. This format has applied since 12 May 2025 and tests up to B2."
                            }}
                                    </p>
                            </div>

                            <!-- 4 Cards Grid (4 columns) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- Card 1: Listening -->
                                <div
                                    class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
                                        <div
                                            class="bg-white rounded-3xl border border-slate-200/80 p-6 flex flex-col justify-between">
                                            <div>
                                                <div class="flex items-center justify-between mb-4">
                                                    <span
                                                        class="w-11 h-11 rounded-2xl bg-blue-50 text-[#0055a4] grid place-content-center text-lg">
                                                        <i class="fas fa-headphones"></i>
                                                    </span>
                                                    <span
                                                        class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-[#002654]">
                                                        20 min
                                                    </span>
                                                    <div
                                                        class="w-10 h-10 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.75"
                                                                d="M3 18v-6a9 9 0 0118 0v6M3 15h3a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-4zm18 0h-3a2 2 0 00-2 2v2a2 2 0 002 2h1a2 2 0 002-2v-4z" />
                                                        </svg>
                                                    </div>
                                                    <span
                                                        class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-[#10233e]">20
                                                        min</span>
                                                </div>
                                                <h3 class="text-lg font-bold text-[#002654]">
                                                    {{ $isFr ? 'Compréhension orale' : 'Listening' }}
                                                </h3>
                                                <p class="mt-2 text-xs font-semibold text-[#0055a4]">
                                                    {{ $isFr ? '25 questions à choix multiple' : '25 multiple-choice questions' }}
                                                </p>
                                                <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                                    {{ $isFr
    ? 'Extraits audio courts parlés à vitesse naturelle de locuteurs natifs.'
    : 'Short audio clips recorded at natural native French speaking speed.'
                                    }}
                                                </p>
                                                <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                                    {{ $isFr ? 'Compréhension orale' : 'Listening' }}
                                                </h3>
                                                <p class="mt-1 text-xs font-semibold text-[#0055a4]">
                                                    {{ $isFr ? '25 questions à choix multiple' : '25 multiple-choice questions' }}
                                                </p>
                                                <p class="mt-2 text-xs text-slate-500 leading-relaxed font-normal">
                                                    {{ $isFr ? 'Extraits audio courts, 1 seule écoute.' : 'Short audio clips played once.' }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Card 2: Reading -->
                                        <div
                                            class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                                            <div
                                                class="bg-white rounded-3xl border border-slate-200/80 p-6 flex flex-col justify-between">
                                                <div>
                                                    <div class="flex items-center justify-between mb-4">
                                                        <span
                                                            class="w-11 h-11 rounded-2xl bg-blue-50 text-[#0055a4] grid place-content-center text-lg">
                                                            <i class="fas fa-book-open"></i>
                                                        </span>
                                                        <span
                                                            class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-[#002654]">
                                                            35 min
                                                        </span>
                                                        <div
                                                            class="w-10 h-10 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="1.75"
                                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                            </svg>
                                                        </div>
                                                        <span
                                                            class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-[#10233e]">35
                                                            min</span>
                                                    </div>
                                                    <h3 class="text-lg font-bold text-[#002654]">
                                                        {{ $isFr ? 'Compréhension écrite' : 'Reading' }}
                                                    </h3>
                                                    <p class="mt-2 text-xs font-semibold text-[#0055a4]">
                                                        {{ $isFr ? '25 questions à choix multiple' : '25 multiple-choice questions' }}
                                                    </p>
                                                    <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                                        {{ $isFr
    ? '84 secondes par question en moyenne, temps de lecture compris.'
    : '84 seconds per question on average, text reading time included.'
                                    }}
                                                    </p>
                                                    <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                                        {{ $isFr ? 'Compréhension écrite' : 'Reading' }}
                                                    </h3>
                                                    <p class="mt-1 text-xs font-semibold text-[#0055a4]">
                                                        {{ $isFr ? '25 questions à choix multiple' : '25 multiple-choice questions' }}
                                                    </p>
                                                    <p class="mt-2 text-xs text-slate-500 leading-relaxed font-normal">
                                                        {{ $isFr ? 'Documents de la vie quotidienne, messages, affiches.' : 'Documents, signs, notices from daily life.' }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Card 3: Writing -->
                                            <div
                                                class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                                                <div
                                                    class="bg-white rounded-3xl border border-slate-200/80 p-6 flex flex-col justify-between">
                                                    <div>
                                                        <div class="flex items-center justify-between mb-4">
                                                            <span
                                                                class="w-11 h-11 rounded-2xl bg-blue-50 text-[#0055a4] grid place-content-center text-lg">
                                                                <i class="fas fa-pen-nib"></i>
                                                            </span>
                                                            <span
                                                                class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-[#002654]">
                                                                30 min
                                                            </span>
                                                            <div
                                                                class="w-10 h-10 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center">
                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="1.75"
                                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                </svg>
                                                            </div>
                                                            <span
                                                                class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-[#10233e]">30
                                                                min</span>
                                                        </div>
                                                        <h3 class="text-lg font-bold text-[#002654]">
                                                            {{ $isFr ? 'Expression écrite' : 'Writing' }}
                                                        </h3>
                                                        <p class="mt-2 text-xs font-semibold text-[#0055a4]">
                                                            {{ $isFr ? '3 exercices' : '3 tasks' }}
                                                        </p>
                                                        <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                                            {{ $isFr
    ? 'Message court, lettre personnelle et texte argumentatif nuancé.'
    : 'Short message, personal letter, and structured opinion essay.'
                                    }}
                                                        </p>
                                                        <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                                            {{ $isFr ? 'Expression écrite' : 'Writing' }}
                                                        </h3>
                                                        <p class="mt-1 text-xs font-semibold text-[#0055a4]">
                                                            {{ $isFr ? '3 tâches' : '3 tasks' }}
                                                        </p>
                                                        <p
                                                            class="mt-2 text-xs text-slate-500 leading-relaxed font-normal">
                                                            {{ $isFr ? 'Message court, lettre personnelle, prise de position.' : 'Message, personal letter, opinion essay.' }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Card 4: Speaking -->
                                                <div
                                                    class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                                                    <div
                                                        class="bg-white rounded-3xl border border-slate-200/80 p-6 flex flex-col justify-between">
                                                        <div>
                                                            <div class="flex items-center justify-between mb-4">
                                                                <span
                                                                    class="w-11 h-11 rounded-2xl bg-blue-50 text-[#0055a4] grid place-content-center text-lg">
                                                                    <i class="fas fa-comments"></i>
                                                                </span>
                                                                <span
                                                                    class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-[#002654]">
                                                                    10 min
                                                                </span>
                                                                <div
                                                                    class="w-10 h-10 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center">
                                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="1.75"
                                                                            d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z" />
                                                                    </svg>
                                                                </div>
                                                                <span
                                                                    class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-[#10233e]">10
                                                                    min</span>
                                                            </div>
                                                            <h3 class="text-lg font-bold text-[#002654]">
                                                                {{ $isFr ? 'Expression orale' : 'Speaking' }}
                                                            </h3>
                                                            <p class="mt-2 text-xs font-semibold text-[#0055a4]">
                                                                {{ $isFr ? 'Entretien individuel' : '1-to-1 interview' }}
                                                            </p>
                                                            <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                                                {{ $isFr
    ? 'Face-à-face avec un examinateur certifié en 3 parties progressives.'
    : 'Face-to-face with a certified examiner in 3 progressive parts.'
                                    }}
                                                            </p>
                                                            <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                                                {{ $isFr ? 'Expression orale' : 'Speaking' }}
                                                            </h3>
                                                            <p class="mt-1 text-xs font-semibold text-[#0055a4]">
                                                                {{ $isFr ? 'Entretien individuel' : '1-to-1 interview' }}
                                                            </p>
                                                            <p
                                                                class="mt-2 text-xs text-slate-500 leading-relaxed font-normal">
                                                                {{ $isFr ? 'Entretien en face-à-face avec un examinateur.' : '1-to-1 interview with an examiner.' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Bottom Total Duration Bar -->
                                                <div class="mt-8 max-w-xl mx-auto">
                                                    <div
                                                        class="flex items-center justify-center gap-3 py-3.5 px-6 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-semibold text-[#002654] text-center shadow-sm">
                                                        <i class="fas fa-clock text-[#0055a4]"></i>
                                                        <span>{{ $isFr ? 'Total : 1 h 35 · Les 4 épreuves sont obligatoires et passées le même jour' : 'Total: 1 h 35 · All 4 compulsory sections are taken on the same day' }}</span>
                                                    </div>
                                                    <div
                                                        class="mt-8 flex items-center justify-center gap-2.5 py-3 px-6 rounded-full bg-slate-50 border border-slate-200/80 text-xs sm:text-sm font-semibold text-[#10233e] max-w-fit mx-auto">
                                                        <svg class="w-4 h-4 text-[#0055A4]" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.75"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span>{{ $isFr ? 'Durée totale : 1 h 35 · Les 4 épreuves sont passées le même jour' : 'Total duration: 1 h 35 · All 4 sections taken on the same day' }}</span>
                                                    </div>
                                                </div>
                </section>

                <!-- ============ SECTION 5: WHERE CANDIDATES LOSE POINTS ============ -->
                <section class="py-16 sm:py-24 bg-[#f8fafc] border-b border-slate-100">
                    <section class="py-16 sm:py-24 bg-[#f4f7fb] border-b border-slate-100">
                        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                            <!-- Centered Heading -->
                            <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                                <div
                                    class="inline-flex items-center gap-2 text-xs font-bold tracking-wider uppercase text-primary-600 mb-2">
                                    <span class="w-2 h-2 rounded-full bg-[#0055a4]"></span>
                                    {{ $isFr ? 'Pièges fréquents' : 'Common Pitfalls' }}
                                    <div class="inline-flex items-center gap-1.5 mb-3">
                                        <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                        <span
                                            class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                        <span
                                            class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                            {{ $isFr ? 'PIÈGES FRÉQUENTS' : 'WHERE POINTS ARE LOST' }}
                                        </span>
                                    </div>
                                    <h2 class="text-2xl sm:text-4xl font-extrabold text-[#002654] tracking-tight">
                                        {{ $isFr ? 'Où les candidats perdent des points au TCF IRN' : 'Where TCF IRN candidates lose points' }}
                                        <h2
                                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                            {{ $isFr ? 'Là où les candidats perdent des points' : 'Where TCF IRN candidates lose points' }}
                                        </h2>
                                        <p
                                            class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto font-normal">
                                            {{ $isFr
    ? "Les points ne se perdent pas sur des règles de grammaire complexes, mais sur les pièges du format."
    : "Points aren't lost on tricky grammar rules, but on format traps."
                            }}
                                        </p>
                                </div>

                                <!-- 2x2 Traps Grid -->
                                <div class="grid sm:grid-cols-2 gap-6 lg:gap-8">
                                    <!-- Trap 1 -->
                                    <div
                                        class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between space-y-4">
                                        <div class="space-y-3">
                                            <div
                                                class="w-9 h-9 rounded-xl bg-red-50 text-[#ef4135] grid place-content-center text-sm font-bold">
                                                <i class="fas fa-stopwatch"></i>
                                            </div>
                                            <h3 class="text-lg sm:text-xl font-bold text-[#002654]">
                                                {{ $isFr ? 'Le chrono, plus que la grammaire' : 'The clock, more than the grammar' }}
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <div
                                                        class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                                                        <div>
                                                            <span
                                                                class="text-xs font-bold text-[#0055A4] bg-blue-50 px-2.5 py-1 rounded-full">01</span>
                                                            <h3
                                                                class="text-lg sm:text-xl font-bold text-[#10233e] mt-3">
                                                                {{ $isFr ? 'L’horloge plus que la grammaire' : 'The clock, more than the grammar' }}
                                                            </h3>
                                                            <p
                                                                class="text-sm text-slate-600 leading-relaxed font-normal">
                                                            <p
                                                                class="text-sm text-slate-500 leading-relaxed font-normal mt-2.5">
                                                                {{ $isFr
    ? "En compréhension écrite, vous avez 35 minutes pour 25 questions. Cela fait 84 secondes par question, lecture comprise. Bloquer 3 minutes sur une question, c'est utiliser le temps de 2."
    : "Reading gives you 35 minutes for 25 questions. That's 84 seconds a question, reading included. Spend 3 minutes stuck on 1 question, and you've used the time of 2."
    ? "La compréhension écrite donne 35 minutes pour 25 questions, soit 84 secondes par question lecture comprise. Passer 3 minutes sur une question difficile consomme le temps de deux."
    : "Reading gives you 35 minutes for 25 questions. That's 84 seconds a question, reading included. Spend 3 minutes stuck on 1 question, and you've used the time of 2."
                                    }}
                                                            </p>
                                                        </div>
                                                        <div
                                                            class="p-4 rounded-xl bg-blue-50/70 border border-blue-200/70 text-xs sm:text-sm font-semibold text-[#003f7a] flex items-center gap-2.5">
                                                            <i class="fas fa-check-circle text-[#0055a4] shrink-0"></i>
                                                            <span>{{ $isFr ? 'Ce que nous faisons : des séries chronométrées, jusqu\'à ce que le rythme devienne automatique.' : 'What we do: timed drills until the pace is automatic.' }}</span>
                                                            <div
                                                                class="mt-6 pt-4 border-t border-slate-100 flex items-start gap-2 text-xs sm:text-[13px] text-slate-700">
                                                                <span
                                                                    class="font-bold text-[#0055A4] shrink-0">{{ $isFr ? 'Ce que nous faisons :' : 'What we do:' }}</span>
                                                                <span>{{ $isFr ? 'Entraînements chronométrés jusqu’à ce que le rythme devienne automatique.' : 'Timed drills until the pace is automatic.' }}</span>
                                                            </div>
                                                        </div>

                                                        <!-- Trap 2 -->
                                                        <div
                                                            class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between space-y-4">
                                                            <div class="space-y-3">
                                                                <div
                                                                    class="w-9 h-9 rounded-xl bg-red-50 text-[#ef4135] grid place-content-center text-sm font-bold">
                                                                    <i class="fas fa-volume-high"></i>
                                                                </div>
                                                                <h3 class="text-lg sm:text-xl font-bold text-[#002654]">
                                                                    {{ $isFr ? "L'oreille habituée au français lent" : 'Ears trained on slow French' }}
                                                                    <div
                                                                        class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                                                                        <div>
                                                                            <span
                                                                                class="text-xs font-bold text-[#0055A4] bg-blue-50 px-2.5 py-1 rounded-full">02</span>
                                                                            <h3
                                                                                class="text-lg sm:text-xl font-bold text-[#10233e] mt-3">
                                                                                {{ $isFr ? 'Une oreille habituée au français ralenti' : 'Ears trained on slow French' }}
                                                                            </h3>
                                                                            <p
                                                                                class="text-sm text-slate-600 leading-relaxed font-normal">
                                                                            <p
                                                                                class="text-sm text-slate-500 leading-relaxed font-normal mt-2.5">
                                                                                {{ $isFr
    ? "Les documents audio du test sont courts et parlés à vitesse naturelle. Les méthodes d'apprentissage, elles, parlent souvent lentement. L'écart se découvre le jour J."
    : "The audio is short and spoken at natural speed. Most courses speak slowly. People find out the difference on exam day."
    ? "Les enregistrements sont courts et à débit naturel. La plupart des cours parlent lentement, et les candidats découvrent le vrai rythme le jour de l'examen."
    : "The audio is short and spoken at natural speed. Most courses speak slowly. People find out the difference on exam day."
                                    }}
                                                                            </p>
                                                                        </div>
                                                                        <div
                                                                            class="p-4 rounded-xl bg-blue-50/70 border border-blue-200/70 text-xs sm:text-sm font-semibold text-[#003f7a] flex items-center gap-2.5">
                                                                            <i
                                                                                class="fas fa-check-circle text-[#0055a4] shrink-0"></i>
                                                                            <span>{{ $isFr ? "Ce que nous faisons : des exercices d'écoute à vitesse native dans votre espace élève, dès la première semaine." : 'What we do: native-speed listening practice in your student portal from week 1.' }}</span>
                                                                            <div
                                                                                class="mt-6 pt-4 border-t border-slate-100 flex items-start gap-2 text-xs sm:text-[13px] text-slate-700">
                                                                                <span
                                                                                    class="font-bold text-[#0055A4] shrink-0">{{ $isFr ? 'Ce que nous faisons :' : 'What we do:' }}</span>
                                                                                <span>{{ $isFr ? 'Écoute à vitesse native dans votre espace élève dès la première semaine.' : 'Native-speed listening practice in your student portal from week 1.' }}</span>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Trap 3 -->
                                                                        <div
                                                                            class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between space-y-4">
                                                                            <div class="space-y-3">
                                                                                <div
                                                                                    class="w-9 h-9 rounded-xl bg-red-50 text-[#ef4135] grid place-content-center text-sm font-bold">
                                                                                    <i class="fas fa-pen-ruler"></i>
                                                                                </div>
                                                                                <h3
                                                                                    class="text-lg sm:text-xl font-bold text-[#002654]">
                                                                                    <div
                                                                                        class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                                                                                        <div>
                                                                                            <span
                                                                                                class="text-xs font-bold text-[#0055A4] bg-blue-50 px-2.5 py-1 rounded-full">03</span>
                                                                                            <h3
                                                                                                class="text-lg sm:text-xl font-bold text-[#10233e] mt-3">
                                                                                                {{ $isFr ? '3 textes en 30 minutes' : '3 texts in 30 minutes' }}
                                                                                            </h3>
                                                                                            <p
                                                                                                class="text-sm text-slate-600 leading-relaxed font-normal">
                                                                                            <p
                                                                                                class="text-sm text-slate-500 leading-relaxed font-normal mt-2.5">
                                                                                                {{ $isFr
    ? "L'expression écrite demande 3 productions en 30 minutes. Le piège classique : soigner la première et bâcler la dernière. Le correcteur, lui, lit les 3."
    : "The writing section asks for 3 pieces in 30 minutes. The classic trap: polishing the first and rushing the last. The examiner reads all 3."
    ? "L'expression écrite demande 3 productions en une demi-heure. Le piège classique : soigner la première et bâcler la troisième. Le correcteur note les 3."
    : "The writing section asks for 3 pieces in 30 minutes. The classic trap: polishing the first and rushing the last. The examiner reads all 3."
                                    }}
                                                                                            </p>
                                                                                        </div>
                                                                                        <div
                                                                                            class="p-4 rounded-xl bg-blue-50/70 border border-blue-200/70 text-xs sm:text-sm font-semibold text-[#003f7a] flex items-center gap-2.5">
                                                                                            <i
                                                                                                class="fas fa-check-circle text-[#0055a4] shrink-0"></i>
                                                                                            <span>{{ $isFr ? 'Ce que nous faisons : un plan de temps par exercice et des structures de phrases à adapter, pas à réciter.' : 'What we do: a time plan for each task and sentence structures you adapt rather than memorise.' }}</span>
                                                                                            <div
                                                                                                class="mt-6 pt-4 border-t border-slate-100 flex items-start gap-2 text-xs sm:text-[13px] text-slate-700">
                                                                                                <span
                                                                                                    class="font-bold text-[#0055A4] shrink-0">{{ $isFr ? 'Ce que nous faisons :' : 'What we do:' }}</span>
                                                                                                <span>{{ $isFr ? 'Des structures efficaces sous contrainte de temps, avec correction personnalisée.' : 'Templates that work under time pressure, with real human correction.' }}</span>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- Trap 4 -->
                                                                                        <div
                                                                                            class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between space-y-4">
                                                                                            <div class="space-y-3">
                                                                                                <div
                                                                                                    class="w-9 h-9 rounded-xl bg-red-50 text-[#ef4135] grid place-content-center text-sm font-bold">
                                                                                                    <i
                                                                                                        class="fas fa-comment-dots"></i>
                                                                                                </div>
                                                                                                <h3
                                                                                                    class="text-lg sm:text-xl font-bold text-[#002654]">
                                                                                                    {{ $isFr ? 'Des réponses trop courtes à l\'oral' : 'Answers that are too short' }}
                                                                                                    <div
                                                                                                        class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                                                                                                        <div>
                                                                                                            <span
                                                                                                                class="text-xs font-bold text-[#0055A4] bg-blue-50 px-2.5 py-1 rounded-full">04</span>
                                                                                                            <h3
                                                                                                                class="text-lg sm:text-xl font-bold text-[#10233e] mt-3">
                                                                                                                {{ $isFr ? "Le blocage devant l'examinateur" : 'Freezing in front of the examiner' }}
                                                                                                            </h3>
                                                                                                            <p
                                                                                                                class="text-sm text-slate-600 leading-relaxed font-normal">
                                                                                                            <p
                                                                                                                class="text-sm text-slate-500 leading-relaxed font-normal mt-2.5">
                                                                                                                {{ $isFr
    ? "En 10 minutes, l'examinateur doit entendre ce qui fait un B2 : argumenter, nuancer, illustrer. Une réponse de 2 phrases ne lui en laisse pas l'occasion."
    : "In 10 minutes, the examiner needs to hear B2 skills: arguing, nuancing, giving examples. A 2-sentence answer gives them nothing to mark."
    ? "L'épreuve orale est enregistrée et transmise à France Éducation international pour double correction. Les silences prolongés font chuter la note."
    : "The speaking interview is recorded and sent to France Éducation international for marking. Awkward silences cost marks."
                                    }}
                                                                                                            </p>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class="p-4 rounded-xl bg-blue-50/70 border border-blue-200/70 text-xs sm:text-sm font-semibold text-[#003f7a] flex items-center gap-2.5">
                                                                                                            <i
                                                                                                                class="fas fa-check-circle text-[#0055a4] shrink-0"></i>
                                                                                                            <span>{{ $isFr ? 'Ce que nous faisons : une méthode simple pour chaque réponse — une idée, un exemple, une nuance.' : 'What we do: a simple pattern for every answer — 1 idea, 1 example, 1 nuance.' }}</span>
                                                                                                            <div
                                                                                                                class="mt-6 pt-4 border-t border-slate-100 flex items-start gap-2 text-xs sm:text-[13px] text-slate-700">
                                                                                                                <span
                                                                                                                    class="font-bold text-[#0055A4] shrink-0">{{ $isFr ? 'Ce que nous faisons :' : 'What we do:' }}</span>
                                                                                                                <span>{{ $isFr ? 'Simulations d’entretien en conditions réelles avec des tuteurs natifs.' : 'Mock interviews under real exam conditions with native French tutors.' }}</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                            </div>
                    </section>

                    <!-- ============ SECTION 6: HOW WE PREPARE YOU ============ -->
                    <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                            <!-- Centered Heading -->
                            <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                                <div
                                    class="inline-flex items-center gap-2 text-xs font-bold tracking-wider uppercase text-primary-600 mb-2">
                                    <span class="w-2 h-2 rounded-full bg-[#0055a4]"></span>
                                    {{ $isFr ? 'Méthodologie' : 'Methodology' }}
                                    <div class="inline-flex items-center gap-1.5 mb-3">
                                        <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                        <span
                                            class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                        <span
                                            class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                            {{ $isFr ? 'NOTRE MÉTHODE' : 'OUR PREPARATION' }}
                                        </span>
                                    </div>
                                    <h2 class="text-2xl sm:text-4xl font-extrabold text-[#002654] tracking-tight">
                                        {{ $isFr ? 'Notre préparation au TCF IRN' : 'How we prepare you for the TCF IRN' }}
                                        <h2
                                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                            {{ $isFr ? 'Comment nous vous préparons au TCF IRN' : 'How we prepare you for the TCF IRN' }}
                                        </h2>
                                        <p
                                            class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto font-normal">
                                            {{ $isFr
    ? "Une méthode claire en 4 étapes, de votre diagnostic initial jusqu'au jour de l'examen."
    : "A clear 4-step method, from your first diagnostic to exam day."
                            }}
                                        </p>
                                </div>

                                <!-- 4 Numbered Step Cards Side-by-Side -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <!-- Step 1 -->
                                    <div
                                        class="bg-slate-50/70 rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-start space-y-4">
                                        <div
                                            class="w-10 h-10 rounded-full bg-[#0055a4] text-white font-extrabold text-sm grid place-content-center shadow-md shadow-blue-500/20">
                                            1
                                            <div
                                                class="rounded-3xl border border-slate-200/80 p-6 bg-slate-50/50 flex flex-col justify-between">
                                                <div>
                                                    <span
                                                        class="w-9 h-9 rounded-full bg-blue-50 text-[#0055A4] font-bold text-sm flex items-center justify-center mb-4">01</span>
                                                    <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                                        {{ $isFr ? 'Votre niveau à la loupe' : 'Your level under the lens' }}
                                                    </h3>
                                                    <p
                                                        class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed font-normal">
                                                        {{ $isFr ? 'Le premier cours évalue vos 4 compétences face au niveau visé.' : 'Your first class tests all 4 skills against your target level.' }}
                                                    </p>
                                                </div>
                                                <h3 class="text-base font-bold text-[#002654] leading-snug">
                                                    {{ $isFr ? 'Votre niveau, épreuve par épreuve' : 'Your level, section by section' }}
                                                </h3>
                                                <p
                                                    class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                                    {{ $isFr
    ? 'Dès le premier cours, votre tuteur situe chaque compétence face au niveau exigé par votre démarche.'
    : 'In your first class, your tutor places each skill against the level your application needs.'
                                }}
                                                </p>
                                            </div>

                                            <!-- Step 2 -->
                                            <div
                                                class="bg-slate-50/70 rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-start space-y-4">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-[#0055a4] text-white font-extrabold text-sm grid place-content-center shadow-md shadow-blue-500/20">
                                                    2
                                                    <div
                                                        class="rounded-3xl border border-slate-200/80 p-6 bg-slate-50/50 flex flex-col justify-between">
                                                        <div>
                                                            <span
                                                                class="w-9 h-9 rounded-full bg-blue-50 text-[#0055A4] font-bold text-sm flex items-center justify-center mb-4">02</span>
                                                            <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                                                {{ $isFr ? 'Un plan sur mesure' : 'A targeted schedule' }}
                                                            </h3>
                                                            <p
                                                                class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed font-normal">
                                                                {{ $isFr ? 'Vos heures vont là où l’écart est le plus grand, sans répéter ce qui est acquis.' : 'Hours go where your gap is widest, with no wasted time on mastered skills.' }}
                                                            </p>
                                                        </div>
                                                        <h3 class="text-base font-bold text-[#002654] leading-snug">
                                                            {{ $isFr ? 'Un plan construit sur votre écart' : 'A plan built on your gap' }}
                                                        </h3>
                                                        <p
                                                            class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                                            {{ $isFr
    ? 'Les heures vont aux épreuves où vous êtes le plus loin du but. Pas de programme générique.'
    : "Your hours go to the sections where you're furthest from the target. No generic syllabus."
                                }}
                                                        </p>
                                                    </div>

                                                    <!-- Step 3 -->
                                                    <div
                                                        class="bg-slate-50/70 rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-start space-y-4">
                                                        <div
                                                            class="w-10 h-10 rounded-full bg-[#0055a4] text-white font-extrabold text-sm grid place-content-center shadow-md shadow-blue-500/20">
                                                            3
                                                            <div
                                                                class="rounded-3xl border border-slate-200/80 p-6 bg-slate-50/50 flex flex-col justify-between">
                                                                <div>
                                                                    <span
                                                                        class="w-9 h-9 rounded-full bg-blue-50 text-[#0055A4] font-bold text-sm flex items-center justify-center mb-4">03</span>
                                                                    <h3
                                                                        class="text-base sm:text-lg font-bold text-[#10233e]">
                                                                        {{ $isFr ? '2 à 4 heures par semaine' : 'Between 2 and 4 hours a week' }}
                                                                    </h3>
                                                                    <p
                                                                        class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed font-normal">
                                                                        {{ $isFr ? 'Un rythme régulier, en direct avec votre tuteur dédié, à vos horaires.' : 'Regular pace, live with your dedicated tutor, at times that suit you.' }}
                                                                    </p>
                                                                </div>
                                                                <h3
                                                                    class="text-base font-bold text-[#002654] leading-snug">
                                                                    {{ $isFr ? 'Des cours en direct, 2 ou 4 h par semaine' : 'Live classes, 2 or 4 hours a week' }}
                                                                </h3>
                                                                <p
                                                                    class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                                                    {{ $isFr
    ? 'En particulier ou en mini-groupe de 2 à 5 personnes, sur Google Meet. Vous recevez les notes après chaque séance.'
    : '1:1 or in a group of 2–5, on Google Meet. You get the class notes after every session.'
                                }}
                                                                </p>
                                                            </div>

                                                            <!-- Step 4 -->
                                                            <div
                                                                class="bg-slate-50/70 rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-start space-y-4">
                                                                <div
                                                                    class="w-10 h-10 rounded-full bg-[#0055a4] text-white font-extrabold text-sm grid place-content-center shadow-md shadow-blue-500/20">
                                                                    4
                                                                    <div
                                                                        class="rounded-3xl border border-slate-200/80 p-6 bg-slate-50/50 flex flex-col justify-between">
                                                                        <div>
                                                                            <span
                                                                                class="w-9 h-9 rounded-full bg-blue-50 text-[#0055A4] font-bold text-sm flex items-center justify-center mb-4">04</span>
                                                                            <h3
                                                                                class="text-base sm:text-lg font-bold text-[#10233e]">
                                                                                {{ $isFr ? "L'environnement réel" : 'The test environment' }}
                                                                            </h3>
                                                                            <p
                                                                                class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed font-normal">
                                                                                {{ $isFr ? 'Examens blancs en conditions réelles pour éliminer le stress du jour J.' : 'Mock exams in real test conditions so exam day feels like second nature.' }}
                                                                            </p>
                                                                        </div>
                                                                        <h3
                                                                            class="text-base font-bold text-[#002654] leading-snug">
                                                                            {{ $isFr ? 'Des examens blancs au chrono' : 'Timed mock exams' }}
                                                                        </h3>
                                                                        <p
                                                                            class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                                                            {{ $isFr
    ? 'Votre espace élève contient des tests au format de l\'examen et des banques d\'exercices par compétence. Chaque devoir reçoit un retour.'
    : 'Your student portal has exam-format tests and practice banks for each skill. Every piece of homework gets feedback.'
                                }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <!-- Centered CTA Button -->
                                                                <div
                                                                    class="mt-12 flex flex-col items-center gap-3 text-center">
                                                                    <a href="/register"
                                                                        class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055a4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5">
                                                                        <div
                                                                            class="mt-10 flex flex-col items-center gap-3">
                                                                            <a href="/register"
                                                                                class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055a4] hover:bg-[#003f7a] transition-all">
                                                                                {{ $isFr ? "S'inscrire" : 'Enroll now' }}
                                                                                <i
                                                                                    class="fas fa-arrow-right text-xs ml-2.5"></i>
                                                                            </a>
                                                                            <p
                                                                                class="text-xs sm:text-[13px] text-slate-500 font-medium">
                                                                                {{ $isFr
    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
    : 'Live classes on Google Meet · 1:1 or small group · Student portal included' 
                            }}
                                                                            <p
                                                                                class="text-xs text-slate-500 font-medium">
                                                                                {{ $isFr ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' : 'Live classes on Google Meet · 1:1 or small group · Student portal included' }}
                                                                            </p>
                                                                        </div>
                                                                </div>
                    </section>

                    <!-- ============ SECTION 7: WHAT TCF IRN LEVEL DO YOU NEED? ============ -->
                    <section class="py-16 sm:py-24 bg-[#f8fafc] border-b border-slate-100">
                        <section class="py-16 sm:py-24 bg-[#f4f7fb] border-b border-slate-100">
                            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                                <!-- Centered Heading -->
                                <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                                    <div
                                        class="inline-flex items-center gap-2 text-xs font-bold tracking-wider uppercase text-primary-600 mb-2">
                                        <span class="w-2 h-2 rounded-full bg-[#0055a4]"></span>
                                        {{ $isFr ? 'Niveaux requis 2026' : '2026 Legal Requirements' }}
                                        <div class="inline-flex items-center gap-1.5 mb-3">
                                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                            <span
                                                class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                            <span
                                                class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                                {{ $isFr ? 'NIVEAU VISÉ' : 'WHICH LEVEL FOR WHICH GOAL' }}
                                            </span>
                                        </div>
                                        <h2 class="text-2xl sm:text-4xl font-extrabold text-[#002654] tracking-tight">
                                            {{ $isFr ? 'Quel niveau viser au TCF IRN ?' : 'What TCF IRN level do you need?' }}
                                            <h2
                                                class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                                {{ $isFr ? 'Quel niveau de TCF IRN vous faut-il ?' : 'What TCF IRN level do you need?' }}
                                            </h2>
                                            <p class="mt-4 text-base text-slate-600 leading-relaxed max-w-2xl mx-auto">
                                            <p
                                                class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto font-normal">
                                                {{ $isFr
    ? "Tout dépend de votre démarche : A2 pour une première carte de séjour pluriannuelle, B1 pour la carte de résident, B2 pour la naturalisation. Ces niveaux s'appliquent depuis le 1er janvier 2026. Vérifiez celui de votre dossier avant de vous inscrire au test."
    : 'It depends on your application: A2 for a first multi-year residence permit, B1 for the 10-year resident card, B2 for citizenship. These levels have applied since 1 January 2026. Check which one applies to you before you book the test.'
    ? 'Le TCF IRN délivre une attestation unique indiquant votre niveau dans chaque épreuve. Le niveau exigé dépend de votre dossier administratif.'
    : 'The TCF IRN gives a single certificate showing your level in each section. Which level you need depends on your application.'
                            }}
                                            </p>
                                    </div>

                                    <!-- 3 Level Cards Side-by-Side (A2, B1, and Highlighted B2) -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 items-stretch">
                                        <!-- Card 1: A2 -->
                                        <div
                                            class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between hover:border-slate-300 transition-all">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                <div
                                                    class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                                                    <div>
                                                        <div
                                                            class="text-4xl sm:text-5xl font-black text-[#0055a4] tracking-tight mb-3">
                                                            A2
                                                        </div>
                                                        <h3 class="text-lg font-bold text-[#002654]">
                                                            {{ $isFr ? 'Carte de séjour pluriannuelle' : 'Multi-year residence permit' }}
                                                        </h3>
                                                        <p
                                                            class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                                                            {{ $isFr
    ? 'Pour le premier renouvellement pluriannuel de titre de séjour en France.'
    : 'Required for first multi-year renewal of French residence cards.'
                                    }}
                                                            <span
                                                                class="text-3xl font-extrabold text-[#0055a4]">A2</span>
                                                        <h3 class="font-bold text-[#10233e] text-lg mt-2">
                                                            {{ $isFr ? 'Carte de séjour pluriannuelle' : 'Multi-year residence permit' }}
                                                        </h3>
                                                        <p
                                                            class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed font-normal">
                                                            {{ $isFr ? 'Exigé depuis les règles 2026 pour la première délivrance ou le renouvellement.' : 'Required under 2026 rules for first issue or renewal.' }}
                                                        </p>
                                                    </div>
                                                    <div class="pt-6 border-t border-slate-100 mt-6">
                                                        <a href="{{ $isFr ? '/fr/titre-de-sejour-niveau-francais/' : '/en/french-residence-permit-language/' }}"
                                                            class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-[#0055a4] hover:text-[#002654] group">
                                                            <span>{{ $isFr ? 'Niveau de français pour un titre de séjour' : 'French level for residence permits' }}</span>
                                                            <i
                                                                class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                                            <div class="pt-6">
                                                                <a href="/en/french-residence-permit-language"
                                                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0055a4] hover:underline">
                                                                    <span>{{ $isFr ? 'Guide carte de séjour' : 'Residence permit rules' }}</span>
                                                                    <span>→</span>
                                                                </a>
                                                            </div>
                                                    </div>

                                                    <!-- Card 2: B1 -->
                                                    <div
                                                        class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between hover:border-slate-300 transition-all">
                                                        <div
                                                            class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                                                            <div>
                                                                <div
                                                                    class="text-4xl sm:text-5xl font-black text-[#0055a4] tracking-tight mb-3">
                                                                    B1
                                                                </div>
                                                                <h3 class="text-lg font-bold text-[#002654]">
                                                                    {{ $isFr ? 'Carte de résident (10 ans)' : '10-year resident card' }}
                                                                </h3>
                                                                <p
                                                                    class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                                                                    {{ $isFr
    ? "Exigé pour l'obtention de la carte de résident de longue durée en France."
    : 'Required for the long-term 10-year resident card in France.'
                                    }}
                                                                    <span
                                                                        class="text-3xl font-extrabold text-[#0055a4]">B1</span>
                                                                <h3 class="font-bold text-[#10233e] text-lg mt-2">
                                                                    {{ $isFr ? 'Carte de résident (10 ans)' : '10-year resident card' }}
                                                                </h3>
                                                                <p
                                                                    class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed font-normal">
                                                                    {{ $isFr ? 'Le niveau B1 est requis pour obtenir la carte de résident de 10 ans.' : 'B1 is required to qualify for the 10-year resident card.' }}
                                                                </p>
                                                            </div>
                                                            <div class="pt-6 border-t border-slate-100 mt-6">
                                                                <a href="{{ $isFr ? '/fr/titre-de-sejour-niveau-francais/' : '/en/french-residence-permit-language/' }}"
                                                                    class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-[#0055a4] hover:text-[#002654] group">
                                                                    <span>{{ $isFr ? 'Niveau de français pour un titre de séjour' : 'French level for residence permits' }}</span>
                                                                    <i
                                                                        class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                                                    <div class="pt-6">
                                                                        <a href="/en/french-residence-permit-language"
                                                                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0055a4] hover:underline">
                                                                            <span>{{ $isFr ? 'Guide carte de résident' : 'Resident card rules' }}</span>
                                                                            <span>→</span>
                                                                        </a>
                                                                    </div>
                                                            </div>

                                                            <!-- Card 3: B2 (DARK NAVY HIGHLIGHT CARD) -->
                                                            <div
                                                                class="bg-[#002654] text-white rounded-3xl p-6 sm:p-8 shadow-xl shadow-navy-900/15 border border-navy-800 flex flex-col justify-between transform md:-translate-y-2">
                                                                <div
                                                                    class="bg-[#002654] text-white rounded-3xl border border-[#001f3f] p-7 flex flex-col justify-between relative overflow-hidden">
                                                                    <div class="absolute inset-x-0 top-0 h-1 flex">
                                                                        <span class="w-1/3 bg-[#0055A4]"></span>
                                                                        <span class="w-1/3 bg-white"></span>
                                                                        <span class="w-1/3 bg-[#EF4135]"></span>
                                                                    </div>
                                                                    <div>
                                                                        <div
                                                                            class="flex items-center justify-between mb-3">
                                                                            <div
                                                                                class="text-4xl sm:text-5xl font-black text-white tracking-tight">
                                                                                B2
                                                                            </div>
                                                                            <span
                                                                                class="px-3 py-1 rounded-full text-[11px] font-bold bg-[#0055a4] text-white border border-blue-400/30">
                                                                                {{ $isFr ? 'Loi 2026' : '2026 Law' }}
                                                                            </span>
                                                                        </div>
                                                                        <h3 class="text-lg font-bold text-white">
                                                                            {{ $isFr ? 'Naturalisation française' : 'French citizenship' }}
                                                                        </h3>
                                                                        <p
                                                                            class="mt-2 text-xs sm:text-sm text-blue-200/90 leading-relaxed">
                                                                            {{ $isFr
    ? 'Niveau B2 obligatoire pour les demandes de nationalité déposées depuis le 1er janvier 2026.'
    : 'B2 overall level mandatory for all citizenship files submitted since 1 January 2026.'
                                    }}
                                                                            <span
                                                                                class="text-3xl font-extrabold text-white">B2</span>
                                                                        <h3 class="font-bold text-white text-lg mt-2">
                                                                            {{ $isFr ? 'Naturalisation française' : 'French citizenship' }}
                                                                        </h3>
                                                                        <p
                                                                            class="text-xs sm:text-sm text-blue-100/90 mt-2 leading-relaxed font-normal">
                                                                            {{ $isFr ? 'Depuis le 1er janvier 2026, la nationalité française exige le niveau B2 à l\'oral et à l\'écrit.' : 'Since 1 January 2026, citizenship requires B2 French, spoken and written.' }}
                                                                        </p>
                                                                    </div>
                                                                    <div class="pt-6 border-t border-white/10 mt-6">
                                                                        <a href="{{ $isFr ? '/fr/test-francais-naturalisation/' : '/en/french-citizenship-language-test/' }}"
                                                                            class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-white hover:text-blue-200 group">
                                                                            <span>{{ $isFr ? 'Test de français pour la naturalisation' : 'The French citizenship language test' }}</span>
                                                                            <i
                                                                                class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                                                            <div class="pt-6">
                                                                                <a href="/en/french-citizenship-language-test"
                                                                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-white hover:underline">
                                                                                    <span>{{ $isFr ? 'Guide naturalisation B2' : 'Citizenship B2 guide' }}</span>
                                                                                    <span>→</span>
                                                                                </a>
                                                                            </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Bottom Text with Links -->
                                                                <div
                                                                    class="mt-12 text-center text-xs sm:text-sm text-slate-600 max-w-3xl mx-auto leading-relaxed">
                                                                    {{ $isFr
    ? 'Depuis 2026, un examen civique est aussi exigé pour la naturalisation par décret et pour les titres de séjour concernés par les règles de 2026.'
    : 'Citizenship by decree and the residence permit applications covered by the 2026 rules also need the French civic exam.'
                        }}
                                                                    <a href="{{ $isFr ? '/fr/examen-civique/' : '/en/french-civic-exam/' }}"
                                                                        class="font-semibold text-[#0055a4] underline hover:text-[#002654]">
                                                                        {{ $isFr ? "En savoir plus sur l'examen civique" : 'French civic exam' }}
                                                                    </a>.
                                                                    {{ $isFr ? 'Vous hésitez encore sur le test ?' : 'Not sure which test to take?' }}
                                                                    <a href="{{ $isFr ? '/fr/tcf-tef-ou-delf/' : '/en/tcf-vs-tef-vs-delf/' }}"
                                                                        class="font-semibold text-[#0055a4] underline hover:text-[#002654]">
                                                                        {{ $isFr ? 'Comparez le TCF, le TEF et le DELF' : 'Compare the TCF, TEF and DELF' }}
                                                                    </a>.
                                                                </div>
                                                            </div>
                        </section>


                        <!-- ============ SECTION 8: FAQ (REUSABLE COMPONENT) ============ -->
                        @include('partials.faq-section', [
                            'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                            'title' => 'TCF IRN<br>FAQ',
                            'description' => $isFr ? "Des réponses claires avant de passer à l'étape suivante." : 'Clear answers before you take your next step.',
                            'contactText' => $isFr ? 'Une autre question ?' : 'Have another question?',
                            'contactUrl' => $isFr ? '/fr/contact/' : '/en/contact/',
                            'faqs' => $faqs
                        ])
                        @include('partials.faq-section', [
                            'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                            'title' => 'TCF IRN<br>FAQ',
                            'description' => $isFr ? "Des réponses claires avant de passer à l'étape suivante." : 'Clear answers before you take your next step.',
                            'contactText' => $isFr ? 'Une autre question ?' : 'Have another question?',
                            'contactUrl' => $isFr ? '/fr/contact/' : '/en/contact/',
                            'faqs' => $faqs
                        ])

                        <!-- ============ SECTION 9: FINAL CTA & SOURCES ============ -->
                        <section class="py-16 sm:py-20 bg-[#f8fafc]">
                            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                                @include('partials.cta-banner', [
                                    'sectionClass' => '',
                                    'eyebrow' => $isFr ? 'NOUS VOUS ACCOMPAGNONS' : "LET'S GET YOU THERE",
                                    'title' => $isFr
                                        ? "Votre niveau. Le niveau exigé.<br class='hidden sm:inline'> Nous comblons l'écart."
                                        : "Your level. The required level.<br class='hidden sm:inline'> We close the gap.",
                                    'description' => $isFr
                                        ? 'Votre premier cours fixe votre niveau dans chaque épreuve et le plan pour atteindre celui de votre dossier. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                                        : 'Your first class sets your level in each section and the plan to reach the one your application needs. You choose the pace: 2 or 4 hours a week.',
                                    'primaryButtonText' => $isFr ? "S'inscrire" : 'Enroll now',
                                    'primaryButtonUrl' => '/register',
                                    'showSecondaryButton' => true,
                                    'secondaryButtonText' => $isFr ? 'Contactez-nous' : 'Talk to us',
                                    'secondaryButtonUrl' => $isFr ? '/fr/contact/' : '/en/contact/',
                                    'helperText' => $isFr
                                        ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                                        : 'Live classes on Google Meet · 1:1 or small group · Student portal included',
                                    'referralNote' => $isFr
                                        ? "Vous venez à deux ? 50 € de remise par mois, pour vous et pour votre ami. <a href='/fr/contact/#parrainage' class='underline font-semibold text-white hover:text-blue-200 ml-1'>Contactez-nous</a>"
                                        : "Coming with a friend? €50 off per month, for you and for them. <a href='/en/contact/#referral' class='underline font-semibold text-white hover:text-blue-200 ml-1'>Contact us</a>"
                                ])
                                <!-- ============ SECTION 9: FINAL CTA & SOURCES ============ -->
                                <section class="py-16 sm:py-20 bg-[#f8fafc]">
                                    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                                        @include('partials.cta-banner', [
                                            'sectionClass' => '',
                                            'eyebrow' => $isFr ? 'NOUS VOUS ACCOMPAGNONS' : "LET'S GET YOU THERE",
                                            'title' => $isFr
                                                ? "Votre niveau. Le niveau exigé.<br class='hidden sm:inline'> Nous comblons l'écart."
                                                : "Your level. The required level.<br class='hidden sm:inline'> We close the gap.",
                                            'description' => $isFr
                                                ? 'Votre premier cours fixe votre niveau dans chaque épreuve et le plan pour atteindre celui de votre dossier. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                                                : 'Your first class sets your level in each section and the plan to reach the one your application needs. You choose the pace: 2 or 4 hours a week.',
                                            'primaryButtonText' => $isFr ? "S'inscrire" : 'Enroll now',
                                            'primaryButtonUrl' => '/register',
                                            'showSecondaryButton' => true,
                                            'secondaryButtonText' => $isFr ? 'Contactez-nous' : 'Talk to us',
                                            'secondaryButtonUrl' => $isFr ? '/fr/contact/' : '/en/contact/',
                                            'helperText' => $isFr
                                                ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                                                : 'Live classes on Google Meet · 1:1 or small group · Student portal included',
                                            'referralNote' => $isFr
                                                ? "Vous venez à deux ? 50 € de remise par mois, pour vous et pour votre ami. <a href='/fr/contact/#parrainage' class='underline font-semibold text-white hover:text-blue-200 ml-1'>Contactez-nous</a>"
                                                : "Coming with a friend? €50 off per month, for you and for them. <a href='/en/contact/#referral' class='underline font-semibold text-white hover:text-blue-200 ml-1'>Contact us</a>"
                                        ])

                                        <!-- Sources Line -->
                                        <div class="mt-10 text-center text-xs text-slate-400 space-x-1">
                                            <span>{{ $isFr ? 'Sources :' : 'Sources:' }}</span>
                                            <a href="https://www.france-education-international.fr/article/tcf-integration-residence-et-nationalite"
                                                target="_blank" rel="noopener" class="underline hover:text-slate-600">
                                                France Éducation international – TCF IRN
                                            </a>
                                            <span>·</span>
                                            <a href="https://www.service-public.fr/particuliers/vosdroits/F2213"
                                                target="_blank" rel="noopener" class="underline hover:text-slate-600">
                                                Service-public.fr – {{ $isFr ? 'naturalisation' : 'citizenship' }}
                                            </a>
                                            <span>·</span>
                                            <a href="https://www.service-public.fr/particuliers/vosdroits/N110"
                                                target="_blank" rel="noopener" class="underline hover:text-slate-600">
                                                Service-public.fr – {{ $isFr ? 'cartes de séjour' : 'residence cards' }}
                                            </a>
                                            <div class="mt-10 text-center text-xs text-slate-400 space-x-1">
                                                <span>{{ $isFr ? 'Sources :' : 'Sources:' }}</span>
                                                <a href="https://www.france-education-international.fr/article/tcf-integration-residence-et-nationalite"
                                                    target="_blank" rel="noopener"
                                                    class="underline hover:text-slate-600">
                                                    France Éducation international – TCF IRN
                                                </a>
                                                <span>·</span>
                                                <a href="https://www.service-public.fr/particuliers/vosdroits/F2213"
                                                    target="_blank" rel="noopener"
                                                    class="underline hover:text-slate-600">
                                                    Service-public.fr – {{ $isFr ? 'naturalisation' : 'citizenship' }}
                                                </a>
                                                <span>·</span>
                                                <a href="https://www.service-public.fr/particuliers/vosdroits/N110"
                                                    target="_blank" rel="noopener"
                                                    class="underline hover:text-slate-600">
                                                    Service-public.fr –
                                                    {{ $isFr ? 'cartes de séjour' : 'residence cards' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                        </section>
        </main>

        <!-- FOOTER: Standard Unified Component -->
        @include('partials.footer')
    </div>
</body>

</html>