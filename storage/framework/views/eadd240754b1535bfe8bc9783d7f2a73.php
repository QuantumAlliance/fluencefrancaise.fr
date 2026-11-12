<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';

    // Determine language based on current URL prefix
    $isFr = request()->is('fr*');

    // Page metadata according to Spec Page 06
    $title = $isFr
        ? "TEF IRN 2026 : épreuves, règles et préparation en ligne"
        : "TEF IRN 2026: Sections, Rules and Online Preparation";

    $metaDesc = $isFr
        ? "TEF IRN 2026 : 4 épreuves obligatoires, attestation valable 2 ans, A2, B1 ou B2 selon votre dossier. Pièges à éviter et préparation en ligne."
        : "TEF IRN in 2026: 4 compulsory sections, results valid 2 years, A2, B1 or B2 depending on your application. Common traps and live online prep.";

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/tef-irn/' : 'https://fluencefrancaise.fr/en/tef-irn/';
    $frUrl = 'https://fluencefrancaise.fr/fr/tef-irn/';
    $enUrl = 'https://fluencefrancaise.fr/en/tef-irn/';
    $tcfUrl = $isFr ? '/fr/tcf-irn/' : '/en/tcf-irn/';
    $compareUrl = $isFr ? '/fr/tcf-tef-ou-delf/' : '/en/tcf-vs-tef-vs-delf/';
    $permitUrl = $isFr ? '/fr/titre-de-sejour-niveau-francais/' : '/en/french-residence-permit-language/';
    $langUrl = $isFr ? '/fr/test-francais-naturalisation/' : '/en/french-citizenship-language-test/';
    $civicUrl = $isFr ? '/fr/examen-civique/' : '/en/french-civic-exam/';
    $delfB2Url = $isFr ? '/fr/delf-b2/' : '/en/delf-b2/';
    $linkClass = 'text-[#0055A4] font-semibold hover:underline';

    // FAQ Data
    $faqs = $isFr ? [
        [
            'q' => "Le TEF IRN est-il accepté pour la naturalisation ?",
            'a' => "Oui. L'attestation doit avoir moins de 2 ans et montrer un niveau global B2, selon les règles officielles de notation du TEF IRN, pour toute demande déposée depuis le 1er janvier 2026."
        ],
        [
            'q' => "TEF IRN ou TCF IRN : lequel choisir ?",
            'a' => 'Les 2 sont acceptés pour les mêmes démarches, avec la même validité de 2 ans. Choisissez selon les dates et les centres près de chez vous. Voir aussi le <a href="' . $tcfUrl . '" class="' . $linkClass . '">TCF IRN</a> et notre comparatif <a href="' . $compareUrl . '" class="' . $linkClass . '">TCF, TEF ou DELF</a>.'
        ],
        [
            'q' => "Que se passe-t-il si j'abandonne une épreuve ?",
            'a' => "Vous ne recevez pas d'attestation. Les 4 épreuves du TEF IRN sont indissociables : il faut toutes les passer."
        ],
        [
            'q' => "Quand reçoit-on les résultats ?",
            'a' => "Sous 1 à 10 jours ouvrés après le test, selon Le français des affaires."
        ],
        [
            'q' => "Existe-t-il un TEF IRN en ligne gratuit ?",
            'a' => "Le test officiel se passe dans un centre agréé et il est payant. Les entraînements gratuits en ligne servent à vous situer, sans valeur dans un dossier. Pour savoir où vous en êtes, votre premier cours mesure vos 4 compétences."
        ],
        [
            'q' => "Le TEF IRN est-il plus facile que le TCF IRN ?",
            'a' => "Aucun des 2 n'est « plus facile » : ils mesurent les mêmes niveaux. Ce qui change, c'est le format. Un candidat à l'aise avec l'un peut l'être moins avec l'autre, d'où l'intérêt de s'entraîner sur le bon."
        ],
        [
            'q' => "Fluence Française fait-elle passer le TEF IRN ?",
            'a' => "Non. Nous préparons au test ; l'inscription se fait auprès d'un centre agréé par Le français des affaires."
        ]
    ] : [
        [
            'q' => "Is the TEF IRN accepted for French citizenship?",
            'a' => "Yes. Your results must be under 2 years old and show an overall B2 level under the official TEF IRN scoring rules for any application filed since 1 January 2026."
        ],
        [
            'q' => "TEF IRN or TCF IRN: which should I take?",
            'a' => 'Both are accepted for the same applications, with the same 2-year validity. Choose based on dates and test centres near you. See the <a href="' . $tcfUrl . '" class="' . $linkClass . '">TCF IRN</a> and our <a href="' . $compareUrl . '" class="' . $linkClass . '">TCF vs TEF vs DELF comparison</a>.'
        ],
        [
            'q' => "What happens if I leave a section?",
            'a' => "You get no results. The TEF IRN's 4 sections can't be separated: you have to sit them all."
        ],
        [
            'q' => "When do I get my results?",
            'a' => "Within 1 to 10 working days after the test, according to Le français des affaires."
        ],
        [
            'q' => "Is there a free TEF IRN practice test online?",
            'a' => "The real test is taken at an approved centre, and it's paid. Free online practice tells you where you stand but has no value in an application. To see where you stand, your first class measures all 4 skills."
        ],
        [
            'q' => "Is the TEF IRN easier than the TCF IRN?",
            'a' => "Neither is “easier”: they measure the same levels. What differs is the format. Someone comfortable with one can struggle with the other, which is why you train for the one you'll take."
        ],
        [
            'q' => "Does Fluence Française run the TEF IRN?",
            'a' => "No. We prepare you for it. You register with a centre approved by Le français des affaires."
        ]
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

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo e($title); ?> | <?php echo e($siteName); ?>">
    <meta property="og:description" content="<?php echo e($metaDesc); ?>">
    <meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
    <meta property="og:site_name" content="<?php echo e($siteName); ?>">

    <!-- Schema.org JSON-LD -->
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
                "courseMode": "online"
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
                        "name": "TEF IRN",
                        "item": "<?php echo e($canonicalUrl); ?>"
                    }
                ]
            },
            {
                "@type": "FAQPage",
                "mainEntity": [
                    <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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

    <!-- Tailwind Play CDN & Google Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            200: '#bae0fd',
                            300: '#7cc5fb',
                            400: '#36a7f7',
                            500: '#0055a4',
                            600: '#00478a',
                            700: '#003970',
                            800: '#002654',
                            900: '#001a3a',
                        },
                        navy: {
                            800: '#002654',
                            900: '#001a3a',
                            950: '#001026',
                        },
                        tricolor: {
                            blue: '#0055A4',
                            white: '#FFFFFF',
                            red: '#EF4135',
                        }
                    },
                    fontFamily: {
                        sans: ['Urbanist', 'system-ui', 'sans-serif'],
                        display: ['Urbanist', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <?php echo $__env->make('partials.favicon', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Shared tricolor theme (fonts, tailwind config, components, aurora) -->
    <?php echo $__env->make('partials.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('partials.custom-scripts-head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body class="antialiased bg-white text-ink">
    <?php echo $__env->make('partials.custom-scripts-body', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="landing-page min-h-screen flex flex-col">
        <!-- Top Site Header (Floating pill navbar) -->
        <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- MAIN PAGE CONTENT -->
        <main class="flex-grow">
            <!-- hero -->
            <section
                class="relative overflow-hidden bg-white pt-28 sm:pt-36 md:pt-40 pb-16 md:pb-20 border-b border-slate-100">
                <div class="hero-aurora-strong"></div>

                <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="max-w-3xl mx-auto text-center reveal">
                        <div class="mb-5 flex justify-center">
                            <nav aria-label="Breadcrumb"
                                class="text-xs text-slate-400 font-medium flex items-center gap-2">
                                <a href="<?php echo e($isFr ? '/fr' : '/en'); ?>"
                                    class="hover:text-primary-600 transition-colors"><?php echo e($isFr ? 'Accueil' : 'Home'); ?></a>
                                <span class="text-slate-300">›</span>
                                <a href="<?php echo e($isFr ? '/fr#exams' : '/en#exams'); ?>"
                                    class="hover:text-primary-600 transition-colors"><?php echo e($isFr ? 'Examens' : 'Exams'); ?></a>
                                <span class="text-slate-300">›</span>
                                <span class="text-slate-700 font-semibold">TEF IRN</span>
                            </nav>
                        </div>

                        <div class="inline-flex items-center justify-center gap-1.5 mb-5">
                            <span class="inline-block w-3.5 h-[3px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[3px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'VOTRE PROCHAIN CHAPITRE COMMENCE PAR LA BONNE PRÉPARATION' : 'YOUR NEXT CHAPTER STARTS WITH THE RIGHT PREPARATION'); ?>

                            </span>
                        </div>

                        <h1
                            class="text-3xl sm:text-5xl lg:text-[56px] font-bold text-[#0c1e38] tracking-medium leading-[1.30] sm:leading-[1.32]">
                            <?php if($isFr): ?>
                                TEF IRN : préparez les 4 épreuves,<br class="hidden sm:inline" />
                                <span class="relative inline-block text-[#0055a4] mt-1 sm:mt-1.5 pb-2.5 sm:pb-3.5">
                                    sans exception
                                    <span class="absolute left-0 right-0 bottom-0 h-[2.5px] sm:h-[3px] rounded-full"
                                        style="background: linear-gradient(90deg, #0055A4 0%, #0055A4 33.3%, #eaebf0 33.3%, #eaebf0 66.6%, #EF4135 66.6%, #EF4135 100%);"></span>
                                </span>
                            <?php else: ?>
                                TEF IRN: Prepare All 4 Sections,<br class="hidden sm:inline" />
                                <span class="relative inline-block text-[#0055a4] mt-1 sm:mt-1.5 pb-2.5 sm:pb-3.5">
                                    No Exceptions
                                    <span class="absolute left-0 right-0 bottom-0 h-[2.5px] sm:h-[3px] rounded-full"
                                        style="background: linear-gradient(90deg, #0055A4 0%, #0055A4 33.3%, #eaebf0 33.3%, #eaebf0 66.6%, #EF4135 66.6%, #EF4135 100%);"></span>
                                </span>
                            <?php endif; ?>
                        </h1>

                        <p
                            class="mt-5 sm:mt-6 text-sm sm:text-base md:text-[17px] text-slate-600 max-w-2xl mx-auto leading-relaxed font-normal">
                            <?php echo e($isFr
    ? "Au TEF IRN, abandonner une seule épreuve vous prive d'attestation : nous préparons les 4, au niveau exigé par votre dossier."
    : "On the TEF IRN, walking out of a single section costs you your results, so we prepare all 4, to the level your application needs."); ?>

                        </p>

                        <div class="mt-8 flex flex-col items-center gap-3">
                            <a href="/register"
                                class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055a4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5">
                                <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                                <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                            </a>
                            <p class="text-xs sm:text-[13px] text-slate-500 font-medium">
                                <?php echo e($isFr
    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
    : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                            </p>
                        </div>

                        <div class="mt-6 flex items-center justify-center gap-2 text-xs font-semibold text-slate-400">
                            <span class="text-slate-400 text-[9px]">●</span>
                            <span><?php echo e($isFr ? 'Exigences 2026' : '2026 requirements'); ?></span>
                            <span class="text-slate-300">/</span>
                            <span><?php echo e($isFr ? 'Mis à jour le 12 novembre 2026' : 'Updated 12 November 2026'); ?></span>
                        </div>

                        <div
                            class="mt-8 pt-8 border-t border-slate-100 flex flex-wrap items-center justify-center gap-6 sm:gap-10 text-xs sm:text-[13px] text-slate-500 font-medium">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#0055A4]"></span>
                                <span><?php echo e($isFr ? 'Les 4 épreuves travaillées' : 'All 4 sections covered'); ?></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#0055A4]"></span>
                                <span><?php echo e($isFr ? 'Examens blancs en conditions réelles' : 'Full mock exams in real conditions'); ?></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#EF4135]"></span>
                                <span><?php echo e($isFr ? 'Retour sur chaque devoir' : 'Feedback on every assignment'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- the tef irn at a glance -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-14 items-center">
                        <div class="lg:col-span-7 space-y-4 sm:space-y-5">
                            <div class="inline-flex items-center gap-1.5">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span
                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                    <?php echo e($isFr ? "CONNAÎTRE L'EXAMEN" : 'KNOW YOUR EXAM'); ?>

                                </span>
                            </div>

                            <h2
                                class="text-3xl sm:text-4xl md:text-[42px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                <?php echo e($isFr ? 'Le TEF IRN en bref' : 'The TEF IRN at a glance'); ?>

                            </h2>

                            <p class="text-sm sm:text-[15px] md:text-base text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? "Le TEF IRN (Test d'évaluation de français – Intégration, Résidence et Nationalité) est proposé par Le français des affaires, rattaché à la CCI Paris Île-de-France. Il est accepté pour la carte de séjour pluriannuelle, la carte de résident et la naturalisation. Ses 4 épreuves se passent dans la même session, et l'attestation est valable 2 ans."
    : "The TEF IRN (Test d'évaluation de français – Intégration, Résidence et Nationalité) is run by Le français des affaires, part of the Paris Chamber of Commerce. It's accepted for the multi-year residence permit, the 10-year resident card and French citizenship. All 4 sections are taken in 1 session, and results are valid for 2 years."); ?>

                            </p>

                            <div
                                class="pt-2 flex items-center gap-2.5 text-xs sm:text-[13px] text-slate-500 font-medium">
                                <svg class="w-4 h-4 text-[#0055A4] shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span><?php echo e($isFr ? 'Préparation en direct en ligne. Confiance totale le jour de l\'examen.' : 'Live online preparation. Real exam-day confidence.'); ?></span>
                            </div>

                            <div class="pt-1">
                                <a href="https://www.lefrancaisdesaffaires.fr/tests-diplomes/test-evaluation-francais-tef/tef-irn/"
                                    target="_blank" rel="noopener"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0055a4] hover:text-[#002654] group transition-colors">
                                    <span><?php echo e($isFr ? 'Règlement officiel Le français des affaires' : 'Official Le français des affaires guidelines'); ?></span>
                                    <i
                                        class="fas fa-arrow-up-right-from-square text-[10px] group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                </a>
                            </div>
                        </div>

                        <div class="lg:col-span-5">
                            <div
                                class="rounded-3xl bg-[#002654] text-white overflow-hidden relative">
                                    <div class="h-[3px] w-full flex">
                                        <span class="w-1/3 h-full bg-[#0055A4]"></span>
                                        <span class="w-1/3 h-full bg-white"></span>
                                        <span class="w-1/3 h-full bg-[#EF4135]"></span>
                                    </div>

                                    <div class="grid grid-cols-2 divide-x divide-white/10">
                                        <div class="divide-y divide-white/10">
                                            <div class="p-6 sm:p-7 space-y-2">
                                                <div class="text-blue-200/70">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.75"
                                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                </div>
                                                <div
                                                    class="text-3xl sm:text-4xl font-bold font-display text-white tracking-tight">
                                                    4</div>
                                                <div class="text-xs sm:text-[13px] text-blue-200/80 font-normal">
                                                    <?php echo e($isFr ? 'Épreuves obligatoires' : 'Compulsory sections'); ?>

                                                </div>
                                            </div>

                                            <div class="p-6 sm:p-7 space-y-2">
                                                <div class="text-blue-200/70">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.75"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div
                                                    class="text-3xl sm:text-4xl font-bold font-display text-white tracking-tight">
                                                    1–10 <?php echo e($isFr ? 'j' : 'days'); ?></div>
                                                <div class="text-xs sm:text-[13px] text-blue-200/80 font-normal">
                                                    <?php echo e($isFr ? 'Résultats rapides' : 'Fast results delay'); ?>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="divide-y divide-white/10">
                                            <div class="p-6 sm:p-7 space-y-2">
                                                <div class="text-blue-200/70">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.75"
                                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                    </svg>
                                                </div>
                                                <div
                                                    class="text-3xl sm:text-4xl font-bold font-display text-white tracking-tight">
                                                    1 <?php echo e($isFr ? 'session' : 'session'); ?></div>
                                                <div class="text-xs sm:text-[13px] text-blue-200/80 font-normal">
                                                    <?php echo e($isFr ? 'Toutes les 4 épreuves' : '1 complete session'); ?>

                                                </div>
                                            </div>

                                            <div class="p-6 sm:p-7 space-y-2">
                                                <div class="text-blue-200/70">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.75"
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                </div>
                                                <div
                                                    class="text-3xl sm:text-4xl font-bold font-display text-white tracking-tight">
                                                    2 <?php echo e($isFr ? 'ans' : 'years'); ?></div>
                                                <div class="text-xs sm:text-[13px] text-blue-200/80 font-normal">
                                                    <?php echo e($isFr ? 'Validité des résultats' : 'Results validity'); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="mt-10 sm:mt-12 bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl overflow-hidden">
                            <div class="divide-y divide-slate-100">
                                <div
                                    class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-[#f8fafc] items-center gap-2 sm:gap-4">
                                    <div class="sm:col-span-3 font-bold text-xs sm:text-sm text-[#10233e]">
                                        <?php echo e($isFr ? 'Pour qui' : "Who it's for"); ?>

                                    </div>
                                    <div
                                        class="sm:col-span-9 text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                        <?php echo e($isFr
    ? "Demandeurs d'une carte pluriannuelle, d'une carte de résident ou de la nationalité française"
    : "Anyone applying for a multi-year permit, a 10-year resident card or French citizenship"); ?>

                                    </div>
                                </div>

                                <div
                                    class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-white items-center gap-2 sm:gap-4">
                                    <div class="sm:col-span-3 font-bold text-xs sm:text-sm text-[#10233e]">
                                        <?php echo e($isFr ? 'Organisme' : 'Run by'); ?>

                                    </div>
                                    <div class="sm:col-span-9 text-xs sm:text-sm text-slate-600 font-normal">
                                        <?php echo e($isFr ? 'Le français des affaires (CCI Paris Île-de-France)' : 'Le français des affaires (Paris Chamber of Commerce)'); ?>

                                    </div>
                                </div>

                                <div
                                    class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-[#f8fafc] items-center gap-2 sm:gap-4">
                                    <div class="sm:col-span-3 font-bold text-xs sm:text-sm text-[#10233e]">
                                        <?php echo e($isFr ? 'Épreuves' : 'Sections'); ?>

                                    </div>
                                    <div class="sm:col-span-9 text-xs sm:text-sm text-slate-600 font-normal">
                                        <?php echo e($isFr
    ? 'Compréhension écrite, compréhension orale, expression écrite, expression orale'
    : 'Reading, listening, writing, speaking'); ?>

                                    </div>
                                </div>

                                <div
                                    class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-white items-center gap-2 sm:gap-4">
                                    <div class="sm:col-span-3 font-bold text-xs sm:text-sm text-[#10233e]">
                                        <?php echo e($isFr ? 'Résultats' : 'Results'); ?>

                                    </div>
                                    <div class="sm:col-span-9 text-xs sm:text-sm text-slate-600 font-normal">
                                        <?php echo e($isFr ? 'Sous 1 à 10 jours ouvrés après le test' : 'Within 1 to 10 working days after the test'); ?>

                                    </div>
                                </div>

                                <div
                                    class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-[#f8fafc] items-center gap-2 sm:gap-4">
                                    <div class="sm:col-span-3 font-bold text-xs sm:text-sm text-[#10233e]">
                                        <?php echo e($isFr ? 'Validité' : 'Validity'); ?>

                                    </div>
                                    <div class="sm:col-span-9 text-xs sm:text-sm text-slate-600 font-normal">
                                        <?php echo e($isFr ? '2 ans' : '2 years'); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>

            <!-- the rule many candidates learn too late -->
            <section class="py-16 sm:py-24 bg-[#f4f7fb] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-center">
                        <div class="lg:col-span-6 space-y-4">
                            <div class="inline-flex items-center gap-1.5">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span
                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                    <?php echo e($isFr ? 'UNE RÈGLE INDISSOCIABLE' : 'A CRUCIAL DIFFERENCE'); ?>

                                </span>
                            </div>

                            <h2
                                class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                <?php echo e($isFr ? 'La règle que beaucoup découvrent trop tard' : 'The rule many candidates learn too late'); ?>

                            </h2>

                            <div
                                class="space-y-4 text-sm sm:text-[15px] text-slate-500 font-normal leading-relaxed pt-1">
                                <p>
                                    <?php echo e($isFr
    ? "Les 4 épreuves du TEF IRN sont indissociables. Si vous abandonnez l'une d'elles, vous ne recevez aucune attestation, même pour celles que vous avez réussies."
    : "The TEF IRN's 4 sections can't be separated. Leave 1 of them, and you get no results at all, not even for the sections you passed."); ?>

                                </p>
                                <p>
                                    <?php echo e($isFr
    ? "Impossible donc de « sauter » l'oral parce que le stress monte. Et chaque compétence reçoit son propre niveau : pour la naturalisation, le résultat global du TEF IRN doit atteindre le B2 selon les règles officielles de calcul."
    : "So you can't skip the speaking test because your nerves kick in. And each skill gets its own level: for citizenship, your overall TEF IRN result must reach B2 under the official scoring rules."); ?>

                                </p>
                                <p>
                                    <?php echo e($isFr
    ? "Votre préparation doit donc couvrir les 4 épreuves, et votre endurance sur toute la session. C'est exactement ce que font nos examens blancs."
    : "Your prep has to cover all 4 sections, and your stamina across the whole session. That's exactly what our mock exams train."); ?>

                                </p>
                            </div>
                        </div>

                        <div class="lg:col-span-6">
                                <div class="bg-[#002654] text-white rounded-3xl  p-7 sm:p-8 relative overflow-hidden">
                                    <div class="absolute inset-x-0 top-0 h-1 flex">
                                        <span class="w-1/3 bg-[#0055A4]"></span>
                                        <span class="w-1/3 bg-white"></span>
                                        <span class="w-1/3 bg-[#EF4135]"></span>
                                    </div>

                                    <div class="flex items-center gap-3.5 pb-6 border-b border-white/10">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-white text-base sm:text-[17px] leading-snug">
                                                <?php echo e($isFr ? '4 épreuves : 1 seule session complète.' : '4 sections: 1 complete session.'); ?>

                                            </h3>
                                            <p class="text-xs text-blue-200/80 font-normal">
                                                <?php echo e($isFr ? '1 épreuve abandonnée = 0 attestation.' : 'Leave 1 section = 0 results.'); ?>

                                            </p>
                                        </div>
                                    </div>

                                    <div class="py-6 grid grid-cols-2 gap-3.5">
                                        <div
                                            class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-3">
                                            <svg class="w-4 h-4 text-blue-300 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            <span
                                                class="text-xs sm:text-sm font-semibold text-white"><?php echo e($isFr ? 'Compréhension écrite' : 'Reading'); ?></span>
                                        </div>

                                        <div
                                            class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-3">
                                            <svg class="w-4 h-4 text-blue-300 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                    d="M3 18v-6a9 9 0 0118 0v6M3 15h3a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-4zm18 0h-3a2 2 0 00-2 2v2a2 2 0 002 2h1a2 2 0 002-2v-4z" />
                                            </svg>
                                            <span
                                                class="text-xs sm:text-sm font-semibold text-white"><?php echo e($isFr ? 'Compréhension orale' : 'Listening'); ?></span>
                                        </div>

                                        <div
                                            class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-3">
                                            <svg class="w-4 h-4 text-blue-300 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            <span
                                                class="text-xs sm:text-sm font-semibold text-white"><?php echo e($isFr ? 'Expression écrite' : 'Writing'); ?></span>
                                        </div>

                                        <div
                                            class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-3">
                                            <svg class="w-4 h-4 text-blue-300 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                    d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z" />
                                            </svg>
                                            <span
                                                class="text-xs sm:text-sm font-semibold text-white"><?php echo e($isFr ? 'Expression orale' : 'Speaking'); ?></span>
                                        </div>
                                    </div>

                                    <div
                                        class="pt-5 border-t border-white/10 flex items-center gap-2 text-xs text-blue-200/90 font-medium">
                                        <span
                                            class="inline-block w-1.5 h-1.5 rounded-full bg-[#EF4135] shrink-0"></span>
                                        <span><?php echo e($isFr ? 'Les 4 épreuves doivent obligatoirement être passées ensemble.' : 'All 4 sections must be completed in the same session.'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>

            <!-- the 4 sections of the tef irn -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'STRUCTURE DES ÉPREUVES' : 'EXAM STRUCTURE'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Les 4 épreuves du TEF IRN' : 'The 4 sections of the TEF IRN'); ?>

                        </h2>
                        <p
                            class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto font-normal">
                            <?php echo e($isFr
    ? "Le TEF IRN comprend 2 épreuves de compréhension sous forme de questionnaires à choix multiple, et 2 épreuves d'expression. L'expression écrite dure 30 minutes pour 2 sujets. L'expression orale dure 10 minutes, face à un examinateur, pour 2 sujets également."
    : "The TEF IRN has 2 comprehension sections, in multiple-choice format, and 2 production sections. Writing takes 30 minutes for 2 tasks. Speaking takes 10 minutes with an examiner, also for 2 tasks."); ?>

                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
                        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-[#10233e]">30
                                        min</span>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Compréhension écrite' : 'Reading'); ?>

                                </h3>
                                <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr ? 'Questions à choix multiple sur des documents écrits (30 min)' : 'Multiple-choice questions on written documents (30 min)'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                d="M3 18v-6a9 9 0 0118 0v6M3 15h3a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-4zm18 0h-3a2 2 0 00-2 2v2a2 2 0 002 2h1a2 2 0 002-2v-4z" />
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Compréhension orale' : 'Listening'); ?>

                                </h3>
                                <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr ? 'Questions à choix multiple sur des documents audio' : 'Multiple-choice questions on audio documents'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-[#10233e]">30
                                        min</span>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Expression écrite' : 'Writing'); ?>

                                </h3>
                                <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr ? '2 sujets en 30 minutes' : '2 tasks in 30 minutes'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z" />
                                        </svg>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-[#10233e]">10
                                        min</span>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Expression orale' : 'Speaking'); ?>

                                </h3>
                                <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr ? '2 sujets en 10 minutes, avec un examinateur' : '2 tasks in 10 minutes, with an examiner'); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-8 flex items-center justify-center gap-2.5 py-3 px-6 rounded-full bg-slate-50 border border-slate-200/80 text-xs sm:text-sm font-semibold text-[#10233e] max-w-fit mx-auto">
                        <svg class="w-4 h-4 text-[#0055A4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        <a href="https://www.lefrancaisdesaffaires.fr/" target="_blank" rel="noopener"
                            class="hover:underline">
                            <span><?php echo e($isFr ? 'Le format détaillé de chaque épreuve est publié par Le français des affaires' : 'The full format for each section is published by Le français des affaires'); ?></span>
                            <span class="ml-1 text-[11px]">&rarr;</span>
                        </a>
                    </div>
                </div>
            </section>

            <!-- where tef irn candidates lose points -->
            <section class="py-16 sm:py-24 bg-[#f4f7fb] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'OÙ SE PERDENT LES POINTS' : 'EXAM TRAPS'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Là où les candidats perdent des points au TEF IRN' : 'Where TEF IRN candidates lose points'); ?>

                        </h2>
                        <p
                            class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto font-normal">
                            <?php echo e($isFr
    ? 'Les 4 pièges les plus fréquents et notre méthode pour les déjouer.'
    : 'The 4 most common traps and how our tutors train you to beat them.'); ?>

                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60 flex items-center gap-1.5">
                                        <i class="fas fa-triangle-exclamation text-[10px]"></i>
                                        <span><?php echo e($isFr ? 'Piège 1' : 'Trap 1'); ?></span>
                                    </span>
                                </div>
                                <h3 class="text-lg sm:text-xl font-bold text-[#10233e]">
                                    <?php echo e($isFr ? "L'endurance sur 4 épreuves" : 'Stamina across 4 sections'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? "Tenir son niveau sur 1 épreuve est une chose. Le tenir jusqu'à la quatrième, quand la fatigue arrive, en est une autre. Or la dernière épreuve compte autant que la première."
    : "Holding your level for 1 section is one thing. Holding it through the fourth, when fatigue sets in, is another. The last section counts as much as the first."); ?>

                                </p>
                            </div>
                            <div class="mt-6 pt-4 bg-[#f2f7fc] rounded-2xl p-4 border border-blue-100/70">
                                <div
                                    class="text-xs font-bold text-[#0055a4] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#0055A4]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed font-normal">
                                    <?php echo e($isFr ? 'Des examens blancs complets, en une seule séance, comme le jour J.' : 'Full mock exams in a single sitting, just like test day.'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60 flex items-center gap-1.5">
                                        <i class="fas fa-triangle-exclamation text-[10px]"></i>
                                        <span><?php echo e($isFr ? 'Piège 2' : 'Trap 2'); ?></span>
                                    </span>
                                </div>
                                <h3 class="text-lg sm:text-xl font-bold text-[#10233e]">
                                    <?php echo e($isFr ? '2 textes en 30 minutes' : '2 texts in 30 minutes'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? "Cela laisse 15 minutes par texte, relecture comprise. Le piège : passer 20 minutes sur le premier et bâcler le second."
    : "That's 15 minutes a text, rereading included. The trap: spending 20 minutes on the first and rushing the second."); ?>

                                </p>
                            </div>
                            <div class="mt-6 pt-4 bg-[#f2f7fc] rounded-2xl p-4 border border-blue-100/70">
                                <div
                                    class="text-xs font-bold text-[#0055a4] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#0055A4]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed font-normal">
                                    <?php echo e($isFr ? 'Un plan de temps strict et des structures de texte prêtes à adapter.' : 'A strict time plan and text structures you can adapt on the spot.'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60 flex items-center gap-1.5">
                                        <i class="fas fa-triangle-exclamation text-[10px]"></i>
                                        <span><?php echo e($isFr ? 'Piège 3' : 'Trap 3'); ?></span>
                                    </span>
                                </div>
                                <h3 class="text-lg sm:text-xl font-bold text-[#10233e]">
                                    <?php echo e($isFr ? '2 sujets en 10 minutes à l\'oral' : '2 speaking tasks in 10 minutes'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? "5 minutes par sujet en moyenne : pas le temps de chercher ses mots. Les candidats qui hésitent longtemps au début n'ont plus le temps de montrer leur niveau."
    : "About 5 minutes a task: no time to hunt for words. Candidates who hesitate at the start run out of time to show their level."); ?>

                                </p>
                            </div>
                            <div class="mt-6 pt-4 bg-[#f2f7fc] rounded-2xl p-4 border border-blue-100/70">
                                <div
                                    class="text-xs font-bold text-[#0055a4] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#0055A4]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed font-normal">
                                    <?php echo e($isFr ? "Des phrases d'ouverture, de relance et de conclusion, répétées jusqu'à l'automatisme." : "Opening, follow-up and closing phrases, drilled until they're automatic."); ?>

                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60 flex items-center gap-1.5">
                                        <i class="fas fa-triangle-exclamation text-[10px]"></i>
                                        <span><?php echo e($isFr ? 'Piège 4' : 'Trap 4'); ?></span>
                                    </span>
                                </div>
                                <h3 class="text-lg sm:text-xl font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Une compétence en retard' : '1 skill lagging behind'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? "Chaque épreuve a son niveau, mais le TEF IRN calcule aussi un niveau global. Une épreuve légèrement plus faible peut être acceptée selon les règles officielles de calcul."
    : "Each section has its own level, but the TEF IRN also calculates an overall level. One slightly weaker section can be accepted under the official scoring rules."); ?>

                                </p>
                            </div>
                            <div class="mt-6 pt-4 bg-[#f2f7fc] rounded-2xl p-4 border border-blue-100/70">
                                <div
                                    class="text-xs font-bold text-[#0055a4] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#0055A4]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed font-normal">
                                    <?php echo e($isFr ? 'Nous mesurons chaque compétence au premier cours, et les heures vont à la plus faible.' : 'We measure every skill in your first class, and your hours go to the weakest.'); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- how we prepare you for the tef irn -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'UN ENSEIGNEMENT EN DIRECT. UN PLAN QUI VOUS CORRESPOND.' : 'LIVE TEACHING. A PLAN THAT FITS YOU.'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Notre préparation au TEF IRN' : 'How we prepare you for the TEF IRN'); ?>

                        </h2>
                    </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                                    <div class="flex flex-col items-start">
                                        <div class="relative flex items-center w-full mb-4">
                                            <span
                                                class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055a4] text-sm
                                                font-semibold flex items-center justify-center border border-blue-100
                                                flex-shrink-0">
                                                01
                                            </span>
                                            <div class="hidden lg:block flex-1 h-[1px] bg-[#e1eaf2] ml-4 -mr-4"></div>
                                        </div>
                                        <h3 class="font-bold text-[#10233e] text-base sm:text-[17px] mb-2">
                                            <?php echo e($isFr ? 'Vos 4 compétences, mesurées' : 'Your 4 skills, measured'); ?>

                                        </h3>
                                        <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal">
                                            <?php echo e($isFr
    ? 'Dès le premier cours, votre tuteur situe chaque épreuve face au niveau de votre dossier.'
    : 'In your first class, your tutor places each section against the level your application needs.'); ?>

                                    </p>
                                </div>

                                        <div class="flex flex-col items-start">
                                            <div class="relative flex items-center w-full mb-4">
                                                <span
                                                    class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055a4] text-sm
                                                    font-semibold flex items-center justify-center border
                                                    border-blue-100 flex-shrink-0">
                                                    02
                                                </span>
                                                <div class="hidden lg:block flex-1 h-[1px] bg-[#e1eaf2] ml-4 -mr-4">
                                                </div>
                                            </div>
                                            <h3 class="font-bold text-[#10233e] text-base sm:text-[17px] mb-2">
                                                <?php echo e($isFr ? 'Un plan construit sur votre écart' : 'A plan built on your gap'); ?>

                                            </h3>
                                            <p
                                                class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal">
                                                <?php echo e($isFr
    ? "Les heures vont à l'épreuve la plus faible, sans négliger les autres."
    : 'Your hours go to your weakest section, without dropping the others.'); ?>

                                        </p>
                                    </div>

                                            <div class="flex flex-col items-start">
                                                <div class="relative flex items-center w-full mb-4">
                                                    <span
                                                        class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055a4]
                                                        text-sm font-semibold flex items-center justify-center border
                                                        border-blue-100 flex-shrink-0">
                                                        03
                                                    </span>
                                                    <div class="hidden lg:block flex-1 h-[1px] bg-[#e1eaf2] ml-4 -mr-4">
                                                    </div>
                                                </div>
                                                <h3 class="font-bold text-[#10233e] text-base sm:text-[17px] mb-2">
                                                    <?php echo e($isFr ? 'Des cours en direct, 2 ou 4 h par semaine' : 'Live classes, 2 or 4 hours a week'); ?>

                                                </h3>
                                                <p
                                                    class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal">
                                                    <?php echo e($isFr
    ? 'En particulier ou en mini-groupe de 2 à 5 personnes, sur Google Meet, avec vos notes après chaque séance.'
    : '1:1 or in a group of 2–5 on Google Meet, with notes after every session.'); ?>

                                            </p>
                                        </div>

                                                <div class="flex flex-col items-start">
                                                    <div class="relative flex items-center w-full mb-4">
                                                        <span
                                                            class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055a4]
                                                            text-sm font-semibold flex items-center justify-center
                                                            border border-blue-100 flex-shrink-0">
                                                            04
                                                        </span>
                                                    </div>
                                                    <h3 class="font-bold text-[#10233e] text-base sm:text-[17px] mb-2">
                                                        <?php echo e($isFr ? 'Des examens blancs complets' : 'Full mock exams'); ?>

                                                    </h3>
                                                    <p
                                                        class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal">
                                                        <?php echo e($isFr
    ? "Les 4 épreuves d'affilée, au chrono, dans votre espace élève. Chaque devoir reçoit un retour."
    : 'All 4 sections back to back, timed, in your student portal. Every
                                                assignment gets feedback.'); ?>

                                                </p>
                                            </div>
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

            <!-- what tef irn level do you need? -->
            <section class="py-16 sm:py-24 bg-[#f4f7fb] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'QUEL NIVEAU VISER ?' : 'WHICH LEVEL DO YOU NEED?'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Quel niveau viser au TEF IRN ?' : 'What TEF IRN level do you need?'); ?>

                        </h2>
                        <p
                            class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto font-normal">
                            <?php echo e($isFr
    ? 'A2 pour une première carte de séjour pluriannuelle, B1 pour la carte de résident, B2 pour la naturalisation. Ces niveaux s\'appliquent depuis le 1er janvier 2026. Un examen civique est aussi exigé pour la naturalisation par décret et pour les titres de séjour concernés par les règles de 2026.'
    : 'A2 for a first multi-year residence permit, B1 for the 10-year resident card, B2 for citizenship. These levels have applied since 1 January 2026. The civic exam is also required for citizenship by decree and for the residence permit applications covered by the 2026 rules.'); ?>

                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div>
                                <span class="text-3xl font-extrabold text-[#0055a4]">A2</span>
                                <h3 class="font-bold text-[#10233e] text-lg mt-2">
                                    <?php echo e($isFr ? 'Carte de séjour pluriannuelle' : 'Multi-year residence permit'); ?>

                                </h3>
                            </div>
                            <div class="pt-6">
                                <a href="<?php echo e($permitUrl); ?>"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0055a4] hover:underline">
                                    <span><?php echo e($isFr ? 'Niveau de français pour un titre de séjour' : 'French level for residence permits'); ?></span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div>
                                <span class="text-3xl font-extrabold text-[#0055a4]">B1</span>
                                <h3 class="font-bold text-[#10233e] text-lg mt-2">
                                    <?php echo e($isFr ? 'Carte de résident' : '10-year resident card'); ?>

                                </h3>
                            </div>
                            <div class="pt-6">
                                <a href="<?php echo e($permitUrl); ?>"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0055a4] hover:underline">
                                    <span><?php echo e($isFr ? 'Niveau de français pour un titre de séjour' : 'French level for residence permits'); ?></span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>

                        <div
                            class="bg-[#002654] text-white rounded-3xl p-7 flex flex-col justify-between relative overflow-hidden">
                            <div class="absolute inset-x-0 top-0 h-1 flex">
                                <span class="w-1/3 bg-[#0055A4]"></span>
                                <span class="w-1/3 bg-white"></span>
                                <span class="w-1/3 bg-[#EF4135]"></span>
                            </div>
                            <div>
                                <span class="text-3xl font-extrabold text-white">B2</span>
                                <h3 class="font-bold text-white text-lg mt-2">
                                    <?php echo e($isFr ? 'Naturalisation' : 'French citizenship'); ?>

                                </h3>
                            </div>
                            <div class="pt-6">
                                <a href="<?php echo e($langUrl); ?>"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-white hover:underline">
                                    <span><?php echo e($isFr ? 'Test de français pour la naturalisation' : 'The French citizenship language test'); ?></span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-8 p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs sm:text-sm">
                        <div class="text-slate-600 font-normal text-center sm:text-left">
                            <?php if($isFr): ?>
                                Pour la naturalisation par décret et les titres de séjour concernés, pensez aussi à l'<a href="<?php echo e($civicUrl); ?>" class="<?php echo e($linkClass); ?>">examen civique</a>.
                            <?php else: ?>
                                Citizenship by decree and the residence permits covered by the 2026 rules also need the <a href="<?php echo e($civicUrl); ?>" class="<?php echo e($linkClass); ?>">French civic exam</a>.
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- faq -->
            <?php echo $__env->make('partials.faq-section', [
                'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                'title' => $isFr ? 'Questions fréquentes<br>sur le TEF IRN' : 'TEF IRN<br>FAQ',
                'description' => $isFr ? "Des réponses claires avant de passer à l'étape suivante." : 'Clear answers before you take your next step.',
                'contactText' => $isFr ? 'Une autre question ?' : 'Have another question?',
                'contactUrl' => $isFr ? '/fr/contact/' : '/en/contact/',
                'faqs' => $faqs
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- final cta & sources -->
            <section class="py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <?php echo $__env->make('partials.cta-banner', [
                        'sectionClass' => '',
                        'eyebrow' => $isFr ? 'NOUS VOUS ACCOMPAGNONS' : "LET'S GET YOU THERE",
                        'title' => $isFr
                            ? "Votre niveau. Le niveau exigé.<br class='hidden sm:inline'> Nous comblons l'écart."
                            : "Your level. The required level.<br class='hidden sm:inline'> We close the gap.",
                        'description' => $isFr
                            ? 'Votre premier cours situe vos 4 compétences face au niveau de votre dossier. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                            : 'Your first class places your 4 skills against the level your application needs. You choose the pace: 2 or 4 hours a week.',
                        'primaryButtonText' => $isFr ? "S'inscrire" : 'Enroll now',
                        'primaryButtonUrl' => '/register',
                        'showSecondaryButton' => true,
                        'secondaryButtonText' => $isFr ? 'Contactez-nous' : 'Talk to us',
                        'secondaryButtonUrl' => $isFr ? '/fr/contact/' : '/en/contact/',
                        'helperText' => $isFr
                            ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                            : 'Live classes on Google Meet · 1:1 or small group · Student portal included'
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="mt-8 text-center text-sm sm:text-[15px] text-slate-500 font-normal">
                        <span><?php echo e($isFr ? 'Vous venez à deux ? 50 € de remise par mois, pour vous et pour votre ami.' : 'Coming with a friend? €50 off per month, for you and for them.'); ?></span>
                        <a href="<?php echo e($isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral'); ?>"
                            class="text-[#0055a4] font-semibold hover:underline ml-1 inline-flex items-center gap-1 group">
                            <span><?php echo e($isFr ? 'Contactez-nous' : 'Contact us'); ?></span>
                            <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </a>
                    </div>

                    <div class="mt-2 text-center text-xs text-slate-400">
                        <?php if($isFr): ?>
                            Votre calendrier est incertain ? Le <a href="<?php echo e($delfB2Url); ?>" class="<?php echo e($linkClass); ?>">DELF B2</a> est un diplôme qui n'expire jamais.
                        <?php else: ?>
                            Unsure of your timeline? The <a href="<?php echo e($delfB2Url); ?>" class="<?php echo e($linkClass); ?>">DELF B2</a> is a diploma that never expires.
                        <?php endif; ?>
                    </div>

                    <div class="my-6 sm:my-8 border-t border-[#edf1f5] max-w-5xl mx-auto"></div>

                    <div
                        class="flex flex-wrap items-center justify-center gap-y-2 text-center text-xs sm:text-[13px] text-slate-400 font-normal">
                        <span
                            class="font-bold tracking-[0.18em] uppercase text-slate-400 mr-3.5"><?php echo e($isFr ? 'SOURCES' : 'SOURCES'); ?></span>
                        <a href="https://www.lefrancaisdesaffaires.fr/tests-diplomes/test-evaluation-francais-tef/tef-irn/"
                            target="_blank" rel="noopener"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1 group">
                            <span>Le français des affaires – TEF IRN</span>
                            <span
                                class="text-[10px] group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform">&#8599;</span>
                        </a>
                        <span class="text-slate-300 mx-2.5 sm:mx-3.5">&middot;</span>
                        <a href="https://www.service-public.fr/particuliers/vosdroits/F2213" target="_blank"
                            rel="noopener"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1 group">
                            <span>Service-public.fr – <?php echo e($isFr ? 'naturalisation' : 'citizenship'); ?></span>
                            <span
                                class="text-[10px] group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform">&#8599;</span>
                        </a>
                        <span class="text-slate-300 mx-2.5 sm:mx-3.5">&middot;</span>
                        <a href="https://www.service-public.fr/particuliers/vosdroits/N110" target="_blank"
                            rel="noopener"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1 group">
                            <span>Service-public.fr – <?php echo e($isFr ? 'cartes de séjour' : 'residence cards'); ?></span>
                            <span
                                class="text-[10px] group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform">&#8599;</span>
                        </a>
                    </div>
                </div>
            </section>
        </main>

        <!-- FOOTER: Standard Unified Component -->
        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</body>

</html><?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/tef-irn.blade.php ENDPATH**/ ?>