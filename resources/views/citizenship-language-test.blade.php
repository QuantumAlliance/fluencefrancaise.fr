<!DOCTYPE html>
@php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';

    // Determine language based on current URL prefix
    $isFr = request()->is('fr*');

    // Page metadata
    $title = $isFr
        ? 'Test de français naturalisation 2026 : le niveau B2 exigé'
        : 'French Citizenship Language Test 2026: B2 Now Required';

    $metaDesc = $isFr
        ? 'Depuis 2026, la naturalisation exige le niveau B2 à l\'oral et à l\'écrit. Les tests acceptés, leur validité et comment atteindre le B2 en ligne.'
        : 'Since January 2026, French citizenship requires B2 French, spoken and written. See which tests count, how long results last and how to reach B2 online.';

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/test-francais-naturalisation/' : 'https://fluencefrancaise.fr/en/french-citizenship-language-test/';
    $frUrl = 'https://fluencefrancaise.fr/fr/test-francais-naturalisation/';
    $enUrl = 'https://fluencefrancaise.fr/en/french-citizenship-language-test/';
    $tcfUrl = $isFr ? '/fr/tcf-irn/' : '/en/tcf-irn/';
    $tefUrl = $isFr ? '/fr/tef-irn/' : '/en/tef-irn/';
    $delfB2Url = $isFr ? '/fr/delf-b2/' : '/en/delf-b2/';
    $compareUrl = $isFr ? '/fr/tcf-tef-ou-delf/' : '/en/tcf-vs-tef-vs-delf/';
    $civicUrl = $isFr ? '/fr/examen-civique/' : '/en/french-civic-exam/';
    $guideUrl = $isFr ? '/fr/naturalisation-francaise/' : '/en/french-citizenship/';
    $permitUrl = $isFr ? '/fr/titre-de-sejour-niveau-francais/' : '/en/french-residence-permit-language/';
    $contactUrl = $isFr ? '/fr/contact/' : '/en/contact/';
    $referralUrl = $isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral';
    $spCitizenship = 'https://www.service-public.fr/particuliers/vosdroits/F2213';
    $spCards = 'https://www.service-public.fr/particuliers/vosdroits/N110';
    $link = 'class="font-semibold text-[#0055A4] hover:underline"';

    // FAQ Data
    $faqs = $isFr ? [
        ['q' => 'Le niveau B1 suffit-il encore pour la naturalisation ?', 'a' => 'Seulement pour les dossiers déposés avant le 1er janvier 2026. Pour toute nouvelle demande, c\'est le B2, à l\'oral et à l\'écrit.'],
        ['q' => 'Existe-t-il un test gratuit avec certificat valable pour la naturalisation ?', 'a' => 'Non. Les tests en ligne gratuits servent seulement à vous situer. Seuls le TCF, le TEF, le DELF, le DALF ou certains diplômes français sont acceptés dans un dossier.'],
        ['q' => 'Combien coûte le test de français pour la naturalisation ?', 'a' => 'Chaque centre agréé fixe son tarif. Pour le TCF IRN ou le TEF IRN, comptez généralement entre 130 et 210 €. Une nouvelle tentative est facturée à nouveau.'],
        ['q' => 'Peut-on passer le test de français en ligne ?', 'a' => 'Non. Le TCF, le TEF et le DELF se passent dans un centre agréé. La préparation, elle, peut se faire entièrement en ligne.'],
        ['q' => 'Mon diplôme étranger en français me dispense-t-il du test ?', 'a' => 'Non. Un diplôme délivré par un autre pays ne compte pas, même si vos études étaient en français. En dehors du TCF et du TEF, seuls les diplômes français ou un diplôme attestant votre niveau de français au B2 ou plus sont acceptés.'],
        ['q' => 'Qui est dispensé du test de français ?', 'a' => 'Les personnes dont le handicap ou l\'état de santé rend l\'évaluation impossible, sur certificat médical. Et, pour la naturalisation, les réfugiés ou apatrides de plus de 70 ans résidant en France depuis au moins 15 ans avec un titre de séjour. Vérifiez votre situation sur <a href="' . $spCitizenship . '" target="_blank" rel="noopener" class="font-semibold text-[#0055A4] hover:underline">service-public.fr</a>.'],
        ['q' => 'Ces règles valent-elles pour la nationalité par mariage ?', 'a' => 'En partie. Depuis le 1er janvier 2026, la nationalité par mariage exige aussi le B2 à l\'oral et à l\'écrit. Mais elle passe par une déclaration : pas d\'examen civique, un entretien d\'assimilation à la place. Consultez notre <a href="' . $guideUrl . '" class="font-semibold text-[#0055A4] hover:underline">guide de la naturalisation française</a>.'],
    ] : [
        ['q' => 'Is B1 still enough for French citizenship?', 'a' => 'Only for applications filed before 1 January 2026. Any new application needs B2, spoken and written.'],
        ['q' => 'Is there a free French test with a certificate for citizenship?', 'a' => 'No. Free online tests only tell you where you stand. Only the TCF, TEF, DELF, DALF or certain French qualifications are accepted in an application.'],
        ['q' => 'How much does the French citizenship language test cost?', 'a' => 'Each approved centre sets its own price. For the TCF IRN or TEF IRN, expect roughly €130–210. A retake is charged again.'],
        ['q' => 'Can I take the test online?', 'a' => 'No. The TCF, TEF and DELF are taken at an approved centre. The preparation can be fully online.'],
        ['q' => 'Does my foreign degree taught in French exempt me?', 'a' => 'No. A degree issued by another country doesn\'t count, even if it was taught in French. Besides the TCF and TEF, only French qualifications or a diploma certifying your French at B2 or above are accepted.'],
        ['q' => 'Who is exempt from the language test?', 'a' => 'People whose disability or health makes testing impossible, with a medical certificate. Also, for naturalisation, refugees or stateless people over 70 who have lived in France for at least 15 years with a residence permit. Check your case on <a href="' . $spCitizenship . '" target="_blank" rel="noopener" class="font-semibold text-[#0055A4] hover:underline">service-public.fr</a>.'],
        ['q' => 'Do these rules apply to citizenship through marriage?', 'a' => 'Partly. Since 1 January 2026, citizenship through marriage also requires B2, spoken and written. But it goes through a declaration: no civic exam, and an assimilation interview instead. See our <a href="' . $guideUrl . '" class="font-semibold text-[#0055A4] hover:underline">French citizenship guide</a>.'],
    ];
