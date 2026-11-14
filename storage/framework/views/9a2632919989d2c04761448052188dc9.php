<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    $isFr = request()->is('fr*');

    $title = $isFr
        ? 'À propos de Fluence Française : notre méthode'
        : 'About Fluence Française: Our Method and Team';
    $metaDesc = $isFr
        ? 'Une école de français en ligne construite autour de vos démarches : titre de séjour, naturalisation, examens. Notre méthode, notre équipe, nos engagements.'
        : 'An online French school built around your residence, citizenship and exam goals. Our method, our team and what we will and won\'t promise.';

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/a-propos/' : 'https://fluencefrancaise.fr/en/about/';
    $frUrl = 'https://fluencefrancaise.fr/fr/a-propos/';
    $enUrl = 'https://fluencefrancaise.fr/en/about/';
    $homeUrl = $isFr ? '/fr' : '/en';

    $steps = $isFr ? [
        ['01', 'Nous partons de votre objectif, pas d\'un manuel.'],
        ['02', 'Nous mesurons vos 4 compétences dès le premier cours, sauf si vous partez de zéro : vous démarrez alors directement le programme A1.'],
        ['03', 'Nous concentrons les heures là où vous êtes le plus loin du but.'],
        ['04', 'Nous vérifions vos progrès avec des examens blancs, pour savoir quand vous êtes prêt.'],
    ] : [
        ['01', 'We start from your goal, not a textbook.'],
        ['02', 'We measure your 4 skills in your first class, unless you\'re starting from zero, in which case you go straight into the A1 programme.'],
        ['03', 'We focus your hours where you\'re furthest from the target.'],
        ['04', 'We check your progress with mock exams, so you know when you\'re ready.'],
    ];

    $promises = $isFr ? [
        'Nous promettons un diagnostic honnête, un plan clair et un tuteur qui connaît votre objectif.',
        'Nous ne promettrons jamais un résultat garanti. Personne ne peut le faire honnêtement : c\'est vous qui passez l\'examen. Notre travail est de vous y amener prêt.',
        'Nous sommes une école de préparation indépendante. Nous ne faisons pas passer le TCF, le TEF, le DELF, le test fide ni l\'examen civique, et nous ne sommes affiliés à aucun organisme certificateur.',
    ] : [
        'We promise an honest assessment, a clear plan and a tutor who knows your goal.',
        'We\'ll never promise a guaranteed result. Nobody can, honestly: you\'re the one taking the exam. Our job is to get you there ready.',
        'We\'re an independent prep school. We don\'t administer the TCF, TEF, DELF, fide test or civic exam, and we\'re not affiliated with any exam body.',
    ];

    $reasons = $isFr
        ? ['Une carte de séjour', 'Une nationalité', 'Un permis', 'Une vie']
        : ['A residence card', 'A passport', 'A permit', 'A life'];

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'AboutPage',
                'name' => $title,
                'description' => $metaDesc,
                'url' => $canonicalUrl,
                'inLanguage' => $isFr ? 'fr' : 'en',
                'publisher' => ['@type' => 'EducationalOrganization', 'name' => $siteName, 'url' => 'https://fluencefrancaise.fr'],
            ],
            [
                '@type' => 'EducationalOrganization',
                'name' => $siteName,
                'url' => 'https://fluencefrancaise.fr',
                'description' => $metaDesc,
            ],
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => $isFr ? 'Accueil' : 'Home', 'item' => $isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en'],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => $isFr ? 'À propos' : 'About', 'item' => $canonicalUrl],
                ],
            ],
        ],
    ];
