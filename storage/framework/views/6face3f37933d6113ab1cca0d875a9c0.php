<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    $isFr = request()->is('fr*');

    $title = $isFr
        ? 'Titre de séjour 2026 : niveau de français exigé (A2, B1)'
        : 'French Residence Permit Language Requirement 2026: A2, B1';

    $metaDesc = $isFr
        ? 'Carte pluriannuelle : A2. Carte de résident : B1. Les niveaux de français exigés depuis 2026, les tests acceptés, les dispenses et comment vous préparer.'
        : 'Multi-year permit: A2. 10-year resident card: B1. The French levels required since 2026, which tests count, who is exempt and how to prepare online.';

    $canonicalUrl = $isFr
        ? 'https://fluencefrancaise.fr/fr/titre-de-sejour-niveau-francais/'
        : 'https://fluencefrancaise.fr/en/french-residence-permit-language/';
    $frUrl = 'https://fluencefrancaise.fr/fr/titre-de-sejour-niveau-francais/';
    $enUrl = 'https://fluencefrancaise.fr/en/french-residence-permit-language/';

    $homeUrl = $isFr ? '/fr' : '/en';
    $a2Id = $isFr ? 'carte-pluriannuelle' : 'multi-year-permit';
    $b1Id = $isFr ? 'carte-de-resident' : 'resident-card';
    $langUrl = $isFr ? '/fr/test-francais-naturalisation/' : '/en/french-citizenship-language-test/';
    $civicUrl = $isFr ? '/fr/examen-civique/' : '/en/french-civic-exam/';
    $tcfUrl = $isFr ? '/fr/tcf-irn/' : '/en/tcf-irn/';
    $tefUrl = $isFr ? '/fr/tef-irn/' : '/en/tef-irn/';
    $delfA2Url = $isFr ? '/fr/delf-a2/' : '/en/delf-a2/';
    $delfB1Url = $isFr ? '/fr/delf-b1/' : '/en/delf-b1/';
    $delfUrl = $isFr ? '/fr/delf/' : '/en/delf/';
    $compareUrl = $isFr ? '/fr/tcf-tef-ou-delf/' : '/en/tcf-vs-tef-vs-delf/';
    $contactUrl = $isFr ? '/fr/contact/' : '/en/contact/';
    $referralUrl = $isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral';
    $spCards = 'https://www.service-public.fr/particuliers/vosdroits/N110';

    $link = 'class="font-semibold text-[#0055A4] hover:underline"';

    $faqs = $isFr ? [
        ['q' => 'Quel niveau de français pour la carte de résident en 2026 ?', 'a' => 'Le B1, depuis le 1er janvier 2026. Avant cette date, l\'A2 suffisait.'],
        ['q' => 'Faut-il un niveau de français pour la carte pluriannuelle ?', 'a' => 'Dans la plupart des cas, oui : depuis le 1er janvier 2026, le niveau A2 pour une première demande. Certaines catégories de cartes sont exclues.'],
        ['q' => 'Qui est dispensé ?', 'a' => 'Les personnes de 65 ans et plus. Pour une première carte pluriannuelle, les catégories exclues comprennent les titres hors CIR, la protection subsidiaire et le statut d\'apatride. Les cartes de résident suivent des règles différentes selon leur catégorie. Une dispense est aussi possible en cas de problème de santé ou de handicap qui empêche de passer le test, sur certificat médical.'],
        ['q' => 'Mon diplôme étranger en français est-il accepté ?', 'a' => 'Non. Un diplôme délivré par les autorités d\'un autre pays n\'est pas accepté, même si vos études étaient en français. Seuls les tests, les diplômes DELF et DALF, et certains diplômes français comptent.'],
        ['q' => 'Vaut-il mieux passer un test ou le DELF ?', 'a' => 'Le TCF IRN et le TEF IRN sont plus courts, mais valables 2 ans. Le DELF est plus long, mais valable à vie : utile si vous visez ensuite la carte de résident ou la nationalité.'],
        ['q' => 'Peut-on se préparer entièrement en ligne ?', 'a' => 'Oui. Nos cours se déroulent en direct sur Google Meet. Seul le test se passe dans un centre agréé.'],
    ] : [
        ['q' => 'What French level do you need for the 10-year resident card in 2026?', 'a' => 'B1, since 1 January 2026. Before that, A2 was enough.'],
        ['q' => 'Do you need French for the multi-year residence permit?', 'a' => 'In most cases, yes: since 1 January 2026, A2 for a first application. Some card categories are excluded.'],
        ['q' => 'Who is exempt?', 'a' => 'People aged 65 and over. For a first multi-year permit, excluded categories include permits outside the CIR, subsidiary protection and stateless status. Resident cards have different category-specific rules. An exemption is also possible if a health condition or disability prevents you from taking the test, with a medical certificate.'],
        ['q' => 'Is my foreign degree taught in French accepted?', 'a' => 'No. A diploma issued by another country\'s authorities isn\'t accepted, even if you studied in French. Only the tests, DELF and DALF diplomas, and certain French qualifications count.'],
        ['q' => 'Should I take a test or the DELF?', 'a' => 'The TCF IRN and TEF IRN are shorter but valid for 2 years. The DELF is longer but valid for life, which helps if you\'re aiming for the resident card or citizenship later.'],
        ['q' => 'Can I prepare fully online?', 'a' => 'Yes. Our classes run live on Google Meet. Only the test itself is taken at an approved centre.'],
    ];

    $levels = $isFr ? [
        ['Carte de séjour pluriannuelle (première demande)', 'A2', 'Pas de niveau exigé', null],
        ['Carte de résident (10 ans)', 'B1', 'A2', null],
        ['Naturalisation', 'B2', 'B1', $langUrl],
    ] : [
        ['Multi-year residence permit (first application)', 'A2', 'No level required', null],
        ['10-year resident card', 'B1', 'A2', null],
        ['Citizenship', 'B2', 'B1', $langUrl],
    ];

    $situations = $isFr ? [
        ['Rendez-vous médical', 'Prenez rendez-vous et décrivez un symptôme simple', 'Expliquez l\'historique du problème et posez vos questions'],
        ['Courrier administratif', 'Repérez la date, le lieu et la demande principale', 'Comprenez le contenu et répondez par écrit'],
        ['Travail', 'Échangez des informations simples avec vos collègues', 'Participez à une réunion sur un sujet connu'],
        ['École des enfants', 'Comprenez un mot de l\'enseignant', 'Discutez des progrès de votre enfant'],
    ] : [
        ['Doctor\'s appointment', 'Book it and describe a simple symptom', 'Explain the history of the problem and ask your questions'],
        ['Official letter', 'Find the date, place and main request', 'Understand the content and reply in writing'],
        ['Work', 'Swap simple information with colleagues', 'Take part in a meeting on a familiar topic'],
        ['Your child\'s school', 'Understand a note from the teacher', 'Discuss your child\'s progress'],
    ];

    $plan = $isFr ? [
        ['01', 'Le niveau de votre carte, vérifié', 'A2 ou B1 : nous partons du niveau exigé pour votre démarche.'],
        ['02', 'Votre niveau, mesuré dès le premier cours', 'Votre tuteur situe vos 4 compétences face à ce niveau. Si vous partez de zéro, il démarre directement le programme A1.'],
        ['03', 'Le bon test', 'TCF IRN, TEF IRN ou DELF : nous vous aidons à choisir selon votre calendrier, puis nous préparons ce format.'],
        ['04', 'Des cours en direct, 2 ou 4 h par semaine', 'En particulier ou en mini-groupe de 2 à 5 personnes, avec examens blancs dans votre espace élève.'],
    ] : [
        ['01', 'Your card\'s level, checked', 'A2 or B1: we start from the level your application requires.'],
        ['02', 'Your level, measured in your first class', 'Your tutor places your 4 skills against that target. If you\'re starting from zero, they go straight into the A1 programme.'],
        ['03', 'The right test', 'TCF IRN, TEF IRN or DELF: we help you choose based on your timeline, then train that format.'],
        ['04', 'Live classes, 2 or 4 hours a week', '1:1 or in a group of 2–5, with mock exams in your student portal.'],
    ];

    $facts = $isFr
        ? ['Préparation TCF IRN, TEF IRN et DELF', 'Du A1 au B2', 'Examens blancs dans votre espace élève']
        : ['TCF IRN, TEF IRN and DELF prep', 'A1 to B2', 'Mock exams in your student portal'];

    $jumps = $isFr ? [
        ['#niveaux', 'Niveaux'],
        ['#' . $a2Id, 'Carte pluriannuelle'],
        ['#' . $b1Id, 'Carte de résident'],
        ['#preuves', 'Preuves'],
        ['#preparation', 'Préparation'],
        ['#faq', 'FAQ'],
    ] : [
        ['#levels', 'Levels'],
        ['#' . $a2Id, 'Multi-year A2'],
        ['#' . $b1Id, 'Resident card'],
        ['#proof', 'Proof'],
        ['#prepare', 'Preparation'],
        ['#faq', 'FAQ'],
    ];

    $levelsId = $isFr ? 'niveaux' : 'levels';
    $proofId = $isFr ? 'preuves' : 'proof';
    $prepareId = $isFr ? 'preparation' : 'prepare';
