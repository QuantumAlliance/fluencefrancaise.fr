<!DOCTYPE html>
@php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    $isFr = request()->is('fr*');

    $title = $isFr
        ? 'TCF, TEF ou DELF : quel test de français choisir en 2026 ?'
        : 'TCF vs TEF vs DELF: Which French Test Should You Take?';

    $metaDesc = $isFr
        ? 'TCF IRN, TEF IRN ou DELF pour votre titre de séjour ou la naturalisation ? Validité, format, notation comparés, et le bon choix en 3 questions.'
        : 'TCF IRN, TEF IRN or DELF for your residence card or French citizenship? Validity, format and scoring compared, and the right choice in 3 questions.';

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/tcf-tef-ou-delf/' : 'https://fluencefrancaise.fr/en/tcf-vs-tef-vs-delf/';
    $frUrl = 'https://fluencefrancaise.fr/fr/tcf-tef-ou-delf/';
    $enUrl = 'https://fluencefrancaise.fr/en/tcf-vs-tef-vs-delf/';

    $homeUrl = $isFr ? '/fr' : '/en';
    $delfUrl = $isFr ? '/fr/delf/' : '/en/delf/';
    $tcfUrl = $isFr ? '/fr/tcf-irn/' : '/en/tcf-irn/';
    $tefUrl = $isFr ? '/fr/tef-irn/' : '/en/tef-irn/';
    $delfA2Url = $isFr ? '/fr/delf-a2/' : '/en/delf-a2/';
    $delfB1Url = $isFr ? '/fr/delf-b1/' : '/en/delf-b1/';
    $delfB2Url = $isFr ? '/fr/delf-b2/' : '/en/delf-b2/';
    $fideUrl = $isFr ? '/fr/test-fide/' : '/en/fide-test/';
    $civicUrl = $isFr ? '/fr/examen-civique/' : '/en/french-civic-exam/';
    $permitUrl = $isFr ? '/fr/titre-de-sejour-niveau-francais/#carte-pluriannuelle' : '/en/french-residence-permit-language/#multi-year-permit';
    $residentUrl = $isFr ? '/fr/titre-de-sejour-niveau-francais/#carte-de-resident' : '/en/french-residence-permit-language/#resident-card';
    $citizenshipUrl = $isFr ? '/fr/test-francais-naturalisation/' : '/en/french-citizenship-language-test/';
    $contactUrl = $isFr ? '/fr/contact/' : '/en/contact/';
    $referralUrl = $isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral';

    $faqs = $isFr ? [
        [
            'q' => 'Le TCF tout public est-il accepté pour la naturalisation ?',
            'a' => 'Pour vos démarches de séjour et de nationalité, préparez le TCF IRN, la version conçue pour l\'intégration, la résidence et la nationalité. En cas de doute sur un autre format, vérifiez auprès de votre préfecture avant de vous inscrire.',
        ],
        [
            'q' => 'Puis-je utiliser un ancien résultat de TCF ou de TEF ?',
            'a' => 'Seulement s\'il a moins de 2 ans et qu\'il montre le niveau exigé aujourd\'hui. Un ancien B1 ne suffit plus pour une naturalisation demandée depuis 2026.',
        ],
        [
            'q' => 'Le DELF est-il plus difficile que le TCF ?',
            'a' => 'Pas forcément. Il est plus long et comporte des productions plus développées. En contrepartie, sa notation globale pardonne davantage un point faible.',
        ],
        [
            'q' => 'Je ne connais pas mon niveau. Par où commencer ?',
            'a' => 'Par votre premier cours. Votre tuteur mesure vos 4 compétences, puis vous aide à choisir le test.',
        ],
    ] : [
        [
            'q' => 'Is the TCF Tout Public accepted for citizenship?',
            'a' => 'For residence and citizenship applications, prepare the TCF IRN, the version built for integration, residence and citizenship. If you\'re unsure about another format, check with your préfecture before you book.',
        ],
        [
            'q' => 'Can I use an old TCF or TEF result?',
            'a' => 'Only if it\'s under 2 years old and shows the level required today. An old B1 is no longer enough for a citizenship application filed since 2026.',
        ],
        [
            'q' => 'Is the DELF harder than the TCF?',
            'a' => 'Not necessarily. It\'s longer and asks for more developed writing and speaking. In return, its overall scoring is more forgiving of 1 weak section.',
        ],
        [
            'q' => 'I don\'t know my level. Where do I start?',
            'a' => 'With your first class. Your tutor measures all 4 skills, then helps you pick the test.',
        ],
    ];

    $rows = $isFr ? [
        ['Type', 'Test', 'Test', 'Diplôme'],
        ['Organisme', 'France Éducation international', 'Le français des affaires (CCI Paris Île-de-France)', 'France Éducation international'],
        ['Format', '4 épreuves, 1 h 35', '4 épreuves obligatoires, 1 session', '4 épreuves : de 1 h 40 (A2) à 2 h 30 (B2) d\'écrit, plus l\'oral'],
        ['Résultat', 'Un niveau par compétence + niveau global', 'Un niveau par compétence + niveau global', 'Diplôme si 50/100, avec 5/25 minimum par épreuve'],
        ['Validité', '2 ans', '2 ans', 'À vie'],
        ['Sessions', 'Fréquentes dans de nombreux centres', 'Fréquentes dans de nombreux centres', 'À dates fixes, selon chaque centre'],
        ['Accepté pour', 'Carte pluriannuelle, carte de résident, naturalisation', 'Carte pluriannuelle, carte de résident, naturalisation', 'Les mêmes, en France et en Suisse'],
    ] : [
        ['Type', 'Test', 'Test', 'Diploma'],
        ['Run by', 'France Éducation international', 'Le français des affaires (Paris Chamber of Commerce)', 'France Éducation international'],
        ['Format', '4 sections, 1 h 35', '4 compulsory sections, 1 session', '4 sections: 1 h 40 (A2) to 2 h 30 (B2) written, plus the oral'],
        ['Result', 'A level per skill + overall level', 'A level per skill + overall level', 'Diploma at 50/100, with at least 5/25 per section'],
        ['Valid for', '2 years', '2 years', 'Life'],
        ['Sessions', 'Frequent, at many centres', 'Frequent, at many centres', 'Fixed dates, set by each centre'],
        ['Accepted for', 'Multi-year permit, resident card, citizenship', 'Multi-year permit, resident card, citizenship', 'The same, in France and Switzerland'],
    ];

    $questions = $isFr ? [
        [
            '01',
            'Vivez-vous en France ou en Suisse ?',
            'En Suisse romande, regardez d\'abord le test fide, conçu pour les démarches suisses. Le DELF y est aussi accepté. Le TCF IRN et le TEF IRN sont faits pour les démarches françaises.',
        ],
        [
            '02',
            'Votre calendrier est-il sûr ?',
            'Si vous déposez votre dossier bientôt et que tout est prêt, un test valable 2 ans suffit. Si l\'instruction peut traîner, ou si vous visez une autre démarche ensuite (carte de résident, puis nationalité), un diplôme à vie vous évite de repasser un test.',
        ],
        [
            '03',
            'Quel est votre profil de compétences ?',
            'Le TCF et le TEF notent chaque compétence séparément et indiquent aussi un niveau global. Le DELF additionne les points : un point fort peut compenser un point plus faible, tant qu\'aucune épreuve ne descend sous 5 sur 25. Les règles de notation diffèrent d\'un examen à l\'autre.',
        ],
    ] : [
        [
            '01',
            'Do you live in France or Switzerland?',
            'In French-speaking Switzerland, look first at the fide test, built for Swiss applications. The DELF is accepted there too. The TCF IRN and TEF IRN are designed for French applications.',
        ],
        [
            '02',
            'Is your timeline certain?',
            'If you\'re filing soon and everything\'s ready, a test valid for 2 years is enough. If processing could drag on, or you\'re planning another application later (resident card, then citizenship), a lifetime diploma saves you from retaking a test.',
        ],
        [
            '03',
            'What\'s your skill profile?',
            'The TCF and TEF report each skill separately and also indicate an overall level. The DELF adds up the points, so a strong section can make up for a weaker one, as long as none drops below 5 out of 25. The scoring rules differ between exams.',
        ],
    ];

    $levels = $isFr ? [
        ['Carte de séjour pluriannuelle', $permitUrl, 'A2'],
        ['Carte de résident', $residentUrl, 'B1'],
        ['Naturalisation', $citizenshipUrl, 'B2'],
    ] : [
        ['Multi-year residence permit', $permitUrl, 'A2'],
        ['10-year resident card', $residentUrl, 'B1'],
        ['French citizenship', $citizenshipUrl, 'B2'],
    ];

    $prep = $isFr ? [
        ['fa-layer-group', 'Les 3 formats', 'Nous préparons le TCF IRN, le TEF IRN et le DELF.'],
        ['fa-compass', 'Le bon test', 'Votre tuteur vous aide à choisir celui qui convient à votre dossier.'],
        ['fa-bullseye', 'Le format exact', 'Puis vous vous entraînez sur le format que vous allez passer.'],
        ['fa-video', '2 ou 4 heures par semaine', 'En direct, en particulier ou en mini-groupe, après la mesure de vos 4 compétences.'],
    ] : [
        ['fa-layer-group', 'All 3 formats', 'We prepare the TCF IRN, the TEF IRN and the DELF.'],
        ['fa-compass', 'The right test', 'Your tutor helps you pick the one that suits your application.'],
        ['fa-bullseye', 'That exact format', 'Then you train on the format you will actually sit.'],
        ['fa-video', '2 or 4 hours a week', 'Live, 1:1 or in a small group, after all 4 skills are measured.'],
    ];

    $jumps = $isFr ? [
        ['#comparison', 'Comparatif'],
        ['#questions', '3 questions'],
        ['#levels', 'Niveaux'],
        ['#prepare', 'Notre préparation'],
        ['#faq', 'FAQ'],
    ] : [
        ['#comparison', 'Comparison'],
        ['#questions', '3 questions'],
        ['#levels', 'Levels'],
        ['#prepare', 'Our preparation'],
        ['#faq', 'FAQ'],
    ];
