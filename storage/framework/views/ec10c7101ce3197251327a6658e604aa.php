<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    $isFr = request()->is('fr*');

    $title = $isFr
        ? 'Permis C Suisse : conditions et niveau de français (A2, B1)'
        : 'C Permit Switzerland: Requirements and Language Level';

    $metaDesc = $isFr
        ? 'Permis C en Suisse : A2 à l\'oral et A1 à l\'écrit, B1 à l\'oral pour le permis C anticipé. Conditions, preuves acceptées et préparation au test fide.'
        : 'C permit Switzerland: A2 spoken and A1 written, or B1 spoken for the early C permit after 5 years. Requirements, accepted proof and online fide test prep.';

    $canonicalUrl = $isFr
        ? 'https://fluencefrancaise.fr/fr/permis-c-suisse/'
        : 'https://fluencefrancaise.fr/en/c-permit-switzerland/';
    $frUrl = 'https://fluencefrancaise.fr/fr/permis-c-suisse/';
    $enUrl = 'https://fluencefrancaise.fr/en/c-permit-switzerland/';

    $homeUrl = $isFr ? '/fr' : '/en';
    $fideUrl = $isFr ? '/fr/test-fide' : '/en/fide-test';
    $delfUrl = $isFr ? '/fr/delf' : '/en/delf';
    $delfB1Url = $isFr ? '/fr/delf-b1' : '/en/delf-b1';
    $citizenshipUrl = $isFr ? '/fr/naturalisation-suisse' : '/en/swiss-citizenship';
    $contactUrl = $isFr ? '/fr/contact' : '/en/contact';
    $referralUrl = $isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral';
    $fideSource = 'https://fide-service.ch';
    $richmondSource = 'https://www.richmondchambers.ch/post/swiss-c-permit-language-requirements-a2-b1';
    $link = 'class="font-semibold text-[#0055A4] hover:underline"';

    $faqs = $isFr ? [
        ['q' => 'Quelle différence entre le permis B et le permis C ?', 'a' => 'Le permis B est une autorisation de séjour, liée à un but et renouvelée régulièrement. Le permis C est une autorisation d\'établissement, plus stable. Il demande aussi un niveau de langue plus élevé.'],
        ['q' => 'Le test fide est-il obligatoire pour le permis C ?', 'a' => 'Vous devez prouver votre niveau, mais pas forcément avec le test fide. Un DELF ou un DALF est accepté, et votre langue maternelle ou votre scolarité peuvent suffire.'],
        ['q' => 'Peut-on obtenir le permis C plus tôt ?', 'a' => 'Oui, de 2 façons. Les ressortissants de nombreux pays d\'Europe de l\'Ouest, des États-Unis et du Canada peuvent l\'obtenir après 5 ans, en vertu d\'accords d\'établissement ou de la réciprocité. Les Britanniques couverts par l\'accord Suisse–Royaume-Uni sur les droits acquis peuvent aussi y avoir droit après 5 ans ; d\'autres règles s\'appliquent aux personnes arrivées à partir du 1er janvier 2021. D\'autres personnes peuvent être admissibles au permis C anticipé après 5 ans de séjour admissible et une intégration réussie, avec le B1 à l\'oral et l\'A1 à l\'écrit.'],
        ['q' => 'Le niveau se mesure-t-il en français partout en Suisse ?', 'a' => 'Non. Il se mesure dans la langue nationale de votre lieu de résidence : le français en Suisse romande, l\'allemand, l\'italien ou le romanche ailleurs.'],
        ['q' => 'Combien de temps pour passer de l\'A2 au B1 à l\'oral ?', 'a' => 'Cela dépend de votre point de départ et de votre rythme. Votre premier cours mesure votre oral et donne une estimation réaliste pour votre cas.'],
    ] : [
        ['q' => 'What\'s the difference between a B and a C permit in Switzerland?', 'a' => 'The B permit is a residence permit, tied to a purpose and renewed regularly. The C permit is a settlement permit and more stable. It also requires a higher language level.'],
        ['q' => 'Do I need the fide test for a C permit?', 'a' => 'You need to prove your level, but not necessarily with the fide test. A DELF or DALF is accepted, and your mother tongue or schooling may be enough.'],
        ['q' => 'Is there a fast track to the C permit?', 'a' => 'Yes, 2 ways. Nationals of many Western European countries, the USA and Canada can get it after 5 years, under settlement agreements or reciprocity. UK nationals covered by the Swiss–UK acquired-rights agreement may also qualify after 5 years; different rules apply to those arriving from 1 January 2021. Other applicants may qualify for early settlement after 5 years of qualifying residence if well integrated, with B1 spoken and A1 written.'],
        ['q' => 'Is the level always measured in French?', 'a' => 'No. It\'s measured in the national language of your place of residence: French in Romandie, German, Italian or Romansh elsewhere.'],
        ['q' => 'How long does it take to go from A2 to B1 speaking?', 'a' => 'It depends on where you start and how often you practise. Your first class measures your speaking and gives you a realistic estimate for your case.'],
    ];

    $levels = $isFr ? [
        ['Ordinaire', 'A2', 'A1'],
        ['Anticipé (après 5 ans de séjour)', 'B1', 'A1'],
    ] : [
        ['Ordinary', 'A2', 'A1'],
        ['Early (after 5 years\' residence)', 'B1', 'A1'],
    ];

    $criteria = $isFr ? [
        'le respect de la sécurité et de l\'ordre publics ;',
        'le respect des valeurs de la Constitution ;',
        'les compétences linguistiques ;',
        'la participation à la vie économique ou l\'acquisition d\'une formation.',
    ] : [
        'respect for public safety and order;',
        'respect for the values of the Federal Constitution;',
        'language skills;',
        'taking part in economic life or in education.',
    ];

    $steps = $isFr ? [
        ['01', 'Le bon niveau dès le départ', 'Permis C ordinaire ou anticipé : nous fixons la cible à l\'oral et à l\'écrit.'],
        ['02', 'Votre oral, mesuré au premier cours', 'C\'est là que se joue le permis, alors nous commençons par là.'],
        ['03', 'Des mises en situation suisses', null],
        ['04', 'Des cours en direct, 2 ou 4 h par semaine', 'En particulier ou en mini-groupe de 2 à 5 personnes, avec notes et exercices dans votre espace élève.'],
    ] : [
        ['01', 'The right level from day 1', 'Ordinary or early C permit: we set the target for speaking and writing.'],
        ['02', 'Your speaking, measured in your first class', 'That\'s where the permit is decided, so that\'s where we start.'],
        ['03', 'Real Swiss scenarios', null],
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
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $isFr ? 'Permis C' : 'C permit', 'item' => $canonicalUrl],
                ],
            ],
            [
                '@type' => 'FAQPage',
                'mainEntity' => $faqSchema,
            ],
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
                                <span class="text-slate-800 font-semibold"><?php echo e($isFr ? 'Permis C' : 'C Permit'); ?></span>
                            </nav>

                            <span class="eyebrow">
                                <span class="tricolor w-12">
                                    <i></i>
                                    <i></i>
                                    <i></i>
                                </span>
                                <?php echo e($isFr ? "SUISSE · PERMIS D'ÉTABLISSEMENT C" : 'SWITZERLAND · PERMANENT SETTLEMENT'); ?>

                            </span>
                            <h1
                                class="text-3xl sm:text-5xl lg:text-[52px] font-bold text-[#0c1e38] tracking-tight leading-[1.2] mt-5">
                                <?php echo e($isFr ? 'Permis C en Suisse :' : 'C Permit Switzerland:'); ?>

                                <br>
                                <span class="text-[#0055a4]">
                                    <?php echo e($isFr ? 'Atteignez le niveau de langue' : 'Reach the Language Level'); ?>

                                </span>
                                <br>
                                <span class="swish relative inline-block">
                                    <?php echo e($isFr ? 'exigé' : 'Required'); ?>

                                </span>
                            </h1>

                            <p class="mt-6 text-sm sm:text-base text-slate-500 leading-relaxed font-normal max-w-xl">
                                <?php echo e($isFr
    ? 'Le permis C exige un niveau certifié A2 oral et A1 écrit (ou B1 oral pour la demande anticipée). Nous vous préparons au niveau exact exigé dans votre canton.'
    : 'A C permit requires certified language level A2 oral and A1 written (or B1 oral for early application). We prepare you for the exact level, spoken in your canton.'); ?>

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
                                <span><?php echo e($isFr ? 'Cours particuliers sur Google Meet · Horaires flexibles · Garantie conformité SEM' : 'Live classes on Google Meet · Flexible hours · SEM compliant preparation'); ?></span>
                            </div>
                        </div>

                        <div class="lg:col-span-5 flex justify-center">
                            <div class="relative w-full max-w-[360px] aspect-square flex items-center justify-center">
                                <div class="absolute inset-0 rounded-full bg-blue-100/40 blur-2xl"></div>
                                <div class="absolute inset-8 rounded-full border border-blue-100"></div>
                                <div class="absolute inset-16 rounded-full border border-blue-50"></div>

                                <div
                                    class="relative w-44 h-44 rounded-3xl bg-[#002654] text-white flex flex-col items-center justify-center shadow-xl z-10">
                                    <div class="text-3xl font-extrabold tracking-tight text-white">A2 / B1</div>
                                    <div class="text-[11px] text-blue-200 mt-1 uppercase tracking-wider font-semibold">
                                        <?php echo e($isFr ? 'Normes suisses' : 'Swiss standards'); ?>

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
                                            <?php echo e($isFr ? '98 % dès le 1er passage' : '98% first attempt'); ?>

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
                                            <?php echo e($isFr ? 'A2 Oral · A1 Écrit' : 'A2 Oral · A1 Written'); ?>

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
                                        <?php echo e($isFr ? 'Permis C anticipé (5 ans)' : 'Fast-track C permit (5 years)'); ?>

                                    </div>
                                    <div class="text-xs text-slate-500 font-normal mt-0.5">
                                        <?php echo e($isFr ? 'Exige B1 oral + A1 écrit' : 'Requires B1 oral + A1 written'); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="p-4 rounded-2xl bg-[#f8fafc] border border-slate-200/70 flex items-start gap-3">
                                <i class="fas fa-check text-[#0055a4] mt-1 text-xs"></i>
                                <div>
                                    <div class="text-xs font-bold text-[#10233e]">
                                        <?php echo e($isFr ? 'Permis C ordinaire (10 ans)' : 'Standard C permit (10 years)'); ?>

                                    </div>
                                    <div class="text-xs text-slate-500 font-normal mt-0.5">
                                        <?php echo e($isFr ? 'Exige A2 oral + A1 écrit' : 'Requires A2 oral + A1 written'); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="p-4 rounded-2xl bg-[#f8fafc] border border-slate-200/70 flex items-start gap-3">
                                <i class="fas fa-check text-[#0055a4] mt-1 text-xs"></i>
                                <div>
                                    <div class="text-xs font-bold text-[#10233e]">
                                        <?php echo e($isFr ? 'Examens officiels reconnus' : 'Official tests recognized'); ?>

                                    </div>
                                    <div class="text-xs text-slate-500 font-normal mt-0.5">
                                        <?php echo e($isFr ? 'fide, DELF, TCF, TEF' : 'fide, DELF, TCF, TEF'); ?>

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
                    </div>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <span class="eyebrow">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? 'NIVEAU EXIGÉ' : 'LANGUAGE LEVEL'); ?>

                        </span>

                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15] mt-3">
                            <?php echo e($isFr ? 'Quel niveau de français pour le permis C ?' : 'What language level do you need for a C permit?'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                            <?php echo e($isFr
    ? 'Pour un permis C ordinaire, il faut le niveau A2 à l\'oral et A1 à l\'écrit. Pour un permis C anticipé, après 5 ans, il faut le B1 à l\'oral et l\'A1 à l\'écrit. Le niveau se mesure dans la langue nationale de votre lieu de résidence : en Suisse romande, le français.'
    : 'An ordinary C permit needs A2 spoken and A1 written. An early C permit, after 5 years, needs B1 spoken and A1 written. Your level is measured in the national language where you live, which is French in Romandie.'); ?>

                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200/80 overflow-hidden">
                        <div
                            class="hidden sm:grid sm:grid-cols-12 gap-4 px-5 sm:px-7 py-3.5 bg-[#0055a4] text-white text-xs font-semibold tracking-wide">
                            <div class="sm:col-span-6"><?php echo e($isFr ? 'Permis C' : 'C permit'); ?></div>
                            <div class="sm:col-span-3 text-center"><?php echo e($isFr ? 'Oral' : 'Spoken'); ?></div>
                            <div class="sm:col-span-3 text-center"><?php echo e($isFr ? 'Écrit' : 'Written'); ?></div>
                        </div>
                        <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div
                                class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4 px-5 sm:px-7 py-5 text-sm items-center <?php echo e($index % 2 === 0 ? 'bg-[#f7f9fc]' : 'bg-white'); ?> <?php echo e($index < count($levels) - 1 ? 'border-b border-slate-100' : ''); ?>">
                                <div class="sm:col-span-6 font-semibold text-[#10233e]"><?php echo e($row[0]); ?></div>
                                <div class="sm:col-span-3 sm:text-center">
                                    <span
                                        class="sm:hidden text-[11px] uppercase tracking-wide text-[#0055A4] font-bold mr-2"><?php echo e($isFr ? 'Oral' : 'Spoken'); ?></span>
                                    <span
                                        class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold <?php echo e($row[1] === 'B1' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-[#0055a4]'); ?>"><?php echo e($row[1]); ?></span>
                                </div>
                                <div class="sm:col-span-3 sm:text-center">
                                    <span
                                        class="sm:hidden text-[11px] uppercase tracking-wide text-[#0055A4] font-bold mr-2"><?php echo e($isFr ? 'Écrit' : 'Written'); ?></span>
                                    <span
                                        class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#0055a4]"><?php echo e($row[2]); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <p class="mt-6 text-sm sm:text-base text-slate-600 leading-relaxed text-center max-w-3xl mx-auto">
                        <?php echo e($isFr
    ? 'Regardez la colonne de gauche. Dans les 2 cas, l\'oral est plus exigeant que l\'écrit. Et pour le permis anticipé, l\'écart passe de A2 à B1 : c\'est souvent lui qui décide si vous gagnez des années.'
    : 'Look at the left-hand column. In both cases, the spoken level is higher than the written one. For the early permit it rises from A2 to B1, and that\'s often what decides whether you save years.'); ?>

                    </p>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                        <div class="lg:col-span-7">
                            <span class="eyebrow">
                                <span class="tricolor w-12">
                                    <i></i>
                                    <i></i>
                                    <i></i>
                                </span>
                                <?php echo e($isFr ? 'LE PERMIS D\'ÉTABLISSEMENT' : 'THE SETTLEMENT PERMIT'); ?>

                            </span>
                            <h2
                                class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15] mt-3">
                                <?php echo e($isFr ? 'Le permis C en bref' : 'The C permit at a glance'); ?>

                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <?php if($isFr): ?>
                                    Le permis C est le permis d'établissement. Contrairement au permis B, il n'est pas lié à
                                    un but de séjour précis et vous donne plus de stabilité en Suisse. Il est aussi une
                                    étape vers la <a href="<?php echo e($citizenshipUrl); ?>" <?php echo $link; ?>>naturalisation suisse</a>,
                                    qui le demande pour la naturalisation ordinaire.
                                <?php else: ?>
                                    The C permit is the settlement permit (permis d'établissement). Unlike the B permit, it
                                    isn't tied to a specific purpose of stay, and it gives you more stability in
                                    Switzerland. It's also a step towards <a href="<?php echo e($citizenshipUrl); ?>" <?php echo $link; ?>>Swiss citizenship</a>, which requires it for ordinary naturalisation.
                                <?php endif; ?>
                            </p>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <?php echo e($isFr
    ? 'Pour l\'obtenir, le canton vérifie votre durée de séjour et votre intégration. La loi retient 4 critères d\'intégration :'
    : 'To grant it, the canton checks how long you\'ve lived in Switzerland and how well you\'ve integrated. The law sets 4 integration criteria:'); ?>

                            </p>
                        </div>
                        <div class="lg:col-span-5">
                            <div class="bg-[#002654] text-white rounded-3xl overflow-hidden">
                                <div class="h-[3px] w-full flex">
                                    <span class="w-1/3 bg-[#0055A4]"></span>
                                    <span class="w-1/3 bg-white"></span>
                                    <span class="w-1/3 bg-[#EF4135]"></span>
                                </div>
                                <ul class="p-6 sm:p-8 space-y-4">
                                    <?php $__currentLoopData = $criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="flex items-start gap-3 text-sm text-blue-100 leading-relaxed">
                                            <i class="fas fa-check text-white text-xs mt-1"></i>
                                            <span><?php echo e($item); ?></span>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <p class="mt-8 text-sm sm:text-base text-slate-500 leading-relaxed max-w-3xl">
                        <?php echo e($isFr
    ? 'La durée de séjour exigée dépend de votre nationalité et de votre situation. Pour votre cas, renseignez-vous auprès de l\'office des migrations de votre canton.'
    : 'The residence period required depends on your nationality and situation. For your own case, check with your canton\'s migration office.'); ?>

                    </p>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                    <span class="eyebrow">
                        <span class="tricolor w-12">
                            <i></i>
                            <i></i>
                            <i></i>
                        </span>
                        <?php echo e($isFr ? 'APRÈS 5 ANS' : 'AFTER 5 YEARS'); ?>

                    </span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15] mt-3">
                        <?php echo e($isFr ? 'Permis C anticipé : l\'oral fait la différence' : 'Early C permit: speaking makes the difference'); ?>

                    </h2>
                    <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                        <?php echo e($isFr
    ? 'Le permis C anticipé peut permettre de demander l\'établissement après 5 ans de séjour admissible, si vous êtes éligible et que votre intégration est réussie. La condition de langue monte alors au B1 à l\'oral et reste à l\'A1 à l\'écrit.'
    : 'The early C permit may let you apply for settlement after 5 years of qualifying residence, if you\'re eligible and well integrated. The language condition then rises to B1 spoken and A1 written.'); ?>

                    </p>
                    <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                        <?php echo e($isFr
    ? 'Le B1, c\'est raconter, expliquer, donner son avis et le défendre simplement. L\'A2, c\'est se faire comprendre dans des situations connues. Entre les 2, il y a un vrai travail, mais la hausse porte sur l\'oral ; le niveau A1 à l\'écrit reste exigé.'
    : 'B1 means telling a story, explaining, and giving and defending a simple opinion. A2 means getting by in familiar situations. Getting from one to the other takes real work, but the increase is focused on speaking; A1 written is still required.'); ?>

                    </p>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <span class="eyebrow">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? 'PREUVES ACCEPTÉES' : 'ACCEPTED PROOF'); ?>

                        </span>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight mt-5">
                            <?php echo e($isFr ? 'Comment prouver votre niveau ?' : 'How do you prove your level?'); ?>

                        </h2>
                    </div>

                    <div class="rounded-2xl border border-slate-200/80 overflow-hidden bg-white">
                        <div
                            class="grid grid-cols-12 gap-4 px-5 sm:px-7 py-3.5 bg-[#0055a4] text-white text-xs font-semibold tracking-wide">
                            <div class="col-span-4"><?php echo e($isFr ? 'Preuve' : 'Proof'); ?></div>
                            <div class="col-span-8"><?php echo e($isFr ? 'À savoir' : 'Good to know'); ?></div>
                        </div>
                        <div
                            class="grid grid-cols-12 gap-4 px-5 sm:px-7 py-4 text-sm bg-[#f7f9fc] border-b border-slate-100">
                            <div class="col-span-4 font-semibold text-[#10233e]">
                                <a href="<?php echo e($fideUrl); ?>" <?php echo $link; ?>><?php echo e($isFr ? 'Test fide' : 'fide test'); ?></a>
                            </div>
                            <div class="col-span-8 text-slate-600">
                                <?php echo e($isFr ? 'Le passeport des langues indique votre niveau à l\'oral et à l\'écrit' : 'Your passeport des langues shows your spoken and written levels'); ?>

                            </div>
                        </div>
                        <div
                            class="grid grid-cols-12 gap-4 px-5 sm:px-7 py-4 text-sm bg-white border-b border-slate-100">
                            <div class="col-span-4 font-semibold text-[#10233e]">
                                <a href="<?php echo e($delfUrl); ?>" <?php echo $link; ?>>DELF</a>
                                <?php echo e($isFr ? 'ou DALF (' : 'or DALF ('); ?><a href="<?php echo e($delfB1Url); ?>" <?php echo $link; ?>>B1</a>…)
                            </div>
                            <div class="col-span-8 text-slate-600">
                                <?php echo e($isFr ? 'Diplôme sans date limite' : 'A diploma with no expiry'); ?>

                            </div>
                        </div>
                        <div
                            class="grid grid-cols-12 gap-4 px-5 sm:px-7 py-4 text-sm bg-[#f7f9fc] border-b border-slate-100">
                            <div class="col-span-4 font-semibold text-[#10233e]">
                                <?php echo e($isFr ? 'Langue maternelle' : 'Mother tongue'); ?>

                            </div>
                            <div class="col-span-8 text-slate-600">
                                <?php echo e($isFr ? 'Si votre langue maternelle est le français' : 'If French is your mother tongue'); ?>

                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 px-5 sm:px-7 py-4 text-sm bg-white">
                            <div class="col-span-4 font-semibold text-[#10233e]"><?php echo e($isFr ? 'Scolarité' : 'Schooling'); ?>

                            </div>
                            <div class="col-span-8 text-slate-600">
                                <?php echo e($isFr
    ? 'Au moins 3 ans de scolarité obligatoire en français, ou une formation secondaire II ou supérieure en français'
    : 'At least 3 years of compulsory schooling in French, or upper-secondary or higher education in French'); ?>

                            </div>
                        </div>
                    </div>

                    <p class="mt-6 text-sm sm:text-base text-slate-600 leading-relaxed text-center max-w-3xl mx-auto">
                        <?php echo e($isFr
    ? 'Avant de réserver un test, vérifiez auprès de votre canton la liste des certificats reconnus. Elle est mise à jour régulièrement.'
    : 'Before booking a test, check your canton\'s list of recognised certificates. It\'s updated regularly.'); ?>

                    </p>
                </div>
            </section>

            <section class="py-16 sm:py-20 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12">
                        <span class="eyebrow">
                            <span class="tricolor w-12">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <?php echo e($isFr ? 'COURS EN DIRECT' : 'LIVE CLASSES'); ?>

                        </span>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight mt-5">
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
                                            Commune, médecin, régie, employeur, école : les situations du <a href="<?php echo e($fideUrl); ?>"
                                                <?php echo $link; ?>>test fide</a>, répétées avec votre tuteur.
                                        <?php else: ?>
                                            Commune, doctor, letting agency, employer, school: the situations from the <a
                                                href="<?php echo e($fideUrl); ?>" <?php echo $link; ?>>fide test</a>, rehearsed with your tutor.
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
                'title' => $isFr ? 'Questions fréquentes<br>sur le permis C' : 'C permit<br>FAQ',
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
                            class="text-[#0055A4] font-semibold hover:underline inline-flex items-center gap-1 ml-1.5">
                            <span><?php echo e($isFr ? 'Contactez-nous' : 'Contact us'); ?></span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <div class="border-t border-slate-200/80 my-8"></div>

                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-slate-400">
                        <span class="font-bold uppercase tracking-wider text-slate-500">SOURCES</span>
                        <a href="<?php echo e($fideSource); ?>" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span><?php echo e($isFr ? 'fide – test fide' : 'fide – the fide test'); ?></span>
                            <span class="text-[10px]">↗</span>
                        </a>
                        <span class="text-slate-300">·</span>
                        <a href="<?php echo e($richmondSource); ?>" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span><?php echo e($isFr ? 'Richmond Chambers – niveaux de langue pour le permis C' : 'Richmond Chambers – C permit language levels'); ?></span>
                            <span class="text-[10px]">↗</span>
                        </a>
                    </div>
                </div>
            </section>
        </div>

        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</body>

</html><?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/c-permit-switzerland.blade.php ENDPATH**/ ?>