?>
<html lang="<?php echo e($isFr ? 'fr' : 'en'); ?>" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($isFr ? $title : $title . ' | ' . $siteName); ?></title>
    <meta name="description" content="<?php echo e($metaDesc); ?>">
    <meta name="robots" content="<?php echo e($settings['robots'] ?? 'index, follow'); ?>">
    <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
    <link rel="alternate" hreflang="fr" href="<?php echo e($frUrl); ?>">
    <link rel="alternate" hreflang="en" href="<?php echo e($enUrl); ?>">
    <link rel="alternate" hreflang="x-default" href="<?php echo e($enUrl); ?>">
    <?php echo $__env->make('partials.social-meta', [
        'ogType' => 'website',
        'ogUrl' => $canonicalUrl,
        'ogTitle' => $isFr ? $title : $title . ' | ' . $siteName,
        'ogDescription' => $metaDesc,
        'ogSiteName' => $siteName,
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script
        type="application/ld+json"><?php echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
    <?php echo $__env->make('partials.favicon', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.custom-scripts-head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body class="antialiased bg-white">
    <?php echo $__env->make('partials.custom-scripts-body', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="landing-page">
        <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <section class="relative overflow-hidden bg-white pt-32 md:pt-40 pb-14 md:pb-20">
            <div class="hero-aurora"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div class="reveal">
                        <nav aria-label="Breadcrumb"
                            class="text-xs text-slate-400 font-medium flex items-center gap-2 mb-5">
                            <a href="<?php echo e($homeUrl); ?>"
                                class="hover:text-primary-600 transition-colors"><?php echo e($isFr ? 'Accueil' : 'Home'); ?></a>
                            <span class="text-slate-300">›</span>
                            <span class="text-slate-700 font-semibold"><?php echo e($isFr ? 'À propos' : 'About'); ?></span>
                        </nav>
                        <span class="eyebrow">
                            <span class="tricolor ">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? 'À propos' : 'About'); ?>

                        </span>
                        <h1 class="mt-5 text-[2.5rem] leading-[1.06] md:text-6xl font-semibold text-ink tracking-tight">
                            <?php echo e($isFr ? 'À propos de Fluence Française' : 'About Fluence Française'); ?>

                        </h1>
                        <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                            <?php echo e($isFr
    ? 'Pour vous, le français n\'est pas un loisir. C\'est une condition : pour une carte de séjour, une nationalité, un permis, une vie. Nous avons construit notre école autour de cette réalité.'
    : 'For you, French isn\'t a hobby. It\'s a requirement: for a residence card, a passport, a permit, a life. We built our school around that reality.'); ?>

                        </p>
                    </div>
                    <div class="reveal" style="transition-delay:.1s">
                        <div class="relative">
                            <div class="absolute inset-0 rotate-3 rounded-5xl bg-primary-600/5"></div>
                            <div class="relative bg-[#002654] text-white rounded-5xl p-8 md:p-10 overflow-hidden">
                                <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                                <p class="text-sm font-semibold tracking-[0.16em] uppercase text-blue-200/80">
                                    <?php echo e($isFr ? 'Une condition' : 'A requirement'); ?>

                                </p>
                                <div class="mt-6 grid grid-cols-2 gap-4">
                                    <?php $__currentLoopData = $reasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="rounded-2xl bg-white/10 px-4 py-4">
                                            <p class="text-base font-semibold leading-snug"><?php echo e($reason); ?></p>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span>
                        <?php echo e($isFr ? 'Notre idée' : 'Our idea'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">
                        <?php echo e($isFr ? 'Notre idée : l\'écart' : 'Our idea: the gap'); ?>

                    </h2>
                    <div class="mt-6 space-y-4 text-lg text-slate-600 leading-relaxed">
                        <p>
                            <?php echo e($isFr
    ? 'Chaque démarche exige un niveau précis : A2, B1, B2. Chaque candidat a un niveau réel, souvent inégal d\'une compétence à l\'autre. Entre les 2, il y a un écart.'
    : 'Every application requires a specific level: A2, B1, B2. Every candidate has a real level, often uneven from one skill to the next. Between the 2, there\'s a gap.'); ?>

                        </p>
                        <p>
                            <?php echo e($isFr
    ? 'Nous ne vendons pas « des cours de français » en général. Nous mesurons cet écart, puis nous le comblons, compétence par compétence.'
    : 'We don\'t sell “French classes” in general. We measure that gap, then close it, skill by skill.'); ?>

                        </p>
                        <p class="text-ink font-semibold">
                            <?php echo e($isFr ? 'Votre niveau. Le niveau exigé. Nous comblons l\'écart.' : 'Your level. The required level. We close the gap.'); ?>

                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span>
                        <?php echo e($isFr ? 'La méthode' : 'The method'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">
                        <?php echo e($isFr ? 'Comment nous enseignons' : 'How we teach'); ?>

                    </h2>
                </div>
                <div class="mt-12 grid md:grid-cols-2 gap-5">
                    <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="reveal card-glow bg-mist rounded-3xl border border-slate-100 p-7"
                            style="transition-delay:<?php echo e($i * 70); ?>ms">
                            <span
                                class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-sm font-bold"><?php echo e($step[0]); ?></span>
                            <p class="mt-5 text-lg text-slate-600 leading-relaxed"><?php echo e($step[1]); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <p class="mt-8 max-w-3xl text-lg text-slate-600 leading-relaxed">
                    <?php echo e($isFr
    ? 'Les cours ont lieu en direct sur Google Meet, en particulier ou en mini-groupe de 2 à 5 personnes. Entre les cours, votre espace élève prend le relais : notes de cours, exercices à vitesse native, banques de questions, examens blancs.'
    : 'Classes run live on Google Meet, 1:1 or in a group of 2–5. Between classes, your student portal takes over: class notes, native-speed practice, question banks, mock exams.'); ?>

                </p>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span>
                        <?php echo e($isFr ? 'Nos engagements' : 'Our commitments'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">
                        <?php echo e($isFr ? 'Ce que nous promettons, et ce que nous ne promettrons jamais' : 'What we promise, and what we never will'); ?>

                    </h2>
                </div>
                <div class="mt-12 grid md:grid-cols-3 gap-5">
                    <?php $__currentLoopData = $promises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $promise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-7"
                            style="transition-delay:<?php echo e($i * 70); ?>ms">
                            <p class="text-slate-600 leading-relaxed"><?php echo e($promise); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span>
                        <?php echo e($isFr ? 'L\'équipe' : 'The team'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">
                        <?php echo e($isFr ? 'Une équipe qui prépare déjà aux examens de français' : 'A team that already prepares people for French exams'); ?>

                    </h2>
                    <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                        <?php echo e($isFr
    ? 'Fluence Française est née de l\'équipe de Focus Frame French, qui prépare au TEF et au TCF les candidats à la résidence permanente au Canada, avec plus de 100 avis 5 étoiles sur Google.'
    : 'Fluence Française comes from the team behind Focus Frame French, which prepares candidates for Canadian permanent residence through the TEF and TCF, with 100+ 5-star Google reviews.'); ?>

                    </p>
                </div>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="reveal rounded-5xl bg-primary-600 text-white text-center px-6 py-14 md:py-16 relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1.5"
                        style="background:linear-gradient(90deg,#003d7a 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)">
                    </div>
                    <a href="/register" class="btn btn-onnavy btn-lg"><?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?></a>
                    <p class="mt-6 text-sm md:text-base text-primary-100">
                        <?php echo e($isFr
    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
    : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                    </p>
                </div>
            </div>
        </section>

        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</body>

</html><?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/about-us.blade.php ENDPATH**/ ?>