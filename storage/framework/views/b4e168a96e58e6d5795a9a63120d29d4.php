<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';

    // Determine language based on current URL prefix
    $isFr = request()->is('fr*');

    // Page metadata
    $title = $isFr
        ? "Test fide : épreuves, niveaux A1-B1 et préparation en ligne"
        : "fide Test: Modules, A1–B1 Levels and Online Preparation";


    $metaDesc = $isFr
        ? "Test fide suisse : épreuves orale (40 min) et écrite (60 min), niveaux A1 à B1 pour permis B, permis C et naturalisation. Préparation en ligne en direct."
        : "Swiss fide test: oral (40 min) and written (60 min) modules, A1 to B1 levels for B permit, C permit, and Swiss citizenship. Live online preparation.";

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/test-fide/' : 'https://fluencefrancaise.fr/en/fide-test/';
    $frUrl = 'https://fluencefrancaise.fr/fr/test-fide/';
    $enUrl = 'https://fluencefrancaise.fr/en/fide-test/';

    // FAQ Data
    $faqs = $isFr ? [
        [
            'q' => "Le test fide est-il accepté pour la naturalisation suisse ?",
            'a' => "Oui. Le passeport des langues fide est officiellement reconnu par le Secrétariat d'État aux migrations (SEM) et par l'ensemble des cantons romands (Genève, Vaud, Fribourg, Neuchâtel, Valais, Jura) pour la nationalité suisse (généralement B1 oral et A2 écrit)."
        ],
        [
            'q' => "Puis-je passer uniquement l'épreuve orale ou uniquement l'écrit ?",
            'a' => "Oui. Les épreuves orale (40 min) et écrite (60 min) sont totalement indépendantes. Vous pouvez vous inscrire aux deux ou à une seule, et ne repasser que le module nécessaire en cas de besoin."
        ],
        [
            'q' => "Quelle est la durée de validité du passeport des langues fide ?",
            'a' => "Le passeport des langues fide a une durée de validité illimitée (à vie). Contrairement au DELF, TCF ou TEF qui expirent après 2 ans pour certains, le passeport fide n'expire jamais."
        ],
        [
            'q' => "Quand reçoit-on les résultats officiels ?",
            'a' => "Le secrétariat fide à Berne envoie votre passeport des langues par voie postale environ 3 à 4 semaines après la date de votre examen."
        ],
        [
            'q' => "Combien coûte le test fide officiel ?",
            'a' => "Les tarifs officiels standards dans les centres agréés sont d'environ 170 CHF pour l'épreuve orale, 120 CHF pour l'épreuve écrite, ou 250 CHF pour le test complet."
        ],
        [
            'q' => "Test fide ou DELF : lequel choisir pour la Suisse ?",
            'a' => "Pour vos démarches administratives suisses (permis de séjour, permis d'établissement et naturalisation), le test fide est conçu spécifiquement pour la vie quotidienne en Suisse et est souvent plus rapide et accessible que le DELF académique."
        ],
        [
            'q' => "Fluence Française fait-elle passer le test fide ?",
            'a' => "Non. Nous sommes un organisme de formation spécialisé qui prépare les candidats à réussir le test. L'examen officiel se passe dans un centre agréé par fide (à Genève, Lausanne, Fribourg, etc.)."
        ]
    ] : [
        [
            'q' => "Is the fide test accepted for Swiss naturalisation?",
            'a' => "Yes. The fide Language Passport is officially recognized by the State Secretariat for Migration (SEM) and across all French-speaking cantons (Geneva, Vaud, Fribourg, Neuchâtel, Valais, Jura) for citizenship applications (typically B1 oral and A2 written)."
        ],
        [
            'q' => "Can I take only the oral or only the written module?",
            'a' => "Yes. The oral module (40 min) and written module (60 min) are completely independent. You can take both together or just one, and retake only the section you need."
        ],
        [
            'q' => "How long is the fide Language Passport valid?",
            'a' => "The fide Language Passport has lifelong validity. Unlike the TCF or TEF which expire after 2 years, your fide certification never expires."
        ],
        [
            'q' => "When do I receive my official test results?",
            'a' => "The fide Secretariat in Bern sends your official Language Passport by post approximately 3 to 4 weeks after your exam date."
        ],
        [
            'q' => "How much does the official fide test cost?",
            'a' => "Standard official fees in Swiss test centres are approximately CHF 170 for the oral module, CHF 120 for the written module, or CHF 250 for both modules together."
        ],
        [
            'q' => "fide or DELF: which one should I take for Switzerland?",
            'a' => "For Swiss administrative requirements (permits and citizenship), fide is specifically designed around daily Swiss communication and is usually more practical and direct than the academic DELF diploma."
        ],
        [
            'q' => "Does Fluence Française run the fide exam?",
            'a' => "No. We prepare candidates for the test; registration and the exam take place in an official fide-accredited test centre in your canton (Geneva, Lausanne, Fribourg, etc.)."
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
                class="relative pt-28 sm:pt-36 md:pt-40 pb-16 md:pb-20 overflow-hidden bg-gradient-to-b from-[#f0f6ff]/70 via-white to-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl relative z-10 text-center">

                    <nav class="flex items-center justify-center gap-1.5 text-xs text-slate-500 mb-6 font-medium">
                        <a href="<?php echo e($isFr ? '/fr' : '/en'); ?>"
                            class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Accueil' : 'Home'); ?></a>
                        <span>›</span>
                        <a href="<?php echo e($isFr ? '/fr/cours' : '/en/courses'); ?>"
                            class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Examens' : 'Exams'); ?></a>
                        <span>›</span>
                        <span class="text-slate-800 font-semibold"><?php echo e($isFr ? 'Test fide' : 'fide test'); ?></span>
                    </nav>

                    <div class="inline-flex items-center gap-1.5 mb-5">
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                        <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                            <?php echo e($isFr ? 'PASSEPORT DES LANGUES SUISSE POUR PERMIS & NATURALISATION' : 'SWISS LANGUAGE PASSPORT FOR PERMITS & NATURALISATION'); ?>

                        </span>
                    </div>

                    <div class="flex flex-col items-center justify-center">
                        <h1
                            class="text-3xl sm:text-5xl lg:text-[56px] font-bold text-[#0c1e38] tracking-medium leading-[1.28]">
                            <?php echo e($isFr ? 'Test fide : Atteignez le niveau' : 'fide Test: Reach the Level'); ?>

                            <br>
                            <span class="relative inline-block text-[#0055a4]">
                                <?php echo e($isFr ? 'exigé par votre permis' : 'Your Permit Requires'); ?>

                            </span>
                        </h1>

                        <div class="w-full max-w-xs sm:max-w-md h-1 flex mt-3">
                            <span class="w-1/2 h-full bg-[#0055A4]"></span>
                            <span class="w-1/2 h-full bg-[#EF4135]"></span>
                        </div>
                    </div>

                    <p class="mt-6 text-sm sm:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto font-normal">
                        <?php echo e($isFr
    ? 'Le test fide évalue le français oral et écrit pour les permis de séjour et la naturalisation suisse. Nous vous préparons au niveau exact exigé par votre canton.'
    : 'The fide test measures oral and written French for Swiss residence and naturalisation. We train you for the exact grade your canton asks for.'); ?>

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
                        <div class="flex items-center gap-2">
                            <i class="fas fa-comments text-[#0055a4]"></i>
                            <span><?php echo e($isFr ? '40 min épreuve orale' : '40 min oral module'); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-pen-nib text-[#0055a4]"></i>
                            <span><?php echo e($isFr ? '60 min épreuve écrite' : '60 min written module'); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-passport text-[#0055a4]"></i>
                            <span><?php echo e($isFr ? 'Passeport des langues valable à vie' : 'Lifelong Language Passport'); ?></span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- the fide test at a glance -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                        <div class="lg:col-span-7">
                            <div class="inline-flex items-center gap-1.5 mb-3">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span
                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                    <?php echo e($isFr ? "DÉCOUVREZ L'EXAMEN" : 'KNOW YOUR EXAM'); ?>

                                </span>
                            </div>
                            <h2
                                class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                <?php echo e($isFr ? 'Le test fide en un coup d\'œil' : 'The fide test at a glance'); ?>

                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? "Le test fide (Français, Italiano, Deutsch in der Schweiz) est l'évaluation officielle reconnue par le Secrétariat d'État aux migrations (SEM). Vos compétences sont certifiées dans un Passeport des langues valable à vie."
    : "The fide test (Français, Italiano, Deutsch in der Schweiz) is the official language assessment recognized by the Swiss State Secretariat for Migration (SEM). Your results are certified in a lifelong Language Passport."); ?>

                            </p>
                            <div class="mt-6 flex items-center gap-2 text-xs sm:text-sm text-[#0055A4] font-semibold">
                                <svg class="w-4 h-4 text-[#0055A4]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span><?php echo e($isFr ? 'Préparation en ligne en direct. Confiance totale le jour J.' : 'Live online preparation. Real exam-day confidence.'); ?></span>
                            </div>
                        </div>

                        <div class="lg:col-span-5">
                            <div class="rounded-3xl bg-[#002654] text-white overflow-hidden relative">
                                <div class="h-[3px] w-full flex">
                                    <span class="w-1/2 h-full bg-[#0055A4]"></span>
                                    <span class="w-1/2 h-full bg-[#EF4135]"></span>
                                </div>
                                <div class="grid grid-cols-2 divide-x divide-y divide-white/10 p-6 sm:p-8">
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">40 min</div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            <?php echo e($isFr ? 'Module oral (Parler)' : 'Oral module (Speaking)'); ?>

                                        </div>
                                    </div>
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">60 min</div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            <?php echo e($isFr ? 'Module écrit (Écrire)' : 'Written module (Writing)'); ?>

                                        </div>
                                    </div>
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">A1–B1</div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            <?php echo e($isFr ? 'Niveaux évalués' : 'Levels assessed'); ?>

                                        </div>
                                    </div>
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">CHF 250</div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            <?php echo e($isFr ? 'Tarif officiel moyen' : 'Standard exam fee'); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 bg-[#f8fafc] rounded-3xl border border-slate-200/80 p-6 sm:p-8">
                        <div class="divide-y divide-slate-200/60">
                            <div class="py-4 first:pt-0 grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-6 text-sm">
                                <span class="md:col-span-4 font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Pour qui ?' : "Who it's for"); ?>

                                </span>
                                <span class="md:col-span-8 text-slate-600 font-normal">
                                    <?php echo e($isFr
    ? 'Toute personne demandant un permis B (séjour), permis C (établissement) ou la nationalité suisse'
    : 'Anyone applying for a Swiss B permit (residence), C permit (settlement), or Swiss citizenship'); ?>

                                </span>
                            </div>
                            <div class="py-4 grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-6 text-sm">
                                <span class="md:col-span-4 font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Niveaux évalués' : 'Levels tested'); ?>

                                </span>
                                <span class="md:col-span-8 text-slate-600 font-normal">
                                    <?php echo e($isFr
    ? 'A1 à B1 (évaluation continue et progressive, sans seuil éliminatoire)'
    : 'A1 to B1 (continuous evaluation, no pass/fail barrier)'); ?>

                                </span>
                            </div>
                            <div class="py-4 grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-6 text-sm">
                                <span class="md:col-span-4 font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Épreuves' : 'Modules'); ?>

                                </span>
                                <span class="md:col-span-8 text-slate-600 font-normal">
                                    <?php echo e($isFr
    ? 'Oral (parler et comprendre, 40 min) et Écrit (lire et écrire, 60 min) — à passer ensemble ou séparément'
    : 'Oral (speaking & listening, 40 min) and Written (reading & writing, 60 min) — take together or separately'); ?>

                                </span>
                            </div>
                            <div class="py-4 grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-6 text-sm">
                                <span class="md:col-span-4 font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Durée totale' : 'Total duration'); ?>

                                </span>
                                <span class="md:col-span-8 text-slate-600 font-normal">
                                    <?php echo e($isFr ? '1 h 40 au total (40 min oral + 60 min écrit)' : '1 h 40 in total (40 min oral + 60 min written)'); ?>

                                </span>
                            </div>
                            <div class="py-4 last:pb-0 grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-6 text-sm">
                                <span class="md:col-span-4 font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Validité' : 'Validity'); ?>

                                </span>
                                <span class="md:col-span-8 text-slate-600 font-normal">
                                    <?php echo e($isFr
    ? 'À vie — le passeport des langues fide ne comporte aucune date d\'expiration'
    : 'Lifelong — the fide Language Passport has no expiry date'); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- what fide level does your permit need? -->
            <section class="py-16 sm:py-24 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'EXIGENCES LINGUISTIQUES' : 'SWISS PERMIT REQUIREMENTS'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Quel niveau fide pour votre permis ?' : 'What fide level does your permit need?'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                            <?php echo e($isFr
    ? 'Les exigences fédérales du SEM (Secrétariat d\'État aux migrations) varient selon le type de permis et peuvent être adaptées par votre canton.'
    : 'Federal requirements set by the SEM vary by permit type and may be adjusted by your Swiss canton.'); ?>

                        </p>
                    </div>

                    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="bg-[#0055a4] text-white">
                                        <th class="py-4 px-6 font-semibold">
                                            <?php echo e($isFr ? 'Permis / Démarche' : 'Permit / Application'); ?>

                                        </th>
                                        <th class="py-4 px-6 font-semibold text-center">
                                            <?php echo e($isFr ? 'Oral (Parler & Comprendre)' : 'Oral (Speaking & Listening)'); ?>

                                        </th>
                                        <th class="py-4 px-6 font-semibold text-center">
                                            <?php echo e($isFr ? 'Écrit (Lire & Écrire)' : 'Written (Reading & Writing)'); ?>

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <?php echo e($isFr ? 'Permis B (Séjour / Regroupement)' : 'Permit B (Residence / Family reunification)'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#0055a4]">A1</span>
                                        </td>
                                        <td class="py-4 px-6 text-center text-slate-400 font-medium">—</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <?php echo e($isFr ? 'Permis C ordinaire (Établissement après 10 ans)' : 'Standard Permit C (Settlement after 10 years)'); ?>

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
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <?php echo e($isFr ? 'Permis C anticipé (Intégration réussie après 5 ans)' : 'Fast-track Permit C (Successful integration after 5 years)'); ?>

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
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <?php echo e($isFr ? 'Naturalisation suisse (Nationalité)' : 'Swiss naturalisation (Citizenship)'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">B1</span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span
                                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#0055a4]">A2</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-center gap-2 text-xs text-slate-500">
                        <i class="fas fa-circle-info text-[#0055a4]"></i>
                        <span>
                            <?php echo e($isFr
    ? 'Exigences cantonales : vérifiez toujours auprès de votre service cantonal de la population (ex. OCPM à Genève, SPOP dans le canton de Vaud).'
    : 'Cantonal variations: always verify requirements with your local migration office (e.g. OCPM Geneva, SPOP Vaud).'); ?>

                        </span>
                    </div>
                </div>
            </section>

            <!-- fide test format -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'STRUCTURE DU TEST' : 'TEST STRUCTURE'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Format du test fide' : 'fide test format'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                            <?php echo e($isFr
    ? 'Le test évalue le français à travers deux modules indépendants axés sur la vie quotidienne en Suisse.'
    : 'The test assesses your French through two independent modules grounded in everyday Swiss life.'); ?>

                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div
                            class="bg-white rounded-3xl border border-slate-200/80 p-7 sm:p-8 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span
                                        class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center text-lg">
                                        <i class="fas fa-comments"></i>
                                    </span>
                                    <span
                                        class="px-3.5 py-1 rounded-full text-xs font-bold bg-[#f0f6fc] text-[#0055a4] border border-blue-100">
                                        40 min
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-[#10233e] mb-2">
                                    <?php echo e($isFr ? 'Parler et comprendre (Oral)' : 'Speaking & Listening (Oral)'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6 font-normal">
                                    <?php echo e($isFr
    ? 'Évalue votre capacité à interagir spontanément avec un interlocuteur suisse dans des situations courantes.'
    : 'Evaluates your ability to communicate spontaneously in standard daily Swiss situations.'); ?>

                                </p>
                                <ul class="space-y-3 text-xs sm:text-sm text-slate-600">
                                    <li class="flex items-start gap-2.5">
                                        <i class="fas fa-check text-[#0055a4] mt-0.5 text-xs"></i>
                                        <span><?php echo e($isFr ? 'Partie 1 : Présentation et échange quotidien' : 'Part 1: Self-introduction & everyday conversation'); ?></span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <i class="fas fa-check text-[#0055a4] mt-0.5 text-xs"></i>
                                        <span><?php echo e($isFr ? 'Partie 2 : Dialogue de mise en situation (médecin, régie, employeur)' : 'Part 2: Role-play dialogue (doctor, landlord, employer)'); ?></span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <i class="fas fa-check text-[#0055a4] mt-0.5 text-xs"></i>
                                        <span><?php echo e($isFr ? 'Partie 3 : Discussion à partir d\'images et expression d\'avis' : 'Part 3: Image-based scenario & opinion sharing'); ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div
                                class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                <span><?php echo e($isFr ? 'Niveaux attestés :' : 'Assessed levels:'); ?></span>
                                <span class="font-bold text-[#0055a4]">A1 · A2 · B1</span>
                            </div>
                        </div>

                        <div
                            class="bg-white rounded-3xl border border-slate-200/80 p-7 sm:p-8 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span
                                        class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center text-lg">
                                        <i class="fas fa-pen-nib"></i>
                                    </span>
                                    <span
                                        class="px-3.5 py-1 rounded-full text-xs font-bold bg-[#f0f6fc] text-[#0055a4] border border-blue-100">
                                        60 min
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-[#10233e] mb-2">
                                    <?php echo e($isFr ? 'Lire et écrire (Écrit)' : 'Reading & Writing (Written)'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6 font-normal">
                                    <?php echo e($isFr
    ? 'Évalue la compréhension de documents suisses authentiques et la rédaction de messages pratiques.'
    : 'Evaluates reading comprehension of authentic Swiss notices and practical written correspondence.'); ?>

                                </p>
                                <ul class="space-y-3 text-xs sm:text-sm text-slate-600">
                                    <li class="flex items-start gap-2.5">
                                        <i class="fas fa-check text-[#0055a4] mt-0.5 text-xs"></i>
                                        <span><?php echo e($isFr ? 'Partie 1 : Compréhension d\'annonces, horaires et courriers' : 'Part 1: Understanding notices, timetables, and mail'); ?></span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <i class="fas fa-check text-[#0055a4] mt-0.5 text-xs"></i>
                                        <span><?php echo e($isFr ? 'Partie 2 : Remplissage de formulaires administratifs suisses' : 'Part 2: Filling out standard Swiss official forms'); ?></span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <i class="fas fa-check text-[#0055a4] mt-0.5 text-xs"></i>
                                        <span><?php echo e($isFr ? 'Partie 3 : Rédaction d\'un message ou d\'un courriel officiel' : 'Part 3: Writing an inquiry, notice, or short letter'); ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div
                                class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                <span><?php echo e($isFr ? 'Niveaux attestés :' : 'Assessed levels:'); ?></span>
                                <span class="font-bold text-[#0055a4]">A1 · A2 · B1</span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-8 text-center bg-[#f0f6fc] rounded-2xl p-4 border border-blue-100/70 text-xs sm:text-sm text-slate-600">
                        <?php echo e($isFr
    ? 'Vous pouvez passer les deux modules le même jour ou séparément. Le passeport des langues certifie chaque épreuve de façon indépendante.'
    : 'You can take both modules on the same day or separately. Your Language Passport certificates each skill independently.'); ?>

                    </div>
                </div>
            </section>

            <!-- where fide candidates lose points -->
            <section class="py-16 sm:py-24 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'LES PIÈGES DU TEST' : 'COMMON PITFALLS'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Là où les candidats perdent des points au test fide' : 'Where fide candidates lose points'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                            <?php echo e($isFr
    ? 'Le test fide ne sanctionne pas la grammaire théorique : il évalue votre efficacité communicative dans la vie réelle en Suisse.'
    : 'The fide test does not penalize theoretical grammar: it evaluates communicative effectiveness in real-life Swiss situations.'); ?>

                        </p>
                    </div>

                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 mb-8">
                        <h3 class="text-lg sm:text-xl font-bold text-[#10233e] mb-4">
                            <?php echo e($isFr ? 'Test fide vs Examens traditionnels (DELF, TCF)' : 'fide vs Traditional Exams (DELF, TCF)'); ?>

                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs sm:text-sm border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-semibold">
                                        <th class="py-3 px-4"><?php echo e($isFr ? 'Critère' : 'Criteria'); ?></th>
                                        <th class="py-3 px-4">
                                            <?php echo e($isFr ? 'Examens académiques (DELF / TCF)' : 'Academic tests (DELF / TCF)'); ?>

                                        </th>
                                        <th class="py-3 px-4 text-[#0055a4]">
                                            <?php echo e($isFr ? 'Test fide suisse' : 'Swiss fide test'); ?>

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr>
                                        <td class="py-3.5 px-4 font-bold text-[#10233e]">
                                            <?php echo e($isFr ? 'Objectif' : 'Main focus'); ?>

                                        </td>
                                        <td class="py-3.5 px-4 text-slate-500">
                                            <?php echo e($isFr ? 'Règles de grammaire, syntaxe et orthographe académiques' : 'Grammar rules, formal structure, and academic syntax'); ?>

                                        </td>
                                        <td class="py-3.5 px-4 text-slate-700 font-medium">
                                            <?php echo e($isFr ? 'Se faire comprendre dans la vie quotidienne suisse' : 'Getting things done in daily Swiss life (work, commune, doctor)'); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3.5 px-4 font-bold text-[#10233e]">
                                            <?php echo e($isFr ? 'Notation' : 'Scoring'); ?>

                                        </td>
                                        <td class="py-3.5 px-4 text-slate-500">
                                            <?php echo e($isFr ? 'Réussite ou échec sur un niveau fixe pré-choisi' : 'Pass or fail for one fixed pre-selected level'); ?>

                                        </td>
                                        <td class="py-3.5 px-4 text-slate-700 font-medium">
                                            <?php echo e($isFr ? 'Évaluation progressive : vos points fixent votre palier (A1, A2 ou B1)' : 'Progressive scale: your score determines your level (A1, A2, or B1)'); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3.5 px-4 font-bold text-[#10233e]">
                                            <?php echo e($isFr ? 'Épreuve orale' : 'Speaking style'); ?>

                                        </td>
                                        <td class="py-3.5 px-4 text-slate-500">
                                            <?php echo e($isFr ? 'Exposé formel, monologue et questions académiques' : 'Formal monologue, abstract presentation, structured thesis'); ?>

                                        </td>
                                        <td class="py-3.5 px-4 text-slate-700 font-medium">
                                            <?php echo e($isFr ? 'Jeu de rôle interactif avec l\'examinateur jouant un rôle suisse' : 'Spontaneous interactive dialogue with examiner acting as Swiss interlocutor'); ?>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div class="space-y-4">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60 inline-flex items-center gap-1.5">
                                    <i class="fas fa-triangle-exclamation text-[10px]"></i>
                                    <span><?php echo e($isFr ? 'Piège 1' : 'Trap 1'); ?></span>
                                </span>
                                <h3 class="text-lg font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Le traiter comme un examen scolaire' : 'Treating it like an academic exam'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Mémoriser des règles de subjonctif au lieu de s\'entraîner à réagir avec spontanéité dans des situations pratiques.'
    : 'Memorizing grammar tables instead of practicing spontaneous spoken reactions in everyday Swiss situations.'); ?>

                                </p>
                            </div>
                            <div class="mt-6 pt-4 bg-[#f2f7fc] rounded-2xl p-4 border border-blue-100/70">
                                <div
                                    class="text-xs font-bold text-[#0055a4] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <i class="fas fa-check text-xs"></i>
                                    <span><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Jeux de rôle en direct : commune, médecin, régie immobilière, employeur.'
    : 'Live role-play simulations: communal office, doctor, rental agency, workplace.'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div class="space-y-4">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60 inline-flex items-center gap-1.5">
                                    <i class="fas fa-triangle-exclamation text-[10px]"></i>
                                    <span><?php echo e($isFr ? 'Piège 2' : 'Trap 2'); ?></span>
                                </span>
                                <h3 class="text-lg font-bold text-[#10233e]">
                                    <?php echo e($isFr ? "L'hésitation et le silence" : 'Freezing up and prolonged silence'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? "Chercher le mot parfait et se taire coûte cher. L'examinateur fide valorise la fluidité et les stratégies de relance."
    : 'Hunting for the "perfect" word and staying silent hurts your score. Examiners reward communicative repair strategies.'); ?>

                                </p>
                            </div>
                            <div class="mt-6 pt-4 bg-[#f2f7fc] rounded-2xl p-4 border border-blue-100/70">
                                <div
                                    class="text-xs font-bold text-[#0055a4] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <i class="fas fa-check text-xs"></i>
                                    <span><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Phrases d\'accroche et automatismes conversationnels pour maintenir le dialogue sans blocage.'
    : 'Conversation starters and conversational maintenance phrases drilled until fluent.'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex flex-col justify-between">
                            <div class="space-y-4">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60 inline-flex items-center gap-1.5">
                                    <i class="fas fa-triangle-exclamation text-[10px]"></i>
                                    <span><?php echo e($isFr ? 'Piège 3' : 'Trap 3'); ?></span>
                                </span>
                                <h3 class="text-lg font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Négliger le module écrit' : 'Neglecting the written module'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Beaucoup réussissent le B1 à l\'oral mais manquent le A2 à l\'écrit faute de connaître les formats de courriers suisses.'
    : 'Many score B1 oral but fall short of A2 writing due to unfamiliarity with standard Swiss official formats.'); ?>

                                </p>
                            </div>
                            <div class="mt-6 pt-4 bg-[#f2f7fc] rounded-2xl p-4 border border-blue-100/70">
                                <div
                                    class="text-xs font-bold text-[#0055a4] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                    <i class="fas fa-check text-xs"></i>
                                    <span><?php echo e($isFr ? 'Ce que nous faisons :' : 'What we do:'); ?></span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Entraînement ciblé sur formulaires administratifs, courriels de réclamation et modèles types.'
    : 'Targeted practice on authentic Swiss administrative forms, email notices, and standard templates.'); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- preparing in geneva, lausanne, fribourg... -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="rounded-3xl bg-[#002654] text-white p-8 sm:p-12 relative overflow-hidden">
                        <div class="absolute inset-x-0 top-0 h-1 flex">
                            <span class="w-1/2 bg-[#0055A4]"></span>
                            <span class="w-1/2 bg-[#EF4135]"></span>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                            <div class="lg:col-span-7">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-blue-200 mb-4">
                                    <?php echo e($isFr ? 'COURS 100% EN LIGNE PARTOUT EN SUISSE' : '100% ONLINE CLASSES ACROSS SWITZERLAND'); ?>

                                </span>
                                <h2
                                    class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white tracking-tight leading-tight">
                                    <?php echo e($isFr
    ? 'Préparation au test fide à Genève, Lausanne, Fribourg, Neuchâtel ou Nyon'
    : 'Preparing for the fide test in Geneva, Lausanne, Fribourg, Neuchâtel or Nyon'); ?>

                                </h2>
                                <p class="mt-4 text-sm sm:text-base text-slate-200 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Nos cours ont lieu en direct sur Google Meet avec votre professeur particulier dédié. Où que vous résidiez en Suisse romande, vos séances s\'adaptent à votre planning professionnel.'
    : 'Our classes take place live on Google Meet with your dedicated private tutor. Wherever you live across Switzerland, your sessions adapt to your work and family schedule.'); ?>

                                </p>
                            </div>
                            <div class="lg:col-span-5">
                                <div class="bg-white/10 backdrop-blur rounded-2xl p-6 border border-white/10">
                                    <div class="flex items-center gap-2 text-sm font-bold text-white mb-3">
                                        <i class="fas fa-location-dot text-[#EF4135]"></i>
                                        <span><?php echo e($isFr ? 'Centres d\'examen agréés en Romandie' : 'Accredited test centres across Romandie'); ?></span>
                                    </div>
                                    <div class="flex flex-wrap gap-2 text-xs font-medium text-slate-200 mb-4">
                                        <span class="px-2.5 py-1 rounded-lg bg-white/10">Genève</span>
                                        <span class="px-2.5 py-1 rounded-lg bg-white/10">Lausanne</span>
                                        <span class="px-2.5 py-1 rounded-lg bg-white/10">Fribourg</span>
                                        <span class="px-2.5 py-1 rounded-lg bg-white/10">Neuchâtel</span>
                                        <span class="px-2.5 py-1 rounded-lg bg-white/10">Nyon</span>
                                        <span class="px-2.5 py-1 rounded-lg bg-white/10">Sion</span>
                                    </div>
                                    <p class="text-xs text-slate-300 leading-relaxed font-normal">
                                        <?php echo e($isFr
    ? 'Préparez-vous à distance avec nous, puis passez l\'épreuve officielle dans le centre le plus proche de votre domicile.'
    : 'Prepare online from home with us, then sit the official test at your local accredited cantonal centre.'); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- how we prepare you for the fide test -->
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
                            <?php echo e($isFr ? 'Notre préparation au test fide' : 'How we prepare you for the fide test'); ?>

                        </h2>
                        <p
                            class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto font-normal">
                            <?php echo e($isFr
    ? 'Un accompagnement ciblé dès votre premier cours jusqu’à l’examen. De vrais scénarios suisses. Pas de programme générique.'
    : 'Focused support from your first class to test day. Real Swiss scenarios. No generic syllabus.'); ?>

                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="flex flex-col items-start">
                            <div class="relative flex items-center w-full mb-4">
                                <span
                                    class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055a4] text-sm font-semibold flex items-center justify-center border border-blue-100 flex-shrink-0">
                                    01
                                </span>
                                <div class="hidden lg:block flex-1 h-[1px] bg-[#e1eaf2] ml-4 -mr-4"></div>
                            </div>
                            <h3 class="font-bold text-[#10233e] text-base sm:text-[17px] mb-2">
                                <?php echo e($isFr ? 'Votre niveau, diagnostiqué' : 'Your level, diagnosed'); ?>

                            </h3>
                            <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? 'Dès le premier cours, votre tuteur évalue votre oral et votre écrit directement face aux critères du SEM.'
    : 'In your first class, your tutor benchmarks your oral and written skills directly against the Swiss SEM criteria.'); ?>

                            </p>
                        </div>

                        <div class="flex flex-col items-start">
                            <div class="relative flex items-center w-full mb-4">
                                <span
                                    class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055a4] text-sm font-semibold flex items-center justify-center border border-blue-100 flex-shrink-0">
                                    02
                                </span>
                                <div class="hidden lg:block flex-1 h-[1px] bg-[#e1eaf2] ml-4 -mr-4"></div>
                            </div>
                            <h3 class="font-bold text-[#10233e] text-base sm:text-[17px] mb-2">
                                <?php echo e($isFr ? 'Un plan bâti sur votre permis' : 'A plan built on your permit goal'); ?>

                            </h3>
                            <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? 'Les heures ciblent votre niveau visé (A1, A2 ou B1) sans perdre de temps sur les acquis.'
    : 'Hours focus on your target level (A1, A2, or B1) without spending time on what you\'ve already mastered.'); ?>

                            </p>
                        </div>

                        <div class="flex flex-col items-start">
                            <div class="relative flex items-center w-full mb-4">
                                <span
                                    class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055a4] text-sm font-semibold flex items-center justify-center border border-blue-100 flex-shrink-0">
                                    03
                                </span>
                                <div class="hidden lg:block flex-1 h-[1px] bg-[#e1eaf2] ml-4 -mr-4"></div>
                            </div>
                            <h3 class="font-bold text-[#10233e] text-base sm:text-[17px] mb-2">
                                <?php echo e($isFr ? 'Cours en direct, 2 ou 4 h par semaine' : 'Live classes, 2 or 4 hours a week'); ?>

                            </h3>
                            <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? 'En particulier ou en mini-groupe de 2 à 5 personnes, sur Google Meet, avec vos notes après chaque séance.'
    : '1:1 or in a group of 2–5 on Google Meet, with notes after every session.'); ?>

                            </p>
                        </div>

                        <div class="flex flex-col items-start">
                            <div class="relative flex items-center w-full mb-4">
                                <span
                                    class="w-10 h-10 rounded-full bg-[#f0f6fc] text-[#0055a4] text-sm font-semibold flex items-center justify-center border border-blue-100 flex-shrink-0">
                                    04
                                </span>
                            </div>
                            <h3 class="font-bold text-[#10233e] text-base sm:text-[17px] mb-2">
                                <?php echo e($isFr ? 'Examens blancs fide complets' : 'Full fide mock exams'); ?>

                            </h3>
                            <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? 'Simulations des épreuves orales et écrites en conditions d\'examen, avec retour détaillé.'
    : 'Simulations of both oral and written parts under timed test conditions, with detailed feedback.'); ?>

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

            <!-- faq -->
            <?php echo $__env->make('partials.faq-section', [
                'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                'title' => $isFr ? 'Test fide<br>FAQ' : 'fide test<br>FAQ',
                'subtitle' => $isFr
                    ? 'Tout ce que vous devez savoir sur le test fide, le passeport des langues et les permis suisses.'
                    : 'Everything you need to know about taking the Swiss fide test in French.',
                'faqs' => $faqs
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- final cta & sources -->
            <section class="py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <?php echo $__env->make('partials.cta-banner', [
                        'eyebrow' => $isFr ? 'ATTEIGNEZ VOTRE OBJECTIF' : "LET'S GET YOU THERE",
                        'title' => $isFr ? "Votre niveau. Le niveau exigé.<br>Nous comblons l'écart." : 'Your level. The required level.<br>We close the gap.',
                        'description' => $isFr
                            ? 'Votre premier cours définit votre niveau dans chaque compétence et le plan pour atteindre celui exigé par votre permis. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                            : 'Your first class sets your level in each section and the plan to reach the one your permit needs. You choose the pace: 2 or 4 hours a week.',
                        'primaryBtnText' => $isFr ? "S'inscrire" : 'Enroll now',
                        'primaryBtnUrl' => '/register',
                        'secondaryBtnText' => $isFr ? 'Nous contacter' : 'Talk to us',
                        'secondaryBtnUrl' => '/contact',
                        'helperText' => $isFr
                            ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                            : 'Live classes on Google Meet · 1:1 or small group · Student portal included'
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="mt-8 text-center text-xs sm:text-sm text-slate-500 font-normal">
                        <span><?php echo e($isFr ? 'Vous venez à deux ? 50 CHF de réduction par mois, pour vous et votre proche.' : 'Coming with a friend? CHF 50 off per month, for you and for them.'); ?></span>
                        <a href="<?php echo e($isFr ? '/fr/contact' : '/en/contact'); ?>"
                            class="text-[#0055a4] font-semibold hover:underline inline-flex items-center gap-1 ml-1.5">
                            <span><?php echo e($isFr ? 'Contactez-nous' : 'Contact us'); ?></span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <div class="border-t border-slate-200/80 my-8"></div>

                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-slate-400">
                        <span
                            class="font-bold uppercase tracking-wider text-slate-500"><?php echo e($isFr ? 'SOURCES' : 'SOURCES'); ?></span>
                        <a href="https://fide-service.ch" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span>fide-service.ch</span>
                            <span class="text-[10px]">↗</span>
                        </a>
                        <span class="text-slate-300">·</span>
                        <a href="https://www.sem.admin.ch" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span>SEM — Secrétariat d'État aux migrations</span>
                            <span class="text-[10px]">↗</span>
                        </a>
                        <span class="text-slate-300">·</span>
                        <a href="https://www.ge.ch/ocpm" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span>Canton de Genève — OCPM</span>
                            <span class="text-[10px]">↗</span>
                        </a>
                        <span class="text-slate-300">·</span>
                        <a href="https://www.vd.ch/population" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span>Canton de Vaud — SPOP</span>
                            <span class="text-[10px]">↗</span>
                        </a>
                    </div>
                </div>
            </section>
        </div>

        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

</body>

</html><?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/fide-test.blade.php ENDPATH**/ ?>