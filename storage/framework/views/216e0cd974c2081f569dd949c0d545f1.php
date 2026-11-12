<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    $isFr = request()->is('fr*');

    $title = $isFr
        ? 'DELF A1, A2, B1, B2 : quel niveau pour vos démarches ?'
        : 'DELF Levels A1 to B2: Which One Do You Need?';
    $metaDesc = $isFr
        ? 'DELF A1 à B2 : quel diplôme pour la carte pluriannuelle, la carte de résident ou la naturalisation ? Notation, validité à vie et préparation en ligne.'
        : 'DELF A1 to B2: which diploma for a multi-year permit, resident card or French citizenship? Scoring, lifetime validity and live online preparation.';

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/delf/' : 'https://fluencefrancaise.fr/en/delf/';
    $frUrl = 'https://fluencefrancaise.fr/fr/delf/';
    $enUrl = 'https://fluencefrancaise.fr/en/delf/';
    $homeUrl = $isFr ? '/fr' : '/en';

    $a2Url = $isFr ? '/fr/delf-a2' : '/en/delf-a2';
    $b1Url = $isFr ? '/fr/delf-b1' : '/en/delf-b1';
    $b2Url = $isFr ? '/fr/delf-b2' : '/en/delf-b2';
    $permitUrl = $isFr ? '/fr/titre-de-sejour-niveau-francais' : '/en/french-residence-permit-language';
    $a2Anchor = $permitUrl . ($isFr ? '#carte-pluriannuelle' : '#multi-year-permit');
    $b1Anchor = $permitUrl . ($isFr ? '#carte-de-resident' : '#resident-card');
    $citizenshipUrl = $isFr ? '/fr/test-francais-naturalisation' : '/en/french-citizenship-language-test';
    $cPermitUrl = $isFr ? '/fr/permis-c-suisse' : '/en/c-permit-switzerland';
    $swissUrl = $isFr ? '/fr/naturalisation-suisse' : '/en/swiss-citizenship';
    $fideUrl = $isFr ? '/fr/test-fide' : '/en/fide-test';
    $tcfUrl = $isFr ? '/fr/tcf-irn' : '/en/tcf-irn';
    $tefUrl = $isFr ? '/fr/tef-irn' : '/en/tef-irn';
    $compareUrl = $isFr ? '/fr/tcf-tef-ou-delf' : '/en/tcf-vs-tef-vs-delf';
    $contactUrl = $isFr ? '/fr/contact' : '/en/contact';
    $referralUrl = $isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral';
    $feiSource = 'https://www.france-education-international.fr/delf-dalf';
    $digiSource = 'https://www.digischool.fr/articles/fle/deroulement-examen-delf/';
    $link = 'class="font-semibold text-[#0055A4] hover:underline"';

    $levels = $isFr ? [
        ['DELF A1', 'A1', 'Un premier diplôme, pour démarrer', null, null],
        ['DELF A2', 'A2', 'Carte de séjour pluriannuelle, permis C ordinaire en Suisse', $a2Url, 'DELF A2'],
        ['DELF B1', 'B1', 'Carte de résident, permis C anticipé et naturalisation en Suisse', $b1Url, 'DELF B1'],
        ['DELF B2', 'B2', 'Naturalisation française', $b2Url, 'DELF B2'],
    ] : [
        ['DELF A1', 'A1', 'A first diploma, to get started', null, null],
        ['DELF A2', 'A2', 'Multi-year residence permit; ordinary C permit in Switzerland', $a2Url, 'DELF A2'],
        ['DELF B1', 'B1', '10-year resident card; early C permit and citizenship in Switzerland', $b1Url, 'DELF B1'],
        ['DELF B2', 'B2', 'French citizenship', $b2Url, 'DELF B2'],
    ];

    $steps = $isFr ? [
        ['01', 'Le bon niveau', 'Nous partons de votre démarche pour fixer la cible.'],
        ['02', 'Vos 4 épreuves, mesurées', 'Dès le premier cours, votre tuteur repère votre point fort et l\'épreuve à sécuriser. Si vous partez de zéro, il démarre directement le programme A1.'],
        ['03', 'Des productions corrigées et des oraux simulés', 'C\'est là que se gagnent les points.'],
        ['04', 'Des cours en direct, 2 ou 4 h par semaine', 'En particulier ou en mini-groupe de 2 à 5 personnes, avec examens blancs dans votre espace élève.'],
    ] : [
        ['01', 'The right level', 'We start from your application to set the target.'],
        ['02', 'Your 4 sections, measured', 'In your first class, your tutor spots your strongest section and the one to secure. If you\'re starting from zero, they go straight into the A1 programme.'],
        ['03', 'Corrected writing and simulated orals', 'That\'s where the marks are won.'],
        ['04', 'Live classes, 2 or 4 hours a week', '1:1 or in a group of 2–5, with mock exams in your student portal.'],
    ];

    $faqs = $isFr ? [
        ['q' => 'Faut-il passer les niveaux du DELF dans l\'ordre ?', 'a' => 'Non. Chaque diplôme est indépendant. Vous vous inscrivez directement au niveau que vous visez.'],
        ['q' => 'Le DELF est-il accepté pour les titres de séjour et la naturalisation ?', 'a' => 'Oui, en France comme en Suisse, au niveau exigé par votre démarche.'],
        ['q' => 'Quelle différence entre le DELF et le DALF ?', 'a' => 'Le DELF couvre les niveaux A1 à B2. Le DALF couvre les niveaux avancés, C1 et C2, au-delà de ce que demandent les démarches de séjour et de nationalité.'],
        ['q' => 'Où passer le DELF ?', 'a' => 'Dans un centre d\'examen agréé : Alliances françaises, universités, instituts, en France et dans le monde. Chaque centre fixe ses dates de session.'],
        ['q' => 'Comment savoir quel niveau viser ?', 'a' => 'Partez du niveau exigé par votre démarche, dans le tableau ci-dessus. Votre premier cours situera ensuite chaque compétence face à ce niveau.'],
    ] : [
        ['q' => 'Do you have to take the DELF levels in order?', 'a' => 'No. Each diploma stands alone. You register directly for the level you want.'],
        ['q' => 'Is the DELF accepted for residence permits and citizenship?', 'a' => 'Yes, in France and in Switzerland, at the level your application requires.'],
        ['q' => 'What\'s the difference between the DELF and the DALF?', 'a' => 'The DELF covers A1 to B2. The DALF covers the advanced levels, C1 and C2, beyond what residence and citizenship applications ask for.'],
        ['q' => 'Where can I take the DELF?', 'a' => 'At an approved exam centre: Alliances Françaises, universities and institutes, in France and around the world. Each centre sets its own session dates.'],
        ['q' => 'How do I know which level to aim for?', 'a' => 'Start from the level your application requires, in the table above. Your first class then places each skill against that level.'],
    ];

    $faqSchema = [];
    foreach ($faqs as $faq) {
        $faqSchema[] = [
            '@type' => 'Question',
            'name' => $faq['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(preg_replace('/\s+/', ' ', strip_tags($faq['a'])))],
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
                '@type' => 'ItemList',
                'name' => $isFr ? 'Niveaux du DELF' : 'DELF levels',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'DELF A1'],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'DELF A2', 'url' => 'https://fluencefrancaise.fr' . $a2Url],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => 'DELF B1', 'url' => 'https://fluencefrancaise.fr' . $b1Url],
                    ['@type' => 'ListItem', 'position' => 4, 'name' => 'DELF B2', 'url' => 'https://fluencefrancaise.fr' . $b2Url],
                ],
            ],
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => $isFr ? 'Accueil' : 'Home', 'item' => $isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en'],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => $isFr ? 'Examens' : 'Exams', 'item' => $canonicalUrl],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => 'DELF', 'item' => $canonicalUrl],
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
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo e($title); ?> | <?php echo e($siteName); ?>">
    <meta property="og:description" content="<?php echo e($metaDesc); ?>">
    <meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
    <meta property="og:site_name" content="<?php echo e($siteName); ?>">
    <script
        type="application/ld+json"><?php echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
    <?php echo $__env->make('partials.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body class="antialiased bg-white text-slate-800 font-sans selection:bg-[#0055A4] selection:text-white">
    <div class="landing-page min-h-screen flex flex-col justify-between">
        <div>
            <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <section
                class="relative overflow-hidden bg-gradient-to-b from-[#f0f6ff]/70 via-white to-white pt-28 sm:pt-36 md:pt-40 pb-12 border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl relative z-10">
                    <div class="max-w-3xl mx-auto text-center">
                        <nav aria-label="Breadcrumb"
                            class="mb-5 flex justify-center text-xs text-slate-400 font-medium items-center gap-2">
                            <a href="<?php echo e($homeUrl); ?>"
                                class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Accueil' : 'Home'); ?></a>
                            <span class="text-slate-300">›</span>
                            <span><?php echo e($isFr ? 'Examens' : 'Exams'); ?></span>
                            <span class="text-slate-300">›</span>
                            <span class="text-slate-700 font-semibold">DELF</span>
                        </nav>
                        <div class="inline-flex items-center justify-center gap-1.5 mb-5">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span
                                class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'DELF · A1 À B2' : 'DELF · A1 TO B2'); ?></span>
                        </div>
                        <h1
                            class="text-3xl sm:text-5xl lg:text-[52px] font-bold text-[#0c1e38] tracking-tight leading-[1.18]">
                            <?php echo e($isFr ? 'DELF :' : 'DELF:'); ?>

                            <span
                                class="text-[#0055a4]"><?php echo e($isFr ? 'choisissez le niveau que votre dossier' : 'Choose the Level Your Application'); ?></span>
                            <br>
                            <span class="text-[#0055a4]"><?php echo e($isFr ? 'exige' : 'Requires'); ?></span>
                        </h1>
                        <span class="block w-full max-w-xs sm:max-w-md h-[3px] mt-3 mx-auto rounded-full"
                            style="background: linear-gradient(90deg, #0055A4 0%, #0055A4 33.3%, #eaebf0 33.3%, #eaebf0 66.6%, #EF4135 66.6%, #EF4135 100%);"></span>
                        <p class="mt-6 text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
                            <?php echo e($isFr
    ? '4 niveaux, de A1 à B2, valables à vie : nous vous aidons à viser celui de votre démarche, puis nous vous y préparons.'
    : '4 levels, A1 to B2, valid for life: we help you aim for the one your application needs, then prepare you for it.'); ?>

                        </p>
                        <div class="mt-8 flex flex-col items-center">
                            <a href="/register"
                                class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055A4] hover:bg-[#003f7a] transition-all">
                                <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                                <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                            </a>
                            <p class="mt-3 text-xs sm:text-[13px] text-slate-500 font-medium">
                                <?php echo e($isFr ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                            </p>
                        </div>
                        <p class="mt-5 text-xs font-semibold text-slate-400">
                            <span class="text-[#0055A4] text-[8px] align-middle mr-1">●</span>
                            <?php echo e($isFr ? 'Mis à jour le 12 novembre 2026' : 'Updated 12 November 2026'); ?>

                        </p>
                    </div>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="max-w-3xl mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span
                                class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'LES 4 NIVEAUX' : 'THE 4 LEVELS'); ?></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            <?php echo e($isFr ? 'Le DELF en bref' : 'The DELF at a glance'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                            <?php echo e($isFr
    ? 'Le DELF (Diplôme d\'études en langue française) est un diplôme officiel du ministère de l\'Éducation nationale. Il existe en 4 niveaux, de A1 à B2, chacun indépendant et valable à vie. C\'est l\'une des preuves acceptées pour les titres de séjour et la naturalisation, en France comme en Suisse.'
    : 'The DELF (Diplôme d\'études en langue française) is an official French Ministry of Education diploma. It comes in 4 levels, A1 to B2, each standing alone and valid for life. It\'s one of the accepted proofs for residence permits and citizenship, in France and in Switzerland.'); ?>

                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 overflow-hidden">
                        <div
                            class="hidden sm:grid sm:grid-cols-12 gap-4 px-5 sm:px-7 py-3.5 bg-[#0055a4] text-white text-xs font-semibold">
                            <div class="sm:col-span-2"><?php echo e($isFr ? 'Diplôme' : 'Diploma'); ?></div>
                            <div class="sm:col-span-2"><?php echo e($isFr ? 'Niveau' : 'Level'); ?></div>
                            <div class="sm:col-span-5">
                                <?php echo e($isFr ? 'À quoi il sert, le plus souvent' : 'Most common use'); ?>

                            </div>
                            <div class="sm:col-span-3"><?php echo e($isFr ? 'Notre page' : 'Our page'); ?></div>
                        </div>
                        <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div
                                class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4 px-5 sm:px-7 py-4 text-sm <?php echo e($index % 2 === 0 ? 'bg-[#f7f9fc]' : 'bg-white'); ?> <?php echo e($index < count($levels) - 1 ? 'border-b border-slate-100' : ''); ?>">
                                <div class="sm:col-span-2 font-semibold text-[#10233e]"><?php echo e($row[0]); ?></div>
                                <div class="sm:col-span-2 font-bold text-[#0055A4]"><?php echo e($row[1]); ?></div>
                                <div class="sm:col-span-5 text-slate-600"><?php echo e($row[2]); ?></div>
                                <div class="sm:col-span-3">
                                    <?php if($row[3]): ?>
                                        <a href="<?php echo e($row[3]); ?>" <?php echo $link; ?>><?php echo e($row[4]); ?></a>
                                    <?php else: ?>
                                        <span class="text-slate-400">—</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="inline-flex items-center gap-1.5 mb-3">
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                        <span
                            class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'AVANT DE CHOISIR' : 'BEFORE YOU CHOOSE'); ?></span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                        <?php echo e($isFr ? '3 règles à connaître avant de choisir' : '3 rules to know before you choose'); ?>

                    </h2>
                    <div class="mt-8 grid lg:grid-cols-3 gap-6">
                        <article class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden">
                            <div class="p-6">
                                <span
                                    class="text-xs bg-[#0055A4]/20 p-2 rounded-full font-bold text-[#0055A4]">01</span>
                                <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                                    <?php echo e($isFr
    ? 'Vous passez directement le niveau qui vous intéresse. Inutile d\'avoir le DELF A2 pour passer le B1. Visez le niveau exigé par votre démarche, pas un de plus, pas un de moins.'
    : 'You go straight to the level you need. You don\'t need the DELF A2 to sit the B1. Aim for the level your application requires, no more, no less.'); ?>

                                </p>
                            </div>
                        </article>
                        <article class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden">
                            <div class="p-6">
                                <span
                                    class="text-xs bg-[#0055A4]/20 p-2 rounded-full font-bold text-[#0055A4]">02</span>
                                <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                                    <?php echo e($isFr
    ? 'La réussite se joue sur 100 points. 4 épreuves notées sur 25. Il faut 50 sur 100 au total, sans aucune épreuve sous 5 sur 25.'
    : 'Passing is decided out of 100. 4 sections, each marked out of 25. You need 50 out of 100 overall, with no section below 5 out of 25.'); ?>

                                </p>
                            </div>
                        </article>
                        <article class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden">
                            <div class="p-6">
                                <span
                                    class="text-xs bg-[#0055A4]/20 p-2 rounded-full font-bold text-[#0055A4]">03</span>
                                <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                                    <?php if($isFr): ?>
                                        Le diplôme n'expire jamais. C'est la grande différence avec le <a
                                            href="<?php echo e($tcfUrl); ?>" <?php echo $link; ?>>TCF IRN</a> et le <a href="<?php echo e($tefUrl); ?>" <?php echo $link; ?>>TEF IRN</a>, dont l'attestation vaut 2 ans.
                                    <?php else: ?>
                                        The diploma never expires. That's the big difference from the <a
                                            href="<?php echo e($tcfUrl); ?>" <?php echo $link; ?>>TCF IRN</a> and <a href="<?php echo e($tefUrl); ?>" <?php echo $link; ?>>TEF IRN</a>, whose results last 2 years.
                                    <?php endif; ?>
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="max-w-3xl mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span
                                class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'VOTRE DÉMARCHE' : 'YOUR APPLICATION'); ?></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            <?php echo e($isFr ? 'Quel DELF pour quelle démarche ?' : 'Which DELF for which application?'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                            <?php echo e($isFr ? 'Partez de votre objectif. Le bon diplôme est celui qui correspond au niveau exigé.' : 'Start from your goal. The right diploma matches the required level.'); ?>

                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 overflow-hidden">
                        <div
                            class="hidden sm:grid sm:grid-cols-12 gap-4 px-5 sm:px-7 py-3.5 bg-[#0055a4] text-white text-xs font-semibold">
                            <div class="sm:col-span-5"><?php echo e($isFr ? 'Vous visez…' : 'Your goal'); ?></div>
                            <div class="sm:col-span-3"><?php echo e($isFr ? 'Niveau exigé' : 'Level required'); ?></div>
                            <div class="sm:col-span-4"><?php echo e($isFr ? 'Diplôme conseillé' : 'Recommended diploma'); ?></div>
                        </div>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4 px-5 sm:px-7 py-4 text-sm bg-[#f7f9fc] border-b border-slate-100">
                            <div class="sm:col-span-5 font-semibold"><a href="<?php echo e($a2Anchor); ?>" <?php echo $link; ?>><?php echo e($isFr ? 'Carte pluriannuelle' : 'Multi-year permit'); ?></a></div>
                            <div class="sm:col-span-3 text-slate-600">A2</div>
                            <div class="sm:col-span-4 text-slate-600">DELF A2</div>
                        </div>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4 px-5 sm:px-7 py-4 text-sm bg-white border-b border-slate-100">
                            <div class="sm:col-span-5 font-semibold"><a href="<?php echo e($b1Anchor); ?>" <?php echo $link; ?>><?php echo e($isFr ? 'Carte de résident' : '10-year resident card'); ?></a></div>
                            <div class="sm:col-span-3 text-slate-600">B1</div>
                            <div class="sm:col-span-4 text-slate-600">DELF B1</div>
                        </div>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4 px-5 sm:px-7 py-4 text-sm bg-[#f7f9fc] border-b border-slate-100">
                            <div class="sm:col-span-5 font-semibold"><a href="<?php echo e($citizenshipUrl); ?>" <?php echo $link; ?>><?php echo e($isFr ? 'Nationalité française' : 'French citizenship'); ?></a></div>
                            <div class="sm:col-span-3 text-slate-600">B2</div>
                            <div class="sm:col-span-4 text-slate-600">DELF B2</div>
                        </div>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4 px-5 sm:px-7 py-4 text-sm bg-white border-b border-slate-100">
                            <div class="sm:col-span-5 font-semibold"><a href="<?php echo e($cPermitUrl); ?>" <?php echo $link; ?>><?php echo e($isFr ? 'Permis C en Suisse' : 'Swiss C permit'); ?></a></div>
                            <div class="sm:col-span-3 text-slate-600">
                                <?php echo e($isFr ? 'A2 ou B1 à l\'oral' : 'A2 or B1 spoken'); ?>

                            </div>
                            <div class="sm:col-span-4 text-slate-600">
                                <?php if($isFr): ?>
                                    DELF A2 ou B1, ou le <a href="<?php echo e($fideUrl); ?>" <?php echo $link; ?>>test fide</a>
                                <?php else: ?>
                                    DELF A2 or B1, or the <a href="<?php echo e($fideUrl); ?>" <?php echo $link; ?>>fide test</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4 px-5 sm:px-7 py-4 text-sm bg-[#f7f9fc]">
                            <div class="sm:col-span-5 font-semibold"><a href="<?php echo e($swissUrl); ?>" <?php echo $link; ?>><?php echo e($isFr ? 'Naturalisation suisse' : 'Swiss citizenship'); ?></a></div>
                            <div class="sm:col-span-3 text-slate-600">
                                <?php echo e($isFr ? 'B1 oral, A2 écrit au minimum' : 'At least B1 spoken, A2 written'); ?>

                            </div>
                            <div class="sm:col-span-4 text-slate-600">DELF B1</div>
                        </div>
                    </div>
                    <p class="mt-6 text-sm sm:text-base text-slate-600 leading-relaxed">
                        <?php if($isFr): ?>
                            Vous hésitez entre un diplôme et un test ? <a href="<?php echo e($compareUrl); ?>" <?php echo $link; ?>>Comparez le
                                TCF, le TEF et le DELF</a>.
                        <?php else: ?>
                            Torn between a diploma and a test? <a href="<?php echo e($compareUrl); ?>" <?php echo $link; ?>>Compare the TCF,
                                TEF and DELF</a>.
                        <?php endif; ?>
                    </p>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                    <div class="inline-flex items-center gap-1.5 mb-3">
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                        <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">C1 ·
                            C2</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                        <?php echo e($isFr ? 'Et le DALF ?' : 'What about the DALF?'); ?>

                    </h2>
                    <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                        <?php echo e($isFr
    ? 'Le DALF certifie les niveaux avancés, C1 et C2. Aucune démarche de séjour ou de nationalité ne les exige : le DELF B2 couvre le niveau le plus élevé demandé, celui de la naturalisation française.'
    : 'The DALF certifies the advanced levels, C1 and C2. No residence or citizenship application requires them: the DELF B2 covers the highest level asked for, the one for French citizenship.'); ?>

                    </p>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span
                                class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'COURS EN DIRECT' : 'LIVE CLASSES'); ?></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            <?php echo e($isFr ? 'Notre préparation au DELF' : 'How we prepare you for the DELF'); ?>

                        </h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-6 rounded-3xl bg-[#f8fafc] border border-slate-200/80">
                                <span
                                    class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055A4] text-sm font-semibold flex items-center justify-center border border-blue-100"><?php echo e($step[0]); ?></span>
                                <h3 class="font-bold text-[#10233e] text-base mt-4 mb-2"><?php echo e($step[1]); ?></h3>
                                <p class="text-[13px] text-slate-500 leading-relaxed"><?php echo e($step[2]); ?></p>
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
                            <?php echo e($isFr ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                        </p>
                    </div>
                </div>
            </section>

            <?php echo $__env->make('partials.faq-section', [
                'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                'title' => $isFr ? 'Questions fréquentes' : 'FAQ',
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
                            ? 'Votre premier cours fixe votre niveau et le diplôme à viser. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                            : 'Your first class sets your level and the diploma to aim for. You choose the pace: 2 or 4 hours a week.',
                        'primaryButtonText' => $isFr ? "S'inscrire" : 'Enroll now',
                        'primaryButtonUrl' => '/register',
                        'showSecondaryButton' => true,
                        'secondaryButtonText' => $isFr ? 'Contactez-nous' : 'Talk to us',
                        'secondaryButtonUrl' => $contactUrl,
                        'helperText' => $isFr
                            ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                            : 'Live classes on Google Meet · 1:1 or small group · Student portal included',
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="mt-8 text-center text-xs sm:text-sm text-slate-500">
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
                        <a href="<?php echo e($feiSource); ?>" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 inline-flex items-center gap-1">
                            <span>France Éducation international – DELF tout public</span><span
                                class="text-[10px]">↗</span>
                        </a>
                        <span class="text-slate-300">·</span>
                        <a href="<?php echo e($digiSource); ?>" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 inline-flex items-center gap-1">
                            <span><?php echo e($isFr ? 'Digischool – déroulement du DELF' : 'Digischool – how the DELF works'); ?></span><span
                                class="text-[10px]">↗</span>
                        </a>
                    </div>
                </div>
            </section>
        </div>
        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</body>

</html><?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/delf.blade.php ENDPATH**/ ?>