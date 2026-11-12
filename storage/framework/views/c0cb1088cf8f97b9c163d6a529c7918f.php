<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';

    // Determine language based on current URL prefix
    $isFr = request()->is('fr*');

    // Page metadata according to Spec Page 02
    $title = $isFr
        ? "TCF IRN 2026 : format, notation et préparation en ligne"
        : "TCF IRN 2026: Format, Scoring and Online Preparation";

    $metaDesc = $isFr
        ? "TCF IRN 2026 : 4 épreuves, 1 h 35, niveau B2 pour la naturalisation. Format détaillé, pièges à éviter et préparation en ligne avec un tuteur."
        : "TCF IRN in 2026: 4 sections, 1 h 35, and B2 now required for French citizenship. Full format, common traps and live online prep with a tutor.";

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/tcf-irn/' : 'https://fluencefrancaise.fr/en/tcf-irn/';
    $frUrl = 'https://fluencefrancaise.fr/fr/tcf-irn/';
    $enUrl = 'https://fluencefrancaise.fr/en/tcf-irn/';
    $permitUrl = $isFr ? '/fr/titre-de-sejour-niveau-francais/' : '/en/french-residence-permit-language/';
    $langUrl = $isFr ? '/fr/test-francais-naturalisation/' : '/en/french-citizenship-language-test/';
    $civicUrl = $isFr ? '/fr/examen-civique/' : '/en/french-civic-exam/';
    $compareUrl = $isFr ? '/fr/tcf-tef-ou-delf/' : '/en/tcf-vs-tef-vs-delf/';
    $delfB2Url = $isFr ? '/fr/delf-b2/' : '/en/delf-b2/';
    $link = 'class="font-semibold text-[#0055A4] hover:underline"';

    $facts = $isFr
        ? ['4 compétences travaillées', 'Examens blancs dans votre espace élève', 'Notes de cours après chaque séance']
        : ['All 4 skills covered', 'Mock exams in your student portal', 'Class notes after every session'];

    // FAQ Data
    $faqs = $isFr ? [
        [
            'q' => "Le TCF IRN est-il accepté pour la naturalisation ?",
            'a' => "Oui. L'attestation doit avoir moins de 2 ans et montrer un niveau global B2, selon les règles de notation du TCF IRN. Pour les dossiers déposés avant le 1er janvier 2026, l'ancien niveau B1 s'applique."
        ],
        [
            'q' => "Combien de temps l'attestation est-elle valable ?",
            'a' => '2 ans à compter de la délivrance de votre attestation. Si votre calendrier est incertain, regardez aussi le <a href="/fr/delf-b2/" class="font-semibold text-[#0055A4] hover:underline">DELF B2</a> : c\'est un diplôme, et il n\'expire jamais.'
        ],
        [
            'q' => "Quelle différence entre le TCF IRN et le TCF tout public ?",
            'a' => "Le TCF IRN est la version conçue pour l'intégration, la résidence et la nationalité en France. Il évalue de A1 à B2, les niveaux demandés pour ces démarches. Le TCF tout public sert surtout aux études et au travail."
        ],
        [
            'q' => "Combien de temps faut-il pour passer de B1 à B2 ?",
            'a' => "Il n'existe pas de durée universelle. Tout dépend de votre écart, épreuve par épreuve : un candidat B2 à l'écrit et B1 à l'oral n'a pas le même chemin qu'un candidat B1 partout. Votre premier cours sert à l'estimer."
        ],
        [
            'q' => "Peut-on passer le TCF IRN en ligne ?",
            'a' => "Non. Le test se passe dans un centre agréé. La préparation, elle, se fait entièrement en ligne, en direct avec votre tuteur."
        ],
        [
            'q' => "Peut-on repasser le TCF IRN ?",
            'a' => "Oui, et chaque tentative est payante. Votre centre vous indique quand vous pouvez vous réinscrire. Mieux vaut s'inscrire quand vos examens blancs atteignent le niveau visé dans les 4 épreuves."
        ],
        [
            'q' => "Fluence Française fait-elle passer le TCF IRN ?",
            'a' => "Non. Nous préparons au test ; l'inscription se fait auprès d'un centre agréé par France Éducation international."
        ]
    ] : [
        [
            'q' => "Is the TCF IRN accepted for French citizenship?",
            'a' => "Yes. Your results must be under 2 years old and show an overall B2 level under the TCF IRN scoring rules. Applications filed before 1 January 2026 are judged on the old B1 level."
        ],
        [
            'q' => "How long are TCF IRN results valid?",
            'a' => '2 years from the date your attestation is issued. If your timeline is uncertain, look at the <a href="/en/delf-b2/" class="font-semibold text-[#0055A4] hover:underline">DELF B2</a> too: it\'s a diploma, and it never expires.'
        ],
        [
            'q' => "What's the difference between the TCF IRN and the TCF Tout Public?",
            'a' => "The TCF IRN is the version built for integration, residence and citizenship in France. It tests A1 to B2, the levels those applications ask for. The TCF Tout Public is mainly used for study and work."
        ],
        [
            'q' => "How long does it take to go from B1 to B2?",
            'a' => "There's no universal answer. It depends on your gap in each section: someone at B2 in writing and B1 in speaking has a shorter road than someone at B1 everywhere. Your first class gives you an estimate."
        ],
        [
            'q' => "Can I take the TCF IRN online?",
            'a' => "No. You take it at an approved test centre. The preparation is fully online, live with your tutor."
        ],
        [
            'q' => "Can I retake the TCF IRN?",
            'a' => "Yes, and each attempt costs a new fee. Your test centre tells you when you can re-register. It's better to book once your mock exams hit the target level in all 4 sections."
        ],
        [
            'q' => "Does Fluence Française run the TCF IRN?",
            'a' => "No. We prepare you for it. You register with a test centre approved by France Éducation international."
        ]
    ];