@endphp
<html lang="{{ $isFr ? 'fr' : 'en' }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | {{ $siteName }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="fr" href="{{ $frUrl }}">
    <link rel="alternate" hreflang="en" href="{{ $enUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $enUrl }}">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $title }} | {{ $siteName }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ $siteName }}">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "Article",
                "headline": "{{ $title }}",
                "description": "{{ $metaDesc }}",
                "author": { "@type": "Organization", "name": "{{ $siteName }}", "url": "https://fluencefrancaise.fr" },
                "publisher": { "@type": "Organization", "name": "{{ $siteName }}", "url": "https://fluencefrancaise.fr" },
                "mainEntityOfPage": "{{ $canonicalUrl }}",
                "inLanguage": "{{ $isFr ? 'fr' : 'en' }}"
            },
            {
                "@type": "BreadcrumbList",
                "itemListElement": [
                    { "@type": "ListItem", "position": 1, "name": "{{ $isFr ? 'Accueil' : 'Home' }}", "item": "{{ $isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en' }}" },
                    { "@type": "ListItem", "position": 2, "name": "Guides", "item": "{{ $isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en' }}" },
                    { "@type": "ListItem", "position": 3, "name": "{{ $isFr ? 'TCF, TEF ou DELF' : 'TCF, TEF or DELF' }}", "item": "{{ $canonicalUrl }}" }
                ]
            },
            {
                "@type": "FAQPage",
                "mainEntity": [
                    @foreach($faqs as $faq)
                        {
                            "@type": "Question",
                            "name": "{{ $faq['q'] }}",
                            "acceptedAnswer": { "@type": "Answer", "text": "{{ trim(preg_replace('/\s+/', ' ', strip_tags($faq['a']))) }}" }
                        }@if(!$loop->last),@endif
                    @endforeach
                ]
            }
        ]
    }
    </script>

    @include('partials.favicon')
    @include('partials.theme')
    @include('partials.custom-scripts-head')
