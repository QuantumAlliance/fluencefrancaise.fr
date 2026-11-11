<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    
    // Determine language based on current URL prefix
    $isFr = request()->is('fr*');
    
    // Metadata
    $title = $isFr 
        ? "Test de français naturalisation 2026 : le niveau B2 exigé"
        : "French Citizenship Language Test 2026: B2 Now Required";
        
    $metaDesc = $isFr
        ? "Depuis 2026, la naturalisation exige le niveau B2 à l'oral et à l'écrit. Les tests acceptés, leur validité et comment atteindre le B2 en ligne."
        : "Since January 2026, French citizenship requires B2 French, spoken and written. See which tests count, how long results last and how to reach B2 online.";
        
    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/test-francais-naturalisation/' : 'https://fluencefrancaise.fr/en/french-citizenship-language-test/';
    $frUrl = 'https://fluencefrancaise.fr/fr/test-francais-naturalisation/';
    $enUrl = 'https://fluencefrancaise.fr/en/french-citizenship-language-test/';
    
    // FAQ Data
    $faqs = $isFr ? [
        [
            'q' => "Le niveau B1 suffit-il encore pour la naturalisation ?",
            'a' => "Seulement pour les dossiers déposés avant le 1er janvier 2026. Pour toute nouvelle demande, c'est le B2, à l'oral et à l'écrit."
        ],
        [
            'q' => "Existe-t-il un test gratuit avec certificat valable pour la naturalisation ?",
            'a' => "Non. Les tests en ligne gratuits servent seulement à vous situer. Seuls le TCF, le TEF, le DELF, le DALF ou certains diplômes français sont acceptés dans un dossier."
        ],
        [
            'q' => "Combien coûte le test de français pour la naturalisation ?",
            'a' => "Chaque centre agréé fixe son tarif. Pour le TCF IRN ou le TEF IRN, comptez généralement entre 130 et 210 €. Une nouvelle tentative est facturée à nouveau."
        ],
        [
            'q' => "Peut-on passer le test de français en ligne ?",
            'a' => "Non. Le TCF, le TEF et le DELF se passent dans un centre agréé. La préparation, elle, peut se faire entièrement en ligne."
        ],
        [
            'q' => "Mon diplôme étranger en français me dispense-t-il du test ?",
            'a' => "Non. Un diplôme délivré par un autre pays ne compte pas, même si vos études étaient en français. En dehors du TCF et du TEF, seuls les diplômes français ou un diplôme attestant votre niveau de français au B2 ou plus sont acceptés."
        ],
        [
            'q' => "Qui est dispensé du test de français ?",
            'a' => "Les personnes dont le handicap ou l'état de santé rend l'évaluation impossible, sur certificat médical. Et, pour la naturalisation, les réfugiés ou apatrides de plus de 70 ans résidant en France depuis au moins 15 ans avec un titre de séjour. Vérifiez votre situation sur <a href=\"https://www.service-public.gouv.fr/\" target=\"_blank\" rel=\"noopener noreferrer\" class=\"text-primary-600 underline\">service-public.fr</a>."
        ],
        [
            'q' => "Ces règles valent-elles pour la nationalité par mariage ?",
            'a' => "En partie. Depuis le 1er janvier 2026, la nationalité par mariage exige aussi le B2 à l'oral et à l'écrit. Mais elle passe par une déclaration : pas d'examen civique, un entretien d'assimilation à la place. Consultez notre guide de la naturalisation française."
        ]
    ] : [
        [
            'q' => "Is B1 still enough for French citizenship?",
            'a' => "Only for applications filed before 1 January 2026. Any new application needs B2, spoken and written."
        ],
        [
            'q' => "Is there a free French test with a certificate for citizenship?",
            'a' => "No. Free online tests only tell you where you stand. Only the TCF, TEF, DELF, DALF or certain French qualifications are accepted in an application."
        ],
        [
            'q' => "How much does the French citizenship language test cost?",
            'a' => "Each approved centre sets its own price. For the TCF IRN or TEF IRN, expect roughly €130–210. A retake is charged again."
        ],
        [
            'q' => "Can I take the test online?",
            'a' => "No. The TCF, TEF and DELF are taken at an approved centre. The preparation can be fully online."
        ],
        [
            'q' => "Does my foreign degree taught in French exempt me?",
            'a' => "No. A degree issued by another country doesn't count, even if it was taught in French. Besides the TCF and TEF, only French qualifications or a diploma certifying your French at B2 or above are accepted."
        ],
        [
            'q' => "Who is exempt from the language test?",
            'a' => "People whose disability or health makes testing impossible, with a medical certificate. Also, for naturalisation, refugees or stateless people over 70 who have lived in France for at least 15 years with a residence permit. Check your case on <a href=\"https://www.service-public.gouv.fr/\" target=\"_blank\" rel=\"noopener noreferrer\" class=\"text-primary-600 underline\">service-public.fr</a>."
        ],
        [
            'q' => "Do these rules apply to citizenship through marriage?",
            'a' => "Partly. Since 1 January 2026, citizenship through marriage also requires B2, spoken and written. But it goes through a declaration: no civic exam, and an assimilation interview instead. See our French citizenship guide."
        ]
    ];

    // Structured Data JSON-LD
    $breadcrumbSchema = [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => $isFr ? 'Accueil' : 'Home',
                'item' => $isFr ? 'https://fluencefrancaise.fr/fr/' : 'https://fluencefrancaise.fr/en/'
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'France',
                'item' => $canonicalUrl
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $isFr ? 'Naturalisation' : 'Citizenship',
                'item' => $canonicalUrl
            ]
        ]
    ];

    $webpageSchema = [
        '@type' => 'WebPage',
        'name' => $title,
        'description' => $metaDesc,
        'url' => $canonicalUrl,
        'publisher' => [
            '@type' => 'EducationalOrganization',
            'name' => $siteName,
            'url' => $canonicalUrl
        ]
    ];

    $faqSchema = [
        '@type' => 'FAQPage',
        'mainEntity' => array_map(function($f) {
            return [
                '@type' => 'Question',
                'name' => $f['q'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($f['a'])
                ]
            ];
        }, $faqs)
    ];

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            $breadcrumbSchema,
            $webpageSchema,
            $faqSchema
        ]
    ];
