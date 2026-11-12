<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';

    // Determine language based on current URL prefix
    $isFr = request()->is('fr*');

    // Page metadata
    $title = $isFr
        ? "Permis C en Suisse : exigences de langue A2/B1 et préparation en ligne"
        : "C Permit Switzerland: Language Requirements & Online Prep (A2/B1)";

    $metaDesc = $isFr
        ? "Exigences linguistiques pour le permis C en Suisse : A2 oral et A1 écrit pour le permis ordinaire (10 ans), B1 oral pour la demande anticipée (5 ans). Préparation en ligne."
        : "Language requirements for the Swiss C permit: A2 oral and A1 written for standard 10-year permit, B1 oral for early 5-year application. Live online preparation.";

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/permis-c-suisse/' : 'https://fluencefrancaise.fr/en/c-permit-switzerland/';
    $frUrl = 'https://fluencefrancaise.fr/fr/permis-c-suisse/';
    $enUrl = 'https://fluencefrancaise.fr/en/c-permit-switzerland/';

    // FAQ Data
    $faqs = $isFr ? [
        [
            'q' => "Quel niveau de français est exigé pour le permis C ordinaire ?",
            'a' => "Selon la loi fédérale sur les étrangers et l'intégration (LEI), la demande ordinaire de permis C après 10 ans de séjour en Suisse exige un niveau de langue attesté A2 à l'oral (parler et comprendre) et A1 à l'écrit (lire et écrire)."
        ],
        [
            'q' => "Quel niveau de français pour la demande anticipée de permis C après 5 ans ?",
            'a' => "Pour une demande anticipée de permis C après 5 ans au titre d'une intégration réussie (article 34 al. 4 LEI), vous devez attester d'un niveau B1 à l'oral et d'au moins A1 à l'écrit."
        ],
        [
            'q' => "Quel examen de langue choisir pour le permis C en Suisse ?",
            'a' => "Le test fide (Passeport des langues) est l'option la plus rapide et la plus adaptée à la vie quotidienne en Suisse. Cependant, les diplômes DELF (DELF A2 ou DELF B1) ainsi que les attestations TCF et TEF de moins de 2 ans sont également pleinement reconnus."
        ],
        [
            'q' => "Existe-t-il des dispenses de test de langue ?",
            'a' => "Oui. Vous pouvez être dispensé si le français est votre langue maternelle, ou si vous avez suivi au moins 3 années de scolarité obligatoire en français en Suisse, ou encore si vous êtes titulaire d'un diplôme du degré secondaire II ou tertiaire obtenu en français."
        ],
        [
            'q' => "Combien de temps faut-il pour atteindre le niveau A2 ou B1 ?",
            'a' => "Avec 2 à 4 heures de cours particuliers par semaine et un travail régulier, la plupart de nos apprenants atteignent le niveau A2 en 6 à 10 semaines, et le niveau B1 en 10 à 16 semaines selon leur niveau de départ."
        ]
    ] : [
        [
            'q' => "What level of French is required for a standard C permit?",
            'a' => "Under the Swiss Federal Foreign Nationals and Integration Act (FNIA / LEI), standard C permit applications after 10 years of residency require certified level A2 spoken (oral) and A1 written (reading & writing)."
        ],
        [
            'q' => "What level do I need for the early (fast-track) C permit?",
            'a' => "For the fast-track C permit after 5 years based on successful integration (Art. 34 para. 4 FNIA), you must demonstrate certified level B1 spoken (oral) and at least A1 written."
        ],
        [
            'q' => "Which language test should I choose for the C permit?",
            'a' => "The fide test (Language Passport) is the fastest and most practical option designed specifically for Swiss residency. However, DELF diplomas (DELF A2 or DELF B1) and TCF/TEF certificates under 2 years old are also fully recognized."
        ],
        [
            'q' => "Are there exemptions from the language requirement?",
            'a' => "Yes. You may be exempt if French is your native language, if you completed at least 3 years of compulsory schooling in French in Switzerland, or if you hold an upper secondary or tertiary degree taught in French."
        ],
        [
            'q' => "How long does it take to reach level A2 or B1?",
            'a' => "With 2 to 4 hours of live instruction per week and consistent practice, most students bridge the gap to level A2 in 6 to 10 weeks, and to level B1 in 10 to 16 weeks depending on their starting point."
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

            <!-- ============ SECTION 1: HERO BANNER ============ -->
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

                            <div class="inline-flex items-center gap-1.5 mb-5">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span
                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                    <?php echo e($isFr ? "SUISSE · PERMIS D'ÉTABLISSEMENT C" : 'SWITZERLAND · PERMANENT SETTLEMENT'); ?>

                                </span>
                            </div>

                            <h1
                                class="text-3xl sm:text-5xl lg:text-[52px] font-bold text-[#0c1e38] tracking-tight leading-[1.2]">
                                <?php echo e($isFr ? 'Permis C en Suisse :' : 'C Permit Switzerland:'); ?>

                                <br>
                                <span class="text-[#0055a4]">
                                    <?php echo e($isFr ? 'Atteignez le niveau de langue' : 'Reach the Language Level'); ?>

                                </span>
                                <br>
                                <span class="relative inline-block">
                                    <?php echo e($isFr ? 'exigé' : 'Required'); ?>

                                    <span
                                        class="absolute bottom-1 left-0 right-0 h-1 bg-gradient-to-r from-[#0055A4] to-[#EF4135] rounded-full"></span>
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

            <!-- ============ SECTION 2: WHAT LANGUAGE LEVEL DO YOU NEED? ============ -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'EXIGENCES FÉDÉRALES' : 'SWISS FEDERAL REQUIREMENTS'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Quel niveau de langue pour obtenir le permis C ?' : 'What language level do you need for a C permit?'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                            <?php echo e($isFr
    ? 'Depuis 2019, la loi fédérale sur les étrangers et l\'intégration (LEI) impose à tout demandeur de permis C de certifier ses compétences dans la langue nationale du canton de domicile (le français en Suisse romande).'
    : 'Under the revised Federal Foreign Nationals and Integration Act (FNIA), anyone applying for a settlement permit (Permis C) must demonstrate certified language proficiency in the national language of their canton of residence.'); ?>

                        </p>
                    </div>

                    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="bg-[#0055a4] text-white">
                                        <th class="py-4 px-6 font-semibold">
                                            <?php echo e($isFr ? 'Type de demande' : 'Application route'); ?>

                                        </th>
                                        <th class="py-4 px-6 font-semibold text-center">
                                            <?php echo e($isFr ? 'Oral (Parler & Comprendre)' : 'Spoken (Oral)'); ?>

                                        </th>
                                        <th class="py-4 px-6 font-semibold text-center">
                                            <?php echo e($isFr ? 'Écrit (Lire & Écrire)' : 'Written (Reading & Writing)'); ?>

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <?php echo e($isFr ? 'Permis C ordinaire (après 10 ans de séjour)' : 'Standard C permit (after 10 years of residence)'); ?>

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
                                            <?php echo e($isFr ? 'Permis C anticipé (après 5 ans pour intégration réussie)' : 'Fast-track / Early C permit (after 5 years)'); ?>

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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 3: THE C PERMIT AT A GLANCE ============ -->
            <section class="py-16 sm:py-24 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? "LE PERMIS D'ÉTABLISSEMENT" : 'THE SETTLEMENT PERMIT'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Le permis C en un coup d\'œil' : 'The C permit at a glance'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                            <?php echo e($isFr
    ? 'Le permis d\'établissement C confère la résidence permanente en Suisse. Contrairement au permis B, il n\'est lié à aucun employeur ni contrat temporaire.'
    : 'The C permit (Permis d\'établissement) grants permanent residency in Switzerland. Unlike the B permit, it is not tied to an employer or fixed-term contract.'); ?>

                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center flex-shrink-0 text-base">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#10233e] text-base mb-1">
                                    <?php echo e($isFr ? 'Droit de séjour et d\'emploi illimité' : 'Unlimited right of residence & employment'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Travaillez pour n\'importe quel employeur, devenez indépendant ou changez de canton sans autorisation préalable.'
    : 'Work for any employer, become self-employed, or change cantons without prior administrative authorization.'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center flex-shrink-0 text-base">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#10233e] text-base mb-1">
                                    <?php echo e($isFr ? 'Accès égal au marché du travail et aux aides' : 'Equal access to the Swiss labour market & welfare'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Protection renforcée contre le renvoi et plein droit aux prestations sociales et de chômage suisses.'
    : 'Protected against revocation on economic grounds and fully entitled to Swiss social security benefits.'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center flex-shrink-0 text-base">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#10233e] text-base mb-1">
                                    <?php echo e($isFr ? 'Fini les renouvellements annuels d\'autorisation' : 'No permit renewals or employer sponsorship'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Seul le titre biométrique physique est renouvelé tous les 5 ans, comme simple formalité administrative.'
    : 'Only the physical biometric card is renewed every 5 years, purely as an administrative routine.'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-2xl bg-blue-50 text-[#0055a4] flex items-center justify-center flex-shrink-0 text-base">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#10233e] text-base mb-1">
                                    <?php echo e($isFr ? 'Le tremplin obligatoire vers la nationalité suisse' : 'The direct gateway to Swiss naturalisation'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Être titulaire du permis C est la condition indispensable pour pouvoir déposer un dossier de naturalisation suisse.'
    : 'Holding a valid C permit is a mandatory prerequisite before you can apply for Swiss citizenship.'); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 4: EARLY C PERMIT: SPEAKING MAKES THE DIFFERENCE ============ -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl text-center">
                    <div class="inline-flex items-center gap-1.5 mb-3">
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                        <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                            <?php echo e($isFr ? 'VOIE ANTICIPÉE (5 ANS)' : 'FAST-TRACK ROUTE (5 YEARS)'); ?>

                        </span>
                    </div>
                    <h2
                        class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                        <?php echo e($isFr ? 'Permis C anticipé : l\'oral fait toute la différence' : 'Early C permit: speaking makes the difference'); ?>

                    </h2>
                    <p class="mt-6 text-sm sm:text-base text-slate-600 leading-relaxed font-normal max-w-2xl mx-auto">
                        <?php echo e($isFr
    ? 'Normalement, l\'octroi du permis C exige 10 ans de séjour continu en Suisse. Cependant, selon l\'article 34 alinéa 4 LEI, les ressortissants de l\'UE/AELE et des pays tiers peuvent l\'obtenir après seulement 5 ans en prouvant une intégration réussie.'
    : 'Normally, a C permit requires 10 years of unbroken residency in Switzerland. But under the fast-track rule for successful integration (Art. 34 para. 4 FNIA), both EU/EFTA and non-EU citizens can obtain it after just 5 years.'); ?>

                    </p>
                    <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal max-w-2xl mx-auto">
                        <?php echo e($isFr
    ? 'L\'élément clé est la maîtrise de la langue : votre français oral doit atteindre le niveau B1 (parler et comprendre) tandis que le niveau A1 suffit pour l\'écrit. Notre formation vous permet d\'atteindre ce palier oral rapidement et sans stress.'
    : 'The key deciding factor is language: your spoken French must reach B1 level (oral) while written French remains at A1 level. Our targeted training focuses on getting your speaking confidence to B1 standard quickly.'); ?>

                    </p>
                </div>
            </section>

            <!-- ============ SECTION 5: HOW DO YOU PROVE YOUR LEVEL? ============ -->
            <section class="py-16 sm:py-24 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'CERTIFICATS OFFICIELS' : 'ACCEPTED PROOF & CERTIFICATES'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Comment prouver votre niveau de français ?' : 'How do you prove your level?'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                            <?php echo e($isFr
    ? 'Les autorités cantonales de la population (OCPM, SPOP, etc.) acceptent les attestations et diplômes reconnus suivants :'
    : 'Swiss cantonal migration authorities (OCPM, SPOP, etc.) accept the following recognized language proofs:'); ?>

                        </p>
                    </div>

                    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="bg-[#0055a4] text-white">
                                        <th class="py-4 px-6 font-semibold w-1/3">
                                            <?php echo e($isFr ? 'Type de preuve' : 'Proof type'); ?>

                                        </th>
                                        <th class="py-4 px-6 font-semibold">
                                            <?php echo e($isFr ? 'Détails et reconnaissance' : 'Details & recognition'); ?>

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            fide (Passeport des langues)
                                        </td>
                                        <td class="py-4 px-6 text-slate-600 font-normal">
                                            <?php echo e($isFr
    ? 'Le test officiel suisse conçu spécifiquement pour le séjour et l\'établissement. Évaluation continue de A1 à B1. Valable à vie.'
    : 'The official Swiss test created specifically for residence and settlement. Continuous assessment for levels A1 to B1. Valid for life.'); ?>

                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            DELF / DALF (A2 ou B1)
                                        </td>
                                        <td class="py-4 px-6 text-slate-600 font-normal">
                                            <?php echo e($isFr
    ? 'Diplômes internationaux délivrés par France Éducation International. Les diplômes DELF A2 et DELF B1 sont reconnus dans toute la Suisse.'
    : 'Internationally recognized diplomas issued by France Éducation International. DELF A2 and DELF B1 certificates are fully accepted across Switzerland.'); ?>

                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            TCF / TEF
                                        </td>
                                        <td class="py-4 px-6 text-slate-600 font-normal">
                                            <?php echo e($isFr
    ? 'Acceptés s\'ils datent de moins de 2 ans et comportent les scores minimaux exigés par votre canton pour chaque épreuve.'
    : 'Accepted if taken within the last 2 years in an accredited test center with required score breakdown.'); ?>

                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <?php echo e($isFr ? 'Scolarité ou diplôme en français' : 'Swiss schooling or degree in French'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-slate-600 font-normal">
                                            <?php echo e($isFr
    ? 'Au moins 3 années d\'école obligatoire suivies en français, ou diplôme du secondaire II ou tertiaire obtenu en langue française.'
    : 'Completion of at least 3 years of compulsory schooling in French, or an upper secondary / tertiary degree taught in French.'); ?>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 6: HOW WE PREPARE YOU ============ -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'NOTRE MÉTHODE' : 'OUR METHOD'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Comment nous vous préparons' : 'How we prepare you'); ?>

                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-2xl bg-[#002654] text-white flex items-center justify-center flex-shrink-0 text-sm">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#10233e] text-base mb-1">
                                    <?php echo e($isFr ? 'Diagnostic ciblé' : 'Targeted diagnostic'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Votre tuteur évalue précisément votre écart face aux critères officiels A2 ou B1 dès votre première séance.'
    : 'Your tutor assesses your exact gap against A2 or B1 Swiss criteria in your first session.'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-2xl bg-[#002654] text-white flex items-center justify-center flex-shrink-0 text-sm">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#10233e] text-base mb-1">
                                    <?php echo e($isFr ? 'Un plan personnalisé' : 'A personalized plan'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Vos heures se concentrent sur l\'expression orale et la fluidité, où se joue l\'obtention du permis C.'
    : 'Your hours focus directly on oral communication, where the standard for C permit approval is set.'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-2xl bg-[#002654] text-white flex items-center justify-center flex-shrink-0 text-sm">
                                <i class="fas fa-video"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#10233e] text-base mb-1">
                                    <?php echo e($isFr ? '100 % en direct sur Google Meet' : '100% live online'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Séances en direct avec votre professeur dédié, programmées selon vos disponibilités professionnelles.'
    : 'Google Meet sessions with your dedicated tutor. Sessions scheduled flexibly around your work hours.'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-2xl bg-[#002654] text-white flex items-center justify-center flex-shrink-0 text-sm">
                                <i class="fas fa-file-signature"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#10233e] text-base mb-1">
                                    <?php echo e($isFr ? 'Examens blancs complets' : 'Full exam simulations'); ?>

                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'Simulations fide ou DELF en conditions d\'examen réelles, avec retours et grille de notation officielle.'
    : 'Mock tests under real exam conditions for the fide or DELF, with full corrections and feedback.'); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl bg-[#002654] text-white p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <div class="text-base sm:text-lg font-bold text-white">
                                <?php echo e($isFr ? 'Prêt à évaluer votre niveau ?' : 'Ready to test your level?'); ?>

                            </div>
                            <div class="text-xs sm:text-sm text-slate-300 font-normal mt-0.5">
                                <?php echo e($isFr
    ? 'Réservez votre séance d\'évaluation et mesurez votre écart dès aujourd\'hui.'
    : 'Book your introductory session and measure your gap today.'); ?>

                            </div>
                        </div>
                        <a href="/register"
                            class="inline-flex items-center justify-center px-6 py-3 rounded-full text-sm font-semibold text-[#002654] bg-white hover:bg-slate-100 transition-all flex-shrink-0">
                            <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                            <i class="fas fa-arrow-right text-xs ml-2"></i>
                        </a>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 7: C PERMIT SWITZERLAND FAQ ============ -->
            <?php echo $__env->make('partials.faq-section', [
                'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                'title' => $isFr ? 'Permis C<br>Suisse<br>FAQ' : 'C Permit<br>Switzerland<br>FAQ',
                'subtitle' => $isFr
                    ? 'Tout ce que vous devez savoir sur les exigences linguistiques du permis d\'établissement en Suisse.'
                    : 'Everything you need to know about Swiss language requirements for the settlement permit.',
                'faqs' => $faqs
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- ============ SECTION 8: FINAL CTA & SOURCES ============ -->
            <section class="py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <?php echo $__env->make('partials.cta-banner', [
                        'eyebrow' => $isFr ? 'ATTEIGNEZ VOTRE OBJECTIF' : "LET'S GET YOU THERE",
                        'title' => $isFr ? "Votre niveau. Le niveau exigé.<br>Nous comblons l'écart." : 'Your level. The required level.<br>We close the gap.',
                        'description' => $isFr
                            ? 'Votre premier cours définit votre niveau dans chaque compétence et le plan pour atteindre celui exigé par votre permis. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                            : 'Your first class sets your level in each section and the plan to reach the one your permit requires. You choose the pace: 2 or 4 hours a week.',
                        'primaryBtnText' => $isFr ? "S'inscrire" : 'Enroll now',
                        'primaryBtnUrl' => '/register',
                        'secondaryBtnText' => $isFr ? 'Découvrir nos cours' : 'Explore our courses',
                        'secondaryBtnUrl' => '/courses',
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
                        <span class="text-slate-300">·</span>
                        <a href="https://fide-service.ch" target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span>fide-service.ch</span>
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