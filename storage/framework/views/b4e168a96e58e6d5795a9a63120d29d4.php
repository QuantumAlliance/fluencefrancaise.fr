<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    $isFr = request()->is('fr*');

    $title = $isFr
        ? 'Test fide en français : format, niveaux et préparation'
        : 'fide Test Preparation: Format, Levels, Online Classes';

    $metaDesc = $isFr
        ? 'Test fide en français : oral de 40 min, écrit de 60 min, niveaux A1 à B1. Le niveau exigé pour le permis C et la préparation en ligne avec un tuteur.'
        : 'The fide test in French: a 40-min spoken part, a 60-min written part, levels A1 to B1. C permit levels, common traps and live online prep with a tutor.';

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/test-fide/' : 'https://fluencefrancaise.fr/en/fide-test/';
    $frUrl = 'https://fluencefrancaise.fr/fr/test-fide/';
    $enUrl = 'https://fluencefrancaise.fr/en/fide-test/';

    $homeUrl = $isFr ? '/fr' : '/en';
    $cPermitUrl = $isFr ? '/fr/permis-c-suisse/' : '/en/c-permit-switzerland/';
    $citizenshipUrl = $isFr ? '/fr/naturalisation-suisse/' : '/en/swiss-citizenship/';
    $delfB1Url = $isFr ? '/fr/delf-b1/' : '/en/delf-b1/';
    $delfUrl = $isFr ? '/fr/delf/' : '/en/delf/';
    $contactUrl = $isFr ? '/fr/contact/' : '/en/contact/';
    $referralUrl = $isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral';
    $fideSource = 'https://fide-service.ch';
    $richmondSource = 'https://www.richmondchambers.ch/post/swiss-c-permit-language-requirements-a2-b1';

    $linkClass = 'text-[#0055A4] font-semibold hover:underline';

    $faqs = $isFr ? [
        [
            'q' => 'Le test fide est-il obligatoire pour le permis C ?',
            'a' => 'Vous devez prouver un niveau A2 à l\'oral et A1 à l\'écrit dans la langue de votre lieu de résidence. Le test fide est un moyen de le prouver. Un diplôme DELF ou DALF est aussi accepté, et certains parcours scolaires dispensent du test.',
        ],
        [
            'q' => 'Combien coûte le test fide ?',
            'a' => 'Jusqu\'à CHF 250 pour les 2 parties, jusqu\'à CHF 170 pour l\'oral seul et jusqu\'à CHF 120 pour l\'écrit seul. Ce sont des prix maximaux : chaque centre fixe son tarif.',
        ],
        [
            'q' => 'Quand reçoit-on les résultats ?',
            'a' => 'Sous 4 semaines après le test, par courrier, sous la forme d\'une notification des résultats. Le passeport des langues est établi si vous atteignez au moins A1 pour un test A1–A2 ou A2 pour un test A2–B1. Pour un test passé entre mi-décembre et mi-janvier, comptez plutôt 6 semaines.',
        ],
        [
            'q' => 'Peut-on passer seulement l\'oral ?',
            'a' => 'Oui. Les 2 parties se passent ensemble ou séparément. Choisissez selon le niveau que votre dossier exige à l\'oral et à l\'écrit.',
        ],
        [
            'q' => 'Existe-t-il un test fide blanc ou en ligne ?',
            'a' => 'Le test lui-même se passe dans un centre d\'examen. Le site officiel propose un test blanc à télécharger et une simulation de la partie orale pour découvrir le format. Nos cours ajoutent des mises en situation corrigées par votre tuteur.',
        ],
        [
            'q' => 'Le DELF remplace-t-il le test fide ?',
            'a' => 'Oui, un diplôme DELF ou DALF est accepté comme preuve de niveau. Avantage : c\'est un diplôme qui n\'expire pas. Voir le <a href="' . $delfB1Url . '" class="' . $linkClass . '">DELF B1</a> et <a href="' . $delfUrl . '" class="' . $linkClass . '">tous les niveaux du DELF</a>.',
        ],
        [
            'q' => 'Fluence Française fait-elle passer le test fide ?',
            'a' => 'Non. Nous vous préparons au test ; vous le passez dans un centre d\'examen fide.',
        ],
    ] : [
        [
            'q' => 'Do I need the fide test for a C permit?',
            'a' => 'You need to prove A2 spoken and A1 written in the language of your place of residence. The fide test is one way to do it. A DELF or DALF diploma is also accepted, and some schooling histories exempt you.',
        ],
        [
            'q' => 'How much does the fide test cost?',
            'a' => 'Up to CHF 250 for both parts, up to CHF 170 for the spoken part only and up to CHF 120 for the written part only. These are maximum prices: each centre sets its own fee.',
        ],
        [
            'q' => 'When do I get my results?',
            'a' => 'Within 4 weeks after the test, by post, as a results notification. A language passport is issued if you reach at least A1 on an A1–A2 test or A2 on an A2–B1 test. For a test taken between mid-December and mid-January, allow about 6 weeks.',
        ],
        [
            'q' => 'Can I take only the spoken part?',
            'a' => 'Yes. You can take the 2 parts together or separately. Choose based on the spoken and written levels your application requires.',
        ],
        [
            'q' => 'Is there a free fide practice test online?',
            'a' => 'The test itself is taken at a test centre. The official fide website offers a downloadable mock test and a speaking simulation, so you can see the format. Our classes add practice scenarios corrected by your tutor.',
        ],
        [
            'q' => 'Can the DELF replace the fide test?',
            'a' => 'Yes, a DELF or DALF diploma is accepted as proof of level. The advantage: it\'s a diploma that never expires. See the <a href="' . $delfB1Url . '" class="' . $linkClass . '">DELF B1</a> and <a href="' . $delfUrl . '" class="' . $linkClass . '">all DELF levels</a>.',
        ],
        [
            'q' => 'Does Fluence Française run the fide test?',
            'a' => 'No. We prepare you for it; you take it at a fide test centre.',
        ],
    ];

    $glance = $isFr ? [
        ['Pour qui', 'Personnes qui demandent un permis B ou C, ou la naturalisation'],
        ['Parties', 'Oral « Parler et comprendre » (40 min) · Écrit « Lire et écrire » (60 min)'],
        ['Niveaux', 'A1, A2, B1'],
        ['Prix', 'Jusqu\'à CHF 250 les 2 parties · jusqu\'à CHF 170 l\'oral seul · jusqu\'à CHF 120 l\'écrit seul'],
        ['Résultats', 'Notification des résultats par courrier sous 4 semaines ; passeport des langues si le niveau minimum est atteint'],
        ['Âge minimum', '16 ans'],
    ] : [
        ['Who it\'s for', 'People applying for a B or C permit, or Swiss citizenship'],
        ['Parts', 'Spoken, “Parler et comprendre” (40 min) · Written, “Lire et écrire” (60 min)'],
        ['Levels', 'A1, A2, B1'],
        ['Price', 'Up to CHF 250 for both parts · up to CHF 170 spoken only · up to CHF 120 written only'],
        ['Results', 'Results notification by post, within 4 weeks; a language passport if the required minimum level is reached'],
        ['Minimum age', '16'],
    ];

    $vocab = $isFr ? [
        ['déjeuner', 'petit-déjeuner', 'le repas du matin'],
        ['dîner', 'déjeuner', 'le repas de midi'],
        ['souper', 'dîner', 'le repas du soir'],
        ['septante, nonante', 'soixante-dix, quatre-vingt-dix', '70, 90'],
        ['huitante (Vaud, Valais, Fribourg)', 'quatre-vingts', '80'],
        ['natel', 'portable', 'téléphone mobile'],
        ['la régie', 'l\'agence immobilière', 'gérance de votre logement'],
    ] : [
        ['déjeuner', 'petit-déjeuner', 'breakfast'],
        ['dîner', 'déjeuner', 'lunch'],
        ['souper', 'dîner', 'dinner'],
        ['septante, nonante', 'soixante-dix, quatre-vingt-dix', '70, 90'],
        ['huitante (Vaud, Valais, Fribourg)', 'quatre-vingts', '80'],
        ['natel', 'portable', 'mobile phone'],
        ['la régie', 'l\'agence immobilière', 'your letting agency'],
    ];

    $facts = $isFr
        ? ['Oral et écrit préparés', 'Mises en situation de la vie en Suisse', 'En ligne, partout en Suisse romande']
        : ['Speaking and writing both covered', 'Real Swiss scenarios', 'Online, anywhere in French-speaking Switzerland'];

    $plan = $isFr ? [
        ['01', 'Le niveau de votre permis, vérifié', 'Permis C, permis C anticipé ou naturalisation : nous partons du niveau exigé, à l\'oral et à l\'écrit.'],
        ['02', 'Votre niveau, mesuré dès le premier cours', 'Votre tuteur situe votre oral et votre écrit face à ce niveau.'],
        ['03', 'Des mises en situation suisses', 'Commune, médecin, régie, école, travail : vous répétez les échanges du test avant de les vivre.'],
        ['04', 'Des cours en direct, 2 ou 4 h par semaine', 'En particulier ou en mini-groupe de 2 à 5 personnes, avec notes de cours et exercices dans votre espace élève.'],
    ] : [
        ['01', 'Your permit\'s level, checked', 'Ordinary C, early C or citizenship: we start from the level required, spoken and written.'],
        ['02', 'Your level, measured in your first class', 'Your tutor places your speaking and writing against that target.'],
        ['03', 'Swiss scenarios', 'Commune, doctor, letting agency, school, work: you rehearse the test\'s conversations before you have them for real.'],
        ['04', 'Live classes, 2 or 4 hours a week', '1:1 or in a group of 2–5, with class notes and practice in your student portal.'],
    ];

    $cities = ['Genève', 'Lausanne', 'Fribourg', 'Neuchâtel', 'Sion'];
