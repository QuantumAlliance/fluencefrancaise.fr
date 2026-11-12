<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    $isFr = request()->is('fr*');
    $title = $isFr
        ? 'Cours de français en ligne pour étrangers : de A1 à B2'
        : 'Online French Classes With a Live Tutor: A1 to B2';
    $metaDesc = $isFr
        ? 'Cours de français en ligne en direct avec un tuteur, en particulier ou en mini-groupe. De A1 à B2, pour vos démarches et examens : TCF, TEF, DELF, fide.'
        : 'Live online French classes with a tutor, 1:1 or in a small group. A1 to B2, built around your residence, citizenship or exam goal: TCF, TEF, DELF, fide.';
    $canonicalUrl = $isFr
        ? 'https://fluencefrancaise.fr/fr/cours-de-francais-en-ligne/'
        : 'https://fluencefrancaise.fr/en/online-french-classes/';
    $enUrl = 'https://fluencefrancaise.fr/en/online-french-classes/';
    $frUrl = 'https://fluencefrancaise.fr/fr/cours-de-francais-en-ligne/';
    $homeUrl = $isFr ? '/fr' : '/en';
    $pricingUrl = $isFr ? '/fr#pricing' : '/en#pricing';
    $referralUrl = $isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral';
    $contactUrl = $isFr ? '/fr/contact' : '/en/contact';
    $permitUrl = $isFr ? '/fr/titre-de-sejour-niveau-francais' : '/en/french-residence-permit-language';
    $citizenshipUrl = $isFr ? '/fr/test-francais-naturalisation' : '/en/french-citizenship-language-test';
    $civicUrl = $isFr ? '/fr/examen-civique' : '/en/french-civic-exam';
    $fideUrl = $isFr ? '/fr/test-fide' : '/en/fide-test';
    $tcfUrl = $isFr ? '/fr/tcf-irn' : '/en/tcf-irn';
    $tefUrl = $isFr ? '/fr/tef-irn' : '/en/tef-irn';
    $delfUrl = $isFr ? '/fr/delf' : '/en/delf';
