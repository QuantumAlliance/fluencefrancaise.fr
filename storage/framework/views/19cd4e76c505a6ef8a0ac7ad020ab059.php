<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    $isFr = request()->is('fr*');

    $title = $isFr
        ? "DELF B1 : épreuves, notation et préparation en ligne"
        : "DELF B1: Exam Format, Scoring and Online Prep";

    $metaDesc = $isFr
        ? "DELF B1 : 4 épreuves, environ 2 h d'écrit et 15 min d'oral, diplôme à vie accepté pour la carte de résident. Pièges à éviter et préparation en ligne."
        : "DELF B1: 4 sections, about 2 hours written plus a 15-min oral, a lifetime diploma accepted for the 10-year resident card. Common traps, online prep.";

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/delf-b1/' : 'https://fluencefrancaise.fr/en/delf-b1/';
    $frUrl = 'https://fluencefrancaise.fr/fr/delf-b1/';
    $enUrl = 'https://fluencefrancaise.fr/en/delf-b1/';

    $residentUrl = $isFr ? '/fr/titre-de-sejour-niveau-francais/' : '/en/french-residence-permit-language/';
    $citizenshipUrl = $isFr ? '/fr/test-francais-naturalisation/' : '/en/french-citizenship-language-test/';
    $cPermitUrl = $isFr ? '/fr/permis-c-suisse/' : '/en/c-permit-switzerland/';
    $swissUrl = $isFr ? '/fr/naturalisation-suisse/' : '/en/swiss-citizenship/';
    $civicUrl = $isFr ? '/fr/examen-civique/' : '/en/french-civic-exam/';
    $compareUrl = $isFr ? '/fr/tcf-tef-ou-delf/' : '/en/tcf-vs-tef-vs-delf/';
    $delfB2Url = $isFr ? '/fr/delf-b2/' : '/en/delf-b2/';
    $delfA2Url = $isFr ? '/fr/delf-a2/' : '/en/delf-a2/';
    $delfUrl = $isFr ? '/fr/delf/' : '/en/delf/';
    $fideUrl = $isFr ? '/fr/test-fide/' : '/en/fide-test/';
    $contactUrl = $isFr ? '/fr/contact/' : '/en/contact/';
    $referralUrl = $isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral';
    $link = 'class="font-semibold text-[#0055A4] hover:underline"';

    $faqs = $isFr ? [
        [
            'q' => 'Le DELF B1 est-il valable pour la carte de résident ?',
            'a' => 'Oui. La carte de résident exige le B1 depuis le 1er janvier 2026, et le DELF B1 le prouve à vie.',
        ],
        [
            'q' => 'Le DELF B1 suffit-il pour la naturalisation ?',
            'a' => "Seulement pour les dossiers déposés avant le 1er janvier 2026. Pour une nouvelle demande, il faut le B2 : visez le <a href=\"{$delfB2Url}\" {$link}>DELF B2</a>.",
        ],
        [
            'q' => 'Quelle note faut-il pour obtenir le DELF B1 ?',
            'a' => "50 sur 100 au total, avec au moins 5 sur 25 dans chaque épreuve. Sous 5 dans une seule épreuve, le diplôme n'est pas délivré.",
        ],
        [
            'q' => "Combien de temps dure l'examen ?",
            'a' => 'Environ 2 heures pour les 3 épreuves collectives, puis 15 minutes d\'oral après 10 minutes de préparation.',
        ],
        [
            'q' => 'Existe-t-il une formation DELF B1 gratuite ?',
            'a' => "France Éducation international publie gratuitement des exemples de sujets, utiles pour découvrir le format. Ce qu'ils ne donnent pas, c'est la correction de vos textes et de votre oral.",
        ],
        [
            'q' => 'Le DELF B1 est-il accepté en Suisse ?',
            'a' => "Oui. Un <a href=\"{$delfUrl}\" {$link}>diplôme DELF</a> est accepté comme alternative au <a href=\"{$fideUrl}\" {$link}>test fide</a> pour prouver votre niveau.",
        ],
        [
            'q' => 'Fluence Française fait-elle passer le DELF ?',
            'a' => "Non. Nous préparons au diplôme ; l'inscription se fait auprès d'un centre d'examen agréé.",
        ],
    ] : [
        [
            'q' => 'Is the DELF B1 valid for the 10-year resident card?',
            'a' => 'Yes. The resident card has required B1 since 1 January 2026, and the DELF B1 proves it for life.',
        ],
        [
            'q' => 'Is the DELF B1 enough for citizenship?',
            'a' => "Only for applications filed before 1 January 2026. New applications need B2, so aim for the <a href=\"{$delfB2Url}\" {$link}>DELF B2</a>.",
        ],
        [
            'q' => 'What score do you need to pass the DELF B1?',
            'a' => '50 out of 100 overall, with at least 5 out of 25 in every section. Below 5 in a single section, no diploma.',
        ],
        [
            'q' => 'How long is the DELF B1 exam?',
            'a' => 'About 2 hours for the 3 written sections, then a 15-minute oral after 10 minutes of prep.',
        ],
        [
            'q' => 'Are there free DELF B1 sample papers?',
            'a' => "Yes. France Éducation international publishes free sample papers that show the format. What they can't give you is feedback on your own writing and speaking.",
        ],
        [
            'q' => 'Is the DELF B1 accepted in Switzerland?',
            'a' => "Yes. A <a href=\"{$delfUrl}\" {$link}>DELF diploma</a> is accepted as an alternative to the <a href=\"{$fideUrl}\" {$link}>fide test</a> to prove your level.",
        ],
        [
            'q' => 'Does Fluence Française run the DELF?',
            'a' => 'No. We prepare you for the diploma. You register with an approved exam centre.',
        ],
    ];

    $glanceRows = $isFr ? [
        ['Pour qui', 'Carte de résident, permis C anticipé ou naturalisation en Suisse, études, travail'],
        ['Épreuves', 'Compréhension orale, compréhension écrite, production écrite, production orale'],
        ['Durée', "Environ 2 h d'écrit + 15 min d'oral (après 10 min de préparation)"],
        ['Réussite', '50/100, avec au moins 5/25 dans chaque épreuve'],
        ['Validité', 'À vie'],
    ] : [
        ["Who it's for", '10-year resident card, early C permit or citizenship in Switzerland, study, work'],
        ['Sections', 'Listening, reading, writing, speaking'],
        ['Duration', 'About 2 h written + a 15-min oral (after 10 min of prep)'],
        ['Pass mark', '50/100, with at least 5/25 in every section'],
        ['Validity', 'Life'],
    ];

    $sections = $isFr ? [
        ['fa-headphones', 'Compréhension orale', 'Vous répondez à des questions sur des documents audio', '25 min environ'],
        ['fa-book-open', 'Compréhension écrite', 'Vous répondez à des questions sur des textes', '45 min'],
        ['fa-pen', 'Production écrite', 'Vous écrivez un texte de 160 mots minimum, en suivant la consigne', '45 min'],
        ['fa-microphone', 'Production orale', 'Entretien sur vous, jeu de rôle, puis point de vue sur un document', '15 min environ'],
    ] : [
        ['fa-headphones', 'Listening', 'Answer questions on audio documents', 'About 25 min'],
        ['fa-book-open', 'Reading', 'Answer questions on texts', '45 min'],
        ['fa-pen', 'Writing', 'Write at least 160 words, following the task instructions', '45 min'],
        ['fa-microphone', 'Speaking', 'An interview about you, a role play, then your view on a document', 'About 15 min'],
    ];

    $traps = $isFr ? [
        [
            'title' => "L'entretien qui semble facile",
            'body' => "L'oral commence par des questions sur vous : votre vie, votre travail, vos projets. Beaucoup répondent en 1 phrase. L'examinateur n'a alors rien à évaluer.",
            'fix' => 'Des réponses développées sur 10 sujets personnels, avec des détails et des exemples.',
        ],
        [
            'title' => 'Le jeu de rôle subi',
            'body' => "Dans l'exercice d'interaction, vous devez obtenir quelque chose : convaincre, négocier, régler un problème. Se contenter de répondre à l'examinateur ne suffit pas.",
            'fix' => 'Des jeux de rôle où votre tuteur résiste, pour que vous appreniez à mener l\'échange.',
        ],
        [
            'title' => 'Un texte trop court ou au présent',
            'body' => 'La production écrite demande 160 mots minimum. Le correcteur évalue votre respect de la consigne, votre capacité à décrire des faits ou des expériences et à exprimer vos idées. Utilisez les temps du passé quand la consigne les demande.',
            'fix' => 'Un modèle en 3 temps — raconter, réagir, conclure — et un travail ciblé sur le passé composé et l\'imparfait.',
        ],
        [
            'title' => 'La compréhension écrite au chrono',
            'body' => 'La compréhension écrite dure 45 minutes, et il y a beaucoup à lire. Le temps reste serré pour qui lit mot à mot.',
            'fix' => "Des stratégies de lecture rapide : repérer la question, chercher l'information, avancer.",
        ],
    ] : [
        [
            'title' => 'The interview that seems easy',
            'body' => 'The oral opens with questions about you: your life, your job, your plans. Many candidates answer in 1 sentence, which leaves the examiner nothing to assess.',
            'fix' => 'Developed answers on 10 personal topics, with details and examples.',
        ],
        [
            'title' => 'Letting the role play happen to you',
            'body' => "In the interaction task, you have to get something done: persuade, negotiate, solve a problem. Just answering the examiner isn't enough.",
            'fix' => 'Role plays where your tutor pushes back, so you learn to lead the exchange.',
        ],
        [
            'title' => "A text that's too short, or all in the present",
            'body' => 'The writing task asks for at least 160 words. The examiner assesses how well you follow the task instructions, describe facts or experiences, and express your ideas. Use past tenses when the task requires them.',
            'fix' => 'A 3-step model — tell, react, conclude — plus focused work on the passé composé and the imparfait.',
        ],
        [
            'title' => 'Reading against the clock',
            'body' => "The reading section lasts 45 minutes, and there's a lot to read. Time stays tight if you read word by word.",
            'fix' => 'Fast-reading strategies: spot the question, find the information, move on.',
        ],
    ];

    $plan = $isFr ? [
        ['01', 'Vos 4 épreuves, mesurées', "Dès le premier cours, votre tuteur repère votre point fort et l'épreuve à sécuriser."],
        ['02', "Les 3 parties de l'oral, répétées", 'Entretien, jeu de rôle, point de vue : chacune a sa méthode.'],
        ['03', 'Des productions corrigées', 'Chaque texte revient avec ce qui vous coûte des points, et comment le corriger.'],
        ['04', 'Des cours en direct, 2 ou 4 h par semaine', 'En particulier ou en mini-groupe de 2 à 5 personnes, avec examens blancs dans votre espace élève.'],
    ] : [
        ['01', 'Your 4 sections, measured', 'In your first class, your tutor spots your strongest section and the one to secure.'],
        ['02', 'All 3 parts of the oral, rehearsed', 'Interview, role play, point of view: each has its own method.'],
        ['03', 'Corrected writing', "Every text comes back showing what's costing you marks, and how to fix it."],
        ['04', 'Live classes, 2 or 4 hours a week', '1:1 or in a group of 2–5, with mock exams in your student portal.'],
    ];

    $facts = $isFr
        ? ['Les 4 épreuves travaillées', 'Productions écrites corrigées', "Simulations des 3 parties de l'oral"]
        : ['All 4 sections covered', 'Corrected writing', 'Practice for all 3 parts of the oral'];

    $applications = $isFr ? [
        ['Carte de résident (France)', $residentUrl, 'B1', 'Oui', true],
        ['Naturalisation française', $citizenshipUrl, 'B2 depuis 2026', 'Non, sauf dossier déposé avant 2026', false],
        ['Permis C anticipé (Suisse)', $cPermitUrl, 'B1 oral, A1 écrit', 'Oui', true],
        ['Naturalisation suisse', $swissUrl, 'B1 oral, A2 écrit (minimum fédéral)', 'Oui, selon votre canton', true],
    ] : [
        ['10-year resident card (France)', $residentUrl, 'B1', 'Yes', true],
        ['French citizenship', $citizenshipUrl, 'B2 since 2026', 'No, unless filed before 2026', false],
        ['Early C permit (Switzerland)', $cPermitUrl, 'B1 spoken, A1 written', 'Yes', true],
        ['Swiss citizenship', $swissUrl, 'B1 spoken, A2 written (federal minimum)', 'Yes, depending on your canton', true],
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
                    { "@type": "ListItem", "position": 3, "name": "DELF B1", "item": "<?php echo e($canonicalUrl); ?>" }
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
            <!-- HERO -->
            <section class="relative overflow-hidden bg-white pt-28 sm:pt-36 md:pt-40 pb-12 border-b border-slate-100">
                <div class="hero-aurora"></div>
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="max-w-3xl mx-auto text-center">
                        <nav aria-label="Breadcrumb" class="mb-5 flex justify-center text-xs text-slate-400 font-medium items-center gap-2">
                            <a href="<?php echo e($isFr ? '/fr' : '/en'); ?>" class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Accueil' : 'Home'); ?></a>
                            <span class="text-slate-300">›</span>
                            <a href="<?php echo e($isFr ? '/fr#exams' : '/en#exams'); ?>" class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Examens' : 'Exams'); ?></a>
                            <span class="text-slate-300">›</span>
                            <span class="text-slate-700 font-semibold">DELF B1</span>
                        </nav>

                        <div class="inline-flex items-center justify-center gap-1.5 mb-5">
                            <span class="inline-block w-3.5 h-[3px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[3px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'VOTRE NIVEAU, RECONNU À VIE' : 'YOUR LEVEL, RECOGNISED FOR LIFE'); ?>

                            </span>
                        </div>

                        <h1 class="text-3xl sm:text-5xl lg:text-[52px] font-bold text-[#0c1e38] tracking-tight leading-[1.18]">
                            <?php echo e($isFr ? 'DELF B1 : validez le B1' : 'DELF B1: Prove B1'); ?>

                            <br>
                            <span class="relative inline-block text-[#0055A4] mt-1 pb-2.5 sm:pb-3">
                                <?php echo e($isFr ? 'une fois pour toutes' : 'Once and for All'); ?>

                                <span class="absolute left-0 right-0 bottom-0 h-[3px] rounded-full" style="background: linear-gradient(90deg, #0055A4 0%, #0055A4 33.3%, #eaebf0 33.3%, #eaebf0 66.6%, #EF4135 66.6%, #EF4135 100%);"></span>
                            </span>
                        </h1>

                        <p class="mt-6 text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
                            <?php echo e($isFr
                                ? 'Le DELF B1 prouve le niveau de la carte de résident pour toujours : nous vous préparons aux 4 épreuves, en direct avec un tuteur.'
                                : "The DELF B1 proves the level for France's 10-year resident card for good, and we prepare you for all 4 sections, live with a tutor."); ?>

                        </p>

                        <div class="mt-8">
                            <a href="/register" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055A4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all">
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
                            <span class="text-[#0055A4] text-[8px] align-middle">●</span>
                            <?php echo e($isFr ? 'Exigences 2026' : '2026 requirements'); ?>

                            <span class="text-slate-300 mx-1.5">/</span>
                            <?php echo e($isFr ? 'Mis à jour le 11 novembre 2026' : 'Updated 11 November 2026'); ?>

                        </p>

                        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-xs sm:text-sm font-medium text-slate-600">
                            <?php $__currentLoopData = $facts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas fa-check text-[#0055A4] text-[11px]"></i>
                                    <?php echo e($fact); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-xs sm:text-sm text-slate-500">
                        <span class="font-semibold text-slate-400"><?php echo e($isFr ? 'Sur cette page' : 'On this page'); ?></span>
                        <a href="#at-a-glance" class="hover:text-[#0055A4]"><?php echo e($isFr ? 'En bref' : 'At a glance'); ?></a>
                        <a href="#sections" class="hover:text-[#0055A4]"><?php echo e($isFr ? 'Format' : 'Test format'); ?></a>
                        <a href="#prepare" class="hover:text-[#0055A4]"><?php echo e($isFr ? 'Notre préparation' : 'Our preparation'); ?></a>
                        <a href="#applications" class="hover:text-[#0055A4]"><?php echo e($isFr ? 'Démarches' : 'Applications'); ?></a>
                        <a href="#faq" class="hover:text-[#0055A4]">FAQ</a>
                        <a href="#enroll" class="font-semibold text-[#0055A4]"><?php echo e($isFr ? 'Commencer' : 'Start preparing'); ?> →</a>
                    </div>
                </div>
            </section>

            <!-- AT A GLANCE -->
            <section id="at-a-glance" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <div class="lg:col-span-7">
                            <div class="inline-flex items-center gap-1.5 mb-3">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? "CONNAÎTRE L'EXAMEN" : 'KNOW YOUR EXAM'); ?></span>
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                <?php echo e($isFr ? 'Le DELF B1 en bref' : 'The DELF B1 at a glance'); ?>

                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <?php echo e($isFr
                                    ? "Le DELF B1 est un diplôme du ministère de l'Éducation nationale, délivré via France Éducation international. Il valide le niveau B1 sur 4 épreuves de 25 points chacune. Il s'obtient avec 50 sur 100, sans aucune épreuve sous 5 sur 25. Il est valable à vie."
                                    : "The DELF B1 is a French Ministry of Education diploma, awarded through France Éducation international. It certifies B1 across 4 sections worth 25 points each. You pass with 50 out of 100 and no section below 5 out of 25. It's valid for life."); ?>

                            </p>
                            <p class="mt-4 inline-flex items-center gap-2 text-xs sm:text-sm text-slate-500 font-medium">
                                <i class="fas fa-shield-halved text-[#0055A4]"></i>
                                <?php echo e($isFr ? "Préparation en direct en ligne. Confiance totale le jour de l'examen." : 'Live online preparation. Real exam-day confidence.'); ?>

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
                                        <div class="text-xs text-blue-100/80 mt-1"><?php echo e($isFr ? 'Épreuves, 25 points chacune' : 'Sections, 25 points each'); ?></div>
                                    </div>
                                    <div class="p-6">
                                        <i class="fas fa-bullseye text-blue-200/80 text-sm"></i>
                                        <div class="mt-2 text-3xl font-extrabold">50/100</div>
                                        <div class="text-xs text-blue-100/80 mt-1"><?php echo e($isFr ? 'Note pour réussir' : 'Overall pass mark'); ?></div>
                                    </div>
                                    <div class="p-6">
                                        <i class="fas fa-clock text-blue-200/80 text-sm"></i>
                                        <div class="mt-2 text-3xl font-extrabold">15 min</div>
                                        <div class="text-xs text-blue-100/80 mt-1"><?php echo e($isFr ? 'Oral, après 10 min de préparation' : 'Oral, after 10-min prep'); ?></div>
                                    </div>
                                    <div class="p-6">
                                        <i class="fas fa-infinity text-blue-200/80 text-sm"></i>
                                        <div class="mt-2 text-3xl font-extrabold"><?php echo e($isFr ? 'À vie' : 'For life'); ?></div>
                                        <div class="text-xs text-blue-100/80 mt-1"><?php echo e($isFr ? 'Validité du diplôme' : 'Diploma validity'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 rounded-2xl border border-slate-200/80 overflow-hidden">
                        <?php $__currentLoopData = $glanceRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-1 sm:gap-4 px-5 sm:px-7 py-4 text-sm <?php echo e($index % 2 === 0 ? 'bg-[#f7f9fc]' : 'bg-white'); ?> <?php echo e($index < count($glanceRows) - 1 ? 'border-b border-slate-100' : ''); ?>">
                                <div class="sm:col-span-3 font-semibold text-[#10233e]"><?php echo e($row[0]); ?></div>
                                <div class="sm:col-span-9 text-slate-600"><?php echo e($row[1]); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <!-- CITIZENSHIP -->
            <section class="py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <div class="lg:col-span-6">
                            <div class="inline-flex items-center gap-1.5 mb-3">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'LE BON DIPLÔME POUR VOTRE DOSSIER' : 'THE RIGHT DIPLOMA FOR YOUR APPLICATION'); ?></span>
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                <?php echo e($isFr ? 'Le DELF B1 suffit-il pour la nationalité française ?' : 'Is the DELF B1 enough for French citizenship?'); ?>

                            </h2>
                            <div class="mt-4 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <p>
                                    <?php echo e($isFr
                                        ? 'Plus pour les nouvelles demandes. Depuis le 1er janvier 2026, la naturalisation exige le B2. Le DELF B1 reste valable pour les dossiers déposés avant cette date, et pour la carte de résident, qui demande le B1.'
                                        : 'Not for new applications. Since 1 January 2026, citizenship requires B2. The DELF B1 still counts for applications filed before that date, and for the 10-year resident card, which requires B1.'); ?>

                                </p>
                                <p>
                                    <?php echo e($isFr
                                        ? "Si vous lisez encore « diplôme B1 pour la nationalité », l'information est ancienne. Préparer le B1 pour une naturalisation aujourd'hui, c'est viser un niveau en dessous de celui exigé."
                                        : "If you still see “B1 diploma for French nationality” online, that information is out of date. Preparing B1 for a citizenship application today means aiming below the required level."); ?>

                                </p>
                                <p>
                                    <?php if($isFr): ?>
                                        Vous visez la nationalité ? Passez directement au <a href="<?php echo e($delfB2Url); ?>" class="font-semibold text-[#0055A4] hover:underline">DELF B2</a> ou lisez le <a href="<?php echo e($citizenshipUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">test de français pour la naturalisation</a>.
                                    <?php else: ?>
                                        Going for citizenship? Go straight to the <a href="<?php echo e($delfB2Url); ?>" class="font-semibold text-[#0055A4] hover:underline">DELF B2</a> or read about the <a href="<?php echo e($citizenshipUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">French citizenship language test</a>.
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="lg:col-span-6">
                            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 grid grid-cols-2 gap-6">
                                <div>
                                    <p class="text-[10px] sm:text-[11px] font-bold tracking-[0.16em] uppercase text-slate-400"><?php echo e($isFr ? 'Carte de résident' : '10-year resident card'); ?></p>
                                    <p class="mt-3 text-5xl font-extrabold text-[#0055A4]">B1</p>
                                    <p class="mt-2 text-xs sm:text-sm text-slate-500"><?php echo e($isFr ? 'Votre diplôme le prouve à vie.' : 'Your diploma proves it for life.'); ?></p>
                                    <span class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-blue-50 text-[#0055A4] px-3 py-1 text-[11px] font-semibold">
                                        <i class="fas fa-check text-[9px]"></i>
                                        <?php echo e($isFr ? 'DELF B1 accepté' : 'DELF B1 accepted'); ?>

                                    </span>
                                </div>
                                <div class="border-l border-slate-100 pl-6">
                                    <p class="text-[10px] sm:text-[11px] font-bold tracking-[0.16em] uppercase text-slate-400"><?php echo e($isFr ? 'Nouvelles demandes de naturalisation' : 'New French citizenship applications'); ?></p>
                                    <p class="mt-3 text-5xl font-extrabold text-[#0055A4]">B2</p>
                                    <p class="mt-2 text-xs sm:text-sm text-slate-500"><?php echo e($isFr ? 'Le niveau exigé depuis 2026.' : 'The level required since 2026.'); ?></p>
                                    <a href="<?php echo e($delfB2Url); ?>" class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-[#0055A4] hover:underline">
                                        <?php echo e($isFr ? 'Voir le DELF B2' : 'Explore DELF B2'); ?> →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4 SECTIONS -->
            <section id="sections" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? "LE JOUR DE L'EXAMEN" : 'KNOW WHAT EXAM DAY LOOKS LIKE'); ?></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            <?php echo e($isFr ? 'Les 4 épreuves du DELF B1' : 'The 4 sections of the DELF B1'); ?>

                        </h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="rounded-2xl border border-slate-200/80 bg-white p-5 flex flex-col">
                                <div class="flex items-start justify-between gap-3">
                                    <span class="w-9 h-9 rounded-full bg-blue-50 text-[#0055A4] flex items-center justify-center">
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
                            ? "Chaque épreuve est notée sur 25. Les 3 premières se passent ensemble, l'oral se passe à part."
                            : 'Each section is marked out of 25. The first 3 are taken together; the oral is separate.'); ?>

                    </p>
                </div>
            </section>

            <!-- TRAPS -->
            <section class="py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="max-w-3xl mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'ÉVITER LES PIÈGES FRÉQUENTS' : 'AVOID THE COMMON TRAPS'); ?></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Où les candidats perdent des points au DELF B1' : 'Where DELF B1 candidates lose points'); ?>

                        </h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <?php $__currentLoopData = $traps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 flex flex-col">
                                <span class="text-sm font-bold text-[#0055A4]"><?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                                <h3 class="mt-2 text-lg font-bold text-[#10233e]"><?php echo e($trap['title']); ?></h3>
                                <p class="mt-2 text-sm text-slate-500 leading-relaxed flex-1"><?php echo e($trap['body']); ?></p>
                                <div class="mt-5 rounded-2xl bg-[#f2f7fc] border border-blue-100/70 px-4 py-3.5">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#0055A4] mb-1"><?php echo e($isFr ? 'Ce que nous faisons' : 'What we do'); ?></p>
                                    <p class="text-xs sm:text-[13px] text-slate-600 leading-relaxed"><?php echo e($trap['fix']); ?></p>
                                </div>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <!-- PREPARE -->
            <section id="prepare" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'UN ENSEIGNEMENT EN DIRECT. UN PLAN QUI VOUS CORRESPOND.' : 'LIVE TEACHING. A PLAN THAT FITS YOU.'); ?></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            <?php echo e($isFr ? 'Notre préparation au DELF B1' : 'How we prepare you for the DELF B1'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500">
                            <?php echo e($isFr
                                ? "Un accompagnement ciblé dès votre premier cours jusqu'à l'examen. Pas de programme générique. Pas d'apprentissage seul."
                                : 'Focused support from your first class to exam day. No generic syllabus. No learning alone.'); ?>

                        </p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <?php $__currentLoopData = $plan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <div class="flex items-center mb-4">
                                    <span class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055A4] text-sm font-semibold flex items-center justify-center border border-blue-100 shrink-0"><?php echo e($step[0]); ?></span>
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
                        <a href="/register" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055A4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all">
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

            <!-- APPLICATIONS -->
            <section id="applications" class="scroll-mt-28 py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'VOTRE PROCHAINE ÉTAPE, CLAIREMENT' : 'YOUR NEXT STEP, CLEARLY EXPLAINED'); ?></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            <?php echo e($isFr ? 'Le DELF B1 pour quelle démarche ?' : 'Which applications does the DELF B1 cover?'); ?>

                        </h2>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 overflow-hidden bg-white">
                        <div class="hidden sm:grid sm:grid-cols-12 bg-[#0055A4] text-white px-6 py-3.5 text-sm font-semibold">
                            <div class="sm:col-span-5"><?php echo e($isFr ? 'Démarche' : 'Application'); ?></div>
                            <div class="sm:col-span-4"><?php echo e($isFr ? 'Niveau exigé' : 'Level required'); ?></div>
                            <div class="sm:col-span-3 sm:text-right"><?php echo e($isFr ? 'Le DELF B1 suffit ?' : 'Is DELF B1 enough?'); ?></div>
                        </div>
                        <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-1 sm:gap-4 px-5 sm:px-6 py-4 text-sm <?php echo e($index % 2 === 0 ? 'bg-white' : 'bg-[#f7f9fc]'); ?> <?php echo e($index < count($applications) - 1 ? 'border-b border-slate-100' : ''); ?>">
                                <div class="sm:col-span-5 font-semibold">
                                    <a href="<?php echo e($app[1]); ?>" class="text-[#0055A4] hover:underline"><?php echo e($app[0]); ?> →</a>
                                </div>
                                <div class="sm:col-span-4 text-slate-600"><?php echo e($app[2]); ?></div>
                                <div class="sm:col-span-3 sm:text-right font-semibold <?php echo e($app[4] ? 'text-emerald-600' : 'text-[#EF4135]'); ?>"><?php echo e($app[3]); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <p class="mt-5 text-center text-xs sm:text-sm text-slate-500 max-w-3xl mx-auto leading-relaxed">
                        <?php if($isFr): ?>
                            En France, la plupart des premières cartes de résident demandent aussi <a href="<?php echo e($civicUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">l'examen civique</a> depuis 2026. Vous hésitez avec un test ? <a href="<?php echo e($compareUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">Comparez le TCF, le TEF et le DELF</a>.
                        <?php else: ?>
                            In France, most first resident card applications now also need the <a href="<?php echo e($civicUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">civic exam</a>. Weighing it against a test? <a href="<?php echo e($compareUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">Compare the TCF, TEF and DELF</a>.
                        <?php endif; ?>
                    </p>
                </div>
            </section>

            <?php echo $__env->make('partials.faq-section', [
                'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                'title' => $isFr ? 'Questions fréquentes<br>sur le DELF B1' : 'DELF B1<br>FAQ',
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
                        ? 'Votre premier cours mesure vos 4 épreuves face au B1 et fixe le plan jusqu’à la session. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                        : 'Your first class measures your 4 sections against B1 and sets the plan up to your session. You choose the pace: 2 or 4 hours a week.',
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

                        <a href="<?php echo e($referralUrl); ?>" class="text-[#0055A4] font-semibold hover:underline ml-1"><?php echo e($isFr ? 'Contactez-nous' : 'Contact us'); ?> →</a>
                    </p>
                    <p class="mt-2 text-sm text-slate-500">
                        <?php echo e($isFr ? 'Vous partez de plus bas ?' : 'Starting from further back?'); ?>

                        <a href="<?php echo e($delfA2Url); ?>" class="text-[#0055A4] font-semibold hover:underline ml-1"><?php echo e($isFr ? 'Voir le DELF A2' : 'See the DELF A2'); ?> →</a>
                    </p>
                    <div class="my-6 border-t border-slate-100"></div>
                    <div class="flex flex-wrap items-center justify-center gap-y-2 text-xs sm:text-[13px] text-slate-400">
                        <span class="font-bold tracking-[0.18em] uppercase mr-3">SOURCES</span>
                        <a href="https://www.france-education-international.fr/delf-dalf" target="_blank" rel="noopener" class="hover:text-slate-600">France Éducation international – DELF B1 ↗</a>
                        <span class="mx-2.5 text-slate-300">·</span>
                        <a href="https://www.service-public.fr/particuliers/vosdroits/F2213" target="_blank" rel="noopener" class="hover:text-slate-600">Service-public.fr – <?php echo e($isFr ? 'naturalisation' : 'citizenship'); ?> ↗</a>
                        <span class="mx-2.5 text-slate-300">·</span>
                        <a href="https://www.service-public.fr/particuliers/vosdroits/N110" target="_blank" rel="noopener" class="hover:text-slate-600">Service-public.fr – <?php echo e($isFr ? 'cartes de séjour' : 'residence cards'); ?> ↗</a>
                    </div>
                </div>
            </div>
        </main>

        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</body>

</html>
<?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/delf-b1.blade.php ENDPATH**/ ?>