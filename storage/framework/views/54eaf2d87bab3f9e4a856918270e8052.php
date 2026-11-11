<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';

    $isFr = request()->is('fr*');

    $title = $isFr
        ? "DELF A2 : épreuves, notation et préparation en ligne"
        : "DELF A2: Exam Format, Scoring and Online Prep";

    $metaDesc = $isFr
        ? "DELF A2 : 4 épreuves, environ 1 h 40 d'écrit et un oral de 6 à 8 min. Diplôme à vie, accepté pour la carte pluriannuelle. Pièges à éviter et préparation."
        : "DELF A2: 4 sections, about 1 h 40 written plus a 6–8 min oral. A lifetime diploma accepted for France's multi-year permit. Common traps, live prep.";

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/delf-a2/' : 'https://fluencefrancaise.fr/en/delf-a2/';
    $frUrl = 'https://fluencefrancaise.fr/fr/delf-a2/';
    $enUrl = 'https://fluencefrancaise.fr/en/delf-a2/';

    $permitUrl = $isFr ? '/fr/titre-de-sejour-niveau-francais/#carte-pluriannuelle' : '/en/french-residence-permit-language/#multi-year-permit';
    $residentUrl = $isFr ? '/fr/titre-de-sejour-niveau-francais/#carte-de-resident' : '/en/french-residence-permit-language/#resident-card';
    $tcfUrl = $isFr ? '/fr/tcf-irn/' : '/en/tcf-irn/';
    $tefUrl = $isFr ? '/fr/tef-irn/' : '/en/tef-irn/';
    $delfB1Url = $isFr ? '/fr/delf-b1/' : '/en/delf-b1/';
    $cPermitUrl = $isFr ? '/fr/permis-c-suisse/' : '/en/c-permit-switzerland/';
    $delfLevelsUrl = $isFr ? '/fr/delf/' : '/en/delf/';
    $contactUrl = $isFr ? '/fr/contact/' : '/en/contact/';
    $referralUrl = $isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral';
    $coursesUrl = $isFr ? '/fr/cours-de-francais-en-ligne' : '/our-courses';

    $link = 'class="font-semibold text-[#0055A4] hover:underline"';

    $faqs = $isFr ? [
        [
            'q' => "Le DELF A2 est-il accepté pour la carte de séjour pluriannuelle ?",
            'a' => "Oui. Depuis le 1er janvier 2026, une première carte pluriannuelle exige l'A2, et le DELF A2 le prouve à vie.",
        ],
        [
            'q' => "Le DELF A2 suffit-il pour la carte de résident ?",
            'a' => "Non. La carte de résident exige le B1 depuis 2026. Il vous faudra le <a href=\"{$delfB1Url}\" {$link}>DELF B1</a> ou un test au niveau B1.",
        ],
        [
            'q' => "Quelle note faut-il pour obtenir le DELF A2 ?",
            'a' => "50 sur 100 au total, avec au moins 5 sur 25 dans chaque épreuve.",
        ],
        [
            'q' => "Peut-on passer le DELF A2 en ligne ?",
            'a' => "Non. L'examen se passe dans un centre agréé. La préparation, elle, peut se faire entièrement en ligne.",
        ],
        [
            'q' => "Le DELF A2 est-il utile en Suisse ?",
            'a' => "Oui. Pour un permis C ordinaire, il faut l'A2 à l'oral et l'A1 à l'écrit, et un diplôme DELF est accepté. Voir le <a href=\"{$cPermitUrl}\" {$link}>permis C</a>.",
        ],
    ] : [
        [
            'q' => "Is the DELF A2 accepted for France's multi-year residence permit?",
            'a' => "Yes. Since 1 January 2026, a first multi-year permit requires A2, and the DELF A2 proves it for life.",
        ],
        [
            'q' => "Is the DELF A2 enough for the 10-year resident card?",
            'a' => "No. The resident card has required B1 since 2026. You'll need the <a href=\"{$delfB1Url}\" {$link}>DELF B1</a> or a test at B1.",
        ],
        [
            'q' => "What score do you need to pass the DELF A2?",
            'a' => "50 out of 100 overall, with at least 5 out of 25 in every section.",
        ],
        [
            'q' => "Can I take the DELF A2 online?",
            'a' => "No. The exam is taken at an approved centre. The preparation can be fully online.",
        ],
        [
            'q' => "Is the DELF A2 useful in Switzerland?",
            'a' => "Yes. An ordinary C permit needs A2 spoken and A1 written, and a DELF diploma is accepted. See the <a href=\"{$cPermitUrl}\" {$link}>C permit</a>.",
        ],
    ];

    $glanceRows = $isFr ? [
        ['Pour qui', 'Carte de séjour pluriannuelle, permis C ordinaire en Suisse, premier diplôme de français'],
        ['Épreuves', 'Compréhension orale, compréhension écrite, production écrite, production orale'],
        ['Durée', "Environ 1 h 40 d'écrit + 6 à 8 min d'oral (après 10 min de préparation)"],
        ['Réussite', '50/100, avec au moins 5/25 dans chaque épreuve'],
        ['Validité', 'À vie'],
    ] : [
        ["Who it's for", "France's multi-year residence permit, Switzerland's ordinary C permit, a first French diploma"],
        ['Sections', 'Listening, reading, writing, speaking'],
        ['Duration', 'About 1 h 40 written + a 6–8 min oral (after 10 min of prep)'],
        ['Pass mark', '50/100, with at least 5/25 in every section'],
        ['Validity', 'Life'],
    ];

    $sections = $isFr ? [
        ['Compréhension orale', 'Vous répondez à des questions sur des documents audio courts du quotidien', '/25'],
        ['Compréhension écrite', 'Vous répondez à des questions sur des documents simples : annonces, messages, articles courts', '/25'],
        ['Production écrite', 'Vous rédigez des textes courts : décrire, raconter, inviter, répondre', '/25'],
        ['Production orale', 'Entretien sur vous, monologue sur un sujet du quotidien, puis jeu de rôle', '/25'],
    ] : [
        ['Listening', 'Answer questions on short everyday recordings', '/25'],
        ['Reading', 'Answer questions on simple documents: ads, messages, short articles', '/25'],
        ['Writing', 'Write short texts: describe, narrate, invite, reply', '/25'],
        ['Speaking', 'An interview about you, a short talk on an everyday topic, then a role play', '/25'],
    ];

    $traps = $isFr ? [
        [
            'title' => 'Répondre à la moitié de la consigne',
            'body' => "Une consigne d'A2 contient souvent 2 ou 3 demandes : décrire, puis inviter, puis proposer une date. Oublier l'une d'elles coûte des points, même avec un français correct.",
            'fix' => 'Souligner chaque demande de la consigne, puis cocher chacune dans votre texte.',
        ],
        [
            'title' => 'Un oral trop court',
            'body' => "L'oral est court : 6 à 8 minutes. Chaque réponse doit donc montrer votre niveau. Une réponse en 3 mots ne le permet pas.",
            'fix' => 'Des réponses développées sur les thèmes du quotidien : famille, travail, logement, loisirs.',
        ],
        [
            'title' => 'Le jeu de rôle sans stratégie',
            'body' => "Acheter un billet, réserver une table, demander un renseignement : le jeu de rôle suit des scénarios prévisibles. Ceux qui les ont répétés partent avec un avantage net.",
            'fix' => "Les scénarios les plus courants, répétés avec votre tuteur jusqu'à l'aisance.",
        ],
    ] : [
        [
            'title' => 'Answering half the task',
            'body' => 'An A2 writing task often contains 2 or 3 requests: describe, then invite, then suggest a date. Miss one and you lose marks, even with correct French.',
            'fix' => 'Underline every request in the task, then tick each one off in your text.',
        ],
        [
            'title' => "An oral that's too short",
            'body' => "The oral is brief: 6 to 8 minutes. Every answer has to show your level, and a 3-word answer can't.",
            'fix' => 'Developed answers on everyday topics: family, work, home, free time.',
        ],
        [
            'title' => 'A role play with no plan',
            'body' => "Buying a ticket, booking a table, asking for information: role plays follow predictable scenarios. Candidates who've rehearsed them start with a clear edge.",
            'fix' => "The most common scenarios, rehearsed with your tutor until they're easy.",
        ],
    ];

    $plan = $isFr ? [
        ['01', 'Vos 4 épreuves, mesurées', "Dès le premier cours, votre tuteur repère votre point fort et l'épreuve à sécuriser. Si vous partez de zéro, il démarre directement le programme A1."],
        ['02', 'Les scénarios du quotidien', 'Logement, santé, transports, achats : le vocabulaire du test est celui de votre vie.'],
        ['03', 'Des textes et des oraux corrigés', 'Chaque production revient avec ce qui vous coûte des points.'],
        ['04', 'Des cours en direct, 2 ou 4 h par semaine', 'En particulier ou en mini-groupe de 2 à 5 personnes, avec examens blancs dans votre espace élève.'],
    ] : [
        ['01', 'Your 4 sections, measured', "In your first class, your tutor spots your strongest section and the one to secure. If you're starting from zero, they go straight into the A1 programme."],
        ['02', 'Everyday scenarios', "Housing, health, transport, shopping: the test's vocabulary is your daily life."],
        ['03', 'Corrected writing and speaking', "Everything you produce comes back showing what's costing you marks."],
        ['04', 'Live classes, 2 or 4 hours a week', '1:1 or in a group of 2–5, with mock exams in your student portal.'],
    ];

    $facts = $isFr ? [
        ['fa-check', 'Les 4 épreuves travaillées'],
        ['fa-pen-nib', 'Textes corrigés'],
        ['fa-comments', "Simulations des 3 parties de l'oral"],
    ] : [
        ['fa-check', 'All 4 sections covered'],
        ['fa-pen-nib', 'Corrected writing'],
        ['fa-comments', 'Practice for all 3 parts of the oral'],
    ];
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
                "@type": "Course",
                "name": "<?php echo e($title); ?>",
                "description": "<?php echo e($metaDesc); ?>",
                "provider": {
                    "@type": "Organization",
                    "name": "<?php echo e($siteName); ?>",
                    "url": "https://fluencefrancaise.fr"
                },
                "courseMode": "online",
                "url": "<?php echo e($canonicalUrl); ?>"
            },
            {
                "@type": "BreadcrumbList",
                "itemListElement": [
                    {
                        "@type": "ListItem",
                        "position": 1,
                        "name": "<?php echo e($isFr ? 'Accueil' : 'Home'); ?>",
                        "item": "<?php echo e($isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en'); ?>"
                    },
                    {
                        "@type": "ListItem",
                        "position": 2,
                        "name": "<?php echo e($isFr ? 'Examens' : 'Exams'); ?>",
                        "item": "<?php echo e($isFr ? 'https://fluencefrancaise.fr/fr#exams' : 'https://fluencefrancaise.fr/en#exams'); ?>"
                    },
                    {
                        "@type": "ListItem",
                        "position": 3,
                        "name": "DELF A2",
                        "item": "<?php echo e($canonicalUrl); ?>"
                    }
                ]
            },
            {
                "@type": "FAQPage",
                "mainEntity": [
                    <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        {
                            "@type": "Question",
                            "name": "<?php echo e($faq['q']); ?>",
                            "acceptedAnswer": {
                                "@type": "Answer",
                                "text": "<?php echo e(trim(preg_replace('/\s+/', ' ', strip_tags($faq['a'])))); ?>"
                            }
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
            <!-- ============ HERO ============ -->
            <section class="relative overflow-hidden bg-white pt-28 sm:pt-36 md:pt-40 pb-14 sm:pb-16">
                <div class="hero-aurora"></div>

                <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="grid lg:grid-cols-12 gap-10 lg:gap-8 items-center">
                        <div class="lg:col-span-7">
                            <nav aria-label="Breadcrumb" class="text-xs text-slate-400 font-medium flex items-center gap-2 mb-5">
                                <a href="<?php echo e($isFr ? '/fr' : '/en'); ?>" class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Accueil' : 'Home'); ?></a>
                                <span class="text-slate-300">›</span>
                                <a href="<?php echo e($isFr ? '/fr#exams' : '/en#exams'); ?>" class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Examens' : 'Exams'); ?></a>
                                <span class="text-slate-300">›</span>
                                <span class="text-slate-700 font-semibold">DELF A2</span>
                            </nav>

                            <div class="inline-flex items-center gap-1.5 mb-4">
                                <span class="inline-block w-3.5 h-[3px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[3px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                    <?php echo e($isFr ? 'LE DIPLÔME A2 À VIE' : 'THE LIFETIME A2 DIPLOMA'); ?>

                                </span>
                            </div>

                            <h1 class="text-3xl sm:text-5xl lg:text-[52px] font-bold text-[#0c1e38] tracking-tight leading-[1.12] max-w-xl">
                                <?php echo e($isFr ? "DELF A2 : validez l'A2 pour votre carte de séjour" : 'DELF A2: Prove A2 for Your Residence Permit'); ?>

                            </h1>

                            <p class="mt-5 text-sm sm:text-base md:text-[17px] text-slate-600 max-w-xl leading-relaxed">
                                <?php echo e($isFr
                                    ? "Depuis 2026, la carte pluriannuelle exige l'A2 : le DELF A2 le prouve à vie, et nous vous y préparons en direct."
                                    : "Since 2026, France's multi-year permit requires A2, and the DELF A2 proves it for life. We prepare you for it, live."); ?>

                            </p>

                            <div class="mt-8 flex flex-wrap items-center gap-4 sm:gap-5">
                                <a href="/register"
                                    class="inline-flex items-center justify-center px-7 py-3.5 rounded-full text-sm sm:text-base font-semibold text-white bg-[#0055A4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all">
                                    <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                                    <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                                </a>
                                <a href="#at-a-glance"
                                    class="inline-flex items-center gap-2 text-sm sm:text-base font-semibold text-[#10233e] hover:text-[#0055A4] transition-colors">
                                    <?php echo e($isFr ? 'Lire le guide' : 'Read the guide'); ?>

                                    <i class="fas fa-chevron-down text-[11px] text-[#EF4135]"></i>
                                </a>
                            </div>

                            <div class="mt-5 flex items-center gap-3">
                                <div class="flex -space-x-2 shrink-0" aria-hidden="true">
                                    <span class="w-7 h-7 rounded-full bg-[#0055A4] ring-2 ring-white flex items-center justify-center text-white text-[10px]"><i class="fas fa-user"></i></span>
                                    <span class="w-7 h-7 rounded-full bg-[#7aa2d4] ring-2 ring-white flex items-center justify-center text-white text-[10px]"><i class="fas fa-user"></i></span>
                                    <span class="w-7 h-7 rounded-full bg-[#EF4135] ring-2 ring-white flex items-center justify-center text-white text-[10px]"><i class="fas fa-user"></i></span>
                                </div>
                                <p class="text-xs sm:text-[13px] text-slate-500 font-medium leading-snug">
                                    <?php echo e($isFr
                                        ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                                        : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                                </p>
                            </div>

                            <p class="mt-4 text-xs font-semibold text-slate-400">
                                <?php echo e($isFr ? 'Exigences 2026' : '2026 requirements'); ?>

                                <span class="text-slate-300 mx-1.5">/</span>
                                <?php echo e($isFr ? 'Mis à jour le 11 novembre 2026' : 'Updated 11 November 2026'); ?>

                            </p>
                        </div>

                        <div class="lg:col-span-5">
                            <div class="relative mx-auto w-full max-w-[440px] h-[380px] sm:h-[420px]">
                                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] sm:w-[340px] sm:h-[340px] rounded-full border border-[#d7e4f5]"></div>
                                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[230px] h-[230px] sm:w-[260px] sm:h-[260px] rounded-full border border-slate-200/90"></div>
                                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-[54%] w-44 h-44 sm:w-52 sm:h-52 rounded-[2rem] bg-[#0055A4] shadow-[0_24px_50px_-18px_rgba(0,85,164,.55)] flex items-center justify-center">
                                    <span class="text-white text-6xl sm:text-7xl font-extrabold tracking-tight leading-none">A2</span>
                                </div>

                                <div class="absolute top-6 right-0 sm:right-2 bg-white rounded-2xl shadow-card border border-slate-100 px-3.5 py-3 flex items-center gap-2.5 max-w-[190px]">
                                    <span class="w-7 h-7 rounded-full bg-blue-50 text-[#0055A4] flex items-center justify-center shrink-0">
                                        <i class="fas fa-check text-[11px]"></i>
                                    </span>
                                    <span>
                                        <span class="block text-[13px] font-bold text-[#10233e] leading-tight"><?php echo e($isFr ? 'Objectif clair' : 'Clear target'); ?></span>
                                        <span class="block text-[11px] text-slate-400 mt-0.5"><?php echo e($isFr ? 'Selon votre dossier' : 'From your application'); ?></span>
                                    </span>
                                </div>

                                <div class="absolute bottom-8 left-0 sm:left-1 bg-white rounded-2xl shadow-card border border-slate-100 px-3.5 py-3 flex items-center gap-2.5 max-w-[200px]">
                                    <span class="w-7 h-7 rounded-full bg-blue-50 text-[#0055A4] flex items-center justify-center shrink-0">
                                        <i class="fas fa-calendar-check text-[11px]"></i>
                                    </span>
                                    <span>
                                        <span class="block text-[13px] font-bold text-[#10233e] leading-tight"><?php echo e($isFr ? 'Plan en direct' : 'Live plan'); ?></span>
                                        <span class="block text-[11px] text-slate-400 mt-0.5"><?php echo e($isFr ? '2 ou 4 heures par semaine' : '2 or 4 hours a week'); ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-2 bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl shadow-sm grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
                        <?php $__currentLoopData = $facts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center gap-3 px-5 sm:px-6 py-5">
                                <span class="w-9 h-9 rounded-full bg-blue-50 text-[#0055A4] flex items-center justify-center shrink-0">
                                    <i class="fas <?php echo e($fact[0]); ?> text-sm"></i>
                                </span>
                                <span class="text-sm font-semibold text-[#10233e] leading-snug"><?php echo e($fact[1]); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <!-- ============ AT A GLANCE ============ -->
            <section id="at-a-glance" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="max-w-3xl mb-8 sm:mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'OFFICIEL ET DÉFINITIF' : 'OFFICIAL AND PERMANENT'); ?>

                            </span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Le DELF A2 en bref' : 'The DELF A2 at a glance'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                            <?php echo e($isFr
                                ? "Le DELF A2 est un diplôme officiel du ministère de l'Éducation nationale. Il certifie un français élémentaire solide : vous vous débrouillez dans les situations simples du quotidien. Il compte 4 épreuves notées sur 25. Il s'obtient avec 50 sur 100, sans épreuve sous 5 sur 25, et il est valable à vie."
                                : "The DELF A2 is an official French Ministry of Education diploma. It certifies solid elementary French: you can handle simple everyday situations. It has 4 sections marked out of 25. You pass with 50 out of 100 and no section below 5 out of 25, and it's valid for life."); ?>

                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200/80 overflow-hidden bg-white">
                        <div class="grid grid-cols-1 sm:grid-cols-12 bg-[#0055A4] text-white px-5 sm:px-7 py-3.5 text-xs sm:text-sm font-semibold">
                            <div class="sm:col-span-4"><?php echo e($isFr ? 'Détail' : 'Detail'); ?></div>
                            <div class="hidden sm:block sm:col-span-8">DELF A2</div>
                        </div>
                        <?php $__currentLoopData = $glanceRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-1 sm:gap-4 px-5 sm:px-7 py-4 sm:py-5 text-sm <?php echo e($index % 2 === 0 ? 'bg-white' : 'bg-[#f7f9fc]'); ?> <?php echo e($index < count($glanceRows) - 1 ? 'border-b border-slate-100' : ''); ?>">
                                <div class="sm:col-span-4 font-semibold text-[#10233e]"><?php echo e($row[0]); ?></div>
                                <div class="sm:col-span-8 text-slate-600 leading-relaxed"><?php echo e($row[1]); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <!-- ============ 4 SECTIONS ============ -->
            <section class="py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="max-w-3xl mb-8 sm:mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'UN DIPLÔME, QUATRE COMPÉTENCES' : 'ONE DIPLOMA, FOUR SKILLS'); ?>

                            </span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Les 4 épreuves du DELF A2' : 'The 4 sections of the DELF A2'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                            <?php echo e($isFr
                                ? "Les 3 premières épreuves se passent ensemble, en 1 h 40 environ. L'oral dure 6 à 8 minutes, après 10 minutes de préparation."
                                : 'The first 3 sections are taken together, in about 1 h 40. The oral lasts 6 to 8 minutes, after 10 minutes of prep.'); ?>

                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200/80 overflow-hidden bg-white">
                        <div class="hidden sm:grid sm:grid-cols-12 bg-[#0055A4] text-white px-6 py-3.5 text-sm font-semibold">
                            <div class="sm:col-span-4"><?php echo e($isFr ? 'Épreuve' : 'Section'); ?></div>
                            <div class="sm:col-span-6"><?php echo e($isFr ? 'Ce que vous faites' : 'What you do'); ?></div>
                            <div class="sm:col-span-2 text-right"><?php echo e($isFr ? 'Note' : 'Marks'); ?></div>
                        </div>
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-1 sm:gap-4 px-5 sm:px-6 py-4 sm:py-5 text-sm <?php echo e($index % 2 === 0 ? 'bg-white' : 'bg-[#f7f9fc]'); ?> <?php echo e($index < count($sections) - 1 ? 'border-b border-slate-100' : ''); ?>">
                                <div class="sm:col-span-4 font-semibold text-[#10233e]"><?php echo e($section[0]); ?></div>
                                <div class="sm:col-span-6 text-slate-600 leading-relaxed"><?php echo e($section[1]); ?></div>
                                <div class="sm:col-span-2 sm:text-right font-semibold text-[#0055A4]"><?php echo e($section[2]); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <!-- ============ WHY DELF A2 ============ -->
            <section class="py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'UN CHOIX STRATÉGIQUE' : 'A STRATEGIC CHOICE'); ?>

                            </span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Pourquoi le DELF A2 pour votre carte pluriannuelle ?' : "Why the DELF A2 for France's multi-year permit?"); ?>

                        </h2>
                        <div class="mt-5 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                            <?php if($isFr): ?>
                                <p>
                                    Depuis le 1er janvier 2026, une première <a href="<?php echo e($permitUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">carte de séjour pluriannuelle</a> exige l'A2. Le <a href="<?php echo e($tcfUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">TCF IRN</a> et le <a href="<?php echo e($tefUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">TEF IRN</a> le prouvent aussi, mais pour 2 ans seulement. Le DELF A2, lui, ne s'arrête jamais de compter.
                                </p>
                                <p>
                                    Et l'A2 n'est souvent qu'une étape. Si vous visez ensuite la <a href="<?php echo e($residentUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">carte de résident</a> (B1), le <a href="<?php echo e($delfB1Url); ?>" class="font-semibold text-[#0055A4] hover:underline">DELF B1</a> sera la marche suivante.
                                </p>
                            <?php else: ?>
                                <p>
                                    Since 1 January 2026, a first <a href="<?php echo e($permitUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">multi-year residence permit</a> requires A2. The <a href="<?php echo e($tcfUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">TCF IRN</a> and <a href="<?php echo e($tefUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">TEF IRN</a> prove it too, but only for 2 years. The DELF A2 counts forever.
                                </p>
                                <p>
                                    A2 is often just a step. If you're aiming for the <a href="<?php echo e($residentUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">10-year resident card</a> (B1) next, the <a href="<?php echo e($delfB1Url); ?>" class="font-semibold text-[#0055A4] hover:underline">DELF B1</a> is the following one.
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ WHERE POINTS ARE LOST ============ -->
            <section class="py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="max-w-3xl mb-10 sm:mb-12">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'CHAQUE POINT COMPTE' : 'PROTECT EVERY MARK'); ?>

                            </span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Où les candidats perdent des points au DELF A2' : 'Where DELF A2 candidates lose points'); ?>

                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <?php $__currentLoopData = $traps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 flex flex-col">
                                <span class="text-sm font-bold text-[#0055A4]"><?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                                <h3 class="mt-3 text-lg font-bold text-[#10233e] leading-snug"><?php echo e($trap['title']); ?></h3>
                                <p class="mt-3 text-sm text-slate-500 leading-relaxed flex-1"><?php echo e($trap['body']); ?></p>
                                <div class="mt-5 rounded-2xl bg-[#f2f7fc] border border-blue-100/70 px-4 py-3.5">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#0055A4] mb-1">
                                        <?php echo e($isFr ? 'Ce que nous faisons' : 'What we do'); ?>

                                    </p>
                                    <p class="text-xs sm:text-[13px] text-slate-600 leading-relaxed"><?php echo e($trap['fix']); ?></p>
                                </div>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <!-- ============ HOW WE PREPARE YOU ============ -->
            <section class="py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="max-w-3xl mb-10 sm:mb-12">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'VOTRE PLAN A2' : 'YOUR A2 PLAN'); ?>

                            </span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Notre préparation au DELF A2' : 'How we prepare you for the DELF A2'); ?>

                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <?php $__currentLoopData = $plan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="rounded-2xl border border-slate-200/80 bg-white px-5 py-5 sm:px-6 sm:py-6 flex items-start gap-4">
                                <span class="w-11 h-11 rounded-xl bg-[#002654] text-white text-sm font-bold flex items-center justify-center shrink-0"><?php echo e($step[0]); ?></span>
                                <div>
                                    <h3 class="font-bold text-[#10233e] text-base leading-snug"><?php echo e($step[1]); ?></h3>
                                    <p class="mt-1.5 text-sm text-slate-500 leading-relaxed"><?php echo e($step[2]); ?></p>
                                </div>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="mt-6 sm:mt-8 rounded-2xl bg-[#002654] text-white px-5 sm:px-7 py-5 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-base sm:text-lg font-bold"><?php echo e($isFr ? 'Prêt à construire votre plan ?' : 'Ready to build your plan?'); ?></p>
                            <p class="mt-1 text-xs sm:text-sm text-blue-100/85">
                                <?php echo e($isFr
                                    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                                    : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                            </p>
                        </div>
                        <a href="/register"
                            class="inline-flex items-center justify-center shrink-0 px-6 py-3 rounded-full text-sm font-semibold text-[#002654] bg-white hover:bg-[#EF4135] hover:text-white transition-all">
                            <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                            <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                        </a>
                    </div>
                </div>
            </section>

            <!-- ============ FAQ ============ -->
            <?php echo $__env->make('partials.faq-section', [
                'eyebrow' => $isFr ? 'RÉPONSES CLAIRES' : 'CLEAR ANSWERS',
                'title' => $isFr ? 'Questions fréquentes<br>sur le DELF A2' : 'DELF A2<br>FAQ',
                'description' => $isFr ? "Des réponses claires avant de passer à l'étape suivante." : 'Clear answers before you take your next step.',
                'contactText' => $isFr ? 'Parler à notre équipe' : 'Talk to our team',
                'contactUrl' => $contactUrl,
                'faqs' => $faqs,
                'sectionClass' => 'py-16 sm:py-20 bg-white border-t border-slate-100',
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="bg-white pb-4">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="border-t border-slate-100 pt-6 flex flex-wrap items-center justify-center gap-y-2 text-center text-xs sm:text-[13px] text-slate-400">
                        <span class="font-bold tracking-[0.18em] uppercase text-slate-400 mr-3"><?php echo e($isFr ? 'SOURCES' : 'SOURCES'); ?></span>
                        <a href="https://www.france-education-international.fr/delf-dalf" target="_blank" rel="noopener"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span>France Éducation international – DELF A2</span>
                            <span class="text-[10px]">↗</span>
                        </a>
                        <span class="text-slate-300 mx-2.5">·</span>
                        <a href="https://www.service-public.fr/particuliers/vosdroits/N110" target="_blank" rel="noopener"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span>Service-public.fr – <?php echo e($isFr ? 'cartes de séjour' : 'residence cards'); ?></span>
                            <span class="text-[10px]">↗</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ============ CTA ============ -->
            <?php echo $__env->make('partials.cta-banner', [
                'sectionClass' => 'pt-8 pb-6 bg-white',
                'eyebrow' => $isFr ? 'NOUS VOUS ACCOMPAGNONS' : "LET'S GET YOU THERE",
                'title' => $isFr
                    ? "Votre niveau. Le niveau exigé.<br class='hidden sm:inline'> Nous comblons l'écart."
                    : "Your level. The required level.<br class='hidden sm:inline'> We close the gap.",
                'description' => $isFr
                    ? "Votre premier cours mesure vos 4 épreuves face à l'A2 et fixe le plan jusqu'à la session. Vous choisissez le rythme : 2 ou 4 heures par semaine."
                    : 'Your first class measures your 4 sections against A2 and sets the plan up to your session. You choose the pace: 2 or 4 hours a week.',
                'primaryButtonText' => $isFr ? "S'inscrire" : 'Enroll now',
                'primaryButtonUrl' => '/register',
                'showSecondaryButton' => true,
                'secondaryButtonText' => $isFr ? 'Découvrir nos cours' : 'Explore our courses',
                'secondaryButtonUrl' => $coursesUrl,
                'helperText' => $isFr
                    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                    : 'Live classes on Google Meet · 1:1 or small group · Student portal included',
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="bg-white pb-16 sm:pb-20">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl text-center">
                    <p class="text-sm text-slate-500">
                        <?php echo e($isFr ? 'Vous venez à deux ? 50 € de remise par mois, pour vous et pour votre ami.' : 'Coming with a friend? €50 off per month, for you and for them.'); ?>

                        <a href="<?php echo e($referralUrl); ?>" class="text-[#0055A4] font-semibold hover:underline ml-1">
                            <?php echo e($isFr ? 'Contactez-nous' : 'Contact us'); ?> →
                        </a>
                    </p>
                    <p class="mt-2 text-sm text-slate-500">
                        <a href="<?php echo e($delfLevelsUrl); ?>" class="text-[#0055A4] font-semibold hover:underline">
                            <?php echo e($isFr ? 'Tous les niveaux : le DELF, de A1 à B2' : 'All levels: the DELF, A1 to B2'); ?> →
                        </a>
                    </p>
                </div>
            </div>
        </main>

        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</body>

</html>
<?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/delf-a2.blade.php ENDPATH**/ ?>