?>
<html lang="<?php echo e($isFr ? 'fr' : 'en'); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta Tags -->
    <title><?php echo e($title); ?> | <?php echo e($siteName); ?></title>
    <meta name="description" content="<?php echo e($metaDesc); ?>">
    <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
    <link rel="alternate" hreflang="fr" href="<?php echo e($frUrl); ?>">
    <link rel="alternate" hreflang="en" href="<?php echo e($enUrl); ?>">
    <link rel="alternate" hreflang="x-default" href="<?php echo e($enUrl); ?>">
    <meta name="robots" content="<?php echo e($settings['robots'] ?? 'index, follow'); ?>">

    <!-- Social Media Meta Tags -->
    <?php echo $__env->make('partials.social-meta', [
        'ogType' => 'article',
        'ogUrl' => $canonicalUrl,
        'ogTitle' => $title . ' | ' . $siteName,
        'ogDescription' => $metaDesc,
        'ogSiteName' => $siteName,
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php
        $faqs = $isFr ? [
            ['q' => 'Je ne connais pas mon niveau. Par où commencer ?', 'a' => 'Par votre premier cours. Votre tuteur mesure votre niveau dans chaque compétence et fixe votre plan à partir de là.'],
            ['q' => 'Combien de temps pour atteindre le B1 ou le B2 ?', 'a' => 'Cela dépend de votre point de départ, de votre rythme et de votre travail entre les cours. Votre tuteur vous donne une estimation réaliste après le premier cours, puis la vérifie avec vos examens blancs.'],
            ['q' => 'Particulier ou mini-groupe : que choisir ?', 'a' => 'Le particulier va plus vite, car tout le cours suit votre écart. Le mini-groupe coûte moins cher et vous fait parler avec d\'autres apprenants. Avec une date d\'examen proche, le particulier est souvent le choix le plus sûr.'],
            ['q' => 'Je suis débutant complet. Puis-je commencer ?', 'a' => 'Oui. Si vous partez de zéro, votre tuteur démarre directement le programme A1. Vous avancez ensuite vers le niveau de votre démarche, étape par étape.'],
            ['q' => 'Comment fonctionne le parrainage ?', 'a' => 'Si vous vous inscrivez avec un ami, vous bénéficiez chacun de 50 € de remise par mois. <a href="' . $referralUrl . '" class="font-semibold text-primary-600 hover:underline">Contactez-nous</a> pour l\'activer.'],
        ] : [
            ['q' => 'I don\'t know my level. Where do I start?', 'a' => 'With your first class. Your tutor measures your level in each skill and builds your plan from there.'],
            ['q' => 'How long does it take to reach B1 or B2?', 'a' => 'It depends on where you start, your pace and the work you do between classes. Your tutor gives you a realistic estimate after the first class, then checks it against your mock exams.'],
            ['q' => 'One-on-one or small group: which should I choose?', 'a' => 'One-on-one is faster, because the whole class follows your gap. A small group costs less and gets you speaking with other learners. With an exam date coming up, one-on-one is usually the safer choice.'],
            ['q' => 'I\'m a complete beginner. Can I start?', 'a' => 'Yes. If you\'re starting from zero, your tutor goes straight into the A1 programme. You then build towards the level your application needs, step by step.'],
            ['q' => 'How does the referral offer work?', 'a' => 'Sign up with a friend and you each get €50 off per month. <a href="' . $referralUrl . '" class="font-semibold text-primary-600 hover:underline">Contact us</a> to activate it.'],
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
                    '@type' => 'Course',
                    'name' => $title,
                    'description' => $metaDesc,
                    'url' => $canonicalUrl,
                    'inLanguage' => $isFr ? 'fr' : 'en',
                    'provider' => ['@type' => 'Organization', 'name' => $siteName, 'url' => 'https://fluencefrancaise.fr'],
                    'courseMode' => 'online',
                    'hasCourseInstance' => [
                        ['@type' => 'CourseInstance', 'name' => $isFr ? 'Particulier standard' : 'Standard one-on-one', 'courseMode' => 'online'],
                        ['@type' => 'CourseInstance', 'name' => $isFr ? 'Particulier 2 heures' : '2-hour one-on-one', 'courseMode' => 'online'],
                        ['@type' => 'CourseInstance', 'name' => $isFr ? 'Mini-groupe' : 'Small group', 'courseMode' => 'online'],
                    ],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => $isFr ? 'Accueil' : 'Home', 'item' => $isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en'],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => $isFr ? 'Cours' : 'Courses', 'item' => $canonicalUrl],
                    ],
                ],
                ['@type' => 'FAQPage', 'mainEntity' => $faqSchema],
            ],
        ];
    ?>
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

        <!-- ===== HERO ===== -->
        <section class="relative overflow-hidden bg-white pt-32 md:pt-40 pb-14 md:pb-20">
            <div class="hero-aurora"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative text-center max-w-3xl">
                <span class="eyebrow justify-center reveal"><span class="tricolor"><i></i><i></i><i></i></span>
                    <?php echo e($isFr ? 'Cours en direct' : 'Live classes'); ?></span>
                <h1 class="reveal mt-5 text-[2.6rem] leading-[1.05] md:text-6xl font-semibold text-ink tracking-tight">
                    <?php echo e($isFr ? 'Cours de français en ligne : de' : 'Online French Classes: From'); ?>

                    <span class="swish text-primary-600"><?php echo e($isFr ? 'A1 à B2' : 'A1 to B2, Live'); ?></span>
                </h1>
                <p class="reveal mt-6 text-lg text-slate-600 leading-relaxed">
                    <?php echo e($isFr
    ? 'Des cours en direct avec un tuteur, construits autour du niveau que votre titre de séjour, votre naturalisation ou votre examen exige.'
    : 'Live classes with a tutor, built around the level your residence permit, citizenship or exam requires.'); ?>

                </p>
                <div class="reveal mt-8">
                    <a href="/register" class="btn btn-primary btn-lg"><?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?> <i
                            class="fas fa-arrow-right text-xs"></i></a>
                    <p class="mt-3 text-sm text-slate-500">
                        <?php echo e($isFr
    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
    : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                    </p>
                </div>
                <div
                    class="reveal mt-8 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm font-medium text-slate-600">
                    <?php $__currentLoopData = ($isFr ? ['Du A1 au B2', '4 compétences', 'TCF, TEF, DELF, fide', 'Examen civique inclus'] : ['A1 to B2', 'All 4 skills', 'TCF, TEF, DELF, fide', 'Civic exam included']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="inline-flex items-center gap-2"><i
                                class="fas fa-check text-primary-600 text-xs"></i><?php echo e($fact); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <p class="reveal mt-5 text-xs font-semibold text-slate-400">
                    <?php echo e($isFr ? 'Mis à jour le 12 novembre 2026' : 'Updated 12 November 2026'); ?>

                </p>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span>
                        <?php echo e($isFr ? 'Votre objectif' : 'Your goal'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">
                        <?php echo e($isFr ? 'Des cours de français pensés pour les étrangers qui s\'installent' : 'French classes built for people settling in France and Switzerland'); ?>

                    </h2>
                    <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                        <?php echo e($isFr
    ? 'Vous n\'apprenez pas le français pour le plaisir d\'une conjugaison. Vous l\'apprenez pour une carte de séjour, une nationalité, un emploi, une vie en France ou en Suisse romande. Nos cours partent de cet objectif et du niveau qu\'il exige.'
    : 'You\'re not learning French for the love of verb tables. You\'re learning it for a residence card, a passport, a job, a life in France or French-speaking Switzerland. Our classes start from that goal and the level it requires.'); ?>

                    </p>
                </div>
                <div class="reveal mt-10 overflow-x-auto rounded-2xl border border-slate-100">
                    <table class="w-full text-left text-sm border-collapse min-w-[640px]">
                        <thead>
                            <tr class="bg-primary-600 text-white">
                                <th class="py-4 px-6 font-semibold"><?php echo e($isFr ? 'Votre objectif' : 'Your goal'); ?></th>
                                <th class="py-4 px-6 font-semibold"><?php echo e($isFr ? 'Niveau visé' : 'Target level'); ?></th>
                                <th class="py-4 px-6 font-semibold">
                                    <?php echo e($isFr ? 'Notre page dédiée' : 'Our dedicated page'); ?>

                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="bg-mist">
                                <td class="py-4 px-6 font-semibold text-ink">
                                    <?php echo e($isFr ? 'Carte de séjour pluriannuelle ou carte de résident' : 'Multi-year permit or 10-year resident card'); ?>

                                </td>
                                <td class="py-4 px-6 text-slate-600">A2 <?php echo e($isFr ? 'ou' : 'or'); ?> B1</td>
                                <td class="py-4 px-6"><a href="<?php echo e($permitUrl); ?>"
                                        class="font-semibold text-primary-600 hover:underline"><?php echo e($isFr ? 'Titre de séjour' : 'Residence permits'); ?></a>
                                </td>
                            </tr>
                            <tr class="bg-white">
                                <td class="py-4 px-6 font-semibold text-ink">
                                    <?php echo e($isFr ? 'Nationalité française' : 'French citizenship'); ?>

                                </td>
                                <td class="py-4 px-6 text-slate-600">B2</td>
                                <td class="py-4 px-6"><a href="<?php echo e($citizenshipUrl); ?>"
                                        class="font-semibold text-primary-600 hover:underline"><?php echo e($isFr ? 'Test de français pour la naturalisation' : 'French citizenship language test'); ?></a>
                                </td>
                            </tr>
                            <tr class="bg-mist">
                                <td class="py-4 px-6 font-semibold text-ink">
                                    <?php echo e($isFr ? 'Examen civique' : 'Civic exam'); ?>

                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    <?php echo e($isFr ? 'Lecture en français' : 'Reading in French'); ?>

                                </td>
                                <td class="py-4 px-6"><a href="<?php echo e($civicUrl); ?>"
                                        class="font-semibold text-primary-600 hover:underline"><?php echo e($isFr ? 'Examen civique' : 'French civic exam'); ?></a>
                                </td>
                            </tr>
                            <tr class="bg-white">
                                <td class="py-4 px-6 font-semibold text-ink">
                                    <?php echo e($isFr ? 'Permis C ou naturalisation en Suisse' : 'C permit or citizenship in Switzerland'); ?>

                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    <?php echo e($isFr ? 'A2 à B1 à l\'oral' : 'A2 to B1 spoken'); ?>

                                </td>
                                <td class="py-4 px-6"><a href="<?php echo e($fideUrl); ?>"
                                        class="font-semibold text-primary-600 hover:underline"><?php echo e($isFr ? 'Test fide' : 'fide test'); ?></a>
                                </td>
                            </tr>
                            <tr class="bg-mist">
                                <td class="py-4 px-6 font-semibold text-ink">
                                    <?php echo e($isFr ? 'Un examen précis' : 'A specific exam'); ?>

                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    <?php echo e($isFr ? 'Selon l\'examen' : 'Depends on the exam'); ?>

                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    <a href="<?php echo e($tcfUrl); ?>" class="font-semibold text-primary-600 hover:underline">TCF
                                        IRN</a>
                                    · <a href="<?php echo e($tefUrl); ?>" class="font-semibold text-primary-600 hover:underline">TEF
                                        IRN</a>
                                    · <a href="<?php echo e($delfUrl); ?>"
                                        class="font-semibold text-primary-600 hover:underline">DELF</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span>
                        <?php echo e($isFr ? 'Les formules' : 'Formats'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">
                        <?php echo e($isFr ? '3 formules, 1 même méthode' : '3 formats, 1 method'); ?>

                    </h2>
                    <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                        <?php echo e($isFr
    ? 'Vous choisissez votre rythme. La méthode, elle, ne change pas : mesurer votre écart, puis le combler.'
    : 'You choose your pace. The method stays the same: measure your gap, then close it.'); ?>

                    </p>
                </div>
                <div class="reveal mt-10 overflow-x-auto rounded-2xl border border-slate-100 bg-white">
                    <table class="w-full text-left text-sm border-collapse min-w-[640px]">
                        <thead>
                            <tr class="bg-primary-600 text-white">
                                <th class="py-4 px-6 font-semibold"><?php echo e($isFr ? 'Formule' : 'Format'); ?></th>
                                <th class="py-4 px-6 font-semibold"><?php echo e($isFr ? 'Rythme' : 'Pace'); ?></th>
                                <th class="py-4 px-6 font-semibold"><?php echo e($isFr ? 'Pour qui' : 'Best for'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="bg-white">
                                <td class="py-4 px-6 font-semibold text-ink">
                                    <?php echo e($isFr ? 'Particulier standard' : 'Standard one-on-one'); ?>

                                </td>
                                <td class="py-4 px-6 text-slate-600"><?php echo e($isFr ? '4 h par semaine' : '4 hours a week'); ?>

                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    <?php echo e($isFr ? 'Vous avez une date d\'examen ou un dossier à déposer bientôt' : 'You have an exam date or an application due soon'); ?>

                                </td>
                            </tr>
                            <tr class="bg-mist">
                                <td class="py-4 px-6 font-semibold text-ink">
                                    <?php echo e($isFr ? 'Particulier 2 heures' : '2-hour one-on-one'); ?>

                                </td>
                                <td class="py-4 px-6 text-slate-600"><?php echo e($isFr ? '2 h par semaine' : '2 hours a week'); ?>

                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    <?php echo e($isFr ? 'Votre emploi du temps est chargé, mais vous voulez avancer chaque semaine' : 'Your schedule is full, but you want progress every week'); ?>

                                </td>
                            </tr>
                            <tr class="bg-white">
                                <td class="py-4 px-6 font-semibold text-ink"><?php echo e($isFr ? 'Mini-groupe' : 'Small group'); ?>

                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    <?php echo e($isFr ? '4 h par semaine, 2 à 5 personnes' : '4 hours a week, 2–5 people'); ?>

                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    <?php echo e($isFr ? 'Vous aimez apprendre avec d\'autres, à un coût plus doux' : 'You like learning with others, at a lower cost'); ?>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="reveal mt-6 text-lg text-slate-600 leading-relaxed max-w-3xl">
                    <?php if($isFr): ?>
                        Les mini-groupes ouvrent par niveau, et les places sont limitées. La préparation à l'examen civique
                        est incluse dans toutes les formules. <a href="<?php echo e($pricingUrl); ?>"
                            class="font-semibold text-primary-600 hover:underline">Voir les tarifs</a>.
                    <?php else: ?>
                        Groups open by level, and places are limited. Civic-exam prep is included in every format. <a
                            href="<?php echo e($pricingUrl); ?>" class="font-semibold text-primary-600 hover:underline">See pricing</a>.
                    <?php endif; ?>
                </p>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                <span class="eyebrow reveal"><span class="tricolor"><i></i><i></i><i></i></span>
                    <?php echo e($isFr ? 'En cours' : 'In class'); ?></span>
                <h2 class="reveal mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">
                    <?php echo e($isFr ? 'Comment se passe un cours' : 'What a class looks like'); ?>

                </h2>
                <p class="reveal mt-4 text-lg text-slate-600 leading-relaxed">
                    <?php echo e($isFr
    ? 'Vos cours ont lieu en direct sur Google Meet, avec votre tuteur. Pas de vidéos préenregistrées, pas d\'application à remplir seul. Après chaque séance, vous recevez vos notes de cours. Chaque devoir reçoit un retour.'
    : 'Your classes run live on Google Meet with your tutor. No pre-recorded videos, no app to grind through alone. After every session, you get your class notes, and every assignment gets feedback.'); ?>

                </p>
                <p class="reveal mt-4 text-lg text-slate-600 leading-relaxed">
                    <?php echo e($isFr
    ? 'Vous avez besoin d\'un ordinateur, d\'une tablette ou d\'un téléphone, et d\'une connexion internet. C\'est tout.'
    : 'All you need is a computer, tablet or phone and an internet connection.'); ?>

                </p>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span>
                        <?php echo e($isFr ? 'Entre les cours' : 'Between classes'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">
                        <?php echo e($isFr ? 'Votre espace élève' : 'Your student portal'); ?>

                    </h2>
                    <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                        <?php echo e($isFr ? 'Entre 2 cours, vous continuez à avancer. Votre espace élève contient :' : 'Between classes, you keep moving. Your student portal includes:'); ?>

                    </p>
                </div>
                <ul class="reveal mt-8 grid md:grid-cols-2 gap-4 max-w-4xl">
                    <?php $__currentLoopData = ($isFr
                            ? ['des PDF et des livres numériques adaptés à votre niveau ;', 'les notes de chacun de vos cours ;', 'des exercices de lecture et d\'écoute à vitesse native ;', 'des banques d\'exercices par compétence ;', 'des examens blancs au format des vrais tests ;', 'un accès gratuit à PrepMyFuture après 6 mois d\'inscription.']
                        : ['PDFs and ebooks matched to your level;', 'notes from every class;', 'native-speed reading and listening practice;', 'practice banks for each skill;', 'mock exams in the format of the real tests;', 'free PrepMyFuture access after 6 months of enrolment.']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex items-start gap-3 bg-white rounded-3xl border border-slate-100 p-5 text-slate-600">
                            <i class="fas fa-check text-primary-600 text-xs mt-1"></i>
                            <span><?php echo e($item); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span>
                        <?php echo e($isFr ? 'La méthode' : 'The method'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">
                        <?php echo e($isFr ? 'Notre méthode : mesurer l\'écart, puis le combler' : 'Our method: measure the gap, then close it'); ?>

                    </h2>
                </div>
                <div class="mt-10 grid md:grid-cols-2 gap-6">
                    <?php
                        $method = $isFr ? [
                            ['01', 'Votre objectif', 'Titre de séjour, nationalité, examen : nous partons du niveau exigé.'],
                            ['02', 'Votre niveau réel', 'Dès le premier cours, votre tuteur situe vos 4 compétences : comprendre, lire, écrire, parler. Si vous partez de zéro, il démarre directement le programme A1.'],
                            ['03', 'Un plan sur votre écart', 'Les heures vont là où vous êtes le plus loin du but.'],
                            ['04', 'Des preuves de progrès', 'Examens blancs réguliers, pour savoir quand vous êtes prêt.'],
                        ] : [
                            ['01', 'Your goal', 'Residence card, citizenship, exam: we start from the level required.'],
                            ['02', 'Your real level', 'In your first class, your tutor places your 4 skills: listening, reading, writing, speaking. If you\'re starting from zero, they go straight into the A1 programme.'],
                            ['03', 'A plan built on your gap', 'Your hours go where you\'re furthest from the target.'],
                            ['04', 'Proof of progress', 'Regular mock exams, so you know when you\'re ready.'],
                        ];
                    ?>
                    <?php $__currentLoopData = $method; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="reveal card-glow bg-white rounded-4xl border border-slate-100 p-8 shadow-card"
                            style="transition-delay:<?php echo e($i * 70); ?>ms">
                            <div class="flex items-center gap-4">
                                <span class="text-4xl font-semibold text-primary-600/30"
                                    style="font-family:'Urbanist',sans-serif"><?php echo e($step[0]); ?></span>
                                <h3 class="text-2xl font-bold text-ink"><?php echo e($step[1]); ?></h3>
                            </div>
                            <p class="mt-3 text-slate-600 leading-relaxed"><?php echo e($step[2]); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="reveal mt-10">
                    <a href="/register" class="btn btn-primary btn-lg"><?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?> <i
                            class="fas fa-arrow-right text-xs"></i></a>
                    <p class="mt-3 text-sm text-slate-500">
                        <?php echo e($isFr
    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
    : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                    </p>
                </div>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span>
                        <?php echo e($isFr ? 'En ligne' : 'Online'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">
                        <?php echo e($isFr ? 'Pourquoi des cours en ligne ?' : 'Why learn online?'); ?>

                    </h2>
                </div>
                <ul class="reveal mt-8 grid md:grid-cols-2 gap-4">
                    <?php $__currentLoopData = ($isFr
                            ? ['Aucun trajet. Vos 2 ou 4 heures servent à apprendre, pas à vous déplacer.', 'Le même tuteur. Il connaît votre niveau, vos erreurs et votre objectif.', 'Partout. En France, en Suisse romande ou ailleurs, avant même votre arrivée.', 'Des horaires qui vous suivent. Les cours particuliers s\'adaptent à votre emploi du temps.']
                        : ['No commute. Your 2 or 4 hours go into learning, not travelling.', 'The same tutor. They know your level, your mistakes and your goal.', 'Anywhere. In France, in Romandie or elsewhere, even before you move.', 'Times that fit your life. One-on-one classes adapt to your schedule.']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex items-start gap-3 bg-white rounded-3xl border border-slate-100 p-5 text-slate-600">
                            <i class="fas fa-check text-primary-600 text-xs mt-1"></i>
                            <span><?php echo e($item); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </section>

        <!-- ===== LEARNING PATH ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> Our learning path</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Four steps, one
                        clear direction</h2>
                </div>
                <?php $steps = [
                    ['n' => '01', 't' => 'Build the Base', 'd' => 'At the beginner levels we focus on the foundations so you can understand and use French in real situations.', 'items' => ['Pronunciation', 'Basic grammar', 'Essential vocabulary', 'Simple conversations', 'Daily-life French', 'Reading & listening basics']],
                    ['n' => '02', 't' => 'Strengthen All 4 Skills', 'd' => 'As you progress, every core skill is trained with structure — not guesswork. You won\'t just study French, you\'ll learn how to use it.', 'items' => ['Speaking', 'Listening', 'Reading', 'Writing']],
                    ['n' => '03', 't' => 'Level Assessment', 'd' => 'At the end of each level you complete an assessment before moving forward. We move on when the foundation is strong — not because time has passed.', 'items' => ['Grammar & vocabulary', 'Speaking ability', 'Listening & reading', 'Writing structure', 'Readiness for next level']],
                    ['n' => '04', 't' => 'Exam-Specific Preparation', 'd' => 'Once you reach B1 or B2, we begin stronger exam preparation: understand the exam, manage the timing, answer with confidence.', 'items' => ['DELF / DALF practice', 'TCF / TEF-style questions', 'Timed exercises', 'Speaking simulations', 'Writing correction', 'Mock tests & strategy']],
                ]; ?>
                <div class="mt-12 grid md:grid-cols-2 gap-6">
                    <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="reveal card-glow bg-white rounded-4xl border border-slate-100 p-8 shadow-card"
                            style="transition-delay:<?php echo e($i * 70); ?>ms">
                            <div class="flex items-center gap-4">
                                <span class="text-4xl font-semibold text-primary-600/30"
                                    style="font-family:'Urbanist',sans-serif"><?php echo e($s['n']); ?></span>
                                <h3 class="text-2xl font-bold text-ink"><?php echo e($s['t']); ?></h3>
                            </div>
                            <p class="mt-3 text-slate-600 leading-relaxed"><?php echo e($s['d']); ?></p>
                            <div class="mt-5 flex flex-wrap gap-2">
                                <?php $__currentLoopData = $s['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span
                                        class="text-sm font-medium text-primary-700 bg-primary-50 px-3 py-1.5 rounded-full"><?php echo e($it); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>

        <!-- ===== LEVELS (accordion) ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
                <div class="text-center mb-12 reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> The
                        levels</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">From your first
                        words to exam-ready</h2>
                </div>
                <?php $levels = [
                    ['code' => 'A1', 'title' => 'Beginner Foundation', 'url' => '/beginner', 'intro' => 'A1 is where students begin building the structure of French.', 'items' => ['Introduce yourself', 'Ask & answer simple questions', 'Use basic verbs', 'Understand simple conversations', 'Talk about daily life', 'Read short texts', 'Write simple sentences'], 'note' => 'A1 is not about speaking perfectly — it is about building the base properly.'],
                    ['code' => 'A2', 'title' => 'Practical Everyday French', 'url' => '/elementary', 'intro' => 'A2 moves you from basic French to more useful daily communication.', 'items' => ['Speak about routine, work, family & travel', 'Understand simple spoken French', 'Read short texts and notices', 'Write short messages & paragraphs', 'Use past and future structures', 'Build stronger vocabulary'], 'note' => 'An important level for integration and residency-related goals in France.'],
                    ['code' => 'B1', 'title' => 'Intermediate French', 'url' => '/intermediate', 'intro' => 'B1 is where French becomes more serious.', 'items' => ['Explain opinions', 'Describe experiences', 'Understand clearer conversations', 'Write structured paragraphs', 'Handle common situations', 'Speak with more confidence', 'Understand longer reading passages'], 'note' => 'Commonly connected to long-term residency goals and a major step toward exam preparation.'],
                    ['code' => 'B2', 'title' => 'Upper-Intermediate French', 'url' => '/upper-intermediate', 'intro' => 'B2 is the level where students need stronger control.', 'items' => ['Clear arguments', 'Detailed opinions', 'Formal & informal communication', 'Longer listening tasks', 'Complex reading passages', 'Structured writing', 'More natural speaking', 'Exam-level performance'], 'note' => 'Often expected for French citizenship, advanced studies and stronger professional communication.'],
                ]; ?>
                <div class="space-y-4 reveal">
                    <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-3xl border border-slate-100 overflow-hidden bg-mist">
                            <button class="lvl-toggle w-full px-6 py-5 flex items-center gap-4 text-left">
                                <span
                                    class="w-12 h-12 rounded-2xl bg-primary-600 text-white grid place-content-center font-bold text-lg shrink-0"
                                    style="font-family:'Urbanist',sans-serif"><?php echo e($lv['code']); ?></span>
                                <span class="flex-1">
                                    <span class="block font-bold text-ink text-lg"><?php echo e($lv['title']); ?></span>
                                    <span class="block text-sm text-slate-500"><?php echo e($lv['intro']); ?></span>
                                </span>
                                <i class="fas fa-plus text-primary-600 transition-transform duration-300 shrink-0"></i>
                            </button>
                            <div class="lvl-content hidden px-6 pb-6">
                                <div class="grid sm:grid-cols-2 gap-x-8 gap-y-2.5 pt-1">
                                    <?php $__currentLoopData = $lv['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p class="flex items-start gap-2.5 text-slate-600"><i
                                                class="fas fa-check text-primary-600 text-xs mt-1.5"></i><span><?php echo e($it); ?></span>
                                        </p>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <p class="mt-5 text-sm text-slate-600 border-l-2 border-[#EF4135] pl-4 italic">
                                    <?php echo e($lv['note']); ?>

                                </p>
                                <a href="<?php echo e($lv['url']); ?>"
                                    class="inline-flex items-center gap-2 mt-5 text-primary-600 font-bold text-sm hover:gap-3 transition-all">Explore
                                    <?php echo e($lv['code']); ?> in detail <i class="fas fa-arrow-right text-xs"></i></a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            'sectionClass' => 'py-16 md:py-24 bg-white',
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- ===== REFERRAL ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal relative overflow-hidden rounded-5xl bg-[#002654] text-white p-8 md:p-14">
                    <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                    <div class="absolute -bottom-24 -right-16 w-80 h-80 rounded-full bg-[#EF4135]/20 blur-3xl"></div>
                    <div class="relative grid md:grid-cols-2 gap-10 items-center">
                        <div>
                            <span class="eyebrow text-[#8eb8e1]"><span class="tricolor"><i></i><i></i><i></i></span>
                                Referral offer</span>
                            <h2 class="mt-4 text-3xl md:text-5xl font-semibold leading-tight">€50 off every month for
                                you <span class="text-[#EF4135]">and</span> your friends</h2>
                            <p class="mt-5 text-lg text-slate-300 leading-relaxed max-w-lg">
                                <?php echo e($isFr ? 'Vous venez à deux ? 50 € de remise par mois, pour vous et pour votre ami.' : 'Coming with a friend? €50 off per month, for you and for them.'); ?>

                            </p>
                            <a href="<?php echo e($referralUrl); ?>"
                                class="btn btn-onnavy btn-lg mt-8"><?php echo e($isFr ? 'Contactez-nous' : 'Contact us'); ?></a>
                        </div>
                        <div
                            class="md:justify-self-end w-full max-w-sm bg-white/5 backdrop-blur-sm border border-white/10 rounded-4xl p-7">
                            <p class="text-7xl font-semibold text-white" style="font-family:'Urbanist',sans-serif">
                                €50<span class="text-2xl text-slate-300 align-top">/mo</span></p>
                            <p class="mt-3 text-slate-300">Off for both of you, every month you're enrolled.</p>
                            <p class="mt-6 text-xs text-slate-400 border-t border-white/10 pt-4">Applies once per
                                student account and cannot be stacked with other offers.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== FINAL CTA ===== -->
        <section class="pb-20 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
                <h2 class="text-3xl md:text-4xl font-semibold text-ink">
                    <?php echo e($isFr ? 'Votre niveau. Le niveau exigé. Nous comblons l\'écart.' : 'Your level. The required level. We close the gap.'); ?>

                </h2>
                <p class="mt-4 text-lg text-slate-600 max-w-xl mx-auto">
                    <?php echo e($isFr ? 'Choisissez votre formule et commencez cette semaine. Votre premier cours fixe votre niveau et votre plan.' : 'Choose your format and start this week. Your first class sets your level and your plan.'); ?>

                </p>
                <div class="mt-7 flex flex-wrap justify-center gap-4">
                    <a href="/register" class="btn btn-primary btn-lg"><?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?></a>
                    <a href="<?php echo e($contactUrl); ?>"
                        class="btn btn-outline btn-lg"><?php echo e($isFr ? 'Contactez-nous' : 'Talk to us'); ?></a>
                </div>
                <p class="mt-4 text-sm text-slate-500">
                    <?php echo e($isFr ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                </p>
            </div>
        </section>

        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <script>
        document.querySelectorAll('.faq-toggle, .lvl-toggle').forEach(function (button) {
            button.addEventListener('click', function () {
                var content = this.parentElement.querySelector('.lvl-content') || this.nextElementSibling;
                var icon = this.querySelector('i.fa-plus, i.fa-chevron-down');
                var isOpen = !content.classList.contains('hidden');
                content.classList.toggle('hidden');
                if (icon) icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(135deg)';
            });
        });
    </script>
</body>

</html><?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/our-courses.blade.php ENDPATH**/ ?>