?>
<html lang="<?php echo e($isFr ? 'fr' : 'en'); ?>" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?></title>
    <meta name="description" content="<?php echo e($metaDesc); ?>">
    <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
    <link rel="alternate" hreflang="fr" href="<?php echo e($frUrl); ?>">
    <link rel="alternate" hreflang="en" href="<?php echo e($enUrl); ?>">
    <link rel="alternate" hreflang="x-default" href="<?php echo e($enUrl); ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo e($title); ?>">
    <meta property="og:description" content="<?php echo e($metaDesc); ?>">
    <meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo e($siteName); ?>">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "Course",
          "name": "<?php echo e($isFr ? 'Préparation au TCF IRN en ligne' : 'TCF IRN Online Preparation'); ?>",
          "description": "<?php echo e($metaDesc); ?>",
          "provider": {
            "@type": "EducationalOrganization",
            "name": "Fluence Française",
            "url": "https://fluencefrancaise.fr"
          },
          "courseMode": "online",
          "inLanguage": ["fr", "en"]
        },
        {
          "@type": "BreadcrumbList",
          "itemListElement": [
            {
              "@type": "ListItem",
              "position": 1,
              "name": "<?php echo e($isFr ? 'Accueil' : 'Home'); ?>",
              "item": "<?php echo e($isFr ? 'https://fluencefrancaise.fr/fr/' : 'https://fluencefrancaise.fr/en/'); ?>"
            },
            {
              "@type": "ListItem",
              "position": 2,
              "name": "<?php echo e($isFr ? 'Examens' : 'Exams'); ?>",
              "item": "<?php echo e($isFr ? 'https://fluencefrancaise.fr/fr/#exams' : 'https://fluencefrancaise.fr/en/#exams'); ?>"
            },
            {
              "@type": "ListItem",
              "position": 3,
              "name": "TCF IRN",
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
                  "name": "<?php echo e(addslashes($faq['q'])); ?>",
                  "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "<?php echo e(addslashes(trim(preg_replace('/\s+/', ' ', strip_tags($faq['a']))))); ?>"
                  }
                }<?php echo e($index < count($faqs) - 1 ? ',' : ''); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          ]
        }
      ]
    }
    </script>

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
                                <span class="text-slate-700 font-semibold">TCF IRN</span>
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
                                TCF IRN : préparez exactement<br class="hidden sm:inline" />
                                <span class="relative inline-block text-[#0055a4] mt-1 sm:mt-1.5 pb-2.5 sm:pb-3.5">
                                    le niveau exigé
                                    <span class="absolute left-0 right-0 bottom-0 h-[2.5px] sm:h-[3px] rounded-full"
                                        style="background: linear-gradient(90deg, #0055A4 0%, #0055A4 33.3%, #eaebf0 33.3%, #eaebf0 66.6%, #EF4135 66.6%, #EF4135 100%);"></span>
                                </span>
                            <?php else: ?>
                                TCF IRN: Prepare for the<br class="hidden sm:inline" />
                                <span class="relative inline-block text-[#0055a4] mt-1 sm:mt-1.5 pb-2.5 sm:pb-3.5">
                                    Exact Level You Need
                                    <span class="absolute left-0 right-0 bottom-0 h-[2.5px] sm:h-[3px] rounded-full"
                                        style="background: linear-gradient(90deg, #0055A4 0%, #0055A4 33.3%, #eaebf0 33.3%, #eaebf0 66.6%, #EF4135 66.6%, #EF4135 100%);"></span>
                                </span>
                            <?php endif; ?>
                        </h1>

                        <p
                            class="mt-5 sm:mt-6 text-sm sm:text-base md:text-[17px] text-slate-600 max-w-2xl mx-auto leading-relaxed font-normal">
                            <?php echo e($isFr
    ? 'Le TCF IRN mesure chaque compétence séparément : nous travaillons celles qui vous séparent du niveau exigé par votre dossier.'
    : 'The TCF IRN scores each skill separately, so we train the skills standing between you and the level your application requires.'); ?>

                        </p>

                        <div class="mt-8 flex flex-col items-center gap-3">
                            <a href="/register"
                                class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055a4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5">
                                <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                                <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                            </a>
                            <p class="text-xs sm:text-[13px] text-slate-500 font-medium mt-4">
                                <?php echo e($isFr
    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
    : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                            </p>
                        </div>

                        <div
                            class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-xs sm:text-sm font-medium text-slate-600">
                            <?php $__currentLoopData = $facts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas fa-check text-[#0055A4] text-[11px]"></i>
                                    <?php echo e($fact); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="mt-6 flex items-center justify-center gap-2 text-xs font-semibold text-slate-400">
                            <span class="text-slate-400 text-[9px]">●</span>
                            <span><?php echo e($isFr ? 'Exigences 2026' : '2026 requirements'); ?></span>
                            <span class="text-slate-300">/</span>
                            <span><?php echo e($isFr ? 'Mis à jour le 11 novembre 2026' : 'Updated 11 November 2026'); ?></span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- the tcf irn at a glance -->
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
                                <?php echo e($isFr ? 'Le TCF IRN en bref' : 'The TCF IRN at a glance'); ?>

                            </h2>

                            <p class="text-sm sm:text-[15px] md:text-base text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? 'Le TCF IRN (Test de connaissance du français – Intégration, Résidence et Nationalité) est accepté pour la carte de séjour pluriannuelle, la carte de résident et la naturalisation. Il dure 1 h 35 et compte 4 épreuves. Son attestation est valable 2 ans.'
    : 'The TCF IRN (Test de connaissance du français – Intégration, Résidence et Nationalité) is a French test accepted for a multi-year residence permit, a 10-year resident card and French citizenship. It takes 1 h 35 and has 4 sections. Your results are valid for 2 years.'); ?>

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
                                <a href="https://www.france-education-international.fr/article/tcf-integration-residence-et-nationalite"
                                    target="_blank" rel="noopener"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0055a4] hover:text-[#002654] group transition-colors">
                                    <span><?php echo e($isFr ? 'Règlement officiel France Éducation international' : 'Official France Éducation international guidelines'); ?></span>
                                    <i
                                        class="fas fa-arrow-up-right-from-square text-[10px] group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                </a>
                            </div>
                        </div>

                        <div class="lg:col-span-5">
                            <div class="rounded-3xl bg-[#002654] text-white overflow-hidden  relative">
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
                                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                </svg>
                                            </div>
                                            <div
                                                class="text-3xl sm:text-4xl font-bold font-display text-white tracking-tight">
                                                A1–B2</div>
                                            <div class="text-xs sm:text-[13px] text-blue-200/80 font-normal">
                                                <?php echo e($isFr ? 'Niveaux évalués' : 'Levels tested'); ?>

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
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div
                                                class="text-3xl sm:text-4xl font-bold font-display text-white tracking-tight">
                                                1 h 35</div>
                                            <div class="text-xs sm:text-[13px] text-blue-200/80 font-normal">
                                                <?php echo e($isFr ? 'Durée totale du test' : 'Total test time'); ?>

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
                                                <?php echo e($isFr ? "Validité des résultats" : 'Results validity'); ?>

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
    : "Anyone applying for a carte de séjour pluriannuelle (multi-year permit), a carte de résident (10-year card) or French citizenship"); ?>

                                </div>
                            </div>

                            <div
                                class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-white items-center gap-2 sm:gap-4">
                                <div class="sm:col-span-3 font-bold text-xs sm:text-sm text-[#10233e]">
                                    <?php echo e($isFr ? 'Niveaux évalués' : 'Levels tested'); ?>

                                </div>
                                <div class="sm:col-span-9 text-xs sm:text-sm text-slate-600 font-normal">
                                    A1 <?php echo e($isFr ? 'à' : 'to'); ?> B2
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-[#f8fafc] items-center gap-2 sm:gap-4">
                                <div class="sm:col-span-3 font-bold text-xs sm:text-sm text-[#10233e]">
                                    <?php echo e($isFr ? 'Épreuves' : 'Sections'); ?>

                                </div>
                                <div class="sm:col-span-9 text-xs sm:text-sm text-slate-600 font-normal">
                                    <?php echo e($isFr
    ? 'Compréhension orale, compréhension écrite, expression écrite, expression orale'
    : 'Listening, reading, writing, speaking'); ?>

                                </div>
                            </div>

                            <div
                                class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-white items-center gap-2 sm:gap-4">
                                <div class="sm:col-span-3 font-bold text-xs sm:text-sm text-[#10233e]">
                                    <?php echo e($isFr ? 'Durée' : 'Duration'); ?>

                                </div>
                                <div class="sm:col-span-9 text-xs sm:text-sm text-slate-600 font-normal">
                                    <?php echo e($isFr ? '1 h 35 au total' : '1 h 35 in total'); ?>

                                </div>
                            </div>

                            <div
                                class="grid grid-cols-1 sm:grid-cols-12 py-4 sm:py-5 px-6 sm:px-8 bg-[#f8fafc] items-center gap-2 sm:gap-4">
                                <div class="sm:col-span-3 font-bold text-xs sm:text-sm text-[#10233e]">
                                    <?php echo e($isFr ? 'Validité' : 'Validity'); ?>

                                </div>
                                <div class="sm:col-span-9 text-xs sm:text-sm text-slate-600 font-normal">
                                    <?php echo e($isFr
    ? "2 ans à compter de la délivrance de l'attestation"
    : '2 years from the date the attestation is issued'); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- your gap is never the same -->
            <section class="py-16 sm:py-24 bg-[#f4f7fb] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-center">
                        <div class="lg:col-span-6 space-y-4">
                            <div class="inline-flex items-center gap-1.5">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span
                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                    <?php echo e($isFr ? 'UNE MEILLEURE MÉTHODE' : 'A SMARTER WAY TO PREPARE'); ?>

                                </span>
                            </div>

                            <h2
                                class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                <?php echo e($isFr ? "Votre écart n'est jamais le même partout." : 'Your gap is never the same in every skill.'); ?>

                            </h2>

                            <div
                                class="space-y-4 text-sm sm:text-[15px] text-slate-500 font-normal leading-relaxed pt-1">
                                <p>
                                    <?php echo e($isFr
    ? "Votre attestation donne un niveau pour chaque compétence. Pour la naturalisation, l'attestation doit indiquer un niveau global B2, calculé à partir des 4 compétences selon les règles de notation du TCF IRN."
    : "Your results show a level for each skill. For citizenship, the attestation must show an overall B2 level, calculated from all 4 skills under the TCF IRN scoring rules."); ?>

                                </p>
                                <p>
                                    <?php echo e($isFr
    ? "Or l'écart est rarement uniforme. On peut lire au niveau B2 et parler au niveau B1. On peut tout comprendre à l'oral et perdre ses moyens devant une page blanche."
    : "And the gap is rarely even. Plenty of people read at B2 and speak at B1. Others understand everything they hear, then freeze in front of a blank page."); ?>

                                </p>
                                <p>
                                    <?php echo e($isFr
    ? "Repasser le test, c'est repayer l'inscription et attendre une nouvelle session. Notre préparation commence donc par mesurer votre niveau dans chacune des 4 épreuves. Les heures vont ensuite là où l'écart est le plus grand."
    : "A retake means paying the fee again and waiting for another session. So we start by measuring your level in each of the 4 sections. Then your hours go where the gap is widest."); ?>

                                </p>
                            </div>
                        </div>

                        <div class="lg:col-span-6">
                            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8">
                                <div class="flex items-center gap-3.5 pb-6 border-b border-slate-100">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-blue-50 text-[#0055a4] flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-[#10233e] text-base sm:text-[17px] leading-snug">
                                            <?php echo e($isFr ? 'Votre point de départ' : 'Your starting point'); ?>

                                        </h3>
                                        <p class="text-xs text-slate-400 font-normal">
                                            <?php echo e($isFr ? 'Un plan pour chaque compétence. Pas juste une moyenne.' : 'A plan for every skill. Not just an average.'); ?>

                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-5 pt-6">
                                    <div>
                                        <div
                                            class="flex items-center justify-between text-xs sm:text-[13px] font-semibold text-[#10233e] mb-2">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#0055A4]" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.75"
                                                        d="M3 18v-6a9 9 0 0118 0v6M3 15h3a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-4zm18 0h-3a2 2 0 00-2 2v2a2 2 0 002 2h1a2 2 0 002-2v-4z" />
                                                </svg>
                                                <?php echo e($isFr ? 'Compréhension orale' : 'Listening'); ?>

                                            </span>
                                            <span
                                                class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50/80 text-[#0055A4]">B1</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                            <div class="bg-[#0055A4] h-2 rounded-full" style="width: 65%"></div>
                                        </div>
                                    </div>

                                    <div>
                                        <div
                                            class="flex items-center justify-between text-xs sm:text-[13px] font-semibold text-[#10233e] mb-2">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#0055A4]" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.75"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                                <?php echo e($isFr ? 'Compréhension écrite' : 'Reading'); ?>

                                            </span>
                                            <span
                                                class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50/80 text-[#0055A4]">B2</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                            <div class="bg-[#0055A4] h-2 rounded-full" style="width: 82%"></div>
                                        </div>
                                    </div>

                                    <div>
                                        <div
                                            class="flex items-center justify-between text-xs sm:text-[13px] font-semibold text-[#10233e] mb-2">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#0055A4]" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.75"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                                <?php echo e($isFr ? 'Expression écrite' : 'Writing'); ?>

                                            </span>
                                            <span
                                                class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50/80 text-[#0055A4]">A2</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                            <div class="bg-[#0055A4] h-2 rounded-full" style="width: 40%"></div>
                                        </div>
                                    </div>

                                    <div>
                                        <div
                                            class="flex items-center justify-between text-xs sm:text-[13px] font-semibold text-[#10233e] mb-2">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#0055A4]" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.75"
                                                        d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z" />
                                                </svg>
                                                <?php echo e($isFr ? 'Expression orale' : 'Speaking'); ?>

                                            </span>
                                            <span
                                                class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50/80 text-[#0055A4]">B1</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                            <div class="bg-[#0055A4] h-2 rounded-full" style="width: 55%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="pt-5 mt-5 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-400 font-medium">
                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#EF4135] shrink-0"></span>
                                    <span><?php echo e($isFr ? 'Votre objectif. Votre rythme. Votre plan personnalisé.' : 'Your target. Your pace. Your personal plan.'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- tcf irn format (the 4 sections) -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'STRUCTURE DES ÉPREUVES' : 'THE 4 SECTIONS'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Format du TCF IRN : les 4 épreuves' : 'TCF IRN format: the 4 sections'); ?>

                        </h2>
                        <p
                            class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto font-normal">
                            <?php echo e($isFr
    ? "Le TCF IRN comprend 4 épreuves obligatoires, passées le même jour. Les 2 épreuves de compréhension sont des questionnaires à choix multiple. Les 2 épreuves d'expression sont évaluées par des correcteurs et des examinateurs. Ce format, en vigueur depuis le 12 mai 2025, évalue jusqu'au niveau B2."
    : "The TCF IRN has 4 compulsory sections, all taken on the same day. Listening and reading are multiple choice. Writing and speaking are marked by examiners. This format has applied since 12 May 2025 and tests up to B2."); ?>

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
                                                d="M3 18v-6a9 9 0 0118 0v6M3 15h3a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-4zm18 0h-3a2 2 0 00-2 2v2a2 2 0 002 2h1a2 2 0 002-2v-4z" />
                                        </svg>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-[#10233e]">20
                                        min</span>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Compréhension orale' : 'Listening'); ?>

                                </h3>
                                <p class="mt-1 text-xs font-semibold text-[#0055a4]">
                                    <?php echo e($isFr ? '25 questions à choix multiple' : '25 multiple-choice questions'); ?>

                                </p>
                                <p class="mt-2 text-xs text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr ? 'Extraits audio courts, 1 seule écoute.' : 'Short audio clips played once.'); ?>

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
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-[#10233e]">35
                                        min</span>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Compréhension écrite' : 'Reading'); ?>

                                </h3>
                                <p class="mt-1 text-xs font-semibold text-[#0055a4]">
                                    <?php echo e($isFr ? '25 questions à choix multiple' : '25 multiple-choice questions'); ?>

                                </p>
                                <p class="mt-2 text-xs text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr ? 'Documents de la vie quotidienne, messages, affiches.' : 'Documents, signs, notices from daily life.'); ?>

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
                                <p class="mt-1 text-xs font-semibold text-[#0055a4]">
                                    <?php echo e($isFr ? '3 exercices' : '3 tasks'); ?>

                                </p>
                                <p class="mt-2 text-xs text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr ? 'Message court, lettre personnelle, prise de position.' : 'Message, personal letter, opinion essay.'); ?>

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
                                <p class="mt-1 text-xs font-semibold text-[#0055a4]">
                                    <?php echo e($isFr ? 'Entretien individuel avec un examinateur' : '1-to-1 interview with an examiner'); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-8 flex items-center justify-center gap-2.5 py-3 px-6 rounded-full bg-slate-50 border border-slate-200/80 text-xs sm:text-sm font-semibold text-[#10233e] max-w-fit mx-auto">
                        <svg class="w-4 h-4 text-[#0055A4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><?php echo e($isFr ? 'Durée totale : 1 h 35 · Les 4 épreuves sont passées le même jour' : 'Total duration: 1 h 35 · All 4 sections taken on the same day'); ?></span>
                    </div>
                </div>
            </section>

            <!-- where candidates lose points -->
            <section class="py-16 sm:py-24 bg-[#f4f7fb] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'PIÈGES FRÉQUENTS' : 'WHERE POINTS ARE LOST'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Où les candidats perdent des points au TCF IRN' : 'Where TCF IRN candidates lose points'); ?>

                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div>
                                <span
                                    class="text-xs font-bold text-[#0055A4] bg-blue-50 px-2.5 py-1 rounded-full">01</span>
                                <h3 class="text-lg sm:text-xl font-bold text-[#10233e] mt-3">
                                    <?php echo e($isFr ? 'Le chrono, plus que la grammaire' : 'The clock, more than the grammar'); ?>

                                </h3>
                                <p class="text-sm text-slate-500 leading-relaxed font-normal mt-2.5">
                                    <?php echo e($isFr
    ? 'En compréhension écrite, vous avez 35 minutes pour 25 questions. Cela fait 84 secondes par question, lecture comprise. Bloquer 3 minutes sur une question, c\'est utiliser le temps de 2.'
    : "Reading gives you 35 minutes for 25 questions. That's 84 seconds a question, reading included. Spend 3 minutes stuck on 1 question, and you've used the time of 2."); ?>

                                </p>
                            </div>
                            <div
                                class="mt-6 pt-4 border-t border-slate-100 flex items-start gap-2 text-xs sm:text-[13px] text-slate-700">
                                <span
                                    class="font-bold text-[#0055A4] shrink-0"><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                                <span><?php echo e($isFr ? 'Des séries chronométrées, jusqu\'à ce que le rythme devienne automatique.' : 'Timed drills until the pace is automatic.'); ?></span>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div>
                                <span
                                    class="text-xs font-bold text-[#0055A4] bg-blue-50 px-2.5 py-1 rounded-full">02</span>
                                <h3 class="text-lg sm:text-xl font-bold text-[#10233e] mt-3">
                                    <?php echo e($isFr ? 'L\'oreille habituée au français lent' : 'Ears trained on slow French'); ?>

                                </h3>
                                <p class="text-sm text-slate-500 leading-relaxed font-normal mt-2.5">
                                    <?php echo e($isFr
    ? 'Les documents audio du test sont courts et parlés à vitesse naturelle. Les méthodes d\'apprentissage, elles, parlent souvent lentement. L\'écart se découvre le jour J.'
    : 'The audio is short and spoken at natural speed. Most courses speak slowly. People find out the difference on exam day.'); ?>

                                </p>
                            </div>
                            <div
                                class="mt-6 pt-4 border-t border-slate-100 flex items-start gap-2 text-xs sm:text-[13px] text-slate-700">
                                <span
                                    class="font-bold text-[#0055A4] shrink-0"><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                                <span><?php echo e($isFr ? 'Des exercices d\'écoute à vitesse native dans votre espace élève, dès la première semaine.' : 'Native-speed listening practice in your student portal from week 1.'); ?></span>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div>
                                <span
                                    class="text-xs font-bold text-[#0055A4] bg-blue-50 px-2.5 py-1 rounded-full">03</span>
                                <h3 class="text-lg sm:text-xl font-bold text-[#10233e] mt-3">
                                    <?php echo e($isFr ? '3 textes en 30 minutes' : '3 texts in 30 minutes'); ?>

                                </h3>
                                <p class="text-sm text-slate-500 leading-relaxed font-normal mt-2.5">
                                    <?php echo e($isFr
    ? 'L\'expression écrite demande 3 productions en 30 minutes. Le piège classique : soigner la première et bâcler la dernière. Le correcteur, lui, lit les 3.'
    : 'The writing section asks for 3 pieces in 30 minutes. The classic trap: polishing the first and rushing the last. The examiner reads all 3.'); ?>

                                </p>
                            </div>
                            <div
                                class="mt-6 pt-4 border-t border-slate-100 flex items-start gap-2 text-xs sm:text-[13px] text-slate-700">
                                <span
                                    class="font-bold text-[#0055A4] shrink-0"><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                                <span><?php echo e($isFr ? 'Un plan de temps par exercice et des structures de phrases à adapter, pas à réciter.' : 'A time plan for each task and sentence structures you adapt rather than memorise.'); ?></span>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div>
                                <span
                                    class="text-xs font-bold text-[#0055A4] bg-blue-50 px-2.5 py-1 rounded-full">04</span>
                                <h3 class="text-lg sm:text-xl font-bold text-[#10233e] mt-3">
                                    <?php echo e($isFr ? 'Des réponses trop courtes à l\'oral' : 'Answers that are too short'); ?>

                                </h3>
                                <p class="text-sm text-slate-500 leading-relaxed font-normal mt-2.5">
                                    <?php echo e($isFr
    ? 'En 10 minutes, l\'examinateur doit entendre ce qui fait un B2 : argumenter, nuancer, illustrer. Une réponse de 2 phrases ne lui en laisse pas l\'occasion.'
    : 'In 10 minutes, the examiner needs to hear B2 skills: arguing, nuancing, giving examples. A 2-sentence answer gives them nothing to mark.'); ?>

                                </p>
                            </div>
                            <div
                                class="mt-6 pt-4 border-t border-slate-100 flex items-start gap-2 text-xs sm:text-[13px] text-slate-700">
                                <span
                                    class="font-bold text-[#0055A4] shrink-0"><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                                <span><?php echo e($isFr ? 'Une méthode simple pour chaque réponse — une idée, un exemple, une nuance.' : 'A simple pattern for every answer — 1 idea, 1 example, 1 nuance.'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- how we prepare you -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'NOTRE MÉTHODE' : 'OUR PREPARATION'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Notre préparation au TCF IRN' : 'How we prepare you for the TCF IRN'); ?>

                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div
                            class="rounded-3xl border border-slate-200/80 p-6 bg-slate-50/50 flex flex-col justify-between">
                            <div>
                                <span
                                    class="w-9 h-9 rounded-full bg-blue-50 text-[#0055A4] font-bold text-sm flex items-center justify-center mb-4">01</span>
                                <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Votre niveau, épreuve par épreuve' : 'Your level, section by section'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed font-normal">
                                    <?php echo e($isFr ? 'Dès le premier cours, votre tuteur situe chaque compétence face au niveau exigé par votre démarche.' : 'In your first class, your tutor places each skill against the level your application needs.'); ?>

                                </p>
                            </div>
                        </div>

                        <div
                            class="rounded-3xl border border-slate-200/80 p-6 bg-slate-50/50 flex flex-col justify-between">
                            <div>
                                <span
                                    class="w-9 h-9 rounded-full bg-blue-50 text-[#0055A4] font-bold text-sm flex items-center justify-center mb-4">02</span>
                                <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Un plan construit sur votre écart' : 'A plan built on your gap'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed font-normal">
                                    <?php echo e($isFr ? 'Les heures vont aux épreuves où vous êtes le plus loin du but. Pas de programme générique.' : 'Your hours go to the sections where you\'re furthest from the target. No generic syllabus.'); ?>

                                </p>
                            </div>
                        </div>

                        <div
                            class="rounded-3xl border border-slate-200/80 p-6 bg-slate-50/50 flex flex-col justify-between">
                            <div>
                                <span
                                    class="w-9 h-9 rounded-full bg-blue-50 text-[#0055A4] font-bold text-sm flex items-center justify-center mb-4">03</span>
                                <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Des cours en direct, 2 ou 4 h par semaine' : 'Live classes, 2 or 4 hours a week'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed font-normal">
                                    <?php echo e($isFr ? 'En particulier ou en mini-groupe de 2 à 5 personnes, sur Google Meet. Vous recevez les notes après chaque séance.' : '1:1 or in a group of 2–5, on Google Meet. You get the class notes after every session.'); ?>

                                </p>
                            </div>
                        </div>

                        <div
                            class="rounded-3xl border border-slate-200/80 p-6 bg-slate-50/50 flex flex-col justify-between">
                            <div>
                                <span
                                    class="w-9 h-9 rounded-full bg-blue-50 text-[#0055A4] font-bold text-sm flex items-center justify-center mb-4">04</span>
                                <h3 class="text-base sm:text-lg font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Des examens blancs au chrono' : 'Timed mock exams'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed font-normal">
                                    <?php echo e($isFr ? 'Votre espace élève contient des tests au format de l\'examen et des banques d\'exercices par compétence. Chaque devoir reçoit un retour.' : 'Your student portal has exam-format tests and practice banks for each skill. Every piece of homework gets feedback.'); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex flex-col items-center gap-3">
                        <a href="/register"
                            class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055a4] hover:bg-[#003f7a] transition-all">
                            <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                            <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                        </a>
                        <p class="text-xs text-slate-500 font-medium">
                            <?php echo e($isFr ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                        </p>
                    </div>
                </div>
            </section>

            <!-- what tcf irn level do you need? -->
            <section class="py-16 sm:py-24 bg-[#f4f7fb] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'NIVEAU VISÉ' : 'WHICH LEVEL FOR WHICH GOAL'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Quel niveau viser au TCF IRN ?' : 'What TCF IRN level do you need?'); ?>

                        </h2>
                        <p
                            class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed max-w-3xl mx-auto font-normal">
                            <?php echo e($isFr
    ? 'Tout dépend de votre démarche : A2 pour une première carte de séjour pluriannuelle, B1 pour la carte de résident, B2 pour la naturalisation. Ces niveaux s\'appliquent depuis le 1er janvier 2026. Vérifiez celui de votre dossier avant de vous inscrire au test.'
    : 'It depends on your application: A2 for a first multi-year residence permit, B1 for the 10-year resident card, B2 for citizenship. These levels have applied since 1 January 2026. Check which one applies to you before you book the test.'); ?>

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
                                    <span>→</span>
                                </a>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div>
                                <span class="text-3xl font-extrabold text-[#0055a4]">B1</span>
                                <h3 class="font-bold text-[#10233e] text-lg mt-2">
                                    <?php echo e($isFr ? 'Carte de résident (10 ans)' : '10-year resident card'); ?>

                                </h3>
                            </div>
                            <div class="pt-6">
                                <a href="<?php echo e($permitUrl); ?>"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0055a4] hover:underline">
                                    <span><?php echo e($isFr ? 'Niveau de français pour un titre de séjour' : 'French level for residence permits'); ?></span>
                                    <span>→</span>
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
                                    <?php echo e($isFr ? 'Naturalisation française' : 'French citizenship'); ?>

                                </h3>
                            </div>
                            <div class="pt-6">
                                <a href="<?php echo e($langUrl); ?>"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-white hover:underline">
                                    <span><?php echo e($isFr ? 'Test de français pour la naturalisation' : 'The French citizenship language test'); ?></span>
                                    <span>→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <p class="mt-8 text-sm sm:text-base text-slate-600 leading-relaxed text-center max-w-3xl mx-auto">
                        <?php if($isFr): ?>
                            Depuis 2026, un <a href="<?php echo e($civicUrl); ?>" <?php echo $link; ?>>examen civique</a> est aussi exigé pour la naturalisation par décret et pour les titres de séjour concernés par les règles de 2026. Vous hésitez encore sur le test ? <a href="<?php echo e($compareUrl); ?>" <?php echo $link; ?>>Comparez le TCF, le TEF et le DELF</a>.
                        <?php else: ?>
                            Citizenship by decree and the residence permit applications covered by the 2026 rules also need the <a href="<?php echo e($civicUrl); ?>" <?php echo $link; ?>>French civic exam</a>. Not sure which test to take? <a href="<?php echo e($compareUrl); ?>" <?php echo $link; ?>>Compare the TCF, TEF and DELF</a>.
                        <?php endif; ?>
                    </p>
                </div>
            </section>
            <!-- faq -->
            <?php echo $__env->make('partials.faq-section', [
                'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                'title' => $isFr ? 'Questions fréquentes<br>sur le TCF IRN' : 'TCF IRN<br>FAQ',
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
                            ? 'Votre premier cours fixe votre niveau dans chaque épreuve et le plan pour atteindre celui de votre dossier. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                            : 'Your first class sets your level in each section and the plan to reach the one your application needs. You choose the pace: 2 or 4 hours a week.',
                        'primaryButtonText' => $isFr ? "S'inscrire" : 'Enroll now',
                        'primaryButtonUrl' => '/register',
                        'showSecondaryButton' => true,
                        'secondaryButtonText' => $isFr ? 'Contactez-nous' : 'Talk to us',
                        'secondaryButtonUrl' => $isFr ? '/fr/contact/' : '/en/contact/',
                        'helperText' => $isFr
                            ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                            : 'Live classes on Google Meet · 1:1 or small group · Student portal included'
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="mt-10 sm:mt-12 text-center text-sm sm:text-[15px] text-slate-500 font-normal">
                        <span><?php echo e($isFr ? 'Vous venez à deux ? 50 € de remise par mois, pour vous et pour votre ami.' : 'Coming with a friend? €50 off per month, for you and for them.'); ?></span>
                        <a href="<?php echo e($isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral'); ?>"
                            class="text-[#0055a4] font-semibold hover:underline ml-1 inline-flex items-center gap-1 group">
                            <span><?php echo e($isFr ? 'Contactez-nous' : 'Contact us'); ?></span>
                            <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </a>
                    </div>

                    <div class="my-6 sm:my-8 border-t border-[#edf1f5] max-w-5xl mx-auto"></div>

                    <div
                        class="flex flex-wrap items-center justify-center gap-y-2 text-center text-xs sm:text-[13px] text-slate-400 font-normal">
                        <span
                            class="font-bold tracking-[0.18em] uppercase text-slate-400 mr-3.5"><?php echo e($isFr ? 'SOURCES' : 'SOURCES'); ?></span>
                        <a href="https://www.france-education-international.fr/article/tcf-integration-residence-et-nationalite"
                            target="_blank" rel="noopener"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1 group">
                            <span>France Éducation international – TCF IRN</span>
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

</html><?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/tcf-irn.blade.php ENDPATH**/ ?>