@endphp
<html lang="{{ $isFr ? 'fr' : 'en' }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.favicon')
    <title>{{ $isFr ? $title : $title . ' | ' . $siteName }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="fr" href="{{ $frUrl }}">
    <link rel="alternate" hreflang="en" href="{{ $enUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $enUrl }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $isFr ? $title : $title . ' | ' . $siteName }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ $siteName }}">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "WebPage",
                "name": "{{ $title }}",
                "description": "{{ $metaDesc }}",
                "url": "{{ $canonicalUrl }}",
                "inLanguage": "{{ $isFr ? 'fr' : 'en' }}",
                "publisher": { "@type": "Organization", "name": "{{ $siteName }}", "url": "https://fluencefrancaise.fr" }
            },
            {
                "@type": "BreadcrumbList",
                "itemListElement": [
                    { "@type": "ListItem", "position": 1, "name": "{{ $isFr ? 'Accueil' : 'Home' }}", "item": "{{ $isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en' }}" },
                    { "@type": "ListItem", "position": 2, "name": "France", "item": "{{ $isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en' }}" },
                    { "@type": "ListItem", "position": 3, "name": "{{ $isFr ? 'Naturalisation' : 'Citizenship' }}", "item": "{{ $canonicalUrl }}" }
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

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (Vite build) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('partials.theme')
</head>

<body class="bg-white text-slate-800 font-sans antialiased selection:bg-[#0055A4] selection:text-white">

    <div class="min-h-screen flex flex-col justify-between">
        <div>
            @include('partials.header')

            <!-- hero banner -->
            <section
                class="relative  pt-32 md:pt-40 pb-16 md:pb-24overflow-hidden bg-gradient-to-b from-[#f0f6ff]/70 via-white to-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl relative z-10 text-center">

                    <nav class="flex items-center justify-center gap-1.5 text-xs text-slate-500 mb-6 font-medium">
                        <a href="{{ $isFr ? '/fr' : '/en' }}"
                            class="hover:text-[#0055A4] transition-colors">{{ $isFr ? 'Accueil' : 'Home' }}</a>
                        <span>›</span>
                        <span>France</span>
                        <span>›</span>
                        <span class="text-slate-800 font-semibold">{{ $isFr ? 'Naturalisation' : 'Citizenship' }}</span>
                    </nav>

                    <span class="eyebrow">
                        <span class="tricolor w-12">
                            <i></i>
                            <i></i>
                            <i></i>
                        </span>
                        {{ $isFr ? 'NATURALISATION FRANÇAISE · NOUVELLE EXIGENCE 2026' : 'FRENCH CITIZENSHIP · NEW 2026 LANGUAGE REQUIREMENT' }}
                    </span>

                    <div class="flex flex-col items-center justify-center mt-5">
                        <h1
                            class="text-3xl sm:text-5xl lg:text-[56px] font-bold text-[#0c1e38] tracking-medium leading-[1.28]">
                            {{ $isFr ? 'Test de français pour la naturalisation :' : 'French Citizenship Language Test:' }}
                            <br>
                            <span class="relative inline-block text-[#0055a4]">
                                {{ $isFr ? 'visez le B2' : 'How to Reach B2' }}
                            </span>
                        </h1>

                        <div class="w-full max-w-xs sm:max-w-md h-1 flex mt-3">
                            <span class="w-1/3 h-full bg-[#0055A4]"></span>
                            <span class="w-1/3 h-full bg-slate-200"></span>
                            <span class="w-1/3 h-full bg-[#EF4135]"></span>
                        </div>
                    </div>

                    <p class="mt-6 text-sm sm:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto font-normal">
                        {{ $isFr
    ? 'Depuis le 1er janvier 2026, la naturalisation exige le B2 à l\'oral et à l\'écrit : voici comment le prouver, et l\'atteindre.'
    : 'Since 1 January 2026, French citizenship requires B2 French, spoken and written: here\'s how to prove it, and how to get there.'
                        }}
                    </p>

                    <div class="mt-8">
                        <a href="/register"
                            class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055a4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5">
                            {{ $isFr ? "S'inscrire" : 'Enroll now' }}
                            <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                        </a>
                        <p class="mt-3 text-xs sm:text-[13px] text-slate-500 font-medium">
                            {{ $isFr
    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
    : 'Live classes on Google Meet · 1:1 or small group · Student portal included'
                            }}
                        </p>
                    </div>

                    <div
                        class="mt-12 pt-8 border-t border-slate-100 flex flex-wrap items-center justify-center gap-6 sm:gap-10 text-xs sm:text-sm text-slate-600">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-certificate text-[#0055a4]"></i>
                            <span>{{ $isFr ? 'Préparation TCF IRN, TEF IRN et DELF B2' : 'TCF IRN, TEF IRN and DELF B2 prep' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-layer-group text-[#0055a4]"></i>
                            <span>{{ $isFr ? 'Examens blancs dans votre espace élève' : 'Mock exams in your student portal' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-award text-[#0055a4]"></i>
                            <span>{{ $isFr ? 'Retour sur chaque devoir' : 'Feedback on every assignment' }}</span>
                        </div>
                    </div>
                    <p class="mt-5 text-xs font-semibold text-slate-400">
                        <span class="text-[#0055A4] text-[8px] align-middle mr-1">●</span>
                        {{ $isFr ? 'Exigences 2026' : '2026 requirements' }}
                        <span class="text-slate-300 mx-1.5">/</span>
                        {{ $isFr ? 'Mis à jour le 12 novembre 2026' : 'Updated 12 November 2026' }}
                    </p>
                </div>
            </section>

            <!-- what level of french do you need? -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                        <div class="lg:col-span-7">
                            <span class="eyebrow">
                                <span class="tricolor w-12">
                                    <i></i>
                                    <i></i>
                                    <i></i>
                                </span>
                                {{ $isFr ? 'RÉFORME 2026' : '2026 LANGUAGE REFORM' }}
                            </span>
                            <h2
                                class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15] mt-3">
                                {{ $isFr ? 'Quel niveau de français pour la naturalisation en 2026 ?' : 'What level of French do you need for French citizenship?' }}
                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                                {{ $isFr
    ? 'Le niveau B2, à l\'oral comme à l\'écrit, pour toute demande déposée depuis le 1er janvier 2026. Vous le prouvez avec un TCF ou un TEF de moins de 2 ans, un DELF B2 ou certains diplômes français. Une simple attestation de cours ne suffit pas.'
    : 'B2, in both speaking and writing, for any application filed since 1 January 2026. You prove it with a TCF or TEF result under 2 years old, a DELF B2 diploma or certain French qualifications. A certificate of attendance from a language school doesn\'t count.'
                                }}
                            </p>
                        </div>

                        <div class="lg:col-span-5">
                            <div class="rounded-3xl bg-[#002654] text-white overflow-hidden relative">
                                <div class="h-[3px] w-full flex">
                                    <span class="w-1/3 h-full bg-[#0055A4]"></span>
                                    <span class="w-1/3 h-full bg-white"></span>
                                    <span class="w-1/3 h-full bg-[#EF4135]"></span>
                                </div>
                                <div class="grid grid-cols-2 divide-x divide-y divide-white/10 p-6 sm:p-8">
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">B2</div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            {{ $isFr ? 'À l\'oral et à l\'écrit' : 'Spoken and written' }}
                                        </div>
                                    </div>
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">2026</div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            {{ $isFr ? 'Depuis le 1er janvier' : 'Since 1 January' }}
                                        </div>
                                    </div>
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">2
                                            {{ $isFr ? 'ans' : 'yrs' }}
                                        </div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            {{ $isFr ? 'TCF et TEF' : 'TCF and TEF' }}
                                        </div>
                                    </div>
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">
                                            {{ $isFr ? 'À vie' : 'Life' }}
                                        </div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            {{ $isFr ? 'DELF et DALF' : 'DELF and DALF' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 rounded-2xl border border-slate-200/80 overflow-hidden">
                        <div
                            class="hidden sm:grid sm:grid-cols-12 gap-4 px-5 sm:px-7 py-3.5 bg-[#002654] text-white text-xs font-semibold tracking-wide">
                            <div class="sm:col-span-5">{{ $isFr ? 'Preuve acceptée' : 'Accepted proof' }}</div>
                            <div class="sm:col-span-2">{{ $isFr ? 'Validité' : 'Valid for' }}</div>
                            <div class="sm:col-span-5">{{ $isFr ? 'À savoir' : 'Good to know' }}</div>
                        </div>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-12 gap-1 sm:gap-4 px-5 sm:px-7 py-4 text-sm bg-[#f7f9fc] border-b border-slate-100">
                            <div class="sm:col-span-5 font-semibold"><a href="{{ $tcfUrl }}" {!! $link !!}>TCF IRN</a>
                            </div>
                            <div class="sm:col-span-2 text-slate-600">{{ $isFr ? '2 ans' : '2 years' }}</div>
                            <div class="sm:col-span-5 text-slate-600">
                                {{ $isFr ? 'Un niveau par compétence, 1 h 35 d\'épreuves' : 'A level for each skill, 1 h 35 of testing' }}
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-12 gap-1 sm:gap-4 px-5 sm:px-7 py-4 text-sm bg-white border-b border-slate-100">
                            <div class="sm:col-span-5 font-semibold"><a href="{{ $tefUrl }}" {!! $link !!}>TEF IRN</a>
                            </div>
                            <div class="sm:col-span-2 text-slate-600">{{ $isFr ? '2 ans' : '2 years' }}</div>
                            <div class="sm:col-span-5 text-slate-600">
                                {{ $isFr ? '4 épreuves obligatoires dans la même session' : '4 compulsory sections in 1 session' }}
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-12 gap-1 sm:gap-4 px-5 sm:px-7 py-4 text-sm bg-[#f7f9fc] border-b border-slate-100">
                            <div class="sm:col-span-5 font-semibold text-[#10233e]"><a href="{{ $delfB2Url }}" {!! $link !!}>DELF B2</a> {{ $isFr ? 'ou DALF' : 'or DALF' }}</div>
                            <div class="sm:col-span-2 text-slate-600">{{ $isFr ? 'À vie' : 'Life' }}</div>
                            <div class="sm:col-span-5 text-slate-600">
                                {{ $isFr ? 'Un diplôme : il n\'expire jamais' : 'A diploma: it never expires' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-1 sm:gap-4 px-5 sm:px-7 py-4 text-sm bg-white">
                            <div class="sm:col-span-5 font-semibold text-[#10233e]">
                                {{ $isFr ? 'Diplôme ou certification français (brevet, diplôme d\'État ou certification professionnelle de niveau 3 et plus)' : 'French qualification (brevet, or a state diploma or professional certification at level 3+)' }}
                            </div>
                            <div class="sm:col-span-2 text-slate-600">{{ $isFr ? 'À vie' : 'Life' }}</div>
                            <div class="sm:col-span-5 text-slate-600">
                                {{ $isFr ? 'Diplômes et certifications français uniquement' : 'French qualifications only' }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- b1 or b2? what changed in 2026 -->
            <section class="py-16 sm:py-24 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                        <div class="lg:col-span-7">
                            <span class="eyebrow">
                                <span class="tricolor w-12">
                                    <i></i>
                                    <i></i>
                                    <i></i>
                                </span>
                                {{ $isFr ? 'DIFFÉRENCE FONDAMENTALE' : 'KEY DIFFERENCE' }}
                            </span>
                            <h2
                                class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15] mt-3">
                                {{ $isFr ? 'B1 ou B2 : ce qui a changé en 2026' : 'B1 or B2? What changed in 2026' }}
                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                                {{ $isFr
    ? 'Si vous avez lu « niveau B1 » quelque part, l\'information date d\'avant 2026. La loi du 26 janvier 2024 a relevé le niveau, et le B2 s\'applique depuis le 1er janvier 2026. Le B1 ne vaut plus que pour les dossiers déposés avant cette date.'
    : 'If you\'ve read “B1” somewhere, that advice predates 2026. The immigration law of 26 January 2024 raised the bar, and B2 has applied since 1 January 2026. B1 now only covers applications filed before that date.'
                                }}
                            </p>
                            <p class="mt-3 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                                {{ $isFr
    ? 'L\'écart entre les 2 niveaux est réel. Au B1, on se débrouille. Au B2, on argumente, on nuance, on suit un débat sans tout faire répéter.'
    : 'The gap between the 2 levels is real. At B1, you get by. At B2, you argue, you nuance, you follow a debate without asking people to repeat themselves.'
                                }}
                            </p>
                            <p class="mt-3 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                                {{ $isFr
    ? 'Préparer le B1 aujourd\'hui, c\'est préparer un niveau qui ne suffit plus pour un nouveau dossier.'
    : 'Preparing for B1 today means preparing for a level that no longer counts on a new application.'
                                }}
                            </p>
                        </div>

                        <div class="lg:col-span-5">
                            <div
                                class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 flex items-center justify-around gap-4">
                                <div class="text-center">
                                    <div class="text-3xl sm:text-4xl font-extrabold text-slate-300">B1</div>
                                    <div class="text-xs text-slate-400 mt-1">{{ $isFr ? 'Avant 2026' : 'Before 2026' }}
                                    </div>
                                </div>
                                <div class="text-slate-300 text-2xl font-bold">→</div>
                                <div class="text-center p-4 rounded-2xl bg-blue-50/70 border border-blue-100">
                                    <div class="text-3xl sm:text-4xl font-extrabold text-[#0055a4]">B2</div>
                                    <div class="text-xs text-[#0055a4] font-bold mt-1">
                                        {{ $isFr ? 'Depuis 2026' : 'Since 2026' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- which test should you take? -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <span class="eyebrow">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            {{ $isFr ? 'COMPARATIF DES EXAMENS' : 'OFFICIAL EXAMS COMPARED' }}
                        </span>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15] mt-3">
                            {{ $isFr ? 'Quel test choisir pour la naturalisation ?' : 'Which test should you take for French citizenship?' }}
                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                            {{ $isFr
    ? 'Les 3 tests sont acceptés. La différence se joue sur la validité et sur la façon de noter. Le TCF IRN et le TEF IRN donnent un niveau par compétence et un niveau global. Le DELF B2 se valide sur une note globale : 50 sur 100, avec au moins 5 sur 25 dans chaque épreuve.'
    : 'All 3 are accepted. The differences are validity and scoring. The TCF IRN and TEF IRN give you a level for each skill and an overall level. The DELF B2 is passed on an overall score: 50 out of 100, with at least 5 out of 25 in each section.'
                            }}
                        </p>
                        <p class="mt-3 text-sm sm:text-base text-slate-500">
                            @if($isFr)
                                Pour aller plus loin : <a href="{{ $compareUrl }}" {!! $link !!}>TCF, TEF ou DELF, le
                                    comparatif complet</a>.
                            @else
                                More detail: <a href="{{ $compareUrl }}" {!! $link !!}>TCF vs TEF vs DELF, the full
                                    comparison</a>.
                            @endif
                        </p>
                    </div>

                    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="bg-[#0055a4] text-white">
                                        <th class="py-4 px-6 font-semibold">{{ $isFr ? 'Critère' : 'Criteria' }}</th>
                                        <th class="py-4 px-6 font-semibold">TCF IRN</th>
                                        <th class="py-4 px-6 font-semibold">TEF IRN</th>
                                        <th class="py-4 px-6 font-semibold">DELF B2</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                    <tr>
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            {{ $isFr ? 'Organisme' : 'Run by' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600">France Éducation international</td>
                                        <td class="py-4 px-6 text-slate-600">
                                            {{ $isFr ? 'Le français des affaires (CCI Paris Île-de-France)' : 'Le français des affaires (Paris Chamber of Commerce)' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600">France Éducation international</td>
                                    </tr>
                                    <tr>
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">Format</td>
                                        <td class="py-4 px-6 text-slate-600">
                                            {{ $isFr ? '4 épreuves, 1 h 35' : '4 sections, 1 h 35' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            {{ $isFr ? '4 épreuves, 1 session' : '4 sections, 1 session' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            {{ $isFr ? '2 h 30 d\'écrit + oral de 20 min' : '2 h 30 written + 20 min speaking' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            {{ $isFr ? 'Résultat' : 'Result' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            {{ $isFr ? 'Un niveau par compétence + niveau global' : 'A level per skill + overall level' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            {{ $isFr ? 'Un niveau par compétence + niveau global' : 'A level per skill + overall level' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            {{ $isFr ? 'Diplôme si 50/100 et 5/25 minimum par épreuve' : 'Diploma at 50/100, minimum 5/25 per section' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            {{ $isFr ? 'Validité' : 'Valid for' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600">{{ $isFr ? '2 ans' : '2 years' }}</td>
                                        <td class="py-4 px-6 text-slate-600">{{ $isFr ? '2 ans' : '2 years' }}</td>
                                        <td class="py-4 px-6 text-slate-600 font-semibold text-[#0055a4]">
                                            {{ $isFr ? 'À vie' : 'Life' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            {{ $isFr ? 'Bon choix si…' : 'Best if…' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            {{ $isFr ? 'Vous voulez un test court' : 'You want a short test' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            {{ $isFr ? 'Les dates ou le centre vous arrangent' : 'The dates or centre suit you' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            {{ $isFr ? 'Votre calendrier est incertain' : 'Your timeline is uncertain' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- the civic exam -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="rounded-3xl bg-[#002654] text-white p-8 sm:p-12 relative overflow-hidden">
                        <div class="absolute inset-x-0 top-0 h-1 flex">
                            <span class="w-1/3 bg-[#0055A4]"></span>
                            <span class="w-1/3 bg-white"></span>
                            <span class="w-1/3 bg-[#EF4135]"></span>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                            <div class="lg:col-span-7">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-blue-200 mb-4">
                                    {{ $isFr ? 'EXAMEN CIVIQUE' : 'THE CIVIC KNOWLEDGE REQUIREMENT' }}
                                </span>
                                <h2
                                    class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white tracking-tight leading-tight">
                                    {{ $isFr ? 'La langue ne suffit plus : l\'examen civique' : 'Language isn\'t the only test: the civic exam' }}
                                </h2>
                                <p class="mt-4 text-sm sm:text-base text-slate-200 leading-relaxed font-normal">
                                    @if($isFr)
                                        Depuis 2026, la naturalisation par décret exige aussi la réussite à un examen
                                        civique : 40 questions, 45 minutes, 32 bonnes réponses minimum. Toutes les questions
                                        sont en français. Votre niveau de lecture compte donc aussi ici. Sa préparation est
                                        incluse dans nos cours, sans supplément. <a href="{{ $civicUrl }}"
                                            class="font-semibold text-white underline underline-offset-2">Tout savoir sur
                                            l'examen civique</a>.
                                    @else
                                        Since 2026, citizenship by decree also requires passing a civic exam: 40 questions,
                                        45 minutes, at least 32 correct. Every question is in French, so your reading level
                                        matters here too. Its prep is included in our classes at no extra cost. <a
                                            href="{{ $civicUrl }}"
                                            class="font-semibold text-white underline underline-offset-2">All about the
                                            French civic exam</a>.
                                    @endif
                                </p>
                            </div>
                            <div class="lg:col-span-5">
                                <div class="rounded-3xl bg-white/10 p-6 grid grid-cols-3 gap-4 text-center">
                                    <div>
                                        <p class="text-3xl font-extrabold">40</p>
                                        <p class="mt-1 text-[11px] text-blue-100/80">
                                            {{ $isFr ? 'Questions' : 'Questions' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-3xl font-extrabold">45</p>
                                        <p class="mt-1 text-[11px] text-blue-100/80">{{ $isFr ? 'Minutes' : 'Minutes' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-3xl font-extrabold">32</p>
                                        <p class="mt-1 text-[11px] text-blue-100/80">
                                            {{ $isFr ? 'Pour réussir' : 'To pass' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- how we get you to b2 -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <span class="eyebrow">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            {{ $isFr ? 'UN ENSEIGNEMENT EN DIRECT. UN PLAN QUI VOUS CORRESPOND.' : 'LIVE TEACHING. A PLAN THAT FITS YOU.' }}
                        </span>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15] mt-3">
                            {{ $isFr ? 'Notre préparation au B2' : 'How we get you to B2' }}
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="flex flex-col items-start">
                            <div class="relative flex items-center w-full mb-4">
                                <span
                                    class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055a4] text-sm font-semibold flex items-center justify-center border border-blue-100 flex-shrink-0">
                                    01
                                </span>
                                <div class="hidden lg:block flex-1 h-[1px] bg-[#e1eaf2] ml-4 -mr-4"></div>
                            </div>
                            <h3 class="font-bold text-[#10233e] text-base sm:text-[17px] mb-2">
                                {{ $isFr ? 'Votre niveau, compétence par compétence' : 'Your level, skill by skill' }}
                            </h3>
                            <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal">
                                {{ $isFr ? 'Dès le premier cours, votre tuteur situe vos 4 compétences face au B2.' : 'In your first class, your tutor places your 4 skills against B2.' }}
                            </p>
                        </div>

                        <div class="flex flex-col items-start">
                            <div class="relative flex items-center w-full mb-4">
                                <span
                                    class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055a4] text-sm font-semibold flex items-center justify-center border border-blue-100 flex-shrink-0">
                                    02
                                </span>
                                <div class="hidden lg:block flex-1 h-[1px] bg-[#e1eaf2] ml-4 -mr-4"></div>
                            </div>
                            <h3 class="font-bold text-[#10233e] text-base sm:text-[17px] mb-2">
                                {{ $isFr ? 'Le bon test pour votre calendrier' : 'The right test for your timeline' }}
                            </h3>
                            <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal">
                                {{ $isFr ? 'TCF IRN, TEF IRN ou DELF B2 : nous vous aidons à choisir, puis nous préparons ce format précis.' : 'TCF IRN, TEF IRN or DELF B2: we help you choose, then train that exact format.' }}
                            </p>
                        </div>

                        <div class="flex flex-col items-start">
                            <div class="relative flex items-center w-full mb-4">
                                <span
                                    class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055a4] text-sm font-semibold flex items-center justify-center border border-blue-100 flex-shrink-0">
                                    03
                                </span>
                                <div class="hidden lg:block flex-1 h-[1px] bg-[#e1eaf2] ml-4 -mr-4"></div>
                            </div>
                            <h3 class="font-bold text-[#10233e] text-base sm:text-[17px] mb-2">
                                {{ $isFr ? 'Des cours en direct, 2 ou 4 h par semaine' : 'Live classes, 2 or 4 hours a week' }}
                            </h3>
                            <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal">
                                {{ $isFr ? 'En particulier ou en mini-groupe de 2 à 5 personnes, sur Google Meet, avec vos notes après chaque séance.' : '1:1 or in a group of 2–5 on Google Meet, with notes after every session.' }}
                            </p>
                        </div>

                        <div class="flex flex-col items-start">
                            <div class="relative flex items-center w-full mb-4">
                                <span
                                    class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055a4] text-sm font-semibold flex items-center justify-center border border-blue-100 flex-shrink-0">
                                    04
                                </span>
                            </div>
                            <h3 class="font-bold text-[#10233e] text-base sm:text-[17px] mb-2">
                                {{ $isFr ? 'Des examens blancs jusqu\'au jour J' : 'Mock exams until test day' }}
                            </h3>
                            <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal">
                                {{ $isFr ? 'Votre espace élève contient des tests au format de l\'examen et des exercices à vitesse native. Chaque devoir reçoit un retour.' : 'Your student portal has exam-format tests and native-speed practice. Every assignment gets feedback.' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-12 text-center">
                        <a href="/register"
                            class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055a4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5">
                            {{ $isFr ? "S'inscrire" : 'Enroll now' }}
                            <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                        </a>
                        <p class="mt-3 text-xs sm:text-[13px] text-slate-500 font-medium">
                            {{ $isFr
    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
    : 'Live classes on Google Meet · 1:1 or small group · Student portal included'
                            }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- faq -->
            @include('partials.faq-section', [
                'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                'title' => $isFr ? 'Questions fréquentes' : 'FAQ',
                'description' => $isFr ? 'Des réponses claires avant de passer à l\'étape suivante.' : 'Clear answers before you take your next step.',
                'contactText' => $isFr ? 'Une autre question ?' : 'Have another question?',
                'contactUrl' => $contactUrl,
                'faqs' => $faqs
            ])

            <!-- final cta & sources -->
            <section class="py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    @include('partials.cta-banner', [
                        'eyebrow' => $isFr ? 'ATTEIGNEZ VOTRE OBJECTIF' : "LET'S GET YOU THERE",
                        'title' => $isFr ? "Votre niveau. Le niveau exigé.<br>Nous comblons l'écart." : 'Your level. The required level.<br>We close the gap.',
                        'description' => $isFr
                            ? 'Votre premier cours situe vos 4 compétences face au B2 et fixe le plan pour y arriver. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                            : 'Your first class places your 4 skills against B2 and sets the plan to get there. You choose the pace: 2 or 4 hours a week.',
                        'primaryButtonText' => $isFr ? "S'inscrire" : 'Enroll now',
                        'primaryButtonUrl' => '/register',
                        'showSecondaryButton' => true,
                        'secondaryButtonText' => $isFr ? 'Contactez-nous' : 'Talk to us',
                        'secondaryButtonUrl' => $contactUrl,
                        'helperText' => $isFr
                            ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                            : 'Live classes on Google Meet · 1:1 or small group · Student portal included'
                    ])

                    <div class="mt-8 text-center text-xs sm:text-sm text-slate-500 font-normal">
                        <span>{{ $isFr ? 'Vous venez à deux ? 50 € de remise par mois, pour vous et pour votre ami.' : 'Coming with a friend? €50 off per month, for you and for them.' }}</span>
                        <a href="{{ $referralUrl }}"
                            class="text-[#0055a4] font-semibold hover:underline inline-flex items-center gap-1 ml-1.5">
                            <span>{{ $isFr ? 'Contactez-nous' : 'Contact us' }}</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                    <p class="mt-3 text-center text-xs sm:text-sm text-slate-500">
                        @if($isFr)
                            Vous visez une carte de séjour plutôt que la nationalité ? <a href="{{ $permitUrl }}" {!! $link !!}>Niveau A2 ou B1 : voir les règles</a>.
                        @else
                            Applying for a residence card rather than citizenship? <a href="{{ $permitUrl }}" {!! $link !!}>See the A2 and B1 rules</a>.
                        @endif
                    </p>

                    <div class="border-t border-slate-200/80 my-8"></div>

                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-slate-400">
                        <span
                            class="font-bold uppercase tracking-wider text-slate-500">{{ $isFr ? 'SOURCES' : 'SOURCES' }}</span>
                        <a href="{{ $spCitizenship }}" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span>Service-public.fr – {{ $isFr ? 'naturalisation' : 'citizenship' }}</span>
                            <span class="text-[10px]">↗</span>
                        </a>
                        <span class="text-slate-300">·</span>
                        <a href="{{ $spCards }}" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span>Service-public.fr – {{ $isFr ? 'cartes de séjour' : 'residence cards' }}</span>
                            <span class="text-[10px]">↗</span>
                        </a>
                    </div>
                </div>
            </section>
        </div>

        @include('partials.footer')
    </div>

</body>

</html>