<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    $isFr = request()->is('fr*');

    $title = $isFr
        ? "DELF B2 : épreuves, notation et préparation en ligne"
        : "DELF B2: Exam Format, Scoring and Online Prep";

    $metaDesc = $isFr
        ? "DELF B2 : 4 épreuves, 50/100 pour réussir, diplôme valable à vie et accepté pour la naturalisation. Les pièges de chaque épreuve et préparation en ligne."
        : "DELF B2: 4 sections, 50/100 to pass, a lifetime diploma accepted for French citizenship. See a corrected B2 essay and prepare live online with a tutor.";

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/delf-b2/' : 'https://fluencefrancaise.fr/en/delf-b2/';
    $frUrl = 'https://fluencefrancaise.fr/fr/delf-b2/';
    $enUrl = 'https://fluencefrancaise.fr/en/delf-b2/';

    $residentUrl = $isFr ? '/fr/titre-de-sejour-niveau-francais/' : '/en/french-residence-permit-language/';
    $citizenshipUrl = $isFr ? '/fr/test-francais-naturalisation/' : '/en/french-citizenship-language-test/';
    $swissUrl = $isFr ? '/fr/naturalisation-suisse/' : '/en/swiss-citizenship/';
    $civicUrl = $isFr ? '/fr/examen-civique/' : '/en/french-civic-exam/';
    $compareUrl = $isFr ? '/fr/tcf-tef-ou-delf/' : '/en/tcf-vs-tef-vs-delf/';
    $delfB1Url = $isFr ? '/fr/delf-b1/' : '/en/delf-b1/';
    $delfUrl = $isFr ? '/fr/delf/' : '/en/delf/';
    $contactUrl = $isFr ? '/fr/contact/' : '/en/contact/';
    $referralUrl = $isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral';
    $samplesUrl = 'https://www.france-education-international.fr/diplome/delf-tout-public/niveau-b2/exemples-sujets';
    $linkClass = 'font-semibold text-[#0055A4] hover:underline';

    $faqs = $isFr ? [
        [
            'q' => 'Le DELF B2 est-il accepté pour la naturalisation ?',
            'a' => 'Oui. Il prouve le niveau B2 exigé depuis le 1er janvier 2026, et il n\'expire pas. Contrairement au TCF ou au TEF, vous n\'avez pas à le repasser si votre dossier tarde.',
        ],
        [
            'q' => 'Quelle note faut-il pour obtenir le DELF B2 ?',
            'a' => '50 sur 100 au total, avec au moins 5 sur 25 dans chacune des 4 épreuves. Sous 5 dans une seule épreuve, le diplôme n\'est pas délivré.',
        ],
        [
            'q' => 'Qu\'est-ce qu\'un bon score au DELF B2 ?',
            'a' => 'Le diplôme s\'obtient dès 50 sur 100, et c\'est le diplôme qui compte pour vos démarches. En entraînement, visez plutôt 60 : cela vous laisse une marge le jour de l\'examen.',
        ],
        [
            'q' => 'Combien de temps dure l\'examen ?',
            'a' => '2 h 30 pour les 3 épreuves collectives, puis 20 minutes d\'oral après 30 minutes de préparation.',
        ],
        [
            'q' => 'Existe-t-il une formation DELF B2 gratuite ?',
            'a' => 'France Éducation international publie gratuitement des <a href="' . $samplesUrl . '" target="_blank" rel="noopener" class="' . $linkClass . '">exemples de sujets</a>. Ils montrent le format. Ce qui leur manque, c\'est la correction de vos propres textes et de votre oral, et c\'est là que se gagnent les points.',
        ],
        [
            'q' => 'Où et quand passer le DELF B2 ?',
            'a' => 'Dans un centre d\'examen agréé (Alliances françaises, universités, instituts), en France ou à l\'étranger. Chaque centre fixe ses dates de session : renseignez-vous tôt pour caler votre préparation.',
        ],
        [
            'q' => 'Fluence Française fait-elle passer le DELF ?',
            'a' => 'Non. Nous préparons au diplôme ; l\'inscription se fait auprès d\'un centre d\'examen agréé.',
        ],
    ] : [
        [
            'q' => 'Is the DELF B2 accepted for French citizenship?',
            'a' => 'Yes. It proves the B2 level required since 1 January 2026, and it doesn\'t expire. Unlike the TCF or TEF, you won\'t need to retake it if your application drags on.',
        ],
        [
            'q' => 'What score do you need to pass the DELF B2?',
            'a' => '50 out of 100 overall, with at least 5 out of 25 in each of the 4 sections. Below 5 in a single section, no diploma.',
        ],
        [
            'q' => 'What is a good DELF B2 score?',
            'a' => 'The diploma starts at 50 out of 100, and the diploma is what counts for your applications. In practice, aim for 60: it gives you a margin on exam day.',
        ],
        [
            'q' => 'How long is the DELF B2 exam?',
            'a' => '2 h 30 for the 3 written sections, then a 20-minute oral after 30 minutes of prep.',
        ],
        [
            'q' => 'Are there free DELF B2 sample papers?',
            'a' => 'Yes. France Éducation international publishes <a href="' . $samplesUrl . '" target="_blank" rel="noopener" class="' . $linkClass . '">free sample papers</a> that show the format. What they can\'t give you is feedback on your own writing and speaking, which is where the marks are won.',
        ],
        [
            'q' => 'Where and when can I take the DELF B2?',
            'a' => 'At an approved exam centre (Alliances Françaises, universities, institutes), in France or abroad. Each centre sets its own session dates, so check early to plan your prep.',
        ],
        [
            'q' => 'Does Fluence Française run the DELF?',
            'a' => 'No. We prepare you for the diploma. You register with an approved exam centre.',
        ],
    ];

    $glanceRows = $isFr ? [
        ['Pour qui', 'Naturalisation, carte de résident, études, travail — en France comme en Suisse'],
        ['Épreuves', 'Compréhension orale, compréhension écrite, production écrite, production orale'],
        ['Durée', "2 h 30 d'épreuves écrites + 20 min d'oral (après 30 min de préparation)"],
        ['Réussite', '50/100, avec au moins 5/25 dans chaque épreuve'],
        ['Validité', 'À vie'],
    ] : [
        ["Who it's for", 'Citizenship, resident card, study, work — in France and Switzerland'],
        ['Sections', 'Listening, reading, writing, speaking'],
        ['Duration', '2 h 30 of written papers + a 20-min oral (after 30 min of prep)'],
        ['Pass mark', '50/100, with at least 5/25 in every section'],
        ['Validity', 'Life'],
    ];

    $sections = $isFr ? [
        ['fa-headphones', 'Compréhension orale', 'Vous répondez à des questions sur des documents audio', '/25'],
        ['fa-book-open', 'Compréhension écrite', 'Vous répondez à des questions sur des textes', '/25'],
        ['fa-pen', 'Production écrite', 'Vous prenez position dans un texte argumenté (lettre, article, contribution à un débat)', '/25'],
        ['fa-microphone', 'Production orale', 'Vous défendez un point de vue à partir d\'un court document, puis vous débattez avec l\'examinateur', '/25'],
    ] : [
        ['fa-headphones', 'Listening', 'Answer questions on audio documents', '/25'],
        ['fa-book-open', 'Reading', 'Answer questions on texts', '/25'],
        ['fa-pen', 'Writing', 'Take a position in an argued text (letter, article, contribution to a debate)', '/25'],
        ['fa-microphone', 'Speaking', 'Defend a point of view based on a short document, then debate it with the examiner', '/25'],
    ];

    $traps = $isFr ? [
        [
            'title' => 'La note éliminatoire',
            'body' => 'Avec 70 sur 100 au total mais 4 sur 25 en production écrite, vous n\'avez pas le diplôme. Une seule épreuve négligée suffit.',
            'fix' => 'Nous repérons votre épreuve la plus fragile dès le premier cours, et nous la sécurisons en priorité.',
        ],
        [
            'title' => 'Un texte qui ne prend pas position',
            'body' => 'Au B2, le correcteur attend une opinion claire, défendue avec des arguments et des exemples. Un texte qui présente « le pour et le contre » sans conclure risque de rester au B1.',
            'fix' => 'Un plan en 10 minutes — thèse, 2 arguments, 1 objection, 1 proposition — puis la rédaction.',
        ],
        [
            'title' => 'Un oral lu, pas parlé',
            'body' => 'Avec 30 minutes de préparation, la tentation est d\'écrire un texte et de le lire. L\'examinateur, lui, évalue votre capacité à parler et à réagir.',
            'fix' => 'Des notes en mots-clés seulement, et des simulations où votre tuteur vous contredit, comme le fera l\'examinateur.',
        ],
        [
            'title' => 'Le débat après l\'exposé',
            'body' => 'Après votre présentation, l\'examinateur discute vos idées. Il peut les contester pour voir si vous savez défendre votre position. Ce n\'est pas un piège : c\'est l\'épreuve.',
            'fix' => 'Des formules pour concéder, nuancer et maintenir votre avis, sans perdre le fil.',
        ],
    ] : [
        [
            'title' => 'The elimination mark',
            'body' => 'Score 70 out of 100 overall but 4 out of 25 in writing, and you don\'t get the diploma. 1 neglected section is enough.',
            'fix' => 'We identify your most fragile section in your first class and secure it first.',
        ],
        [
            'title' => 'An essay that doesn\'t take a side',
            'body' => 'At B2, the examiner expects a clear opinion, backed by arguments and examples. A text that lists pros and cons without concluding risks staying at B1.',
            'fix' => 'A 10-minute plan — thesis, 2 arguments, 1 objection, 1 proposal — then the writing.',
        ],
        [
            'title' => 'Reading your speaking notes aloud',
            'body' => 'With 30 minutes of prep, it\'s tempting to write a script and read it. But the examiner is assessing how you speak and react.',
            'fix' => 'Keyword-only notes, and simulations where your tutor pushes back, as the examiner will.',
        ],
        [
            'title' => 'The debate after your presentation',
            'body' => 'After you present, the examiner discusses your ideas and may challenge them to see if you can defend your position. It isn\'t a trap: it\'s the test.',
            'fix' => 'Phrases to concede, nuance and hold your view without losing the thread.',
        ],
    ];

    $plan = $isFr ? [
        ['01', 'Vos 4 épreuves, mesurées', 'Dès le premier cours, votre tuteur repère votre point fort et l\'épreuve à sécuriser.'],
        ['02', 'Des productions corrigées en détail', 'Chaque texte revient avec ce qui vous coûte des points, et comment le corriger.'],
        ['03', 'Des simulations d\'oral', 'Préparation de 30 minutes, exposé, puis débat avec votre tuteur dans le rôle de l\'examinateur.'],
        ['04', 'Des cours en direct, 2 ou 4 h par semaine', 'En particulier ou en mini-groupe de 2 à 5 personnes, avec examens blancs dans votre espace élève.'],
    ] : [
        ['01', 'Your 4 sections, measured', 'In your first class, your tutor spots your strongest section and the one to secure.'],
        ['02', 'Detailed essay corrections', 'Every text comes back showing what\'s costing you marks, and how to fix it.'],
        ['03', 'Speaking simulations', '30 minutes of prep, your presentation, then a debate with your tutor playing the examiner.'],
        ['04', 'Live classes, 2 or 4 hours a week', '1:1 or in a group of 2–5, with mock exams in your student portal.'],
    ];

    $facts = $isFr
        ? ['Les 4 épreuves travaillées', 'Productions écrites corrigées', 'Simulations d\'oral avec votre tuteur']
        : ['All 4 sections covered', 'Corrected essays', 'Speaking simulations with your tutor'];

    $spare = $isFr
        ? 'Oui, avec marge (ou le <a href="' . $delfB1Url . '" class="font-semibold text-[#0055A4] hover:underline">DELF B1</a>)'
        : 'Yes, with room to spare (or the <a href="' . $delfB1Url . '" class="font-semibold text-[#0055A4] hover:underline">DELF B1</a>)';

    $applications = $isFr ? [
        ['Naturalisation française', $citizenshipUrl, 'B2', 'Oui', false],
        ['Carte de résident', $residentUrl, 'B1', $spare, true],
        ['Naturalisation suisse', $swissUrl, 'B1 oral, A2 écrit (minimum fédéral)', 'Oui', false],
    ] : [
        ['French citizenship', $citizenshipUrl, 'B2', 'Yes', false],
        ['10-year resident card', $residentUrl, 'B1', $spare, true],
        ['Swiss citizenship', $swissUrl, 'B1 spoken, A2 written (federal minimum)', 'Yes', false],
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
                "provider": { "@type": "Organization", "name": "<?php echo e($siteName); ?>", "url": "https://fluencefrancaise.fr" },
                "courseMode": "online",
                "url": "<?php echo e($canonicalUrl); ?>"
            },
            {
                "@type": "BreadcrumbList",
                "itemListElement": [
                    { "@type": "ListItem", "position": 1, "name": "<?php echo e($isFr ? 'Accueil' : 'Home'); ?>", "item": "<?php echo e($isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en'); ?>" },
                    { "@type": "ListItem", "position": 2, "name": "<?php echo e($isFr ? 'Examens' : 'Exams'); ?>", "item": "<?php echo e($isFr ? 'https://fluencefrancaise.fr/fr#exams' : 'https://fluencefrancaise.fr/en#exams'); ?>" },
                    { "@type": "ListItem", "position": 3, "name": "DELF B2", "item": "<?php echo e($canonicalUrl); ?>" }
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
                            <a href="<?php echo e($isFr ? '/fr' : '/en'); ?>"
                                class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Accueil' : 'Home'); ?></a>
                            <span class="text-slate-300">›</span>
                            <a href="<?php echo e($isFr ? '/fr#exams' : '/en#exams'); ?>"
                                class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Examens' : 'Exams'); ?></a>
                            <span class="text-slate-300">›</span>
                            <span class="text-slate-700 font-semibold">DELF B2</span>
                        </nav>

                        <span class="eyebrow">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? 'LE DIPLÔME B2, À VIE' : 'THE B2 DIPLOMA, FOR LIFE'); ?>

                        </span>
                        <h1
                            class="text-3xl sm:text-5xl lg:text-[52px] font-bold text-[#0c1e38] tracking-tight leading-[1.18] mt-5">
                            <?php echo e($isFr ? 'DELF B2 : le diplôme B2' : 'DELF B2: The B2 Diploma'); ?>

                            <br>
                            <span class="relative inline-block text-[#0055A4] mt-1 pb-2.5 sm:pb-3">
                                <?php echo e($isFr ? 'qui n\'expire jamais' : 'That Never Expires'); ?>

                                <span class="absolute left-0 right-0 bottom-0 h-[3px] rounded-full"
                                    style="background: linear-gradient(90deg, #0055A4 0%, #0055A4 33.3%, #eaebf0 33.3%, #eaebf0 66.6%, #EF4135 66.6%, #EF4135 100%);"></span>
                            </span>
                        </h1>

                        <p class="mt-6 text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
                            <?php echo e($isFr
    ? 'Le DELF B2 prouve votre niveau pour la naturalisation, à vie : nous vous préparons aux 4 épreuves, avec corrections détaillées.'
    : 'The DELF B2 proves your level for French citizenship for life, and we prepare you for all 4 sections with detailed corrections.'); ?>

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

                        <p class="mt-4 text-xs font-semibold text-slate-400">
                            <?php echo e($isFr ? 'Mis à jour le 12 novembre 2026' : 'Updated 12 November 2026'); ?>

                        </p>

                        <div
                            class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-xs sm:text-sm font-medium text-slate-600">
                            <?php $__currentLoopData = $facts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas fa-check text-[#0055A4] text-[11px]"></i>
                                    <?php echo e($fact); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div
                        class="mt-8 flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-xs sm:text-sm text-slate-500">
                        <span
                            class="font-semibold text-slate-400"><?php echo e($isFr ? 'Sur cette page' : 'On this page'); ?></span>
                        <a href="#at-a-glance" class="hover:text-[#0055A4]"><?php echo e($isFr ? 'En bref' : 'At a glance'); ?></a>
                        <a href="#why"
                            class="hover:text-[#0055A4]"><?php echo e($isFr ? 'Pourquoi le diplôme' : 'Why a diploma'); ?></a>
                        <a href="#sections" class="hover:text-[#0055A4]"><?php echo e($isFr ? 'Format' : 'Test format'); ?></a>
                        <a href="#prepare"
                            class="hover:text-[#0055A4]"><?php echo e($isFr ? 'Notre préparation' : 'Our preparation'); ?></a>
                        <a href="#applications"
                            class="hover:text-[#0055A4]"><?php echo e($isFr ? 'Démarches' : 'Applications'); ?></a>
                        <a href="#faq" class="hover:text-[#0055A4]">FAQ</a>
                        <a href="#enroll"
                            class="font-semibold text-[#0055A4]"><?php echo e($isFr ? 'Commencer' : 'Start preparing'); ?> →</a>
                    </div>
                </div>
            </section>

            <section id="at-a-glance" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <div class="lg:col-span-7">
                            <span class="eyebrow">
                                <span class="tricolor w-12">
                                    <i></i>
                                    <i></i>
                                    <i></i>
                                </span>
                                <?php echo e($isFr ? "CONNAÎTRE L'EXAMEN" : 'KNOW YOUR EXAM'); ?>

                            </span>

                            <h2
                                class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15] mt-3">
                                <?php echo e($isFr ? 'Le DELF B2 en bref' : 'The DELF B2 at a glance'); ?>

                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <?php echo e($isFr
    ? "Le DELF B2 (Diplôme d'études en langue française) est un diplôme délivré par le ministère de l'Éducation nationale, via France Éducation international. Il valide le niveau B2 sur 4 épreuves notées sur 25 points chacune. Il s'obtient avec 50 sur 100, sans aucune épreuve sous 5 sur 25. Il est valable à vie."
    : "The DELF B2 (Diplôme d'études en langue française) is a diploma awarded by the French Ministry of Education through France Éducation international. It certifies B2 across 4 sections, each marked out of 25. You pass with 50 out of 100 and no section below 5 out of 25. It's valid for life."); ?>

                            </p>
                            <p
                                class="mt-4 inline-flex items-center gap-2 text-xs sm:text-sm text-slate-500 font-medium">
                                <i class="fas fa-shield-halved text-[#0055A4]"></i>
                                <?php echo e($isFr ? 'Préparation en direct en ligne. Confiance totale le jour de l\'examen.' : 'Live online preparation. Real exam-day confidence.'); ?>

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
                                        <i class="fas fa-layer-group text-blue-200/80 text-sm"></i>
                                        <div class="mt-2 text-3xl font-extrabold">4</div>
                                        <div class="text-xs text-blue-100/80 mt-1">
                                            <?php echo e($isFr ? 'Épreuves, 25 points chacune' : 'Sections, 25 points each'); ?>

                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <i class="fas fa-bullseye text-blue-200/80 text-sm"></i>
                                        <div class="mt-2 text-3xl font-extrabold">50/100</div>
                                        <div class="text-xs text-blue-100/80 mt-1">
                                            <?php echo e($isFr ? 'Note pour réussir' : 'Overall pass mark'); ?>

                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <i class="fas fa-shield-halved text-blue-200/80 text-sm"></i>
                                        <div class="mt-2 text-3xl font-extrabold">5/25</div>
                                        <div class="text-xs text-blue-100/80 mt-1">
                                            <?php echo e($isFr ? 'Minimum dans chaque épreuve' : 'Minimum in each section'); ?>

                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <i class="fas fa-infinity text-blue-200/80 text-sm"></i>
                                        <div class="mt-2 text-3xl font-extrabold"><?php echo e($isFr ? 'À vie' : 'For life'); ?>

                                        </div>
                                        <div class="text-xs text-blue-100/80 mt-1">
                                            <?php echo e($isFr ? 'Validité du diplôme' : 'Diploma validity'); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 rounded-2xl border border-slate-200/80 overflow-hidden">
                        <?php $__currentLoopData = $glanceRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div
                                class="grid grid-cols-1 sm:grid-cols-12 gap-1 sm:gap-4 px-5 sm:px-7 py-4 text-sm <?php echo e($index % 2 === 0 ? 'bg-[#f7f9fc]' : 'bg-white'); ?> <?php echo e($index < count($glanceRows) - 1 ? 'border-b border-slate-100' : ''); ?>">
                                <div class="sm:col-span-3 font-semibold text-[#10233e]"><?php echo e($row[0]); ?></div>
                                <div class="sm:col-span-9 text-slate-600"><?php echo e($row[1]); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <section id="why" class="scroll-mt-28 py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <div class="lg:col-span-7">
                            <span class="eyebrow">
                                <span class="tricolor w-12">
                                    <i></i>
                                    <i></i>
                                    <i></i>
                                </span>
                                <?php echo e($isFr ? 'UN DIPLÔME, PAS UN TEST' : 'A DIPLOMA, NOT A TEST RESULT'); ?>

                            </span>
                            <h2
                                class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15] mt-3">
                                <?php echo e($isFr ? 'Pourquoi le DELF B2 plutôt qu\'un test ?' : 'Why choose the DELF B2 over a test?'); ?>

                            </h2>
                            <div class="mt-4 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <p>
                                    <?php echo e($isFr
    ? 'Le TCF IRN et le TEF IRN donnent une attestation valable 2 ans. Le DELF B2 est un diplôme : une fois obtenu, il ne s\'arrête jamais de compter. Si votre dossier de naturalisation risque de prendre du temps, cette différence pèse lourd.'
    : 'The TCF IRN and TEF IRN give you results valid for 2 years. The DELF B2 is a diploma: once you have it, it counts forever. If your citizenship application could take a while, that difference matters.'); ?>

                                </p>
                                <p>
                                    <?php echo e($isFr
    ? 'La notation diffère aussi. Le TCF et le TEF donnent un niveau par compétence et un niveau global. Le DELF B2 se valide sur une note globale : un point fort peut compenser un point plus faible, tant qu\'aucune épreuve ne descend sous 5 sur 25.'
    : 'The scoring differs too. The TCF and TEF give a level for each skill and an overall level. The DELF B2 is passed on an overall score, so a strong section can make up for a weaker one, as long as none drops below 5 out of 25.'); ?>

                                </p>
                                <p>
                                    <?php echo e($isFr
    ? 'En contrepartie, le DELF est plus long, et ses sessions ont lieu à dates fixes dans chaque centre. Il se prépare donc avec un calendrier.'
    : 'The trade-off: the DELF is longer, and each centre runs it on fixed session dates. You prepare it with a calendar in mind.'); ?>

                                </p>
                            </div>
                        </div>
                        <div class="lg:col-span-5">
                            <div class="rounded-3xl bg-[#002654] text-white p-8 sm:p-10">
                                <p class="text-[11px] font-bold tracking-[0.18em] uppercase text-blue-200/80">
                                    <?php echo e($isFr ? 'Pour réussir' : 'To pass'); ?>

                                </p>
                                <p class="mt-3 text-5xl sm:text-6xl font-extrabold tracking-tight">50/100</p>
                                <p class="mt-4 text-sm sm:text-base text-blue-100/90 leading-relaxed">
                                    <?php echo e($isFr ? 'Avec au moins 5/25 dans chaque épreuve.' : 'With at least 5/25 in every section.'); ?>

                                </p>
                                <p class="mt-3 text-sm text-white/70 leading-relaxed">
                                    <?php echo e($isFr ? 'Sous 5 dans une seule épreuve, pas de diplôme.' : 'Below 5 in one section, no diploma.'); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="sections" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <span class="eyebrow">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? "LE JOUR DE L'EXAMEN" : 'KNOW WHAT EXAM DAY LOOKS LIKE'); ?>

                        </span>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight mt-3">
                            <?php echo e($isFr ? 'Les 4 épreuves du DELF B2' : 'The 4 sections of the DELF B2'); ?>

                        </h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="rounded-2xl border border-slate-200/80 bg-white p-5 flex flex-col">
                                <div class="flex items-start justify-between gap-3">
                                    <span
                                        class="w-9 h-9 rounded-full bg-blue-50 text-[#0055A4] flex items-center justify-center">
                                        <i class="fas <?php echo e($section[0]); ?> text-sm"></i>
                                    </span>
                                    <span class="text-[11px] font-semibold text-slate-400"><?php echo e($section[3]); ?></span>
                                </div>
                                <h3 class="mt-4 font-bold text-[#10233e]"><?php echo e($section[1]); ?></h3>
                                <p class="mt-1.5 text-sm text-slate-500 leading-relaxed"><?php echo e($section[2]); ?></p>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <p class="mt-5 rounded-2xl bg-[#f4f7fb] px-5 py-3.5 text-xs sm:text-sm text-slate-500 text-center">
                        <i class="fas fa-clock text-[#0055A4] mr-1.5"></i>
                        <?php echo e($isFr
    ? "Les 3 premières épreuves se passent ensemble, en 2 h 30. L'oral dure 20 minutes, après 30 minutes de préparation."
    : 'The first 3 sections are taken together, in 2 h 30. The speaking test lasts 20 minutes, after 30 minutes of prep.'); ?>

                    </p>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="max-w-3xl mb-10">
                        <span class="eyebrow">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? 'ÉVITER LES PIÈGES FRÉQUENTS' : 'AVOID THE COMMON TRAPS'); ?>

                        </span>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15] mt-3">
                            <?php echo e($isFr ? 'Où les candidats perdent des points au DELF B2' : 'Where DELF B2 candidates lose points'); ?>

                        </h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <?php $__currentLoopData = $traps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 flex flex-col">
                                <span
                                    class="text-sm font-bold text-[#0055A4]"><?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                                <h3 class="mt-2 text-lg font-bold text-[#10233e]"><?php echo e($trap['title']); ?></h3>
                                <p class="mt-2 text-sm text-slate-500 leading-relaxed flex-1"><?php echo e($trap['body']); ?></p>
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

            <section id="prepare" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12">
                        <span class="eyebrow">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? 'UN ENSEIGNEMENT EN DIRECT. UN PLAN QUI VOUS CORRESPOND.' : 'LIVE TEACHING. A PLAN THAT FITS YOU.'); ?>

                        </span>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight mt-3">
                            <?php echo e($isFr ? 'Notre préparation au DELF B2' : 'How we prepare you for the DELF B2'); ?>

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

            <section id="applications" class="scroll-mt-28 py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <span class="eyebrow">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? 'VOTRE PROCHAINE ÉTAPE, CLAIREMENT' : 'YOUR NEXT STEP, CLEARLY EXPLAINED'); ?>

                        </span>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight mt-3">
                            <?php echo e($isFr ? 'Le DELF B2 pour quelle démarche ?' : 'Which applications does the DELF B2 cover?'); ?>

                        </h2>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 overflow-hidden bg-white">
                        <div
                            class="hidden sm:grid sm:grid-cols-12 bg-[#0055A4] text-white px-6 py-3.5 text-sm font-semibold">
                            <div class="sm:col-span-5"><?php echo e($isFr ? 'Démarche' : 'Application'); ?></div>
                            <div class="sm:col-span-3"><?php echo e($isFr ? 'Niveau exigé' : 'Level required'); ?></div>
                            <div class="sm:col-span-4 sm:text-right">
                                <?php echo e($isFr ? 'Le DELF B2 suffit ?' : 'Is DELF B2 enough?'); ?>

                            </div>
                        </div>
                        <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div
                                class="grid grid-cols-1 sm:grid-cols-12 gap-1 sm:gap-4 px-5 sm:px-6 py-4 text-sm <?php echo e($index % 2 === 0 ? 'bg-white' : 'bg-[#f7f9fc]'); ?> <?php echo e($index < count($applications) - 1 ? 'border-b border-slate-100' : ''); ?>">
                                <div class="sm:col-span-5 font-semibold">
                                    <a href="<?php echo e($app[1]); ?>" class="text-[#0055A4] hover:underline"><?php echo e($app[0]); ?> →</a>
                                </div>
                                <div class="sm:col-span-3 text-slate-600"><?php echo e($app[2]); ?></div>
                                <div class="sm:col-span-4 sm:text-right font-semibold text-emerald-600"><?php echo $app[3]; ?>

                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <p class="mt-5 text-center text-xs sm:text-sm text-slate-500 max-w-3xl mx-auto leading-relaxed">
                        <?php if($isFr): ?>
                            En France, la naturalisation par décret et la plupart des premières cartes de séjour demandent
                            aussi <a href="<?php echo e($civicUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">l'examen
                                civique</a> depuis 2026. Vous hésitez avec un test ? <a href="<?php echo e($compareUrl); ?>"
                                class="font-semibold text-[#0055A4] hover:underline">Comparez le TCF, le TEF et le DELF</a>.
                        <?php else: ?>
                            In France, citizenship by decree and most first residence permits now also need the <a
                                href="<?php echo e($civicUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">civic exam</a>.
                            Weighing it against a test? <a href="<?php echo e($compareUrl); ?>"
                                class="font-semibold text-[#0055A4] hover:underline">Compare the TCF, TEF and DELF</a>.
                        <?php endif; ?>
                    </p>
                </div>
            </section>

            <?php echo $__env->make('partials.faq-section', [
                'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                'title' => $isFr ? 'Questions fréquentes<br>sur le DELF B2' : 'DELF B2<br>FAQ',
                'description' => $isFr ? "Des réponses claires avant de passer à l'étape suivante." : 'Clear answers before you take your next step.',
                'contactText' => $isFr ? 'Une autre question ?' : 'Have another question?',
                'contactUrl' => $contactUrl,
                'faqs' => $faqs,
                'sectionClass' => 'py-16 sm:py-20 bg-white',
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div id="enroll" class="scroll-mt-28">
                <?php echo $__env->make('partials.cta-banner', [
                    'sectionClass' => 'pt-4 pb-6 bg-white',
                    'eyebrow' => $isFr ? 'NOUS VOUS ACCOMPAGNONS' : "LET'S GET YOU THERE",
                    'title' => $isFr
                        ? "Votre niveau. Le niveau exigé.<br class='hidden sm:inline'> Nous comblons l'écart."
                        : "Your level. The required level.<br class='hidden sm:inline'> We close the gap.",
                    'description' => $isFr
                        ? 'Votre premier cours mesure vos 4 épreuves face au B2 et fixe le plan jusqu’à la session. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                        : 'Your first class measures your 4 sections against B2 and sets the plan up to your session. You choose the pace: 2 or 4 hours a week.',
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
                    <p class="mt-2 text-sm text-slate-500">
                        <a href="<?php echo e($delfUrl); ?>"
                            class="text-[#0055A4] font-semibold hover:underline"><?php echo e($isFr ? 'Tous les niveaux : le DELF, de A1 à B2' : 'All levels: the DELF, A1 to B2'); ?>

                            →</a>
                    </p>
                    <div class="my-6 border-t border-slate-100"></div>
                    <div
                        class="flex flex-wrap items-center justify-center gap-y-2 text-xs sm:text-[13px] text-slate-400">
                        <span
                            class="font-bold tracking-[0.18em] uppercase mr-3"><?php echo e($isFr ? 'SOURCES' : 'SOURCES'); ?></span>
                        <a href="https://www.france-education-international.fr/delf-dalf" target="_blank" rel="noopener"
                            class="hover:text-slate-600">France Éducation international – DELF B2 ↗</a>
                        <span class="mx-2.5 text-slate-300">·</span>
                        <a href="https://www.service-public.fr/particuliers/vosdroits/F2213" target="_blank"
                            rel="noopener" class="hover:text-slate-600">Service-public.fr –
                            <?php echo e($isFr ? 'naturalisation' : 'citizenship'); ?> ↗</a>
                    </div>
                </div>
            </div>
        </main>

        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</body>

</html><?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/delf-b2.blade.php ENDPATH**/ ?>