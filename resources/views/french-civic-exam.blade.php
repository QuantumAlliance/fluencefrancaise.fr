<!DOCTYPE html>
@php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    $isFr = request()->is('fr*');

    $title = $isFr
        ? 'Examen civique 2026 : format, thèmes et préparation'
        : 'French Civic Exam 2026: Format, Themes and How to Prepare';

    $metaDesc = $isFr
        ? 'Examen civique 2026 (naturalisation, carte de résident) : 40 QCM, 45 min, 32 bonnes réponses exigées. Les 5 thèmes et le piège de la lecture.'
        : 'The French civic exam in 2026: 40 multiple-choice questions in French, 45 minutes, 32 correct to pass. The 5 themes, the reading trap and how to prepare.';

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/examen-civique/' : 'https://fluencefrancaise.fr/en/french-civic-exam/';
    $frUrl = 'https://fluencefrancaise.fr/fr/examen-civique/';
    $enUrl = 'https://fluencefrancaise.fr/en/french-civic-exam/';

    $homeUrl = $isFr ? '/fr' : '/en';
    $permitUrl = $isFr ? '/fr/titre-de-sejour-niveau-francais/' : '/en/french-residence-permit-language/';
    $langUrl = $isFr ? '/fr/test-francais-naturalisation/' : '/en/french-citizenship-language-test/';
    $guideUrl = $isFr ? '/fr/naturalisation-francaise/' : '/en/french-citizenship/';
    $tcfUrl = $isFr ? '/fr/tcf-irn/' : '/en/tcf-irn/';
    $tefUrl = $isFr ? '/fr/tef-irn/' : '/en/tef-irn/';
    $delfUrl = $isFr ? '/fr/delf/' : '/en/delf/';
    $contactUrl = $isFr ? '/fr/contact/' : '/en/contact/';
    $referralUrl = $isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral';
    $spCards = 'https://www.service-public.fr/particuliers/vosdroits/N110';
    $spCitizenship = 'https://www.service-public.fr/particuliers/vosdroits/F2213';

    $faqs = $isFr ? [
        ['q' => 'Combien de questions compte l\'examen civique ?', 'a' => '40 questions à choix multiple : 28 questions de connaissances et 12 mises en situation. Vous avez 45 minutes au maximum.'],
        ['q' => 'Quelle note faut-il pour réussir ?', 'a' => '32 bonnes réponses sur 40, soit 80 %. Vous pouvez donc vous tromper 8 fois au maximum.'],
        ['q' => 'L\'examen civique se passe-t-il en français ?', 'a' => 'Oui, uniquement en français, sans traduction. Un bon niveau de lecture est donc indispensable, même si vous connaissez les réponses.'],
        ['q' => 'Où passer l\'examen civique ?', 'a' => 'Dans un centre agréé par France Éducation international ou par Le français des affaires, en France ou à l\'étranger. Chaque centre fixe ses tarifs et ses dates.'],
        ['q' => 'L\'attestation de réussite expire-t-elle ?', 'a' => 'Non. Une fois l\'examen réussi, l\'attestation reste valable. C\'est une différence importante avec le TCF et le TEF, valables 2 ans.'],
        ['q' => 'Peut-on repasser l\'examen civique ?', 'a' => 'Oui. Chaque nouvelle tentative est payante. Mieux vaut s\'inscrire quand vous obtenez 32 sur 40 en entraînement, de façon régulière.'],
        ['q' => 'La préparation à l\'examen civique coûte-t-elle en plus ?', 'a' => 'Non. Elle est incluse dans nos cours, quelle que soit votre formule. Vous la travaillez avec votre tuteur, en même temps que votre test de langue.'],
        ['q' => 'Fluence Française fait-elle passer l\'examen civique ?', 'a' => 'Non. Nous vous y préparons ; l\'examen se passe dans un centre agréé.'],
    ] : [
        ['q' => 'How many questions are on the civic exam?', 'a' => '40 multiple-choice questions: 28 on knowledge and 12 real-life situations. You have 45 minutes at most.'],
        ['q' => 'What score do you need to pass?', 'a' => '32 correct out of 40, or 80%. That means you can get 8 wrong at most.'],
        ['q' => 'Is the civic exam in English?', 'a' => 'No. It\'s in French only, with no translation. A solid reading level matters even if you know the answers.'],
        ['q' => 'Where do you take the civic exam?', 'a' => 'At a centre approved by France Éducation international or Le français des affaires, in France or abroad. Each centre sets its own prices and dates.'],
        ['q' => 'Does the pass certificate expire?', 'a' => 'No. Once you pass, it stays valid. That\'s a key difference from the TCF and TEF, which last 2 years.'],
        ['q' => 'Can you retake the civic exam?', 'a' => 'Yes, and each attempt costs a new fee. Book it once you\'re scoring 32 out of 40 in practice, consistently.'],
        ['q' => 'Does civic-exam prep cost extra?', 'a' => 'No. It\'s included in our classes, whichever format you choose. You work on it with your tutor, alongside your language test.'],
        ['q' => 'Does Fluence Française run the civic exam?', 'a' => 'No. We prepare you for it; you take it at an approved centre.'],
    ];

    $glance = $isFr ? [
        ['Pour qui', 'La plupart des premières cartes pluriannuelles et premières cartes de résident, naturalisation par décret'],
        ['Format', '40 questions à choix multiple : 28 de connaissances, 12 mises en situation'],
        ['Durée', '45 minutes maximum'],
        ['Seuil de réussite', '32 bonnes réponses sur 40 (80 %)'],
        ['Langue', 'Français uniquement, sans traduction'],
        ['Où', 'Dans un centre agréé, sur ordinateur ou tablette'],
        ['Validité', 'L\'attestation de réussite n\'expire pas'],
    ] : [
        ['Who takes it', 'Most first multi-year permits and first resident cards, citizenship by decree'],
        ['Format', '40 multiple-choice questions: 28 on knowledge, 12 real-life situations'],
        ['Time', '45 minutes maximum'],
        ['Pass mark', '32 out of 40 (80%)'],
        ['Language', 'French only, no translation'],
        ['Where', 'At an approved centre, on a computer or tablet'],
        ['Validity', 'Your pass certificate doesn\'t expire'],
    ];

    $who = $isFr ? [
        'une première demande de carte de séjour pluriannuelle concernée par les règles de 2026',
        'une première demande de carte de résident concernée par les règles de 2026',
        'une demande de naturalisation par décret',
    ] : [
        'a first carte de séjour pluriannuelle (multi-year residence permit) covered by the 2026 rules',
        'a first carte de résident (10-year resident card) covered by the 2026 rules',
        'French citizenship by decree (naturalisation)',
    ];

    $themes = $isFr ? [
        ['01', 'Principes et valeurs de la République', 'Devise, symboles, laïcité.'],
        ['02', 'Droits et devoirs', 'Égalité femmes-hommes, non-discrimination, impôts, école.'],
        ['03', 'Histoire, géographie et culture', 'Grandes dates, territoires, patrimoine.'],
        ['04', 'Système institutionnel et politique', 'Président, Parlement, élections, collectivités.'],
        ['05', 'Vivre dans la société française', 'Santé, emploi, logement, numéros d\'urgence.'],
    ] : [
        ['01', 'Principles and values of the Republic', 'Motto, symbols, laïcité (secularism).'],
        ['02', 'Rights and duties', 'Gender equality, non-discrimination, taxes, school.'],
        ['03', 'History, geography and culture', 'Key dates, territories, heritage.'],
        ['04', 'Institutions and politics', 'President, Parliament, elections, local government.'],
        ['05', 'Living in French society', 'Health, work, housing, emergency numbers.'],
    ];

    $plan = $isFr ? [
        ['01', 'Les 5 thèmes, expliqués', 'Votre tuteur vous fait comprendre le pourquoi de chaque règle. Ce que l\'on comprend, on le retient.'],
        ['02', 'Le vocabulaire des questions', 'Suffrage universel, collectivité territoriale, liberté de conscience : les mots qui bloquent la lecture sont travaillés un par un.'],
        ['03', 'Des QCM chronométrés', 'Vous vous entraînez au rythme réel : environ 1 minute par question.'],
        ['04', 'Inclus dans votre préparation', ''],
    ] : [
        ['01', 'The 5 themes, explained', 'Your tutor shows you the why behind each rule. What you understand, you remember.'],
        ['02', 'The vocabulary of the questions', 'Suffrage universel, collectivité territoriale, liberté de conscience: we work through the words that block your reading, one by one.'],
        ['03', 'Timed quizzes', 'You practise at the real pace: about 1 minute a question.'],
        ['04', 'Included in your prep', ''],
    ];

    $facts = $isFr
        ? ['Les 5 thèmes travaillés', 'QCM chronométrés', 'Inclus dans votre préparation']
        : ['All 5 themes covered', 'Timed practice quizzes', 'Included in your prep'];

    $jumps = $isFr ? [
        ['#at-a-glance', 'En bref'],
        ['#who', 'Pour qui'],
        ['#themes', 'Thèmes'],
        ['#prepare', 'Préparation'],
        ['#faq', 'FAQ'],
    ] : [
        ['#at-a-glance', 'At a glance'],
        ['#who', 'Who takes it'],
        ['#themes', 'Themes'],
        ['#prepare', 'Preparation'],
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
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }} | {{ $siteName }}">
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
                    { "@type": "ListItem", "position": 3, "name": "{{ $isFr ? 'Examen civique' : 'Civic exam' }}", "item": "{{ $canonicalUrl }}" }
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
            <section class="relative overflow-hidden bg-white pt-28 sm:pt-36 md:pt-40 pb-12 border-b border-slate-100">
                <div class="hero-aurora"></div>
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="max-w-3xl mx-auto text-center">
                        <nav aria-label="Breadcrumb"
                            class="mb-5 flex justify-center text-xs text-slate-400 font-medium items-center gap-2">
                            <a href="{{ $homeUrl }}"
                                class="hover:text-[#0055A4] transition-colors">{{ $isFr ? 'Accueil' : 'Home' }}</a>
                            <span class="text-slate-300">›</span>
                            <span>France</span>
                            <span class="text-slate-300">›</span>
                            <span
                                class="text-slate-700 font-semibold">{{ $isFr ? 'Examen civique' : 'Civic exam' }}</span>
                        </nav>

                        <div class="inline-flex items-center justify-center gap-1.5 mb-5">
                            <span class="inline-block w-3.5 h-[3px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[3px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                {{ $isFr ? 'L\'EXAMEN CIVIQUE 2026' : 'THE 2026 CIVIC EXAM' }}
                            </span>
                        </div>

                        <h1
                            class="text-3xl sm:text-5xl lg:text-[48px] font-bold text-[#0c1e38] tracking-tight leading-[1.15]">
                            {{ $isFr ? 'Examen civique :' : 'French Civic Exam:' }}
                            <span class="block w-fit max-w-full mx-auto text-[#0055A4] mt-1">
                                {{ $isFr ? 'obtenez vos 32 bonnes réponses sur 40' : 'Get Your 32 Out of 40' }}
                                <span class="block h-[3px] mt-2 rounded-full"
                                    style="background: linear-gradient(90deg, #0055A4 0%, #0055A4 33.3%, #eaebf0 33.3%, #eaebf0 66.6%, #EF4135 66.6%, #EF4135 100%);"></span>
                            </span>
                        </h1>

                        <p class="mt-6 text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
                            {{ $isFr
    ? '40 questions en français, 45 minutes, 80 % de bonnes réponses : l\'examen civique teste vos connaissances, et aussi votre lecture.'
    : '40 questions in French, 45 minutes, 80% to pass: the civic exam tests what you know about France, and how fast you read French.' }}
                        </p>

                        <div class="mt-8">
                            <a href="/register"
                                class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055A4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all">
                                {{ $isFr ? "S'inscrire" : 'Enroll now' }}
                                <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                            </a>
                            <p class="mt-3 text-xs sm:text-[13px] text-slate-500 font-medium">
                                {{ $isFr
    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
    : 'Live classes on Google Meet · 1:1 or small group · Student portal included' }}
                            </p>
                        </div>

                        <div
                            class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-xs sm:text-sm font-medium text-slate-600">
                            @foreach($facts as $fact)
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas fa-check text-[#0055A4] text-[11px]"></i>
                                    {{ $fact }}
                                </span>
                            @endforeach
                        </div>

                        <p class="mt-5 text-xs font-semibold text-slate-400">
                            <span class="text-[#0055A4] text-[8px] align-middle mr-1">●</span>
                            {{ $isFr ? 'Exigences 2026' : '2026 requirements' }}
                            <span class="text-slate-300 mx-1.5">/</span>
                            {{ $isFr ? 'Mis à jour le 12 novembre 2026' : 'Updated 12 November 2026' }}
                        </p>
                    </div>

                    <div class="mt-8 flex flex-wrap items-center justify-center gap-2.5">
                        @foreach($jumps as $jump)
                            <a href="{{ $jump[0] }}"
                                class="inline-flex items-center px-4 py-2 rounded-full border border-slate-200 bg-white text-xs sm:text-sm font-medium text-slate-600 hover:text-[#0055A4] hover:border-[#0055A4]/30 transition-colors">
                                {{ $jump[1] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="at-a-glance" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <div class="lg:col-span-7">
                            <div class="inline-flex items-center gap-1.5 mb-3">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span
                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">{{ $isFr ? "CONNAÎTRE L'EXAMEN" : 'KNOW THE EXAM' }}</span>
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                {{ $isFr ? 'L\'examen civique en bref' : 'The French civic exam at a glance' }}
                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                                {{ $isFr
    ? 'Depuis le 1er janvier 2026, l\'examen civique est obligatoire pour la naturalisation par décret et pour la plupart des premières cartes de séjour pluriannuelles et premières cartes de résident. C\'est un QCM de 40 questions en français, à faire en 45 minutes. Il faut 32 bonnes réponses, soit 80 %.'
    : "Since 1 January 2026, the examen civique is compulsory for citizenship by decree and for most first multi-year residence permits and first 10-year resident cards. It's 40 multiple-choice questions in French, in 45 minutes. You need 32 correct answers, or 80%." }}
                            </p>
                        </div>
                        <div class="lg:col-span-5">
                            <div class="rounded-3xl bg-[#002654] text-white overflow-hidden">
                                <div class="h-[3px] w-full flex">
                                    <span class="w-1/3 bg-[#0055A4]"></span>
                                    <span class="w-1/3 bg-white"></span>
                                    <span class="w-1/3 bg-[#EF4135]"></span>
                                </div>
                                <div class="grid grid-cols-2 divide-x divide-y divide-white/10">
                                    <div class="p-6">
                                        <div class="text-3xl font-extrabold">40</div>
                                        <div class="text-xs text-blue-100/80 mt-1">
                                            {{ $isFr ? 'Questions à choix multiple' : 'Multiple-choice questions' }}
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <div class="text-3xl font-extrabold">45 min</div>
                                        <div class="text-xs text-blue-100/80 mt-1">
                                            {{ $isFr ? 'Durée maximum' : 'Maximum time' }}
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <div class="text-3xl font-extrabold">32/40</div>
                                        <div class="text-xs text-blue-100/80 mt-1">
                                            {{ $isFr ? 'Pour réussir, soit 80 %' : 'To pass, or 80%' }}
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <div class="text-3xl font-extrabold">{{ $isFr ? 'À vie' : 'For life' }}</div>
                                        <div class="text-xs text-blue-100/80 mt-1">
                                            {{ $isFr ? 'L\'attestation n\'expire pas' : 'The certificate doesn\'t expire' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 rounded-2xl border border-slate-200/80 overflow-hidden">
                        @foreach($glance as $index => $row)
                            <div
                                class="grid grid-cols-1 sm:grid-cols-12 gap-1 sm:gap-4 px-5 sm:px-7 py-4 text-sm {{ $index % 2 === 0 ? 'bg-[#f7f9fc]' : 'bg-white' }} {{ $index < count($glance) - 1 ? 'border-b border-slate-100' : '' }}">
                                <div class="sm:col-span-3 font-semibold text-[#10233e]">{{ $row[0] }}</div>
                                <div class="sm:col-span-9 text-slate-600">{{ $row[1] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="who" class="scroll-mt-28 py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                        <div class="lg:col-span-7">
                            <div class="inline-flex items-center gap-1.5 mb-3">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span
                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">{{ $isFr ? 'POUR QUI' : "WHO IT'S FOR" }}</span>
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                                {{ $isFr ? 'Qui doit passer l\'examen civique ?' : 'Who has to take the civic exam?' }}
                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500">
                                {{ $isFr ? 'Vous êtes concerné si vous déposez :' : "You need it if you're applying for:" }}
                            </p>
                            <ul class="mt-4 space-y-3">
                                @foreach($who as $item)
                                    <li class="flex items-start gap-3 text-sm sm:text-base text-slate-600">
                                        <i class="fas fa-check text-[#0055A4] text-xs mt-1.5"></i>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="lg:col-span-5">
                            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7">
                                <h3 class="font-bold text-[#10233e]">
                                    {{ $isFr ? 'Dispenses et cas particuliers' : 'Exemptions and special cases' }}
                                </h3>
                                <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                                    {{ $isFr
    ? 'Les personnes de plus de 65 ans ne le passent pas. Pour une première carte pluriannuelle, les catégories exclues comprennent les titres hors contrat d\'intégration républicaine, la protection subsidiaire et le statut d\'apatride. Pour une carte de résident, les dispenses dépendent de la catégorie de carte. Un problème de santé ou un handicap peut ouvrir droit à des aménagements ou à une dispense, sur certificat médical.'
    : "People over 65 don't take it. For a first multi-year permit, excluded categories include permits outside the republican integration contract, subsidiary protection and stateless status. For resident cards, exemptions depend on the card category. A health condition or disability can lead to adapted conditions or an exemption, with a medical certificate." }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="mt-8 text-sm sm:text-base text-slate-500 leading-relaxed max-w-4xl">
                        @if($isFr)
                            L'examen ne remplace pas le test de langue : il s'y ajoute. Selon votre démarche, il vous faut
                            aussi le niveau A2 ou B1 pour un <a href="{{ $permitUrl }}"
                                class="font-semibold text-[#0055A4] hover:underline">titre de séjour</a>, ou le B2 pour la
                            <a href="{{ $langUrl }}"
                                class="font-semibold text-[#0055A4] hover:underline">naturalisation</a>. La nationalité par
                            déclaration, après un mariage par exemple, suit d'autres règles.
                        @else
                            It comes on top of the language test, not instead of it. Depending on your application, you also
                            need A2 or B1 for a <a href="{{ $permitUrl }}"
                                class="font-semibold text-[#0055A4] hover:underline">residence card</a> or B2 for <a
                                href="{{ $langUrl }}" class="font-semibold text-[#0055A4] hover:underline">citizenship</a>.
                            Citizenship by declaration, after marriage for example, follows different rules.
                        @endif
                    </p>
                </div>
            </section>

            <section id="themes" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span
                                class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">{{ $isFr ? 'CE QUE COUVRENT LES QUESTIONS' : 'WHAT THE QUESTIONS COVER' }}</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            {{ $isFr ? 'Les 5 thèmes de l\'examen civique' : 'The 5 themes of the civic exam' }}
                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                            {{ $isFr
    ? 'Les questions couvrent 5 thèmes. 28 questions testent vos connaissances. Les 12 autres décrivent une situation de la vie courante et vous demandent la bonne réaction. Le ministère a publié les questions de connaissances ; les mises en situation, elles, ne sont pas publiques.'
    : "The questions cover 5 themes. 28 test what you know. The other 12 describe an everyday situation and ask for the right response. The ministry has published the knowledge questions; the situation questions aren't public." }}
                        </p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        @foreach($themes as $theme)
                            <article class="rounded-2xl border border-slate-200/80 bg-white p-5">
                                <span class="text-sm font-bold text-[#0055A4]">{{ $theme[0] }}</span>
                                <h3 class="mt-3 font-bold text-[#10233e] leading-snug">{{ $theme[1] }}</h3>
                                <p class="mt-2 text-sm text-slate-500 leading-relaxed">{{ $theme[2] }}</p>
                            </article>
                        @endforeach
                    </div>
                    <p
                        class="mt-6 rounded-2xl bg-[#f4f7fb] px-5 py-4 text-sm text-slate-500 leading-relaxed text-center">
                        {{ $isFr
    ? 'Apprendre une liste par cœur ne suffit pas pour les 12 mises en situation. Il faut comprendre les principes pour reconnaître la bonne réponse dans un cas que vous n\'avez jamais vu.'
    : "Memorising a list won't get you through the 12 situations. You need to understand the principles to spot the right answer in a case you've never seen." }}
                    </p>
                </div>
            </section>

            <section class="py-6 sm:py-10 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="rounded-3xl bg-[#002654] text-white px-6 sm:px-10 py-10 sm:py-12">
                        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                            <div class="lg:col-span-7">
                                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight">
                                    {{ $isFr ? 'Pourquoi l\'examen civique est aussi un test de français' : 'Why the civic exam is also a French test' }}
                                </h2>
                                <div class="mt-4 space-y-3 text-sm sm:text-base text-blue-100/90 leading-relaxed">
                                    <p>
                                        {{ $isFr
    ? '45 minutes pour 40 questions, cela fait 67,5 secondes par question. Chaque question est rédigée en français, sans traduction possible. Les mises en situation, surtout, sont de courts textes à lire et à comprendre vite.'
    : '45 minutes for 40 questions is 67.5 seconds a question. Every question is in French, with no translation. The situation questions in particular are short texts you have to read and understand fast.' }}
                                    </p>
                                    <p>
                                        {{ $isFr
    ? 'Connaître la réponse ne sert à rien si la question vous échappe. Un mot comme suffrage, collectivité ou laïcité mal compris, et le point est perdu.'
    : 'Knowing the answer is no use if the question loses you. Misread 1 word like suffrage, collectivité or laïcité, and the point is gone.' }}
                                    </p>
                                    <p>
                                        {{ $isFr
    ? 'Vous avez droit à 8 erreurs. Pas une de plus. C\'est pourquoi nous préparons les connaissances et la lecture ensemble.'
    : "You can afford 8 mistakes. Not 9. That's why we train the knowledge and the reading together." }}
                                    </p>
                                </div>
                            </div>
                            <div class="lg:col-span-5">
                                <div class="rounded-3xl bg-white/10 p-6 sm:p-7">
                                    <p class="text-5xl font-extrabold tracking-tight">32<span
                                            class="text-2xl text-blue-200/80">/40</span></p>
                                    <p class="mt-1 text-sm text-blue-100/80">
                                        {{ $isFr ? '8 erreurs au maximum' : '8 mistakes at most' }}
                                    </p>
                                    <div class="mt-5 grid grid-cols-10 gap-1.5" aria-hidden="true">
                                        @for($i = 1; $i <= 40; $i++)
                                            <span
                                                class="aspect-square rounded-sm {{ $i <= 32 ? 'bg-[#008000]/60' : 'bg-white/20' }}"></span>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="prepare" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span
                                class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">{{ $isFr ? 'EN DIRECT. INCLUS DANS VOTRE PRÉPARATION.' : 'LIVE TEACHING. INCLUDED IN YOUR PREP.' }}</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            {{ $isFr ? 'Notre préparation à l\'examen civique' : 'How we prepare you for the civic exam' }}
                        </h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach($plan as $index => $step)
                            <div>
                                <div class="flex items-center mb-4">
                                    <span
                                        class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055A4] text-sm font-semibold flex items-center justify-center border border-blue-100 shrink-0">{{ $step[0] }}</span>
                                    @if($index < 3)
                                        <div class="hidden lg:block flex-1 h-px bg-[#e1eaf2] ml-4"></div>
                                    @endif
                                </div>
                                <h3 class="font-bold text-[#10233e] text-base mb-2">{{ $step[1] }}</h3>
                                <p class="text-[13px] text-slate-500 leading-relaxed">
                                    @if($index === 3)
                                        @if($isFr)
                                            L'examen civique fait partie de vos cours, sans supplément, en même temps que votre
                                            préparation au <a href="{{ $tcfUrl }}"
                                                class="font-semibold text-[#0055A4] hover:underline">TCF IRN</a>, au <a
                                                href="{{ $tefUrl }}" class="font-semibold text-[#0055A4] hover:underline">TEF
                                                IRN</a> ou au <a href="{{ $delfUrl }}"
                                                class="font-semibold text-[#0055A4] hover:underline">DELF</a>.
                                        @else
                                            The civic exam is part of your classes at no extra cost, alongside your <a
                                                href="{{ $tcfUrl }}" class="font-semibold text-[#0055A4] hover:underline">TCF
                                                IRN</a>, <a href="{{ $tefUrl }}"
                                                class="font-semibold text-[#0055A4] hover:underline">TEF IRN</a> or <a
                                                href="{{ $delfUrl }}" class="font-semibold text-[#0055A4] hover:underline">DELF</a>
                                            prep.
                                        @endif
                                    @else
                                        {{ $step[2] }}
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-12 text-center">
                        <a href="/register"
                            class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055A4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all">
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
                'title' => $isFr ? 'Questions fréquentes<br>sur l\'examen civique' : 'French civic exam<br>FAQ',
                'description' => $isFr ? 'Des réponses claires avant de passer à l\'étape suivante.' : 'Clear answers before you take your next step.',
                'contactText' => $isFr ? 'Une autre question ?' : 'Have another question?',
                'contactUrl' => $contactUrl,
                'faqs' => $faqs,
                'sectionClass' => 'py-16 sm:py-20 bg-[#f4f7fb]',
            ])

            <div id="enroll" class="scroll-mt-28">
                @include('partials.cta-banner', [
                    'sectionClass' => 'pt-4 pb-6 bg-white',
                    'eyebrow' => $isFr ? 'NOUS VOUS ACCOMPAGNONS' : "LET'S GET YOU THERE",
                    'title' => $isFr
                        ? "Votre niveau. Le niveau exigé.<br class='hidden sm:inline'> Nous comblons l'écart."
                        : "Your level. The required level.<br class='hidden sm:inline'> We close the gap.",
                    'description' => $isFr
                        ? 'L\'examen civique et le test de langue se préparent mieux ensemble. Même tuteur, mêmes cours, 2 ou 4 heures par semaine, et l\'examen civique est inclus sans supplément.'
                        : 'The civic exam and the language test are best prepared together. Same tutor, same classes, 2 or 4 hours a week, with civic-exam prep included at no extra cost.',
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
                        <a href="{{ $referralUrl }}"
                            class="text-[#0055A4] font-semibold hover:underline ml-1">{{ $isFr ? 'Contactez-nous' : 'Contact us' }}
                            →</a>
                    </p>
                    <p class="mt-2 text-sm text-slate-500">
                        {{ $isFr ? 'Vous préparez la nationalité ?' : 'Applying for citizenship?' }}
                        <a href="{{ $guideUrl }}"
                            class="text-[#0055A4] font-semibold hover:underline ml-1">{{ $isFr ? 'Lisez notre guide de la naturalisation française' : 'Read our French citizenship guide' }}
                            →</a>
                    </p>
                    <div class="my-6 border-t border-slate-100"></div>
                    <div
                        class="flex flex-wrap items-center justify-center gap-y-2 text-xs sm:text-[13px] text-slate-400">
                        <span class="font-bold tracking-[0.18em] uppercase mr-3">SOURCES</span>
                        <a href="{{ $spCards }}" target="_blank" rel="noopener"
                            class="hover:text-slate-600">Service-public.fr –
                            {{ $isFr ? 'cartes de séjour' : 'residence cards' }} ↗</a>
                        <span class="mx-2.5 text-slate-300">·</span>
                        <a href="{{ $spCitizenship }}" target="_blank" rel="noopener"
                            class="hover:text-slate-600">Service-public.fr –
                            {{ $isFr ? 'naturalisation' : 'citizenship' }} ↗</a>
                    </div>
                </div>
            </div>
        </main>

        @include('partials.footer')
    </div>
</body>

</html>