?>
<html lang="<?php echo e($isFr ? 'fr' : 'en'); ?>" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo $__env->make('partials.favicon', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                "name": <?php echo json_encode($title, JSON_UNESCAPED_UNICODE); ?>,
                "description": <?php echo json_encode($metaDesc, JSON_UNESCAPED_UNICODE); ?>,
                "url": "<?php echo e($canonicalUrl); ?>",
                "provider": {
                    "@type": "EducationalOrganization",
                    "name": "<?php echo e($siteName); ?>",
                    "url": "https://fluencefrancaise.fr"
                },
                "courseMode": "online",
                "inLanguage": "<?php echo e($isFr ? 'fr' : 'en'); ?>"
            },
            {
                "@type": "BreadcrumbList",
                "itemListElement": [
                    { "@type": "ListItem", "position": 1, "name": "<?php echo e($isFr ? 'Accueil' : 'Home'); ?>", "item": "<?php echo e($isFr ? 'https://fluencefrancaise.fr/fr/' : 'https://fluencefrancaise.fr/en/'); ?>" },
                    { "@type": "ListItem", "position": 2, "name": "<?php echo e($isFr ? 'Suisse' : 'Switzerland'); ?>", "item": "<?php echo e($isFr ? 'https://fluencefrancaise.fr/fr/' : 'https://fluencefrancaise.fr/en/'); ?>" },
                    { "@type": "ListItem", "position": 3, "name": "<?php echo e($isFr ? 'Test fide' : 'fide test'); ?>", "item": "<?php echo e($canonicalUrl); ?>" }
                ]
            },
            {
                "@type": "FAQPage",
                "mainEntity": [
                    <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        {
                            "@type": "Question",
                            "name": <?php echo json_encode($faq['q'], JSON_UNESCAPED_UNICODE); ?>,
                            "acceptedAnswer": {
                                "@type": "Answer",
                                "text": <?php echo json_encode(trim(preg_replace('/\s+/', ' ', strip_tags($faq['a']))), JSON_UNESCAPED_UNICODE); ?>

                            }
                        }<?php if(!$loop->last): ?>,<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ]
            }
        ]
    }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->make('partials.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body class="bg-white text-slate-800 font-sans antialiased selection:bg-[#0055A4] selection:text-white">

    <div class="min-h-screen flex flex-col justify-between">
        <div>
            <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <section
                class="relative pt-28 sm:pt-36 md:pt-40 pb-16 md:pb-20 overflow-hidden bg-gradient-to-b from-[#f0f6ff]/70 via-white to-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl relative z-10 text-center">

                    <nav class="flex items-center justify-center gap-1.5 text-xs text-slate-500 mb-6 font-medium">
                        <a href="<?php echo e($homeUrl); ?>"
                            class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Accueil' : 'Home'); ?></a>
                        <span>›</span>
                        <span><?php echo e($isFr ? 'Suisse' : 'Switzerland'); ?></span>
                        <span>›</span>
                        <span class="text-slate-800 font-semibold"><?php echo e($isFr ? 'Test fide' : 'fide test'); ?></span>
                    </nav>

                    <span class="eyebrow">
                        <span class="tricolor w-12">
                            <i></i>
                            <i></i>
                            <i></i>
                        </span>
                        <?php echo e($isFr ? 'TEST FIDE · SUISSE ROMANDE' : 'FIDE TEST · FRENCH-SPEAKING SWITZERLAND'); ?>

                    </span>
                    <div class="flex flex-col items-center justify-center mt-5">
                        <h1
                            class="text-3xl sm:text-5xl lg:text-[56px] font-bold text-[#0c1e38] tracking-medium leading-[1.28]">
                            <?php echo e($isFr ? 'Test fide :' : 'fide Test:'); ?>

                            <span class="text-[#0055a4]"><?php echo e($isFr ? 'atteignez le niveau' : 'Reach the Level'); ?></span>
                            <br>
                            <span
                                class="text-[#0055a4]"><?php echo e($isFr ? 'exigé pour votre permis' : 'Your Permit Requires'); ?></span>
                        </h1>

                        <span class="block w-full max-w-xs sm:max-w-md h-[3px] mt-3 rounded-full"
                            style="background: linear-gradient(90deg, #0055A4 0%, #0055A4 33.3%, #eaebf0 33.3%, #eaebf0 66.6%, #EF4135 66.6%, #EF4135 100%);"></span>
                    </div>

                    <p class="mt-6 text-sm sm:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto font-normal">
                        <?php echo e($isFr
    ? 'Le test fide évalue votre français de tous les jours en Suisse : nous vous préparons au niveau exact de votre permis.'
    : 'The fide test checks the French you use every day in Switzerland, and we prepare you for the exact level your permit needs.'); ?>

                    </p>

                    <div class="mt-8">
                        <a href="/register"
                            class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055a4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5">
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
                        class="mt-12 pt-8 border-t border-slate-100 flex flex-wrap items-center justify-center gap-6 sm:gap-10 text-xs sm:text-sm text-slate-600">
                        <?php $__currentLoopData = $facts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check text-[#0055a4]"></i>
                                <span><?php echo e($fact); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <p class="mt-5 text-xs font-semibold text-slate-400">
                        <span class="text-[#0055A4] text-[8px] align-middle mr-1">●</span>
                        <?php echo e($isFr ? 'Mis à jour le 12 novembre 2026' : 'Updated 12 November 2026'); ?>

                    </p>
                </div>
            </section>

            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                        <div class="lg:col-span-7">
                            <span class="eyebrow mb-3">
                                <span class="tricolor w-12">
                                    <i></i>
                                    <i></i>
                                    <i></i>
                                </span>
                                <?php echo e($isFr ? "DÉCOUVREZ L'EXAMEN" : 'KNOW YOUR EXAM'); ?>

                            </span>
                            <h2
                                class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                <?php echo e($isFr ? 'Le test fide en bref' : 'The fide test at a glance'); ?>

                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? 'Le test fide est le test de langue conçu pour les démarches de migration en Suisse : permis de séjour et naturalisation. Il comprend une partie orale de 40 minutes et une partie écrite de 60 minutes. Il évalue les niveaux A1, A2 et B1, à travers des situations de la vie quotidienne en Suisse.'
    : 'The fide test is the language test built for Swiss immigration procedures: residence permits and citizenship. It has a 40-minute spoken part and a 60-minute written part. It tests levels A1, A2 and B1, using situations from everyday life in Switzerland.'); ?>

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
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">40 min</div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            <?php echo e($isFr ? 'Oral « Parler et comprendre »' : 'Spoken, “Parler et comprendre”'); ?>

                                        </div>
                                    </div>
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">60 min</div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            <?php echo e($isFr ? 'Écrit « Lire et écrire »' : 'Written, “Lire et écrire”'); ?>

                                        </div>
                                    </div>
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">A1–B1</div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            <?php echo e($isFr ? 'Niveaux' : 'Levels'); ?>

                                        </div>
                                    </div>
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">CHF 250</div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            <?php echo e($isFr ? 'Jusqu\'à, les 2 parties' : 'Up to, for both parts'); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 rounded-2xl border border-slate-200/80 overflow-hidden">
                        <?php $__currentLoopData = $glance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div
                                class="grid grid-cols-1 sm:grid-cols-12 gap-1 sm:gap-4 px-5 sm:px-7 py-4 text-sm <?php echo e($index % 2 === 0 ? 'bg-[#f7f9fc]' : 'bg-white'); ?> <?php echo e($index < count($glance) - 1 ? 'border-b border-slate-100' : ''); ?>">
                                <div class="sm:col-span-3 font-semibold text-[#10233e]"><?php echo e($row[0]); ?></div>
                                <div class="sm:col-span-9 text-slate-600"><?php echo e($row[1]); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <section class="py-16 sm:py-24 bg-[#f4f7fb] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <span class="eyebrow mb-3">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? 'EXIGENCES LINGUISTIQUES' : 'SWISS PERMIT REQUIREMENTS'); ?>

                        </span>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Quel niveau fide pour votre permis ?' : 'What fide level does your permit need?'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                            <?php echo e($isFr
    ? 'Tout dépend de votre démarche. Le niveau se mesure dans la langue nationale parlée là où vous habitez : en Suisse romande, le français. Pour la naturalisation, le minimum fédéral est B1 à l\'oral et A2 à l\'écrit, et certains cantons demandent davantage.'
    : 'It depends on your application. Your level is measured in the national language where you live. In Romandie (Geneva, Vaud, Neuchâtel, Jura and the French-speaking parts of Bern, Fribourg and Valais), that\'s French. For citizenship, the federal minimum is B1 spoken and A2 written, and some cantons ask for more.'); ?>

                        </p>
                    </div>

                    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="bg-[#0055a4] text-white">
                                        <th class="py-4 px-6 font-semibold"><?php echo e($isFr ? 'Démarche' : 'Application'); ?>

                                        </th>
                                        <th class="py-4 px-6 font-semibold text-center"><?php echo e($isFr ? 'Oral' : 'Spoken'); ?>

                                        </th>
                                        <th class="py-4 px-6 font-semibold text-center">
                                            <?php echo e($isFr ? 'Écrit' : 'Written'); ?>

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr>
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <?php echo e($isFr ? 'Permis B (certains cas)' : 'B permit (some cases)'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#0055a4]">A1</span>
                                        </td>
                                        <td class="py-4 px-6 text-center text-slate-400 font-medium">—</td>
                                    </tr>
                                    <tr class="bg-[#f7f9fc]">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <a href="<?php echo e($cPermitUrl); ?>"
                                                class="<?php echo e($linkClass); ?>"><?php echo e($isFr ? 'Permis C ordinaire' : 'Ordinary C permit'); ?></a>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#0055a4]">A2</span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#0055a4]">A1</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <a href="<?php echo e($cPermitUrl); ?>"
                                                class="<?php echo e($linkClass); ?>"><?php echo e($isFr ? 'Permis C anticipé' : 'Early C permit'); ?></a>
                                            <span
                                                class="font-normal text-slate-500"><?php echo e($isFr ? '(après 5 ans)' : '(after 5 years)'); ?></span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">B1</span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#0055a4]">A1</span>
                                        </td>
                                    </tr>
                                    <tr class="bg-[#f7f9fc]">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <a href="<?php echo e($citizenshipUrl); ?>"
                                                class="<?php echo e($linkClass); ?>"><?php echo e($isFr ? 'Naturalisation' : 'Swiss citizenship'); ?></a>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">B1</span>
                                        </td>
                                        <td class="py-4 px-6 text-center text-slate-700 font-medium">
                                            A2 <span
                                                class="text-slate-500 font-normal"><?php echo e($isFr ? '(minimum fédéral)' : '(federal minimum)'); ?></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <p class="mt-6 text-sm sm:text-base text-slate-600 leading-relaxed text-center max-w-3xl mx-auto">
                        <?php echo e($isFr
    ? 'Regardez l\'écart entre les 2 colonnes. Pour le permis C, l\'oral est plus exigeant que l\'écrit. C\'est souvent là que la préparation doit porter en premier.'
    : 'Notice the gap between the 2 columns. For a C permit, the spoken level is higher than the written one, so that\'s usually where your prep should start.'); ?>

                    </p>
                </div>
            </section>

            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <span class="eyebrow mb-3">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? 'STRUCTURE DU TEST' : 'TEST STRUCTURE'); ?>

                        </span>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Le format du test fide' : 'fide test format'); ?>

                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div
                            class="relative overflow-hidden bg-white rounded-3xl border border-slate-200/80 p-7 sm:p-8">
                            <div class="flex items-center justify-between mb-4">
                                <span
                                    class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center text-lg">
                                    <i class="fas fa-comments"></i>
                                </span>
                                <span
                                    class="px-3.5 py-1 rounded-full text-xs font-bold bg-[#f0f6fc] text-[#0055a4] border border-blue-100">40
                                    min</span>
                            </div>
                            <h3 class="text-xl font-bold text-[#10233e] mb-2">
                                <?php echo e($isFr ? 'Oral « Parler et comprendre »' : 'Spoken, “Parler et comprendre”'); ?>

                            </h3>
                            <p class="text-sm text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? 'Vous échangez avec un examinateur et vous comprenez des situations du quotidien.'
    : 'Talk with an examiner and follow everyday situations.'); ?>

                            </p>
                        </div>

                        <div
                            class="relative overflow-hidden bg-white rounded-3xl border border-slate-200/80 p-7 sm:p-8">
                            <div class="flex items-center justify-between mb-4">
                                <span
                                    class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center text-lg">
                                    <i class="fas fa-pen-nib"></i>
                                </span>
                                <span
                                    class="px-3.5 py-1 rounded-full text-xs font-bold bg-[#f0f6fc] text-[#0055a4] border border-blue-100">60
                                    min</span>
                            </div>
                            <h3 class="text-xl font-bold text-[#10233e] mb-2">
                                <?php echo e($isFr ? 'Écrit « Lire et écrire »' : 'Written, “Lire et écrire”'); ?>

                            </h3>
                            <p class="text-sm text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? 'Vous lisez des documents et vous rédigez des textes courts liés à la vie en Suisse.'
    : 'Read documents and write short texts about life in Switzerland.'); ?>

                            </p>
                        </div>
                    </div>

                    <p class="mt-8 text-sm sm:text-base text-slate-600 leading-relaxed text-center max-w-3xl mx-auto">
                        <?php echo e($isFr
    ? 'Les situations viennent de votre vie réelle : le travail, le cabinet médical, la commune. Vous pouvez passer les 2 parties ensemble ou séparément. Si votre dossier ne demande qu\'un niveau oral, l\'oral seul peut suffire.'
    : 'The situations come from real life: work, the doctor\'s surgery, the commune (local council). You can take both parts together or separately. If your application only needs a spoken level, the spoken part alone may be enough.'); ?>

                    </p>
                </div>
            </section>

            <section class="py-16 sm:py-24 bg-[#f4f7fb] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <span class="eyebrow mb-3">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? 'LES PIÈGES DU TEST' : 'COMMON PITFALLS'); ?>

                        </span>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Où les candidats perdent des points au test fide' : 'Where fide candidates lose points'); ?>

                        </h2>
                    </div>

                    <div
                        class="relative overflow-hidden bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 mb-8">
                        <h3 class="text-lg sm:text-xl font-bold text-[#10233e] mb-3">
                            <?php echo e($isFr ? 'Le français des manuels n\'est pas celui de Suisse romande' : 'Textbook French isn\'t Swiss French'); ?>

                        </h3>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed mb-6">
                            <?php echo e($isFr
    ? 'Le test se déroule dans un décor suisse. Or beaucoup de méthodes enseignent le français de France. 7 exemples qui piègent les candidats :'
    : 'The test is set in Switzerland, but most courses teach the French of France. 7 differences that catch candidates out:'); ?>

                        </p>
                        <div class="overflow-x-auto rounded-2xl border border-slate-200/80">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="bg-[#0055a4] text-white text-xs sm:text-sm">
                                        <th class="py-3.5 px-4 font-semibold">
                                            <?php echo e($isFr ? 'En Suisse romande' : 'Swiss French'); ?>

                                        </th>
                                        <th class="py-3.5 px-4 font-semibold">
                                            <?php echo e($isFr ? 'En France' : 'French in France'); ?>

                                        </th>
                                        <th class="py-3.5 px-4 font-semibold"><?php echo e($isFr ? 'Sens' : 'Meaning'); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php $__currentLoopData = $vocab; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $word): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class=" <?php echo e($index % 2 === 0 ? 'bg-[#f7f9fc]' : 'bg-white'); ?>">
                                            <td class="py-3.5 px-4 font-semibold text-[#10233e]"><?php echo e($word[0]); ?></td>
                                            <td class="py-3.5 px-4 text-slate-600"><?php echo e($word[1]); ?></td>
                                            <td class="py-3.5 px-4 text-slate-600"><?php echo e($word[2]); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="mt-6 text-sm sm:text-base text-slate-600 leading-relaxed">
                            <?php echo e($isFr
    ? 'Un rendez-vous « pour le dîner » n\'est pas le soir. Ce genre de détail fait perdre une réponse.'
    : 'An appointment “pour le dîner” isn\'t in the evening. A detail like that costs you an answer.'); ?>

                        </p>
                        <div class="mt-5 bg-[#f2f7fc] rounded-2xl p-4 border border-blue-100/70">
                            <div
                                class="text-xs font-bold text-[#0055a4] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#0055A4]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                <?php echo e($isFr
    ? 'Des mises en situation suisses, avec le vocabulaire de la région où vous vivez.'
    : 'Swiss scenarios, with the vocabulary of the region you live in.'); ?>

                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            class="relative overflow-hidden bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-[#10233e] mb-3">
                                    <?php echo e($isFr ? 'La peur de demander de répéter' : 'Being afraid to ask for a repeat'); ?>

                                </h3>
                                <p class="text-sm text-slate-500 leading-relaxed">
                                    <?php echo e($isFr
    ? 'À l\'oral, beaucoup de candidats se taisent plutôt que d\'avouer qu\'ils n\'ont pas compris. Pourtant, demander une précision fait partie de la communication. « Pardon, vous pouvez répéter ? » vaut mieux qu\'un silence.'
    : 'In the spoken part, many candidates go quiet rather than admit they didn\'t understand. But asking for clarification is part of communicating. “Pardon, vous pouvez répéter ?” beats silence.'); ?>

                                </p>
                            </div>
                            <div class="mt-6 bg-[#f2f7fc] rounded-2xl p-4 border border-blue-100/70">
                                <div
                                    class="text-xs font-bold text-[#0055a4] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#0055A4]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                                </div>
                                <p class="text-sm text-slate-600 leading-relaxed">
                                    <?php echo e($isFr
    ? 'Nous entraînons les phrases qui gardent l\'échange ouvert, jusqu\'à ce qu\'elles sortent sans réfléchir.'
    : 'We drill the phrases that keep a conversation going until they come out without thinking.'); ?>

                                </p>
                            </div>
                        </div>

                        <div
                            class="relative overflow-hidden bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-[#10233e] mb-3">
                                    <?php echo e($isFr ? 'Vouloir écrire trop compliqué' : 'Writing too ambitiously'); ?>

                                </h3>
                                <p class="text-sm text-slate-500 leading-relaxed">
                                    <?php echo e($isFr
    ? 'Au niveau A1 ou A2, un message simple qui répond à toute la consigne vaut plus qu\'une phrase ambitieuse pleine d\'erreurs. La consigne d\'abord, le style ensuite.'
    : 'At A1 or A2, a simple message that covers the whole task scores better than an ambitious sentence full of mistakes. The task first, style second.'); ?>

                                </p>
                            </div>
                            <div class="mt-6 bg-[#f2f7fc] rounded-2xl p-4 border border-blue-100/70">
                                <div
                                    class="text-xs font-bold text-[#0055a4] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#0055A4]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                                </div>
                                <p class="text-sm text-slate-600 leading-relaxed">
                                    <?php echo e($isFr
    ? 'Des modèles de messages pour les situations courantes — excuser une absence, déplacer un rendez-vous, répondre à la régie.'
    : 'Message templates for everyday situations — excusing an absence, moving an appointment, replying to your letting agency.'); ?>

                                </p>
                            </div>
                        </div>

                        <div
                            class="relative overflow-hidden bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-[#10233e] mb-3">
                                    <?php echo e($isFr ? 'Préparer le mauvais niveau' : 'Preparing for the wrong level'); ?>

                                </h3>
                                <p class="text-sm text-slate-500 leading-relaxed">
                                    <?php echo e($isFr
    ? 'Préparer le B1 alors que votre permis demande l\'A2, ce sont des mois en trop. Viser l\'A2 alors que votre canton exige le B1, c\'est un refus.'
    : 'Preparing for B1 when your permit needs A2 costs you months. Aiming for A2 when your canton requires B1 gets you a refusal.'); ?>

                                </p>
                            </div>
                            <div class="mt-6 bg-[#f2f7fc] rounded-2xl p-4 border border-blue-100/70">
                                <div
                                    class="text-xs font-bold text-[#0055a4] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#0055A4]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                                </div>
                                <p class="text-sm text-slate-600 leading-relaxed">
                                    <?php echo e($isFr
    ? 'Nous vérifions d\'abord le niveau exigé pour votre démarche, puis nous construisons le plan.'
    : 'We check the level your application requires first, then build the plan.'); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="rounded-3xl bg-[#002654] text-white p-8 sm:p-12 relative overflow-hidden">
                        <div class="absolute inset-x-0 top-0 h-1 flex">
                            <span class="w-1/3 bg-[#0055A4]"></span>
                            <span class="w-1/3 bg-white"></span>
                            <span class="w-1/3 bg-[#EF4135]"></span>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                            <div class="lg:col-span-7">
                                <h2
                                    class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white tracking-tight leading-tight">
                                    <?php echo e($isFr
    ? 'Préparer le test fide à Genève, Lausanne, Fribourg, Neuchâtel ou Sion'
    : 'Preparing for the fide test in Geneva, Lausanne, Fribourg, Neuchâtel or Sion'); ?>

                                </h2>
                                <p class="mt-4 text-sm sm:text-base text-slate-200 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Nos cours se suivent en ligne, en direct. Vous vous préparez depuis chez vous, où que vous viviez en Suisse romande, aux horaires qui vous conviennent. Le test, lui, se passe dans un centre d\'examen fide : la liste des centres et des dates est sur'
    : 'Our classes are live and online. You prepare from home, wherever you live in Romandie, at times that suit you. You take the test itself at a fide test centre: the list of centres and dates is on'); ?>

                                    <a href="<?php echo e($fideSource); ?>" target="_blank" rel="noopener noreferrer"
                                        class="text-white font-semibold underline underline-offset-2">fide-service.ch</a>.
                                </p>
                            </div>
                            <div class="lg:col-span-5">
                                <div class="bg-white/10 backdrop-blur rounded-2xl p-6 border border-white/10">
                                    <div class="flex items-center gap-2 text-sm font-bold text-white mb-3">
                                        <i class="fas fa-location-dot text-[#EF4135]"></i>
                                        <span><?php echo e($isFr ? 'Centres d\'examen fide' : 'fide test centres'); ?></span>
                                    </div>
                                    <div class="flex flex-wrap gap-2 text-xs font-medium text-slate-200">
                                        <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="px-2.5 py-1 rounded-lg bg-white/10"><?php echo e($city); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <span class="eyebrow mb-3">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? 'UN ENSEIGNEMENT EN DIRECT. UN PLAN QUI VOUS CORRESPOND.' : 'LIVE TEACHING. A PLAN THAT FITS YOU.'); ?>

                        </span>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Notre préparation au test fide' : 'How we prepare you for the fide test'); ?>

                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <?php $__currentLoopData = $plan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex flex-col items-start">
                                <div class="relative flex items-center w-full mb-4">
                                    <span
                                        class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055a4] text-sm font-semibold flex items-center justify-center border border-blue-100 flex-shrink-0">
                                        <?php echo e($step[0]); ?>

                                    </span>
                                    <?php if($step[0] !== '04'): ?>
                                        <div class="hidden lg:block flex-1 h-[1px] bg-[#e1eaf2] ml-4 -mr-4"></div>
                                    <?php endif; ?>
                                </div>
                                <h3 class="font-bold text-[#10233e] text-base sm:text-[17px] mb-2"><?php echo e($step[1]); ?></h3>
                                <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal"><?php echo e($step[2]); ?>

                                </p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="mt-12 text-center">
                        <a href="/register"
                            class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055a4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5">
                            <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                            <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                        </a>
                        <p class="mt-3 text-xs sm:text-[13px] text-slate-500 font-medium">
                            <?php echo e($isFr
    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
    : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                        </p>
                    </div>
                </div>
            </section>

            <?php echo $__env->make('partials.faq-section', [
                'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                'title' => $isFr ? 'Questions fréquentes<br>sur le test fide' : 'fide test<br>FAQ',
                'description' => $isFr ? 'Des réponses claires avant de passer à l\'étape suivante.' : 'Clear answers before you take your next step.',
                'contactText' => $isFr ? 'Une autre question ?' : 'Have another question?',
                'contactUrl' => $contactUrl,
                'faqs' => $faqs,
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <section class="py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <?php echo $__env->make('partials.cta-banner', [
                        'sectionClass' => 'pt-4 pb-6 bg-white',
                        'eyebrow' => $isFr ? 'NOUS VOUS ACCOMPAGNONS' : "LET'S GET YOU THERE",
                        'title' => $isFr
                            ? "Votre niveau. Le niveau exigé.<br class='hidden sm:inline'> Nous comblons l'écart."
                            : "Your level. The required level.<br class='hidden sm:inline'> We close the gap.",
                        'description' => $isFr
                            ? 'Votre premier cours mesure votre oral et votre écrit face au niveau de votre permis. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                            : 'Your first class measures your speaking and writing against the level your permit needs. You choose the pace: 2 or 4 hours a week.',
                        'primaryButtonText' => $isFr ? "S'inscrire" : 'Enroll now',
                        'primaryButtonUrl' => '/register',
                        'showSecondaryButton' => true,
                        'secondaryButtonText' => $isFr ? 'Contactez-nous' : 'Talk to us',
                        'secondaryButtonUrl' => $contactUrl,
                        'helperText' => $isFr
                            ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                            : 'Live classes on Google Meet · 1:1 or small group · Student portal included',
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="mt-8 text-center text-xs sm:text-sm text-slate-500 font-normal">
                        <span><?php echo e($isFr ? 'Vous venez à deux ? 50 € de remise par mois, pour vous et pour votre ami.' : 'Coming with a friend? €50 off per month, for you and for them.'); ?></span>
                        <a href="<?php echo e($referralUrl); ?>"
                            class="text-[#0055a4] font-semibold hover:underline inline-flex items-center gap-1 ml-1.5">
                            <span><?php echo e($isFr ? 'Contactez-nous' : 'Contact us'); ?></span>
                            <span>→</span>
                        </a>
                    </div>

                    <div class="border-t border-slate-200/80 my-8"></div>

                    <div class="flex flex-wrap items-center justify-center gap-x-3 gap-y-2 text-xs text-slate-400">
                        <span class="font-bold uppercase tracking-wider text-slate-500">SOURCES</span>
                        <a href="<?php echo e($fideSource); ?>" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors">
                            <?php echo e($isFr ? 'fide – test fide' : 'fide – the fide test'); ?> ↗
                        </a>
                        <span class="text-slate-300">·</span>
                        <a href="<?php echo e($richmondSource); ?>" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors">
                            <?php echo e($isFr ? 'Richmond Chambers – niveaux de langue pour le permis C' : 'Richmond Chambers – C permit language levels'); ?>

                            ↗
                        </a>
                    </div>
                </div>
            </section>
        </div>

        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

</body>

</html><?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/fide-test.blade.php ENDPATH**/ ?>