<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';

    // Determine language based on current URL prefix
    $isFr = request()->is('fr*');

    // Page metadata
    $title = $isFr
        ? 'Naturalisation suisse : conditions et niveau de français'
        : 'Swiss Citizenship 2026: Requirements and Language Level';

    $metaDesc = $isFr
        ? 'Naturalisation suisse ordinaire : permis C, 10 ans de séjour, B1 à l\'oral et A2 à l\'écrit. Conditions, preuves de langue et préparation en ligne.'
        : 'Swiss citizenship by ordinary naturalisation: a C permit, 10 years of residence, B1 spoken and A2 written. Requirements, language proof and online prep.';

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/naturalisation-suisse/' : 'https://fluencefrancaise.fr/en/swiss-citizenship/';
    $frUrl = 'https://fluencefrancaise.fr/fr/naturalisation-suisse/';
    $enUrl = 'https://fluencefrancaise.fr/en/swiss-citizenship/';

    $cPermitUrl = $isFr ? '/fr/permis-c-suisse' : '/en/c-permit-switzerland';
    $fideUrl = $isFr ? '/fr/test-fide' : '/en/fide-test';
    $delfUrl = $isFr ? '/fr/delf' : '/en/delf';
    $delfB1Url = $isFr ? '/fr/delf-b1' : '/en/delf-b1';
    $contactUrl = $isFr ? '/fr/contact' : '/en/contact';
    $referralUrl = $isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral';
    $genevaSource = 'https://www.ge.ch/devenir-suisse';
    $richmondSource = 'https://www.richmondchambers.ch/swiss-settlement-citizenship/swiss-citizenship-by-naturalisation';
    $link = 'class="font-semibold text-[#0055A4] hover:underline"';

    $faqs = $isFr ? [
        ['q' => 'Combien d\'années faut-il pour demander la nationalité suisse ?', 'a' => 'Au moins 10 ans de séjour en Suisse, dont 3 dans les 5 ans avant la demande. Les années passées en Suisse entre 8 et 18 ans comptent double, mais vous devez y avoir réellement vécu au moins 6 ans. Le canton fixe en plus une durée minimale chez lui.'],
        ['q' => 'Faut-il un permis C pour la naturalisation ?', 'a' => 'Pour la naturalisation ordinaire, oui. Le permis C est obligatoire et doit rester valable pendant toute la procédure. La naturalisation facilitée n\'exige pas forcément de permis C.'],
        ['q' => 'Le test fide est-il obligatoire ?', 'a' => 'Il faut prouver votre niveau. Le test fide est un moyen de le faire. Un DELF ou un DALF, votre langue maternelle ou votre scolarité peuvent aussi être acceptés.'],
        ['q' => 'Quel est le canton le plus facile pour la naturalisation ?', 'a' => 'Aucun classement officiel n\'existe. Les cantons diffèrent sur la durée de séjour sur place, le niveau de langue et la forme de l\'examen de connaissances. Ce qui compte, ce sont les règles du canton où vous vivez.'],
        ['q' => 'Le niveau A2 à l\'oral suffit-il ?', 'a' => 'Non. Le minimum fédéral est B1 à l\'oral. L\'A2 à l\'oral correspond au <a href="' . $cPermitUrl . '" class="font-semibold text-[#0055A4] hover:underline">permis C ordinaire</a>, pas à la naturalisation.'],
    ] : [
        ['q' => 'How many years does it take to get Swiss citizenship?', 'a' => 'At least 10 years of residence in Switzerland, including 3 of the 5 years before you apply. Years spent in Switzerland between ages 8 and 18 count double, but you must have actually lived there for at least 6 years. Your canton also sets its own minimum residence.'],
        ['q' => 'Do you need a C permit for Swiss citizenship?', 'a' => 'For ordinary naturalisation, yes. The C permit is required and must stay valid throughout the procedure. Facilitated naturalisation does not necessarily require a C permit.'],
        ['q' => 'Is the fide test compulsory?', 'a' => 'You have to prove your level. The fide test is one way to do it. A DELF or DALF, your mother tongue or your schooling may also be accepted.'],
        ['q' => 'What\'s the easiest canton to get Swiss citizenship?', 'a' => 'There\'s no official ranking. Cantons differ on local residence periods, language level and the format of the knowledge check. What matters is the rules of the canton you live in.'],
        ['q' => 'Is A2 speaking enough?', 'a' => 'No. The federal minimum is B1 spoken. A2 spoken is the level for an ordinary <a href="' . $cPermitUrl . '" class="font-semibold text-[#0055A4] hover:underline">C permit</a>, not for citizenship.'],
    ];

    $requirements = $isFr ? [
        ['Permis', 'cpermit', 'Permis C', ', valable pendant toute la procédure'],
        ['Séjour en Suisse', 'text', '10 ans au moins, dont 3 dans les 5 ans avant la demande. Les années entre 8 et 18 ans comptent double, mais vous devez avoir réellement vécu en Suisse au moins 6 ans'],
        ['Séjour dans le canton', 'text', 'Fixé par chaque canton, en général de 2 à 5 ans'],
        ['Intégration', 'text', 'Respect de l\'ordre public et des valeurs de la Constitution, participation à la vie économique ou à une formation'],
        ['Langue', 'text', 'B1 à l\'oral et A2 à l\'écrit au minimum, dans la langue de votre canton'],
        ['Connaissances', 'text', 'Bonne connaissance de la vie en Suisse : géographie, histoire, politique, société'],
    ] : [
        ['Permit', 'cpermit', 'A C permit', ', valid throughout the procedure'],
        ['Residence in Switzerland', 'text', 'At least 10 years, including 3 of the 5 years before you apply. Years between ages 8 and 18 count double, but you must have actually lived in Switzerland for at least 6 years'],
        ['Residence in your canton', 'text', 'Set by each canton, usually 2 to 5 years'],
        ['Integration', 'text', 'Respect for public order and constitutional values, taking part in working life or education'],
        ['Language', 'text', 'At least B1 spoken and A2 written, in your canton\'s language'],
        ['Knowledge', 'text', 'Familiarity with life in Switzerland: geography, history, politics, society'],
    ];

    $steps = $isFr ? [
        ['01', 'La cible de votre canton', 'Nous vérifions le niveau exigé à l\'oral et à l\'écrit là où vous vivez.'],
        ['02', 'Votre oral, mesuré au premier cours', 'Votre tuteur situe votre expression et votre compréhension face au B1.'],
        ['03', 'Le test, puis l\'entretien', null],
        ['04', 'Des cours en direct, 2 ou 4 h par semaine', 'En particulier ou en mini-groupe de 2 à 5 personnes, avec notes et exercices dans votre espace élève.'],
    ] : [
        ['01', 'Your canton\'s target', 'We check the spoken and written level required where you live.'],
        ['02', 'Your speaking, measured in your first class', 'Your tutor places your speaking and listening against B1.'],
        ['03', 'The test, then the interview', null],
        ['04', 'Live classes, 2 or 4 hours a week', '1:1 or in a group of 2–5, with notes and practice in your student portal.'],
    ];

    $faqSchema = [];
    foreach ($faqs as $faq) {
        $faqSchema[] = [
            '@type' => 'Question',
            'name' => $faq['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => trim(preg_replace('/\s+/', ' ', strip_tags($faq['a']))),
            ],
        ];
    }
    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebPage',
                'name' => $title,
                'description' => $metaDesc,
                'url' => $canonicalUrl,
                'inLanguage' => $isFr ? 'fr' : 'en',
                'publisher' => ['@type' => 'Organization', 'name' => $siteName, 'url' => 'https://fluencefrancaise.fr'],
            ],
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => $isFr ? 'Accueil' : 'Home', 'item' => $isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en'],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => $isFr ? 'Suisse' : 'Switzerland', 'item' => $isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en'],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $isFr ? 'Naturalisation' : 'Citizenship', 'item' => $canonicalUrl],
                ],
            ],
            ['@type' => 'FAQPage', 'mainEntity' => $faqSchema],
        ],
    ];
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

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo e($title); ?> | <?php echo e($siteName); ?>">
    <meta property="og:description" content="<?php echo e($metaDesc); ?>">
    <meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
    <meta property="og:site_name" content="<?php echo e($siteName); ?>">

    <script
        type="application/ld+json"><?php echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (Vite build) -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <?php echo $__env->make('partials.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body class="bg-white text-slate-800 font-sans antialiased selection:bg-[#0055A4] selection:text-white">

    <div class="min-h-screen flex flex-col justify-between">
        <div>
            <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- hero banner -->
            <section
                class="relative  pt-32 md:pt-40 pb-16 md:pb-24overflow-hidden bg-gradient-to-b from-[#f0f6ff]/70 via-white to-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl relative z-10">

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                        <div class="lg:col-span-7">
                            <nav class="flex items-center gap-1.5 text-xs text-slate-500 mb-6 font-medium">
                                <a href="<?php echo e($isFr ? '/fr' : '/en'); ?>"
                                    class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Accueil' : 'Home'); ?></a>
                                <span>›</span>
                                <a href="<?php echo e($isFr ? '/fr/cours' : '/en/courses'); ?>"
                                    class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Suisse' : 'Switzerland'); ?></a>
                                <span>›</span>
                                <span
                                    class="text-slate-800 font-semibold"><?php echo e($isFr ? 'Naturalisation suisse' : 'Swiss Citizenship'); ?></span>
                            </nav>
                            <span class="eyebrow">
                                <span class="tricolor w-12">
                                    <i></i>
                                    <i></i>
                                    <i></i>
                                </span>
                                <?php echo e($isFr ? 'SUISSE · NATURALISATION ORDINAIRE & FACILITÉE' : 'SWISS CITIZENSHIP · LANGUAGE REQUIREMENTS'); ?>

                            </span>
                            <h1
                                class="text-3xl sm:text-5xl lg:text-[52px] font-bold text-[#0c1e38] tracking-tight leading-[1.2] mt-5">
                                <?php echo e($isFr ? 'Naturalisation suisse :' : 'Swiss Citizenship: Reach'); ?>

                                <br>
                                <span class="text-[#0055a4]">
                                    <?php echo e($isFr ? 'Atteignez le niveau de langue' : 'the Language Level'); ?>

                                </span>
                                <br>
                                <span class="swish relative inline-block">
                                    <?php echo e($isFr ? 'exigé' : 'Required'); ?>

                            </h1>

                            <p class="mt-6 text-sm sm:text-base text-slate-500 leading-relaxed font-normal max-w-xl">
                                <?php echo e($isFr
    ? 'Pour devenir Suisse, il faut le B1 à l\'oral et l\'A2 à l\'écrit au minimum : nous vous préparons à ce niveau, et à l\'entretien.'
    : 'Becoming Swiss takes at least B1 spoken and A2 written French, and we prepare you for that level and for the interview.'); ?>

                            </p>

                            <div class="mt-8 flex flex-wrap items-center gap-4">
                                <a href="/register"
                                    class="inline-flex items-center justify-center px-7 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055a4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5">
                                    <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                                    <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                                </a>
                                <a href="<?php echo e($isFr ? '/fr/contact' : '/en/contact'); ?>"
                                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#0055a4] hover:text-[#003f7a] px-4 py-3 transition-colors">
                                    <span><?php echo e($isFr ? 'Mesurer votre écart' : 'Calculate your gap'); ?></span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>

                            <div class="mt-6 flex items-center gap-2 text-xs text-slate-500 font-medium">
                                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span><?php echo e($isFr ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?></span>
                            </div>
                        </div>

                        <div class="lg:col-span-5 flex justify-center">
                            <div class="relative w-full max-w-[360px] aspect-square flex items-center justify-center">
                                <div class="absolute inset-0 rounded-full bg-blue-100/40 blur-2xl"></div>
                                <div class="absolute inset-8 rounded-full border border-blue-100"></div>
                                <div class="absolute inset-16 rounded-full border border-blue-50"></div>

                                <div
                                    class="relative w-44 h-44 rounded-3xl bg-[#002654] text-white flex flex-col items-center justify-center shadow-xl z-10">
                                    <div class="text-3xl font-extrabold tracking-tight text-white">B1 + A2</div>
                                    <div class="text-[11px] text-blue-200 mt-1 uppercase tracking-wider font-semibold">
                                        <?php echo e($isFr ? 'Naturalisation suisse' : 'Swiss naturalisation'); ?>

                                    </div>
                                    <div class="mt-3 flex gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                                    </div>
                                </div>

                                <div
                                    class="absolute top-4 right-2 bg-white rounded-2xl border border-slate-100 shadow-lg p-3 flex items-center gap-2.5 z-20">
                                    <span
                                        class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <div>
                                        <div class="text-xs font-bold text-[#10233e]">
                                            <?php echo e($isFr ? 'Taux de réussite' : 'Pass rate'); ?>

                                        </div>
                                        <div class="text-[10px] text-slate-400">
                                            <?php echo e($isFr ? '99 % dès le 1er passage' : '99% first attempt'); ?>

                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="absolute bottom-4 left-2 bg-white rounded-2xl border border-slate-100 shadow-lg p-3 flex items-center gap-2.5 z-20">
                                    <span
                                        class="w-8 h-8 rounded-xl bg-blue-50 text-[#0055a4] flex items-center justify-center text-sm font-bold">
                                        <i class="fas fa-certificate"></i>
                                    </span>
                                    <div>
                                        <div class="text-xs font-bold text-[#10233e]">
                                            <?php echo e($isFr ? 'fide & DELF' : 'fide & DELF'); ?>

                                        </div>
                                        <div class="text-[10px] text-slate-400">
                                            <?php echo e($isFr ? 'B1 Oral · A2 Écrit' : 'B1 Oral · A2 Written'); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-14 pt-8 border-t border-slate-100">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="p-4 rounded-2xl bg-[#f8fafc] border border-slate-200/70 flex items-start gap-3">
                                <i class="fas fa-check text-[#0055a4] mt-1 text-xs"></i>
                                <div>
                                    <div class="text-xs font-bold text-[#10233e]">
                                        <?php echo e($isFr ? 'Préparation au test fide et au DELF' : 'fide test and DELF prep'); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="p-4 rounded-2xl bg-[#f8fafc] border border-slate-200/70 flex items-start gap-3">
                                <i class="fas fa-check text-[#0055a4] mt-1 text-xs"></i>
                                <div>
                                    <div class="text-xs font-bold text-[#10233e]">
                                        <?php echo e($isFr ? 'Oral B1 ciblé' : 'Focused B1 speaking'); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="p-4 rounded-2xl bg-[#f8fafc] border border-slate-200/70 flex items-start gap-3">
                                <i class="fas fa-check text-[#0055a4] mt-1 text-xs"></i>
                                <div>
                                    <div class="text-xs font-bold text-[#10233e]">
                                        <?php echo e($isFr ? 'En ligne, partout en Suisse romande' : 'Online, anywhere in French-speaking Switzerland'); ?>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-[11px] font-semibold tracking-wider text-slate-400 uppercase">
                            <span>GENÈVE</span>
                            <span>·</span>
                            <span>VAUD</span>
                            <span>·</span>
                            <span>FRIBOURG</span>
                            <span>·</span>
                            <span>NEUCHÂTEL</span>
                            <span>·</span>
                            <span>VALAIS</span>
                            <span>·</span>
                            <span>JURA</span>
                        </div>
                        <p class="mt-5 text-center text-xs font-semibold text-slate-400">
                            <span class="text-[#0055A4] text-[8px] align-middle mr-1">●</span>
                            <?php echo e($isFr ? 'Mis à jour le 12 novembre 2026' : 'Updated 12 November 2026'); ?>

                        </p>
                    </div>
                </div>
            </section>

            <!-- swiss citizenship requirements -->
            <section class="py-16 sm:py-20 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <span class="eyebrow mb-3">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? 'CONDITIONS' : 'REQUIREMENTS'); ?>

                        </span>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Les conditions de la naturalisation suisse' : 'Swiss citizenship requirements'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                            <?php echo e($isFr
    ? 'Pour une naturalisation ordinaire, il faut un permis C, au moins 10 ans de séjour en Suisse dont 3 dans les 5 dernières années, et une intégration réussie. Le niveau de langue minimum est B1 à l\'oral et A2 à l\'écrit. Votre canton et votre commune ajoutent leurs propres conditions.'
    : 'For ordinary naturalisation, you need a C permit, at least 10 years of residence in Switzerland including 3 of the last 5, and successful integration. The minimum language level is B1 spoken and A2 written. Your canton and commune add their own conditions.'); ?>

                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200/80 overflow-hidden bg-white">
                        <div
                            class="grid grid-cols-12 gap-4 px-5 sm:px-7 py-3.5 bg-[#0055a4] text-white text-xs font-semibold tracking-wide">
                            <div class="col-span-4"><?php echo e($isFr ? 'Condition' : 'Requirement'); ?></div>
                            <div class="col-span-8"><?php echo e($isFr ? 'Ce qui est demandé' : 'What\'s asked'); ?></div>
                        </div>
                        <?php $__currentLoopData = $requirements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div
                                class="grid grid-cols-12 gap-4 px-5 sm:px-7 py-4 text-sm <?php echo e($index % 2 === 0 ? 'bg-[#f7f9fc]' : 'bg-white'); ?> <?php echo e($index < count($requirements) - 1 ? 'border-b border-slate-100' : ''); ?>">
                                <div class="col-span-4 font-semibold text-[#10233e]"><?php echo e($row[0]); ?></div>
                                <div class="col-span-8 text-slate-600">
                                    <?php if($row[1] === 'cpermit'): ?>
                                        <a href="<?php echo e($cPermitUrl); ?>" <?php echo $link; ?>><?php echo e($row[2]); ?></a><?php echo e($row[3]); ?>

                                    <?php else: ?>
                                        <?php echo e($row[2]); ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                    <span class="eyebrow mb-3">
                        <span class="tricolor w-12">
                            <i></i>
                            <i></i>
                            <i></i>
                        </span>
                        <?php echo e($isFr ? 'NIVEAU DE LANGUE' : 'LANGUAGE LEVEL'); ?>

                    </span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15]">
                        <?php echo e($isFr ? 'Quel niveau de français pour la naturalisation suisse ?' : 'Swiss citizenship language requirements'); ?>

                    </h2>
                    <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                        <?php echo e($isFr
    ? 'Le minimum fédéral est B1 à l\'oral, en expression comme en compréhension, et A2 à l\'écrit. Certains cantons demandent davantage ; Genève applique le minimum fédéral. Vérifiez la règle de votre canton avant de réserver un test.'
    : 'The federal minimum is B1 spoken (speaking and listening) and A2 written. Some cantons ask for more; Geneva applies the federal minimum. Check your canton\'s rule before you book a test.'); ?>

                    </p>
                    <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                        <?php if($isFr): ?>
                            Le niveau se mesure dans la langue officielle de votre canton : le français en Suisse romande.
                            Vous le prouvez avec le <a href="<?php echo e($fideUrl); ?>" <?php echo $link; ?>>test fide</a>, un diplôme <a
                                href="<?php echo e($delfB1Url); ?>" <?php echo $link; ?>>DELF B1</a> ou plus, votre langue maternelle ou votre
                            parcours scolaire.
                        <?php else: ?>
                            Your level is measured in your canton's official language, which is French in Romandie. You
                            prove it with the <a href="<?php echo e($fideUrl); ?>" <?php echo $link; ?>>fide test</a>, a <a
                                href="<?php echo e($delfB1Url); ?>" <?php echo $link; ?>>DELF B1</a> diploma or higher, your mother tongue or
                            your schooling.
                        <?php endif; ?>
                    </p>
                    <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                        <?php echo e($isFr
    ? 'L\'écart clé se trouve à l\'oral. Le B1, c\'est raconter, expliquer, donner son avis et le défendre. C\'est aussi ce dont vous aurez besoin le jour de l\'entretien.'
    : 'The key gap is speaking. B1 means telling a story, explaining, and giving and defending your opinion. It\'s also what you\'ll need on interview day.'); ?>

                    </p>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                    <span class="eyebrow mb-3">
                        <span class="tricolor w-12">
                            <i></i>
                            <i></i>
                            <i></i>
                        </span>
                        <?php echo e($isFr ? 'TEST ET ENTRETIEN' : 'TEST AND INTERVIEW'); ?>

                    </span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15]">
                        <?php echo e($isFr ? 'La langue compte 2 fois : test et entretien' : 'Language counts twice: test and interview'); ?>

                    </h2>
                    <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                        <?php echo e($isFr
    ? 'Les cantons vérifient aussi vos connaissances sur la Suisse et sur votre canton : géographie, histoire, politique, vie sociale. Selon le canton, cela passe par un entretien, un test, ou les 2.'
    : 'Cantons also check what you know about Switzerland and your canton: geography, history, politics, social life. Depending on the canton, that means an interview, a test, or both.'); ?>

                    </p>
                    <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                        <?php echo e($isFr
    ? 'Tout se déroule en français. Un candidat qui connaît les réponses mais peine à s\'exprimer part avec un handicap. Votre oral sert donc 2 fois : pour prouver votre niveau, puis pour montrer votre intégration.'
    : 'It all happens in French. A candidate who knows the answers but struggles to express them starts at a disadvantage. So your speaking does 2 jobs: proving your level, then showing your integration.'); ?>

                    </p>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                    <span class="eyebrow mb-3">
                        <span class="tricolor w-12">
                            <i></i>
                            <i></i>
                            <i></i>
                        </span>
                        <?php echo e($isFr ? 'CONJOINTS' : 'BY MARRIAGE'); ?>

                    </span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15]">
                        <?php echo e($isFr ? 'Naturalisation facilitée pour les conjoints' : 'Swiss citizenship by marriage'); ?>

                    </h2>
                    <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                        <?php echo e($isFr
    ? 'Si vous êtes marié à une personne de nationalité suisse, une procédure facilitée existe. Si vous vivez en Suisse, elle demande notamment 3 ans de mariage et 5 ans de séjour en Suisse, dont l\'année qui précède la demande. Si vous vivez à l\'étranger avec votre conjoint suisse, vous pouvez y avoir droit après 6 ans de mariage, si vous avez des liens étroits avec la Suisse. Votre intégration, langue comprise, est aussi examinée : renseignez-vous sur ce qui s\'applique à votre dossier.'
    : 'If you\'re married to a Swiss citizen, a facilitated procedure exists. For applicants living in Switzerland, it requires, among other things, 3 years of marriage and 5 years of residence in Switzerland, including the year before you apply. Spouses of Swiss citizens living abroad may qualify after 6 years of marriage if they have close ties with Switzerland. Your integration, language included, is still assessed, so check what applies to your case.'); ?>

                    </p>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12">
                        <span class="eyebrow mb-3">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? 'COURS EN DIRECT' : 'LIVE CLASSES'); ?>

                        </span>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            <?php echo e($isFr ? 'Notre préparation' : 'How we prepare you'); ?>

                        </h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-6 rounded-3xl bg-[#f8fafc] border border-slate-200/80">
                                <span
                                    class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055A4] text-sm font-semibold flex items-center justify-center border border-blue-100"><?php echo e($step[0]); ?></span>
                                <h3 class="font-bold text-[#10233e] text-base mt-4 mb-2"><?php echo e($step[1]); ?></h3>
                                <p class="text-[13px] text-slate-500 leading-relaxed">
                                    <?php if($step[0] === '03'): ?>
                                        <?php if($isFr): ?>
                                            Nous préparons le <a href="<?php echo e($fideUrl); ?>" <?php echo $link; ?>>test fide</a> ou le <a
                                                href="<?php echo e($delfUrl); ?>" <?php echo $link; ?>>DELF</a>, puis l'entretien en français : parler
                                            de votre parcours, de votre commune, de la Suisse.
                                        <?php else: ?>
                                            We prepare you for the <a href="<?php echo e($fideUrl); ?>" <?php echo $link; ?>>fide test</a> or <a
                                                href="<?php echo e($delfUrl); ?>" <?php echo $link; ?>>DELF</a>, then for the interview in French: your
                                            background, your commune, Switzerland.
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php echo e($step[2]); ?>

                                    <?php endif; ?>
                                </p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="mt-12 text-center">
                        <a href="/register"
                            class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055A4] hover:bg-[#003f7a] transition-all">
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
                'title' => $isFr ? 'Questions fréquentes<br>sur la naturalisation suisse' : 'Swiss citizenship<br>FAQ',
                'description' => $isFr ? 'Des réponses claires avant de passer à l\'étape suivante.' : 'Clear answers before you take your next step.',
                'contactText' => $isFr ? 'Une autre question ?' : 'Have another question?',
                'contactUrl' => $contactUrl,
                'faqs' => $faqs,
                'sectionClass' => 'py-16 sm:py-20 bg-white border-b border-slate-100',
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
                            ? 'Votre premier cours mesure votre oral et votre écrit face au niveau de votre canton. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                            : 'Your first class measures your speaking and writing against your canton\'s level. You choose the pace: 2 or 4 hours a week.',
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
                            class="text-[#0055A4] font-semibold hover:underline inline-flex items-center gap-1 ml-1.5">
                            <span><?php echo e($isFr ? 'Contactez-nous' : 'Contact us'); ?></span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <div class="border-t border-slate-200/80 my-8"></div>

                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-slate-400">
                        <span class="font-bold uppercase tracking-wider text-slate-500">SOURCES</span>
                        <a href="<?php echo e($genevaSource); ?>" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span><?php echo e($isFr ? 'République et canton de Genève – Devenir Suisse' : 'Republic and Canton of Geneva – Becoming Swiss'); ?></span>
                            <span class="text-[10px]">↗</span>
                        </a>
                        <span class="text-slate-300">·</span>
                        <a href="<?php echo e($richmondSource); ?>" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span><?php echo e($isFr ? 'Richmond Chambers – naturalisation ordinaire' : 'Richmond Chambers – ordinary naturalisation'); ?></span>
                            <span class="text-[10px]">↗</span>
                        </a>
                    </div>
                </div>
            </section>

            <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

</body>

</html><?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/swiss-citizenship.blade.php ENDPATH**/ ?>