?>
<html lang="<?php echo e($isFr ? 'fr' : 'en'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo e($title); ?></title>
    <meta name="description" content="<?php echo e($metaDesc); ?>">
    <meta name="robots" content="<?php echo e($settings['robots'] ?? 'index, follow'); ?>">
    <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
    <link rel="alternate" hreflang="fr" href="<?php echo e($frUrl); ?>">
    <link rel="alternate" hreflang="en" href="<?php echo e($enUrl); ?>">
    <link rel="alternate" hreflang="x-default" href="<?php echo e($enUrl); ?>">

    <?php echo $__env->make('partials.social-meta', [
        'ogType' => 'website',
        'ogUrl' => $canonicalUrl,
        'ogTitle' => $title,
        'ogDescription' => $metaDesc,
        'ogSiteName' => $siteName,
        'ogImage' => isset($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : null,
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script type="application/ld+json"><?php echo json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>

    <?php echo $__env->make('partials.favicon', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.custom-scripts-head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>
<body class="antialiased bg-white text-ink">
    <?php echo $__env->make('partials.custom-scripts-body', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="landing-page">
        <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- BREADCRUMB -->
        <div class="pt-28 md:pt-36 pb-3 bg-white border-b border-slate-100">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <nav aria-label="Breadcrumb" class="text-xs text-slate-500 font-medium flex items-center gap-2">
                    <a href="<?php echo e($isFr ? '/fr' : '/en'); ?>" class="hover:text-primary-600 transition-colors"><?php echo e($isFr ? 'Accueil' : 'Home'); ?></a>
                    <span class="text-slate-300">›</span>
                    <span class="text-slate-600">France</span>
                    <span class="text-slate-300">›</span>
                    <span class="text-primary-600 font-semibold"><?php echo e($isFr ? 'Naturalisation' : 'Citizenship'); ?></span>
                </nav>
            </div>
        </div>

        <!-- ============ SECTION 1: HERO ============ -->
        <section class="relative overflow-hidden bg-white pt-10 md:pt-16 pb-16 md:pb-24">
            <div class="hero-aurora-strong"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="max-w-3xl mx-auto text-center reveal">
                    <div class="inline-flex flex-col items-center justify-center gap-1.5 mb-3">
                        <span class="text-xs sm:text-[13px] font-bold tracking-[0.22em] uppercase text-primary-600"><?php echo e($isFr ? 'NATIONALITÉ FRANÇAISE' : 'FRENCH CITIZENSHIP'); ?></span>
                        <span class="tricolor my-1" style="height:3.5px;width:48px;"><i></i><i></i><i></i></span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-semibold text-ink tracking-tight leading-tight">
                        <?php echo e($isFr ? 'Test de français pour la naturalisation : visez le B2' : 'French Citizenship Language Test: How to Reach B2'); ?>

                    </h1>

                    <p class="mt-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <?php echo e($isFr ? 'Mis à jour le 6 octobre 2026' : 'Updated 6 October 2026'); ?>

                    </p>

                    <p class="mt-6 text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
                        <?php echo e($isFr 
                            ? 'Depuis le 1er janvier 2026, la naturalisation exige le B2 à l\'oral et à l\'écrit : voici comment le prouver, et l\'atteindre.' 
                            : 'Since 1 January 2026, French citizenship requires B2 French, spoken and written: here\'s how to prove it, and how to get there.'); ?>

                    </p>

                    <div class="mt-8 flex flex-col items-center gap-3">
                        <a href="/register" class="btn btn-primary btn-lg">
                            <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?> <i class="fas fa-arrow-right text-xs ml-1"></i>
                        </a>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium">
                            <?php echo e($isFr 
                                ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' 
                                : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                        </p>
                    </div>

                    <!-- 3 FACTS STRIP -->
                    <div class="mt-12 grid sm:grid-cols-3 gap-4 pt-8 border-t border-slate-100 text-left">
                        <div class="flex items-center gap-3 bg-mist rounded-2xl p-4 border border-slate-100">
                            <span class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 grid place-content-center shrink-0"><i class="fas fa-award"></i></span>
                            <span class="text-sm font-semibold text-ink"><?php echo e($isFr ? 'Préparation TCF IRN, TEF IRN et DELF B2' : 'TCF IRN, TEF IRN and DELF B2 prep'); ?></span>
                        </div>
                        <div class="flex items-center gap-3 bg-mist rounded-2xl p-4 border border-slate-100">
                            <span class="w-10 h-10 rounded-xl bg-accent-soft text-[#EF4135] grid place-content-center shrink-0"><i class="fas fa-laptop"></i></span>
                            <span class="text-sm font-semibold text-ink"><?php echo e($isFr ? 'Examens blancs dans votre espace élève' : 'Mock exams in your student portal'); ?></span>
                        </div>
                        <div class="flex items-center gap-3 bg-mist rounded-2xl p-4 border border-slate-100">
                            <span class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 grid place-content-center shrink-0"><i class="fas fa-pen-nib"></i></span>
                            <span class="text-sm font-semibold text-ink"><?php echo e($isFr ? 'Retour sur chaque devoir' : 'Feedback on every assignment'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 2: ACCEPTED PROOF ============ -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> <?php echo e($isFr ? 'Règle officielle' : 'Official Rule'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink">
                        <?php echo e($isFr ? 'Quel niveau de français pour la naturalisation en 2026 ?' : 'What level of French do you need for French citizenship?'); ?>

                    </h2>
                    <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                        <?php echo e($isFr 
                            ? 'Le niveau B2, à l\'oral comme à l\'écrit, pour toute demande déposée depuis le 1er janvier 2026. Vous le prouvez avec un TCF ou un TEF de moins de 2 ans, un DELF B2 ou certains diplômes français. Une simple attestation de cours ne suffit pas.'
                            : 'B2, in both speaking and writing, for any application filed since 1 January 2026. You prove it with a TCF or TEF result under 2 years old, a DELF B2 diploma or certain French qualifications. A certificate of attendance from a language school doesn\'t count.'); ?>

                    </p>

                    <!-- Table -->
                    <div class="mt-8 bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-card">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="py-4 px-6 font-bold text-ink"><?php echo e($isFr ? 'Preuve acceptée' : 'Accepted proof'); ?></th>
                                    <th class="py-4 px-6 font-bold text-ink"><?php echo e($isFr ? 'Validité' : 'Valid for'); ?></th>
                                    <th class="py-4 px-6 font-bold text-ink text-right"><?php echo e($isFr ? 'À savoir' : 'Good to know'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink">
                                        <a href="<?php echo e($isFr ? '/fr/tcf-irn' : '/en/tcf-irn'); ?>" class="text-primary-600 underline font-semibold hover:text-primary-700">TCF IRN</a>
                                    </td>
                                    <td class="py-4 px-6 text-slate-700 font-semibold"><?php echo e($isFr ? '2 ans' : '2 years'); ?></td>
                                    <td class="py-4 px-6 text-slate-600 text-right"><?php echo e($isFr ? "Un niveau par compétence, 1 h 35 d'épreuves" : 'A level for each skill, 1 h 35 of testing'); ?></td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink">
                                        <span>TEF IRN</span>
                                    </td>
                                    <td class="py-4 px-6 text-slate-700 font-semibold"><?php echo e($isFr ? '2 ans' : '2 years'); ?></td>
                                    <td class="py-4 px-6 text-slate-600 text-right"><?php echo e($isFr ? '4 épreuves obligatoires dans la même session' : '4 compulsory sections in 1 session'); ?></td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink">
                                        <span>DELF B2 <?php echo e($isFr ? 'ou DALF' : 'or DALF'); ?></span>
                                    </td>
                                    <td class="py-4 px-6 font-bold text-primary-600"><?php echo e($isFr ? 'À vie' : 'Life'); ?></td>
                                    <td class="py-4 px-6 text-slate-600 text-right"><?php echo e($isFr ? "Un diplôme : il n'expire jamais" : 'A diploma: it never expires'); ?></td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-ink">
                                        <span><?php echo e($isFr ? 'Diplôme ou certification français (brevet, diplôme d\'État ou certification professionnelle de niveau 3 et plus)' : 'French qualification (brevet, or a state diploma or professional certification at level 3+)'); ?></span>
                                    </td>
                                    <td class="py-4 px-6 font-bold text-primary-600"><?php echo e($isFr ? 'À vie' : 'Life'); ?></td>
                                    <td class="py-4 px-6 text-slate-600 text-right"><?php echo e($isFr ? 'Diplômes et certifications français uniquement' : 'French qualifications only'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 3: B1 OR B2? ============ -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> <?php echo e($isFr ? 'Changement 2026' : '2026 Changes'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink">
                        <?php echo e($isFr ? 'B1 ou B2 : ce qui a changé en 2026' : 'B1 or B2? What changed in 2026'); ?>

                    </h2>
                    
                    <div class="mt-6 space-y-5 text-lg text-slate-600 leading-relaxed">
                        <p>
                            <?php echo e($isFr
                                ? "Si vous avez lu « niveau B1 » quelque part, l'information date d'avant 2026. La loi du 26 janvier 2024 a relevé le niveau, et le B2 s'applique depuis le 1er janvier 2026. Le B1 ne vaut plus que pour les dossiers déposés avant cette date."
                                : "If you've read “B1” somewhere, that advice predates 2026. The immigration law of 26 January 2024 raised the bar, and B2 has applied since 1 January 2026. B1 now only covers applications filed before that date."); ?>

                        </p>
                        <p>
                            <?php echo e($isFr
                                ? "L'écart entre les 2 niveaux est réel. Au B1, on se débrouille. Au B2, on argumente, on nuance, on suit un débat sans tout faire répéter."
                                : "The gap between the 2 levels is real. At B1, you get by. At B2, you argue, you nuance, you follow a debate without asking people to repeat themselves."); ?>

                        </p>
                        <div class="bg-primary-50/70 border-l-4 border-primary-600 rounded-2xl p-6 text-ink font-medium">
                            <?php echo e($isFr
                                ? "Préparer le B1 aujourd'hui, c'est préparer un niveau qui ne suffit plus pour un nouveau dossier."
                                : "Preparing for B1 today means preparing for a level that no longer counts on a new application."); ?>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 4: WHICH TEST TO TAKE? ============ -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> <?php echo e($isFr ? 'Comparatif' : 'Comparison'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink">
                        <?php echo e($isFr ? 'Quel test choisir pour la naturalisation ?' : 'Which test should you take for French citizenship?'); ?>

                    </h2>
                    <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                        <?php echo e($isFr
                            ? "Les 3 tests sont acceptés. La différence se joue sur la validité et sur la façon de noter. Le TCF IRN et le TEF IRN donnent un niveau par compétence et un niveau global. Le DELF B2 se valide sur une note globale : 50 sur 100, avec au moins 5 sur 25 dans chaque épreuve."
                            : "All 3 are accepted. The differences are validity and scoring. The TCF IRN and TEF IRN give you a level for each skill and an overall level. The DELF B2 is passed on an overall score: 50 out of 100, with at least 5 out of 25 in each section."); ?>

                    </p>

                    <!-- Table -->
                    <div class="mt-8 bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-card">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="py-4 px-5 font-bold text-ink"></th>
                                    <th class="py-4 px-5 font-bold text-primary-600">TCF IRN</th>
                                    <th class="py-4 px-5 font-bold text-ink">TEF IRN</th>
                                    <th class="py-4 px-5 font-bold text-ink">DELF B2</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="py-4 px-5 font-bold text-ink bg-slate-50/70"><?php echo e($isFr ? 'Organisme' : 'Run by'); ?></td>
                                    <td class="py-4 px-5 text-slate-700">France Éducation international</td>
                                    <td class="py-4 px-5 text-slate-700"><?php echo e($isFr ? 'Le français des affaires (CCI Paris Île-de-France)' : 'Le français des affaires (Paris Chamber of Commerce)'); ?></td>
                                    <td class="py-4 px-5 text-slate-700">France Éducation international</td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-5 font-bold text-ink bg-slate-50/70"><?php echo e($isFr ? 'Format' : 'Format'); ?></td>
                                    <td class="py-4 px-5 text-slate-700"><?php echo e($isFr ? '4 épreuves, 1 h 35' : '4 sections, 1 h 35'); ?></td>
                                    <td class="py-4 px-5 text-slate-700"><?php echo e($isFr ? '4 épreuves, 1 session' : '4 sections, 1 session'); ?></td>
                                    <td class="py-4 px-5 text-slate-700"><?php echo e($isFr ? '2 h 30 d\'écrit + oral de 20 min' : '2 h 30 written + 20 min speaking'); ?></td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-5 font-bold text-ink bg-slate-50/70"><?php echo e($isFr ? 'Résultat' : 'Result'); ?></td>
                                    <td class="py-4 px-5 text-slate-700"><?php echo e($isFr ? 'Un niveau par compétence + niveau global' : 'A level per skill + overall level'); ?></td>
                                    <td class="py-4 px-5 text-slate-700"><?php echo e($isFr ? 'Un niveau par compétence + niveau global' : 'A level per skill + overall level'); ?></td>
                                    <td class="py-4 px-5 text-slate-700"><?php echo e($isFr ? 'Diplôme si 50/100 et 5/25 minimum par épreuve' : 'Diploma at 50/100, minimum 5/25 per section'); ?></td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-5 font-bold text-ink bg-slate-50/70"><?php echo e($isFr ? 'Validité' : 'Valid for'); ?></td>
                                    <td class="py-4 px-5 font-semibold text-slate-700"><?php echo e($isFr ? '2 ans' : '2 years'); ?></td>
                                    <td class="py-4 px-5 font-semibold text-slate-700"><?php echo e($isFr ? '2 ans' : '2 years'); ?></td>
                                    <td class="py-4 px-5 font-bold text-primary-600"><?php echo e($isFr ? 'À vie' : 'Life'); ?></td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-5 font-bold text-ink bg-slate-50/70"><?php echo e($isFr ? 'Bon choix si…' : 'Best if…'); ?></td>
                                    <td class="py-4 px-5 text-slate-700"><?php echo e($isFr ? 'Vous voulez un test court' : 'You want a short test'); ?></td>
                                    <td class="py-4 px-5 text-slate-700"><?php echo e($isFr ? 'Les dates ou le centre vous arrangent' : 'The dates or centre suit you'); ?></td>
                                    <td class="py-4 px-5 text-slate-700"><?php echo e($isFr ? 'Votre calendrier est incertain' : 'Your timeline is uncertain'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="mt-6 text-sm text-slate-600 leading-relaxed">
                        <?php echo e($isFr 
                            ? 'Pour aller plus loin : TCF, TEF ou DELF, le comparatif complet' 
                            : 'More detail: TCF vs TEF vs DELF, the full comparison'); ?>

                    </p>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 5: CIVIC EXAM ============ -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto reveal bg-[#002654] text-white rounded-3xl p-8 md:p-12 relative overflow-hidden shadow-lift">
                    <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                    <span class="eyebrow text-[#8eb8e1]"><span class="tricolor"><i></i><i></i><i></i></span> <?php echo e($isFr ? 'Épreuve civique' : 'Civic Test'); ?></span>
                    <h2 class="mt-4 text-2xl md:text-3xl font-semibold">
                        <?php echo e($isFr ? 'La langue ne suffit plus : l\'examen civique' : 'Language isn\'t the only test: the civic exam'); ?>

                    </h2>
                    <p class="mt-4 text-slate-300 leading-relaxed">
                        <?php echo e($isFr
                            ? 'Depuis 2026, la naturalisation par décret exige aussi la réussite à un examen civique : 40 questions, 45 minutes, 32 bonnes réponses minimum. Toutes les questions sont en français. Votre niveau de lecture compte donc aussi ici. Sa préparation est incluse dans nos cours, sans supplément.'
                            : 'Since 2026, citizenship by decree also requires passing a civic exam: 40 questions, 45 minutes, at least 32 correct. Every question is in French, so your reading level matters here too. Its prep is included in our classes at no extra cost.'); ?>

                    </p>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 6: HOW WE GET YOU TO B2 ============ -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> <?php echo e($isFr ? 'Méthode' : 'Methodology'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink">
                        <?php echo e($isFr ? 'Notre préparation au B2' : 'How we get you to B2'); ?>

                    </h2>

                    <?php
                        $prepSteps = $isFr ? [
                            "<strong>Votre niveau, compétence par compétence.</strong> Dès le premier cours, votre tuteur situe vos 4 compétences face au B2.",
                            "<strong>Le bon test pour votre calendrier.</strong> TCF IRN, TEF IRN ou DELF B2 : nous vous aidons à choisir, puis nous préparons ce format précis.",
                            "<strong>Des cours en direct, 2 ou 4 h par semaine.</strong> En particulier ou en mini-groupe de 2 à 5 personnes, sur Google Meet, avec vos notes après chaque séance.",
                            "<strong>Des examens blancs jusqu'au jour J.</strong> Votre espace élève contient des tests au format de l'examen et des exercices à vitesse native. Chaque devoir reçoit un retour."
                        ] : [
                            "<strong>Your level, skill by skill.</strong> In your first class, your tutor places your 4 skills against B2.",
                            "<strong>The right test for your timeline.</strong> TCF IRN, TEF IRN or DELF B2: we help you choose, then train that exact format.",
                            "<strong>Live classes, 2 or 4 hours a week.</strong> 1:1 or in a group of 2–5 on Google Meet, with notes after every session.",
                            "<strong>Mock exams until test day.</strong> Your student portal has exam-format tests and native-speed practice. Every assignment gets feedback."
                        ];
                    ?>

                    <div class="mt-8 space-y-4">
                        <?php $__currentLoopData = $prepSteps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-start gap-4 bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                            <span class="w-8 h-8 rounded-full bg-primary-600 text-white grid place-content-center text-sm font-bold shrink-0 mt-0.5"><?php echo e($idx + 1); ?></span>
                            <p class="text-slate-700 leading-relaxed"><?php echo $step; ?></p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="mt-10 text-center flex flex-col items-center gap-2">
                        <a href="/register" class="btn btn-primary btn-lg">
                            <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?> <i class="fas fa-arrow-right text-xs ml-1"></i>
                        </a>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium">
                            <?php echo e($isFr 
                                ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' 
                                : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 7: FAQ ============ -->
        <section id="faq" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                <div class="text-center mb-12 reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> FAQ</span>
                    <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink">
                        <?php echo e($isFr ? 'Questions fréquentes' : 'FAQ'); ?>

                    </h2>
                </div>

                <div class="space-y-3 reveal">
                    <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-mist rounded-2xl border border-slate-100 overflow-hidden">
                        <button class="faq-toggle w-full px-6 py-5 flex justify-between items-center text-left gap-4 cursor-pointer">
                            <span class="font-bold text-ink text-base"><?php echo e($item['q']); ?></span>
                            <i class="fas fa-plus text-primary-600 text-sm transition-transform duration-300 shrink-0"></i>
                        </button>
                        <div class="faq-content hidden px-6 pb-6 text-slate-600 leading-relaxed text-sm">
                            <?php echo $item['a']; ?>

                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>

        <!-- ============ SECTION 8: FINAL CTA & SOURCES ============ -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal rounded-5xl bg-primary-600 text-white text-center px-6 py-14 md:py-16 relative overflow-hidden max-w-4xl mx-auto shadow-lift">
                    <div class="absolute inset-x-0 bottom-0 h-1.5" style="background:linear-gradient(90deg,#003d7a 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>

                    <h2 class="text-3xl md:text-5xl font-semibold leading-tight max-w-2xl mx-auto">
                        <?php echo e($isFr ? 'Votre niveau. Le niveau exigé. Nous comblons l\'écart.' : 'Your level. The required level. We close the gap.'); ?>

                    </h2>

                    <p class="mt-4 text-lg text-primary-100 max-w-xl mx-auto">
                        <?php echo e($isFr 
                            ? 'Votre premier cours situe vos 4 compétences face au B2 et fixe le plan pour y arriver. Vous choisissez le rythme : 2 ou 4 heures par semaine.' 
                            : 'Your first class places your 4 skills against B2 and sets the plan to get there. You choose the pace: 2 or 4 hours a week.'); ?>

                    </p>

                    <div class="mt-8 flex flex-col items-center gap-3">
                        <a href="/register" class="btn btn-onnavy btn-lg">
                            <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                        </a>
                        <p class="text-xs sm:text-sm text-primary-100 font-medium">
                            <?php echo e($isFr 
                                ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' 
                                : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                        </p>
                    </div>

                    <!-- Referral note -->
                    <div class="mt-8 pt-6 border-t border-white/20 text-xs sm:text-sm text-primary-100 space-y-2">
                        <p>
                            <?php if($isFr): ?>
                                Vous venez à deux ? 50 € de remise par mois, pour vous et pour votre ami. <a href="/fr/contact#parrainage" class="text-white underline font-bold hover:text-accent">Contactez-nous</a>
                            <?php else: ?>
                                Coming with a friend? €50 off per month, for you and for them. <a href="/en/contact#referral" class="text-white underline font-bold hover:text-accent">Contact us</a>
                            <?php endif; ?>
                        </p>
                        <p class="text-primary-200">
                            <?php echo e($isFr 
                                ? 'Vous visez une carte de séjour plutôt que la nationalité ? Niveau A2 ou B1 :' 
                                : 'Applying for a residence card rather than citizenship? See the A2 and B1 rules'); ?>

                        </p>
                    </div>
                </div>

                <!-- Official Sources Line -->
                <div class="mt-12 text-center text-xs text-slate-400 max-w-3xl mx-auto">
                    <p>
                        <strong><?php echo e($isFr ? 'Sources :' : 'Sources:'); ?></strong>
                        <a href="https://www.service-public.gouv.fr/" target="_blank" rel="noopener noreferrer" class="underline hover:text-slate-600">Service-public.fr – <?php echo e($isFr ? 'naturalisation' : 'citizenship'); ?></a>
                        ·
                        <a href="https://www.service-public.gouv.fr/" target="_blank" rel="noopener noreferrer" class="underline hover:text-slate-600">Service-public.fr – <?php echo e($isFr ? 'cartes de séjour' : 'residence cards'); ?></a>
                    </p>
                </div>
            </div>
        </section>

        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <script>
        // FAQ accordion
        document.querySelectorAll('.faq-toggle').forEach(function (button) {
            button.addEventListener('click', function () {
                var content = this.nextElementSibling;
                var icon = this.querySelector('i');
                var isOpen = !content.classList.contains('hidden');
                content.classList.toggle('hidden');
                if (icon) icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(45deg)';
            });
        });
    </script>
</body>
</html>

<?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/citizenship-language-test.blade.php ENDPATH**/ ?>