</head>

<body class="antialiased bg-white text-ink">
    @include('partials.custom-scripts-body')

    <div class="landing-page min-h-screen flex flex-col">
        @include('partials.header')

        <main class="flex-grow">
            <section class="relative overflow-hidden bg-white pt-28 sm:pt-36 md:pt-40 pb-10">
                <div class="hero-aurora"></div>
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="grid lg:grid-cols-12 gap-10 lg:gap-8 items-center">
                        <div class="lg:col-span-7">
                            <nav aria-label="Breadcrumb" class="text-xs text-slate-400 font-medium flex items-center gap-2 mb-5">
                                <a href="{{ $homeUrl }}" class="hover:text-[#0055A4] transition-colors">{{ $isFr ? 'Accueil' : 'Home' }}</a>
                                <span class="text-slate-300">›</span>
                                <span>Guides</span>
                                <span class="text-slate-300">›</span>
                                <span class="text-slate-700 font-semibold">{{ $isFr ? 'TCF, TEF ou DELF' : 'TCF, TEF or DELF' }}</span>
                            </nav>

                            <div class="inline-flex items-center gap-1.5 mb-4">
                                <span class="inline-block w-3.5 h-[3px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[3px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                    {{ $isFr ? 'CHOISIR LE BON TEST' : 'CHOOSE THE RIGHT TEST' }}
                                </span>
                            </div>

                            <h1 class="text-3xl sm:text-5xl lg:text-[46px] font-bold text-[#0c1e38] tracking-tight leading-[1.12]">
                                {{ $isFr ? 'TCF, TEF ou DELF :' : 'TCF, TEF or DELF:' }}
                                <span class="block w-fit max-w-full text-[#0055A4] mt-1">
                                    {{ $isFr ? 'le bon test en 3 questions' : 'The Right Test in 3 Questions' }}
                                    <span class="block h-[3px] mt-2 rounded-full" style="background: linear-gradient(90deg, #0055A4 0%, #0055A4 33.3%, #eaebf0 33.3%, #eaebf0 66.6%, #EF4135 66.6%, #EF4135 100%);"></span>
                                </span>
                            </h1>

                            <p class="mt-6 text-sm sm:text-base md:text-[17px] text-slate-600 max-w-xl leading-relaxed">
                                {{ $isFr
                                    ? 'Les 3 sont acceptés pour vos démarches, mais ils ne se valent pas pour votre dossier : voici comment choisir sans vous tromper.'
                                    : "All 3 are accepted for your application, but they don't serve it equally, so here's how to choose without getting it wrong." }}
                            </p>

                            <div class="mt-8 flex flex-wrap items-center gap-4 sm:gap-5">
                                <a href="/register" class="inline-flex items-center justify-center px-7 py-3.5 rounded-full text-sm sm:text-base font-semibold text-white bg-[#0055A4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all">
                                    {{ $isFr ? "S'inscrire" : 'Enroll now' }}
                                    <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                                </a>
                                <a href="#short-answer" class="inline-flex items-center gap-2 text-sm sm:text-base font-semibold text-[#10233e] hover:text-[#0055A4] transition-colors">
                                    {{ $isFr ? 'Lire le guide' : 'Read the guide' }}
                                    <i class="fas fa-chevron-down text-[11px] text-[#EF4135]"></i>
                                </a>
                            </div>

                            <p class="mt-5 text-xs sm:text-[13px] text-slate-500 font-medium">
                                {{ $isFr
                                    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                                    : 'Live classes on Google Meet · 1:1 or small group · Student portal included' }}
                            </p>
                            <p class="mt-3 text-xs font-semibold text-slate-400">
                                <span class="text-[#0055A4] text-[8px] align-middle mr-1">●</span>
                                {{ $isFr ? 'Mis à jour le 12 novembre 2026' : 'Updated 12 November 2026' }}
                            </p>
                        </div>

                        <div class="lg:col-span-5">
                            <div class="relative mx-auto w-full max-w-[420px] h-[320px] sm:h-[360px]">
                                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[280px] h-[280px] sm:w-[320px] sm:h-[320px] rounded-full border border-[#d7e4f5]"></div>
                                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[210px] h-[210px] sm:w-[240px] sm:h-[240px] rounded-full border border-slate-200/90"></div>
                                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 -rotate-6 w-48 h-48 sm:w-56 sm:h-56 rounded-[2rem] bg-[#002654] shadow-[0_24px_50px_-18px_rgba(0,38,84,.45)] flex flex-col items-center justify-center text-center px-5">
                                    <p class="text-[10px] sm:text-[11px] font-bold tracking-[0.2em] uppercase text-blue-200/80">{{ $isFr ? 'Votre prochaine étape' : 'Your next step' }}</p>
                                    <p class="mt-2 text-5xl sm:text-6xl font-extrabold text-white tracking-tight leading-none">{{ $isFr ? '3 tests' : '3 tests' }}</p>
                                    <p class="mt-3 text-xs sm:text-sm font-medium text-blue-100/90">TCF · TEF · DELF</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 sm:mt-4 flex flex-wrap items-center justify-center gap-2.5">
                        @foreach($jumps as $jump)
                            <a href="{{ $jump[0] }}" class="inline-flex items-center px-4 py-2 rounded-full border border-slate-200 bg-white text-xs sm:text-sm font-medium text-slate-600 hover:text-[#0055A4] hover:border-[#0055A4]/30 transition-colors">
                                {{ $jump[1] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="short-answer" class="scroll-mt-28 py-16 sm:py-20 bg-white border-t border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl text-center">
                    <div class="inline-flex items-center gap-1.5 mb-3">
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                        <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">{{ $isFr ? 'LA RÉPONSE COURTE' : 'THE SHORT ANSWER' }}</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                        {{ $isFr ? 'TCF, TEF ou DELF : la réponse courte' : 'TCF vs TEF vs DELF: the short answer' }}
                    </h2>
                    <p class="mt-5 text-sm sm:text-base text-slate-500 leading-relaxed">
                        @if($isFr)
                            Pour un dossier rapide, le TCF IRN ou le TEF IRN : ils sont courts et équivalents, mais valables 2 ans. Pour un dossier qui peut durer, ou plusieurs démarches à la suite, le <a href="{{ $delfUrl }}" class="font-semibold text-[#0055A4] hover:underline">DELF</a> : il est plus long, mais valable à vie. En Suisse romande, le test fide ou le DELF.
                        @else
                            For a quick application, take the TCF IRN or TEF IRN: they're short and equivalent, but valid for 2 years. For an application that could drag on, or several applications in a row, take the <a href="{{ $delfUrl }}" class="font-semibold text-[#0055A4] hover:underline">DELF</a>: it's longer, but valid for life. In French-speaking Switzerland, take the fide test or the DELF.
                        @endif
                    </p>
                </div>
            </section>

            <section id="comparison" class="scroll-mt-28 py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">{{ $isFr ? 'CÔTE À CÔTE' : 'SIDE BY SIDE' }}</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            {{ $isFr ? 'Le comparatif complet' : 'The full comparison' }}
                        </h2>
                    </div>

                    <div class="rounded-2xl border border-slate-200/80 overflow-hidden bg-white">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] text-sm text-left">
                                <thead>
                                    <tr class="bg-[#0055A4] text-white">
                                        <th class="px-5 py-3.5 font-semibold w-[18%]"></th>
                                        <th class="px-5 py-3.5 font-semibold">TCF IRN</th>
                                        <th class="px-5 py-3.5 font-semibold">TEF IRN</th>
                                        <th class="px-5 py-3.5 font-semibold">DELF (A2, B1, B2)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rows as $index => $row)
                                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-[#f7f9fc]' }} border-t border-slate-100">
                                            <th class="px-5 py-4 font-semibold text-[#10233e] align-top">{{ $row[0] }}</th>
                                            <td class="px-5 py-4 text-slate-600 align-top leading-relaxed">{{ $row[1] }}</td>
                                            <td class="px-5 py-4 text-slate-600 align-top leading-relaxed">{{ $row[2] }}</td>
                                            <td class="px-5 py-4 text-slate-600 align-top leading-relaxed">{{ $row[3] }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-white border-t border-slate-100">
                                        <th class="px-5 py-4 font-semibold text-[#10233e] align-top">{{ $isFr ? 'Notre page' : 'Our page' }}</th>
                                        <td class="px-5 py-4 align-top">
                                            <a href="{{ $tcfUrl }}" class="font-semibold text-[#0055A4] hover:underline">TCF IRN →</a>
                                        </td>
                                        <td class="px-5 py-4 align-top">
                                            <a href="{{ $tefUrl }}" class="font-semibold text-[#0055A4] hover:underline">TEF IRN →</a>
                                        </td>
                                        <td class="px-5 py-4 align-top leading-relaxed">
                                            <a href="{{ $delfA2Url }}" class="font-semibold text-[#0055A4] hover:underline">DELF A2</a>
                                            <span class="text-slate-300 mx-1">·</span>
                                            <a href="{{ $delfB1Url }}" class="font-semibold text-[#0055A4] hover:underline">B1</a>
                                            <span class="text-slate-300 mx-1">·</span>
                                            <a href="{{ $delfB2Url }}" class="font-semibold text-[#0055A4] hover:underline">B2</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section id="questions" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">{{ $isFr ? 'DÉCIDER EN 3 QUESTIONS' : 'DECIDE IN 3 QUESTIONS' }}</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            {{ $isFr ? 'Le bon test en 3 questions' : 'The right test in 3 questions' }}
                        </h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        @foreach($questions as $question)
                            <article class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 flex flex-col">
                                <span class="text-sm font-bold text-[#0055A4]">{{ $question[0] }}</span>
                                <h3 class="mt-3 text-lg font-bold text-[#10233e] leading-snug">{{ $question[1] }}</h3>
                                <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                                    @if($loop->first)
                                        @if($isFr)
                                            En Suisse romande, regardez d'abord le <a href="{{ $fideUrl }}" class="font-semibold text-[#0055A4] hover:underline">test fide</a>, conçu pour les démarches suisses. Le DELF y est aussi accepté. Le TCF IRN et le TEF IRN sont faits pour les démarches françaises.
                                        @else
                                            In French-speaking Switzerland, look first at the <a href="{{ $fideUrl }}" class="font-semibold text-[#0055A4] hover:underline">fide test</a>, built for Swiss applications. The DELF is accepted there too. The TCF IRN and TEF IRN are designed for French applications.
                                        @endif
                                    @else
                                        {{ $question[2] }}
                                    @endif
                                </p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl text-center">
                    <div class="inline-flex items-center gap-1.5 mb-3">
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                        <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">{{ $isFr ? 'MÊME NIVEAU, FORMAT DIFFÉRENT' : 'SAME LEVEL, DIFFERENT FORMAT' }}</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                        {{ $isFr ? 'TCF IRN ou TEF IRN : lequel est le plus facile ?' : 'TCF IRN or TEF IRN: which is easier?' }}
                    </h2>
                    <div class="mt-5 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                        <p>
                            {{ $isFr
                                ? 'Aucun des 2. Ils mesurent les mêmes niveaux, avec la même validité, pour les mêmes démarches. La différence se joue sur le format, les dates et les centres près de chez vous.'
                                : 'Neither. They measure the same levels, with the same validity, for the same applications. The difference is the format, the dates and the centres near you.' }}
                        </p>
                        <p class="rounded-2xl bg-white border border-slate-200/80 px-5 py-4 text-left sm:text-center">
                            {{ $isFr
                                ? 'Une règle les distingue quand même : au TEF IRN, les 4 épreuves sont indissociables. Abandonnez-en une, et vous n\'avez aucune attestation. Quel que soit votre choix, entraînez-vous sur le format exact que vous allez passer.'
                                : "One rule does set them apart: on the TEF IRN, the 4 sections can't be separated. Leave 1, and you get no results at all. Whichever you choose, practise on the exact format you'll sit." }}
                        </p>
                    </div>
                </div>
            </section>

            <section id="levels" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">{{ $isFr ? 'LES EXIGENCES 2026' : 'THE 2026 REQUIREMENTS' }}</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            {{ $isFr ? 'Quel niveau viser ?' : 'What level should you aim for?' }}
                        </h2>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 overflow-hidden bg-white">
                        <div class="grid grid-cols-12 bg-[#0055A4] text-white px-5 sm:px-6 py-3.5 text-sm font-semibold">
                            <div class="col-span-8">{{ $isFr ? 'Démarche' : 'Application' }}</div>
                            <div class="col-span-4 text-right">{{ $isFr ? 'Niveau exigé depuis 2026' : 'Level required since 2026' }}</div>
                        </div>
                        @foreach($levels as $index => $level)
                            <div class="grid grid-cols-12 gap-3 px-5 sm:px-6 py-4 text-sm items-center {{ $index % 2 === 0 ? 'bg-white' : 'bg-[#f7f9fc]' }} {{ $index < count($levels) - 1 ? 'border-b border-slate-100' : '' }}">
                                <div class="col-span-8 font-semibold">
                                    <a href="{{ $level[1] }}" class="text-[#0055A4] hover:underline">{{ $level[0] }} →</a>
                                </div>
                                <div class="col-span-4 text-right font-bold text-[#10233e]">{{ $level[2] }}</div>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-5 text-center text-xs sm:text-sm text-slate-500 leading-relaxed">
                        @if($isFr)
                            Pour la naturalisation par décret et les titres de séjour concernés par les règles de 2026, <a href="{{ $civicUrl }}" class="font-semibold text-[#0055A4] hover:underline">l'examen civique</a> s'ajoute au test de langue.
                        @else
                            Citizenship by decree and the residence permit applications covered by the 2026 rules also need the <a href="{{ $civicUrl }}" class="font-semibold text-[#0055A4] hover:underline">civic exam</a> on top of the language test.
                        @endif
                    </p>
                </div>
            </section>

            <section id="prepare" class="scroll-mt-28 py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">{{ $isFr ? 'LES 3 FORMATS' : 'ALL 3 FORMATS' }}</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            {{ $isFr ? 'Notre préparation' : 'How we prepare you' }}
                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                            {{ $isFr
                                ? 'Nous préparons les 3 formats. Dès le premier cours, votre tuteur mesure vos 4 compétences, vous aide à choisir le test qui convient à votre dossier, puis vous entraîne sur ce format précis. Cours en direct, 2 ou 4 heures par semaine, en particulier ou en mini-groupe.'
                                : 'We prepare all 3 formats. In your first class, your tutor measures your 4 skills, helps you pick the test that suits your application, then trains you on that exact format. Live classes, 2 or 4 hours a week, 1:1 or in a small group.' }}
                        </p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-4xl mx-auto">
                        @foreach($prep as $item)
                            <article class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 flex gap-4">
                                <span class="w-10 h-10 rounded-full bg-blue-50 text-[#0055A4] flex items-center justify-center shrink-0">
                                    <i class="fas {{ $item[0] }} text-sm"></i>
                                </span>
                                <div>
                                    <h3 class="font-bold text-[#10233e]">{{ $item[1] }}</h3>
                                    <p class="mt-1 text-sm text-slate-500 leading-relaxed">{{ $item[2] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="mt-10 text-center">
                        <a href="/register" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055A4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all">
                            {{ $isFr ? "S'inscrire" : 'Enroll now' }}
                            <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                        </a>
                        <p class="mt-3 text-xs sm:text-[13px] text-slate-500">
                            {{ $isFr
                                ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                                : 'Live classes on Google Meet · 1:1 or small group · Student portal included' }}
                        </p>
                    </div>
                </div>
            </section>

            @include('partials.faq-section', [
                'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                'title' => $isFr ? 'Questions fréquentes<br>TCF, TEF ou DELF' : 'TCF, TEF or<br>DELF FAQ',
                'description' => $isFr ? 'Des réponses claires avant de passer à l\'étape suivante.' : 'Clear answers before you take your next step.',
                'contactText' => $isFr ? 'Une autre question ?' : 'Have another question?',
                'contactUrl' => $contactUrl,
                'faqs' => $faqs,
                'sectionClass' => 'py-16 sm:py-20 bg-white',
            ])

            <div id="enroll" class="scroll-mt-28">
                @include('partials.cta-banner', [
                    'sectionClass' => 'pt-4 pb-6 bg-white',
                    'eyebrow' => $isFr ? 'NOUS VOUS ACCOMPAGNONS' : "LET'S GET YOU THERE",
                    'title' => $isFr
                        ? "Votre niveau. Le niveau exigé.<br class='hidden sm:inline'> Nous comblons l'écart."
                        : "Your level. The required level.<br class='hidden sm:inline'> We close the gap.",
                    'description' => $isFr
                        ? 'Le bon test, le bon niveau, le bon plan : tout part de votre premier cours. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                        : 'The right test, the right level, the right plan: it all starts with your first class. You choose the pace: 2 or 4 hours a week.',
                    'primaryButtonText' => $isFr ? "S'inscrire" : 'Enroll now',
                    'primaryButtonUrl' => '/register',
                    'showSecondaryButton' => true,
                    'secondaryButtonText' => $isFr ? 'Contactez-nous' : 'Talk to us',
                    'secondaryButtonUrl' => $contactUrl,
                    'helperText' => $isFr
                        ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                        : 'Live classes on Google Meet · 1:1 or small group · Student portal included',
                ])
            </div>

            <div class="bg-white pb-16">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl text-center">
                    <p class="text-sm text-slate-500">
                        {{ $isFr ? 'Vous venez à deux ? 50 € de remise par mois, pour vous et pour votre ami.' : 'Coming with a friend? €50 off per month, for you and for them.' }}
                        <a href="{{ $referralUrl }}" class="text-[#0055A4] font-semibold hover:underline ml-1">{{ $isFr ? 'Contactez-nous' : 'Contact us' }} →</a>
                    </p>
                    <div class="my-6 border-t border-slate-100"></div>
                    <div class="flex flex-wrap items-center justify-center gap-y-2 text-xs sm:text-[13px] text-slate-400">
                        <span class="font-bold tracking-[0.18em] uppercase mr-3">SOURCES</span>
                        <a href="https://www.service-public.fr/particuliers/vosdroits/F2213" target="_blank" rel="noopener" class="hover:text-slate-600">Service-public.fr – {{ $isFr ? 'naturalisation' : 'citizenship' }} ↗</a>
                        <span class="mx-2.5 text-slate-300">·</span>
                        <a href="https://www.service-public.fr/particuliers/vosdroits/N110" target="_blank" rel="noopener" class="hover:text-slate-600">Service-public.fr – {{ $isFr ? 'cartes de séjour' : 'residence cards' }} ↗</a>
                        <span class="mx-2.5 text-slate-300">·</span>
                        <a href="https://www.lefrancaisdesaffaires.fr/" target="_blank" rel="noopener" class="hover:text-slate-600">Le français des affaires – TEF IRN ↗</a>
                    </div>
                </div>
            </div>
        </main>

        @include('partials.footer')
    </div>
</body>

</html>