?>
<html lang="<?php echo e($isFr ? 'fr' : 'en'); ?>" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($title); ?> | <?php echo e($siteName); ?></title>
    <meta name="description" content="<?php echo e($metaDesc); ?>">
    <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
    <link rel="alternate" hreflang="fr" href="<?php echo e($frUrl); ?>">
    <link rel="alternate" hreflang="en" href="<?php echo e($enUrl); ?>">
    <link rel="alternate" hreflang="x-default" href="<?php echo e($enUrl); ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo e($title); ?> | <?php echo e($siteName); ?>">
    <meta property="og:description" content="<?php echo e($metaDesc); ?>">
    <meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
    <meta property="og:site_name" content="<?php echo e($siteName); ?>">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "WebPage",
                "name": "<?php echo e($title); ?>",
                "description": "<?php echo e($metaDesc); ?>",
                "url": "<?php echo e($canonicalUrl); ?>",
                "inLanguage": "<?php echo e($isFr ? 'fr' : 'en'); ?>",
                "publisher": { "@type": "Organization", "name": "<?php echo e($siteName); ?>", "url": "https://fluencefrancaise.fr" }
            },
            {
                "@type": "BreadcrumbList",
                "itemListElement": [
                    { "@type": "ListItem", "position": 1, "name": "<?php echo e($isFr ? 'Accueil' : 'Home'); ?>", "item": "<?php echo e($isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en'); ?>" },
                    { "@type": "ListItem", "position": 2, "name": "France", "item": "<?php echo e($isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en'); ?>" },
                    { "@type": "ListItem", "position": 3, "name": "<?php echo e($isFr ? 'Titre de séjour' : 'Residence permits'); ?>", "item": "<?php echo e($canonicalUrl); ?>" }
                ]
            },
            {
                "@type": "FAQPage",
                "mainEntity": [
                    <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        {
                            "@type": "Question",
                            "name": "<?php echo e($faq['q']); ?>",
                            "acceptedAnswer": { "@type": "Answer", "text": "<?php echo e(trim(preg_replace('/\s+/', ' ', strip_tags($faq['a'])))); ?>" }
                        }<?php if(!$loop->last): ?>,<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ]
            }
        ]
    }
    </script>

    <?php echo $__env->make('partials.favicon', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.custom-scripts-head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body class="antialiased bg-white text-ink">
    <?php echo $__env->make('partials.custom-scripts-body', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="landing-page min-h-screen flex flex-col">
        <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <main class="flex-grow">
            <section class="relative overflow-hidden bg-white pt-28 sm:pt-36 md:pt-40 pb-12 border-b border-slate-100">
                <div class="hero-aurora"></div>
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="max-w-3xl mx-auto text-center">
                        <nav aria-label="Breadcrumb"
                            class="mb-5 flex justify-center text-xs text-slate-400 font-medium items-center gap-2">
                            <a href="<?php echo e($homeUrl); ?>"
                                class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Accueil' : 'Home'); ?></a>
                            <span class="text-slate-300">›</span>
                            <span>France</span>
                            <span class="text-slate-300">›</span>
                            <span
                                class="text-slate-700 font-semibold"><?php echo e($isFr ? 'Titre de séjour' : 'Residence permits'); ?></span>
                        </nav>

                        <div class="inline-flex items-center justify-center gap-1.5 mb-5">
                            <span class="inline-block w-3.5 h-[3px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[3px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'TITRE DE SÉJOUR · 2026' : 'RESIDENCE PERMITS · 2026'); ?>

                            </span>
                        </div>

                        <h1
                            class="text-3xl sm:text-5xl lg:text-[48px] font-bold text-[#0c1e38] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Titre de séjour :' : 'French Residence Permits:'); ?>

                            <span class="block w-fit max-w-full mx-auto text-[#0055A4] mt-1">
                                <?php echo e($isFr ? 'atteignez l\'A2 ou le B1 exigé' : 'Reach the A2 or B1 Required'); ?>

                                <span class="block h-[3px] mt-2 rounded-full"
                                    style="background: linear-gradient(90deg, #0055A4 0%, #0055A4 33.3%, #eaebf0 33.3%, #eaebf0 66.6%, #EF4135 66.6%, #EF4135 100%);"></span>
                            </span>
                        </h1>

                        <p class="mt-6 text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
                            <?php echo e($isFr
                                ? 'Depuis le 1er janvier 2026, la plupart des premières cartes pluriannuelles demandent l\'A2 et la carte de résident le B1. Nous vous préparons au niveau de votre carte.'
                                : 'Since 1 January 2026, most first multi-year permits require A2 French and the 10-year resident card B1. We prepare you for the level your card needs.'); ?>

                        </p>

                        <div class="mt-8">
                            <a href="/register"
                                class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055A4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all">
                                <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                                <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                            </a>
                            <p class="mt-3 text-xs sm:text-[13px] text-slate-500 font-medium">
                                <?php echo e($isFr
                                    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                                    : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                            </p>
                        </div>

                        <div
                            class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-xs sm:text-sm font-medium text-slate-600">
                            <?php $__currentLoopData = $facts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas fa-check text-[#0055A4] text-[11px]"></i>
                                    <?php echo e($fact); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <p class="mt-5 text-xs font-semibold text-slate-400">
                            <span class="text-[#0055A4] text-[8px] align-middle mr-1">●</span>
                            <?php echo e($isFr ? 'Exigences 2026' : '2026 requirements'); ?>

                            <span class="text-slate-300 mx-1.5">/</span>
                            <?php echo e($isFr ? 'Mis à jour le 12 novembre 2026' : 'Updated 12 November 2026'); ?>

                        </p>
                    </div>

                    <div class="mt-8 flex flex-wrap items-center justify-center gap-2.5">
                        <?php $__currentLoopData = $jumps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jump): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($jump[0]); ?>"
                                class="inline-flex items-center px-4 py-2 rounded-full border border-slate-200 bg-white text-xs sm:text-sm font-medium text-slate-600 hover:text-[#0055A4] hover:border-[#0055A4]/30 transition-colors">
                                <?php echo e($jump[1]); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <section id="<?php echo e($levelsId); ?>" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <div class="lg:col-span-7">
                            <div class="inline-flex items-center gap-1.5 mb-3">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span
                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'EXIGENCES 2026' : '2026 REQUIREMENTS'); ?></span>
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                <?php echo e($isFr ? 'Quel niveau de français pour un titre de séjour en 2026 ?' : 'What level of French do you need for a French residence permit in 2026?'); ?>

                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <?php echo e($isFr
                                    ? 'Depuis le 1er janvier 2026, une première carte de séjour pluriannuelle exige le niveau A2. La carte de résident exige le B1. Ces règles concernent les ressortissants non européens de moins de 65 ans. Toutes les cartes ne sont pas concernées : pour une première carte pluriannuelle, les exclusions comprennent les titres hors contrat d\'intégration républicaine (CIR), la protection subsidiaire et le statut d\'apatride. Les cartes de résident suivent des règles différentes selon leur catégorie. Vérifiez la catégorie de votre carte sur service-public.fr. Pour la plupart des premières demandes, un examen civique s\'ajoute aussi, sauf si vous avez plus de 65 ans.'
                                    : 'Since 1 January 2026, a first carte de séjour pluriannuelle (multi-year residence permit) requires A2. The carte de résident (10-year resident card) requires B1. These rules apply to non-EU nationals under 65. Not every card is covered: for a first multi-year permit, excluded categories include permits outside the republican integration contract (CIR), subsidiary protection and stateless status. Resident cards follow different rules depending on their category. Check your card\'s category on service-public.fr. Most first applications for these cards also require the civic exam, unless you\'re over 65.'); ?>

                            </p>
                        </div>
                        <div class="lg:col-span-5">
                            <div class="rounded-3xl bg-[#002654] text-white overflow-hidden">
                                <div class="h-[3px] w-full flex">
                                    <span class="w-1/3 bg-[#0055A4]"></span>
                                    <span class="w-1/3 bg-white"></span>
                                    <span class="w-1/3 bg-[#EF4135]"></span>
                                </div>
                                <div class="grid grid-cols-2 divide-x divide-white/10">
                                    <div class="p-6 sm:p-7">
                                        <div class="text-4xl font-extrabold">A2</div>
                                        <div class="text-xs text-blue-100/80 mt-2 leading-relaxed">
                                            <?php echo e($isFr ? 'Carte de séjour pluriannuelle' : 'Multi-year residence permit'); ?>

                                        </div>
                                    </div>
                                    <div class="p-6 sm:p-7">
                                        <div class="text-4xl font-extrabold">B1</div>
                                        <div class="text-xs text-blue-100/80 mt-2 leading-relaxed">
                                            <?php echo e($isFr ? 'Carte de résident, 10 ans' : '10-year resident card'); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="px-6 sm:px-7 py-4 border-t border-white/10 text-xs text-blue-100/80">
                                    <?php echo e($isFr ? 'Depuis le 1er janvier 2026' : 'Since 1 January 2026'); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 rounded-2xl border border-slate-200/80 overflow-hidden">
                        <div
                            class="hidden sm:grid sm:grid-cols-12 gap-4 px-5 sm:px-7 py-3.5 bg-[#002654] text-white text-xs font-semibold tracking-wide">
                            <div class="sm:col-span-6"><?php echo e($isFr ? 'Titre' : 'Card'); ?></div>
                            <div class="sm:col-span-3"><?php echo e($isFr ? 'Niveau exigé' : 'Level required'); ?></div>
                            <div class="sm:col-span-3"><?php echo e($isFr ? 'Avant 2026' : 'Before 2026'); ?></div>
                        </div>
                        <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div
                                class="grid grid-cols-1 sm:grid-cols-12 gap-1 sm:gap-4 px-5 sm:px-7 py-4 text-sm <?php echo e($index % 2 === 0 ? 'bg-[#f7f9fc]' : 'bg-white'); ?> <?php echo e($index < count($levels) - 1 ? 'border-b border-slate-100' : ''); ?>">
                                <div class="sm:col-span-6 font-semibold text-[#10233e]">
                                    <span class="sm:hidden text-[11px] uppercase tracking-wide text-slate-400 font-bold block mb-1"><?php echo e($isFr ? 'Titre' : 'Card'); ?></span>
                                    <?php if($row[3]): ?>
                                        <a href="<?php echo e($row[3]); ?>" class="text-[#0055A4] hover:underline"><?php echo e($row[0]); ?></a>
                                        <span class="font-medium text-slate-400"><?php echo e($isFr ? ' (pour comparaison)' : ' (for comparison)'); ?></span>
                                    <?php else: ?>
                                        <?php echo e($row[0]); ?>

                                    <?php endif; ?>
                                </div>
                                <div class="sm:col-span-3 text-[#0055A4] font-bold">
                                    <span class="sm:hidden text-[11px] uppercase tracking-wide text-slate-400 font-bold mr-2"><?php echo e($isFr ? 'Niveau' : 'Level'); ?></span>
                                    <?php echo e($row[1]); ?>

                                </div>
                                <div class="sm:col-span-3 text-slate-600">
                                    <span class="sm:hidden text-[11px] uppercase tracking-wide text-slate-400 font-bold mr-2"><?php echo e($isFr ? 'Avant 2026' : 'Before 2026'); ?></span>
                                    <?php echo e($row[2]); ?>

                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span
                                class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'A2 ET B1' : 'A2 AND B1'); ?></span>
                        </div>
                    </div>
                    <div class="grid lg:grid-cols-2 gap-5">
                        <article id="<?php echo e($a2Id); ?>" class="scroll-mt-28 bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#f0f6fc] text-[#0055A4] text-xs font-bold tracking-wide">A2</span>
                            <h2 class="mt-4 text-2xl sm:text-[28px] font-bold text-[#10233e] tracking-tight leading-snug">
                                <?php echo e($isFr ? 'Carte de séjour pluriannuelle : le niveau A2' : 'Multi-year residence permit: the A2 level'); ?>

                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <?php echo e($isFr
                                    ? 'L\'A2 correspond à un français de survie solide. Vous comprenez des phrases simples sur des sujets familiers, vous vous présentez, vous faites vos courses, vous décrivez votre quotidien.'
                                    : 'A2 is solid everyday French. You understand simple sentences on familiar topics, introduce yourself, shop, and describe your daily life.'); ?>

                            </p>
                            <p class="mt-3 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <?php if($isFr): ?>
                                    C'est le premier palier. Pour beaucoup, c'est aussi le plus rapide à atteindre avec un travail régulier. Le <a href="<?php echo e($delfA2Url); ?>" <?php echo $link; ?>>DELF A2</a>, le <a href="<?php echo e($tcfUrl); ?>" <?php echo $link; ?>>TCF IRN</a> ou le <a href="<?php echo e($tefUrl); ?>" <?php echo $link; ?>>TEF IRN</a> permettent de le prouver.
                                <?php else: ?>
                                    It's the first step, and for many people the quickest to reach with steady work. The <a href="<?php echo e($delfA2Url); ?>" <?php echo $link; ?>>DELF A2</a>, the <a href="<?php echo e($tcfUrl); ?>" <?php echo $link; ?>>TCF IRN</a> or the <a href="<?php echo e($tefUrl); ?>" <?php echo $link; ?>>TEF IRN</a> can prove it.
                                <?php endif; ?>
                            </p>
                        </article>

                        <article id="<?php echo e($b1Id); ?>" class="scroll-mt-28 bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#002654] text-white text-xs font-bold tracking-wide">B1</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#fff1ef] text-[#EF4135] text-xs font-bold tracking-wide"><?php echo e($isFr ? 'Depuis 2026' : 'Since 2026'); ?></span>
                            </div>
                            <h2 class="mt-4 text-2xl sm:text-[28px] font-bold text-[#10233e] tracking-tight leading-snug">
                                <?php echo e($isFr ? 'Carte de résident : le niveau B1' : '10-year resident card: the B1 level'); ?>

                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <?php echo e($isFr
                                    ? 'Le B1 change de dimension. Vous suivez une conversation claire, vous racontez une expérience, vous donnez votre avis et vous le justifiez. Vous vous débrouillez seul dans la plupart des situations de la vie courante.'
                                    : 'B1 is a different league. You follow a clear conversation, tell someone about an experience, give your opinion and back it up. You handle most everyday situations on your own.'); ?>

                            </p>
                            <p class="mt-3 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <?php if($isFr): ?>
                                    Passer de l'A2 au B1, c'est passer de « se faire comprendre » à « tenir une vraie conversation ». Le <a href="<?php echo e($delfB1Url); ?>" <?php echo $link; ?>>DELF B1</a>, le <a href="<?php echo e($tcfUrl); ?>" <?php echo $link; ?>>TCF IRN</a> ou le <a href="<?php echo e($tefUrl); ?>" <?php echo $link; ?>>TEF IRN</a> permettent de le prouver.
                                <?php else: ?>
                                    Going from A2 to B1 means going from “getting understood” to “holding a real conversation”. The <a href="<?php echo e($delfB1Url); ?>" <?php echo $link; ?>>DELF B1</a>, the <a href="<?php echo e($tcfUrl); ?>" <?php echo $link; ?>>TCF IRN</a> or the <a href="<?php echo e($tefUrl); ?>" <?php echo $link; ?>>TEF IRN</a> can prove it.
                                <?php endif; ?>
                            </p>
                        </article>
                    </div>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span
                                class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'LE FRANÇAIS AU QUOTIDIEN' : 'EVERYDAY FRENCH'); ?></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            <?php echo e($isFr ? 'A2 et B1, concrètement' : 'A2 and B1 in real life'); ?>

                        </h2>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 overflow-hidden">
                        <div
                            class="hidden sm:grid sm:grid-cols-12 gap-4 px-5 sm:px-7 py-3.5 bg-[#f7f9fc] text-xs font-semibold tracking-wide text-slate-500 border-b border-slate-100">
                            <div class="sm:col-span-3"><?php echo e($isFr ? 'Situation' : 'Situation'); ?></div>
                            <div class="sm:col-span-4"><?php echo e($isFr ? 'Au niveau A2, vous…' : 'At A2, you…'); ?></div>
                            <div class="sm:col-span-5"><?php echo e($isFr ? 'Au niveau B1, vous…' : 'At B1, you…'); ?></div>
                        </div>
                        <?php $__currentLoopData = $situations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div
                                class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4 px-5 sm:px-7 py-5 text-sm <?php echo e($index < count($situations) - 1 ? 'border-b border-slate-100' : ''); ?>">
                                <div class="sm:col-span-3 font-semibold text-[#10233e]"><?php echo e($row[0]); ?></div>
                                <div class="sm:col-span-4 text-slate-600">
                                    <span class="sm:hidden text-[11px] uppercase tracking-wide text-[#0055A4] font-bold block mb-1">A2</span>
                                    <?php echo e($row[1]); ?>

                                </div>
                                <div class="sm:col-span-5 text-slate-600">
                                    <span class="sm:hidden text-[11px] uppercase tracking-wide text-[#0055A4] font-bold block mb-1">B1</span>
                                    <?php echo e($row[2]); ?>

                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <section id="<?php echo e($proofId); ?>" class="scroll-mt-28 py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                        <div class="lg:col-span-5">
                            <div class="inline-flex items-center gap-1.5 mb-3">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span
                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'PREUVES ACCEPTÉES' : 'ACCEPTED PROOF'); ?></span>
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                                <?php echo e($isFr ? 'Quelles preuves sont acceptées ?' : 'Which proof is accepted?'); ?>

                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <?php echo e($isFr
                                    ? 'Le niveau ne se déclare pas, il se prouve. La préfecture accepte une attestation de test ou un diplôme français. Les diplômes délivrés par les autorités d\'un autre pays ne sont pas acceptés, même si ce pays est francophone.'
                                    : 'You can\'t just state your level; you have to prove it. The préfecture accepts a test result or a French qualification. Diplomas issued by another country\'s authorities aren\'t accepted, even from a French-speaking country.'); ?>

                            </p>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <?php if($isFr): ?>
                                    Vous hésitez entre test et diplôme ? <a href="<?php echo e($compareUrl); ?>" <?php echo $link; ?>>Comparez le TCF, le TEF et le DELF</a>.
                                <?php else: ?>
                                    Torn between a test and a diploma? <a href="<?php echo e($compareUrl); ?>" <?php echo $link; ?>>Compare the TCF, TEF and DELF</a>.
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="lg:col-span-7">
                            <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden">
                                <div
                                    class="grid grid-cols-12 gap-4 px-5 sm:px-7 py-3.5 bg-[#002654] text-white text-xs font-semibold tracking-wide">
                                    <div class="col-span-8"><?php echo e($isFr ? 'Preuve' : 'Proof'); ?></div>
                                    <div class="col-span-4"><?php echo e($isFr ? 'Validité' : 'Valid for'); ?></div>
                                </div>
                                <div class="grid grid-cols-12 gap-4 px-5 sm:px-7 py-4 text-sm border-b border-slate-100">
                                    <div class="col-span-8 text-[#10233e]">
                                        <a href="<?php echo e($tcfUrl); ?>" <?php echo $link; ?>>TCF IRN</a>
                                        <?php echo e($isFr ? 'ou' : 'or'); ?>

                                        <a href="<?php echo e($tefUrl); ?>" <?php echo $link; ?>>TEF IRN</a>
                                    </div>
                                    <div class="col-span-4 font-semibold text-slate-600"><?php echo e($isFr ? '2 ans' : '2 years'); ?></div>
                                </div>
                                <div class="grid grid-cols-12 gap-4 px-5 sm:px-7 py-4 text-sm bg-[#f7f9fc] border-b border-slate-100">
                                    <div class="col-span-8 text-[#10233e]">
                                        <a href="<?php echo e($delfUrl); ?>" <?php echo $link; ?>>DELF</a>
                                        <?php echo e($isFr ? 'ou DALF (A2, B1, B2…)' : 'or DALF (A2, B1, B2…)'); ?>

                                    </div>
                                    <div class="col-span-4 font-semibold text-slate-600"><?php echo e($isFr ? 'À vie' : 'Life'); ?></div>
                                </div>
                                <div class="grid grid-cols-12 gap-4 px-5 sm:px-7 py-4 text-sm border-b border-slate-100">
                                    <div class="col-span-8 text-[#10233e]">DUEF, DFP, DCL</div>
                                    <div class="col-span-4 font-semibold text-slate-600"><?php echo e($isFr ? 'Diplômes, sans date limite' : 'Diplomas, no expiry'); ?></div>
                                </div>
                                <div class="grid grid-cols-12 gap-4 px-5 sm:px-7 py-4 text-sm bg-[#f7f9fc]">
                                    <div class="col-span-8 text-[#10233e] leading-relaxed">
                                        <?php echo e($isFr
                                            ? 'Diplôme français : CAP, BEP, brevet, CFG, ou certification de niveau 3 et plus'
                                            : 'French qualification: CAP, BEP, brevet, CFG, or a level 3+ certification'); ?>

                                    </div>
                                    <div class="col-span-4 font-semibold text-slate-600"><?php echo e($isFr ? 'Diplômes, sans date limite' : 'Diplomas, no expiry'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-6 sm:py-10 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="rounded-3xl bg-[#002654] text-white px-6 sm:px-10 py-10 sm:py-12">
                        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                            <div class="lg:col-span-7">
                                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight">
                                    <?php echo e($isFr ? 'Le test de langue ne suffit plus : l\'examen civique' : 'The language test isn\'t enough: the civic exam'); ?>

                                </h2>
                                <p class="mt-4 text-sm sm:text-base text-blue-100/90 leading-relaxed">
                                    <?php if($isFr): ?>
                                        Pour la plupart des premières cartes pluriannuelles et des premières cartes de résident, vous devez aussi réussir l'examen civique : 40 questions en français, 45 minutes, 32 bonnes réponses. Votre niveau de lecture compte donc 2 fois. Sa préparation est incluse dans nos cours, sans supplément. <a href="<?php echo e($civicUrl); ?>" class="font-semibold text-white underline underline-offset-2">Tout savoir sur l'examen civique</a>.
                                    <?php else: ?>
                                        For most first multi-year permits and first resident cards, you also have to pass the civic exam: 40 questions in French, 45 minutes, 32 correct. So your reading level counts twice. Its prep is included in our classes at no extra cost. <a href="<?php echo e($civicUrl); ?>" class="font-semibold text-white underline underline-offset-2">All about the French civic exam</a>.
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="lg:col-span-5">
                                <div class="rounded-3xl bg-white/10 p-6 sm:p-7 grid grid-cols-3 gap-4 text-center">
                                    <div>
                                        <p class="text-3xl font-extrabold">40</p>
                                        <p class="mt-1 text-[11px] text-blue-100/80"><?php echo e($isFr ? 'Questions' : 'Questions'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-3xl font-extrabold">45</p>
                                        <p class="mt-1 text-[11px] text-blue-100/80"><?php echo e($isFr ? 'Minutes' : 'Minutes'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-3xl font-extrabold">32</p>
                                        <p class="mt-1 text-[11px] text-blue-100/80"><?php echo e($isFr ? 'Bonnes réponses' : 'To pass'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="<?php echo e($prepareId); ?>" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span
                                class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'COURS EN DIRECT' : 'LIVE CLASSES'); ?></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            <?php echo e($isFr ? 'Notre préparation' : 'How we prepare you'); ?>

                        </h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <?php $__currentLoopData = $plan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <div class="flex items-center mb-4">
                                    <span
                                        class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055A4] text-sm font-semibold flex items-center justify-center border border-blue-100 shrink-0"><?php echo e($step[0]); ?></span>
                                    <?php if($index < 3): ?>
                                        <div class="hidden lg:block flex-1 h-px bg-[#e1eaf2] ml-4"></div>
                                    <?php endif; ?>
                                </div>
                                <h3 class="font-bold text-[#10233e] text-base mb-2"><?php echo e($step[1]); ?></h3>
                                <p class="text-[13px] text-slate-500 leading-relaxed"><?php echo e($step[2]); ?></p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="mt-12 text-center">
                        <a href="/register"
                            class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055A4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all">
                            <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                            <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                        </a>
                        <p class="mt-3 text-xs sm:text-[13px] text-slate-500">
                            <?php echo e($isFr
                                ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                                : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                        </p>
                    </div>
                </div>
            </section>

            <?php echo $__env->make('partials.faq-section', [
                'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                'title' => $isFr ? 'Questions fréquentes<br>sur le titre de séjour' : 'Residence permit<br>FAQ',
                'description' => $isFr ? 'Des réponses claires avant de passer à l\'étape suivante.' : 'Clear answers before you take your next step.',
                'contactText' => $isFr ? 'Une autre question ?' : 'Have another question?',
                'contactUrl' => $contactUrl,
                'faqs' => $faqs,
                'sectionClass' => 'py-16 sm:py-20 bg-[#f4f7fb]',
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div id="enroll" class="scroll-mt-28">
                <?php echo $__env->make('partials.cta-banner', [
                    'sectionClass' => 'pt-4 pb-6 bg-white',
                    'eyebrow' => $isFr ? 'NOUS VOUS ACCOMPAGNONS' : "LET'S GET YOU THERE",
                    'title' => $isFr
                        ? "Votre niveau. Le niveau exigé.<br class='hidden sm:inline'> Nous comblons l'écart."
                        : "Your level. The required level.<br class='hidden sm:inline'> We close the gap.",
                    'description' => $isFr
                        ? 'Votre premier cours situe votre niveau face à celui de votre carte. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                        : 'Your first class places your level against the one your card needs. You choose the pace: 2 or 4 hours a week.',
                    'primaryButtonText' => $isFr ? "S'inscrire" : 'Enroll now',
                    'primaryButtonUrl' => '/register',
                    'showSecondaryButton' => true,
                    'secondaryButtonText' => $isFr ? 'Contactez-nous' : 'Talk to us',
                    'secondaryButtonUrl' => $contactUrl,
                    'helperText' => $isFr
                        ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                        : 'Live classes on Google Meet · 1:1 or small group · Student portal included',
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <div class="bg-white pb-16">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl text-center">
                    <p class="text-sm text-slate-500">
                        <?php echo e($isFr ? 'Vous venez à deux ? 50 € de remise par mois, pour vous et pour votre ami.' : 'Coming with a friend? €50 off per month, for you and for them.'); ?>

                        <a href="<?php echo e($referralUrl); ?>"
                            class="text-[#0055A4] font-semibold hover:underline ml-1"><?php echo e($isFr ? 'Contactez-nous' : 'Contact us'); ?>

                            →</a>
                    </p>
                    <div class="my-6 border-t border-slate-100"></div>
                    <div
                        class="flex flex-wrap items-center justify-center gap-y-2 text-xs sm:text-[13px] text-slate-400">
                        <span class="font-bold tracking-[0.18em] uppercase mr-3">SOURCES</span>
                        <a href="<?php echo e($spCards); ?>" target="_blank" rel="noopener"
                            class="hover:text-slate-600">Service-public.fr –
                            <?php echo e($isFr ? 'carte de résident et carte pluriannuelle : justifier de votre connaissance du français' : 'proving your French for the resident card and multi-year permit'); ?>

                            ↗</a>
                    </div>
                </div>
            </div>
        </main>

        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</body>

</html>
<?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/french-residence-permit.blade.php ENDPATH**/ ?>