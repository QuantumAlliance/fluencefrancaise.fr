<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';

    // Determine language based on current URL prefix
    $isFr = request()->is('fr*');

    // Page metadata
    $title = $isFr
        ? "Test de français naturalisation 2026 : le niveau B2 exigé"
        : "French Citizenship Language Test: How to Reach B2";

    $metaDesc = $isFr
        ? "Depuis 2026, la naturalisation exige le niveau B2 à l'oral et à l'écrit. Les tests acceptés (TCF, TEF, DELF B2), examen civique et préparation en ligne en direct."
        : "Since 2026, French citizenship requires B2 French across all 4 skills. Official accepted tests (TCF, TEF, DELF B2), civic exam, and live online preparation.";

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/test-francais-naturalisation/' : 'https://fluencefrancaise.fr/en/french-citizenship-language-test/';
    $frUrl = 'https://fluencefrancaise.fr/fr/test-francais-naturalisation/';
    $enUrl = 'https://fluencefrancaise.fr/en/french-citizenship-language-test/';

    // FAQ Data
    $faqs = $isFr ? [
        [
            'q' => "Le niveau B1 suffit-il encore pour la naturalisation ?",
            'a' => "Seulement pour les dossiers officiellement déposés avant le 1er janvier 2026. Pour toute nouvelle demande déposée depuis cette date, le niveau B2 est obligatoire sur les 4 compétences (compréhension orale, écrite, expression orale et écrite)."
        ],
        [
            'q' => "Le B2 s'applique-t-il aussi à la nationalité par mariage ?",
            'a' => "Oui. Depuis le 1er janvier 2026, la nationalité par déclaration (mariage avec conjoint français) exige également le niveau B2 sur l'ensemble des 4 épreuves."
        ],
        [
            'q' => "Quel test est le plus accessible pour le B2 : TCF IRN, TEF IRN ou DELF B2 ?",
            'a' => "Les trois évaluent la même norme B2 du CECRL. Le TCF IRN et le TEF IRN se déroulent sur ordinateur avec une échelle de points progressive, tandis que le DELF B2 est un examen complet sur table et oral conférant un diplôme valable à vie."
        ],
        [
            'q' => "Quelle est la durée de validité des attestations de langue ?",
            'a' => "Les attestations du TCF IRN et du TEF IRN sont valables pendant 2 ans à compter de la date de passation. Le diplôme du DELF B2 est valable à vie et ne périme jamais."
        ],
        [
            'q' => "Un diplôme universitaire étranger dispensé en français est-il accepté ?",
            'a' => "Non. Les diplômes délivrés par des établissements étrangers ne dispensent pas du test, même si les études ont été suivies en français. Seuls les diplômes français ou une attestation officielle (TCF, TEF, DELF B2) sont recevables."
        ],
        [
            'q' => "En quoi consiste l'examen civique pour la naturalisation ?",
            'a' => "Les candidats à la naturalisation par décret doivent justifier d'une connaissance suffisante de l'histoire, de la culture, des institutions et des valeurs de la République française, évaluée via un questionnaire et l'entretien d'assimilation en préfecture."
        ],
        [
            'q' => "Combien de temps faut-il pour passer du niveau B1 au niveau B2 ?",
            'a' => "Avec 2 à 4 heures de cours en direct par semaine associées à un entraînement ciblé, la plupart de nos apprenants comblent l'écart du B1 au B2 en 8 à 14 semaines."
        ]
    ] : [
        [
            'q' => "Is level B1 still accepted for French citizenship?",
            'a' => "Only for applications officially filed before 1 January 2026. For all new applications filed since that date, level B2 is strictly required across all 4 skills (listening, reading, writing, and speaking)."
        ],
        [
            'q' => "Does the B2 requirement apply to citizenship by marriage?",
            'a' => "Yes. Under the new 2026 decree, citizenship by declaration (marriage to a French spouse) also requires certified level B2 across all 4 skills."
        ],
        [
            'q' => "Which test is easiest to reach B2: TCF IRN, TEF IRN or DELF B2?",
            'a' => "All three assess the same CEFR B2 standard. TCF IRN and TEF IRN are computer-based with progressive scoring, while DELF B2 is a traditional written and oral examination awarding a lifetime diploma."
        ],
        [
            'q' => "How long are the test certificates valid?",
            'a' => "TCF IRN and TEF IRN certificates are valid for 2 years from the exam date. The DELF B2 diploma has lifelong validity and never expires."
        ],
        [
            'q' => "Are foreign university degrees taught in French accepted?",
            'a' => "No. Diplomas issued by institutions outside France do not provide an exemption, even if your studies were conducted entirely in French. Only French national diplomas or approved test results (TCF, TEF, DELF B2) are accepted."
        ],
        [
            'q' => "What does the civic exam requirement involve?",
            'a' => "Candidates applying by decree must demonstrate sufficient knowledge of French history, culture, institutions, and Republican principles, evaluated through an official questionnaire and the prefecture assimilation interview."
        ],
        [
            'q' => "How long does it take to prepare from B1 to B2?",
            'a' => "With 2 to 4 hours of live instruction per week and targeted practice, most students bridge the gap from B1 to B2 in 8 to 14 weeks."
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
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl relative z-10 text-center">

                    <nav class="flex items-center justify-center gap-1.5 text-xs text-slate-500 mb-6 font-medium">
                        <a href="<?php echo e($isFr ? '/fr' : '/en'); ?>"
                            class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Accueil' : 'Home'); ?></a>
                        <span>›</span>
                        <a href="<?php echo e($isFr ? '/fr/cours' : '/en/courses'); ?>"
                            class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Examens' : 'Exams'); ?></a>
                        <span>›</span>
                        <span class="text-slate-800 font-semibold"><?php echo e($isFr ? 'Naturalisation' : 'Citizenship'); ?></span>
                    </nav>

                    <div class="inline-flex items-center gap-1.5 mb-5">
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                        <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                            <?php echo e($isFr ? 'NATURALISATION FRANÇAISE · NOUVELLE EXIGENCE 2026' : 'FRENCH CITIZENSHIP · NEW 2026 LANGUAGE REQUIREMENT'); ?>

                        </span>
                    </div>

                    <div class="flex flex-col items-center justify-center">
                        <h1
                            class="text-3xl sm:text-5xl lg:text-[56px] font-bold text-[#0c1e38] tracking-medium leading-[1.28]">
                            <?php echo e($isFr ? 'Test de français pour la naturalisation :' : 'French Citizenship Language Test:'); ?>

                            <br>
                            <span class="relative inline-block text-[#0055a4]">
                                <?php echo e($isFr ? 'Visez le niveau B2' : 'How to Reach B2'); ?>

                            </span>
                        </h1>

                        <div class="w-full max-w-xs sm:max-w-md h-1 flex mt-3">
                            <span class="w-1/3 h-full bg-[#0055A4]"></span>
                            <span class="w-1/3 h-full bg-slate-200"></span>
                            <span class="w-1/3 h-full bg-[#EF4135]"></span>
                        </div>
                    </div>

                    <p class="mt-6 text-sm sm:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto font-normal">
                        <?php echo e($isFr
    ? 'Depuis le 1er janvier 2026, la naturalisation exige le niveau B2 sur les 4 compétences. Nous vous préparons à l\'examen de votre choix, du diagnostic initial au jour de l\'épreuve.'
    : 'Since 1 January 2026, French citizenship requires level B2 in French across all 4 skills. We train you for the exact test you choose, from your first class to exam day.'); ?>

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
                            <i class="fas fa-certificate text-[#0055a4]"></i>
                            <span><?php echo e($isFr ? 'Niveau B2 obligatoire en 2026' : 'B2 required since 1 Jan 2026'); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-layer-group text-[#0055a4]"></i>
                            <span><?php echo e($isFr ? '4 épreuves obligatoires' : '4 compulsory skills'); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-award text-[#0055a4]"></i>
                            <span><?php echo e($isFr ? 'TCF IRN, TEF IRN ou DELF B2' : 'TCF IRN, TEF IRN or DELF B2'); ?></span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 2: WHAT LEVEL OF FRENCH DO YOU NEED? ============ -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                        <div class="lg:col-span-7">
                            <div class="inline-flex items-center gap-1.5 mb-3">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span
                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                    <?php echo e($isFr ? 'RÉFORME 2026' : '2026 LANGUAGE REFORM'); ?>

                                </span>
                            </div>
                            <h2
                                class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                <?php echo e($isFr ? 'Quel niveau de français pour la naturalisation en 2026 ?' : 'What level of French do you need for French citizenship?'); ?>

                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? "L'article 21-24 du Code civil exige désormais le niveau B2 du CECRL pour la nationalité française (décret applicable depuis le 1er janvier 2026). Il s'applique à la naturalisation par décret et par déclaration (mariage)."
    : "Article 21-24 of the French Civil Code now requires level B2 of the CEFR for French nationality (decree effective 1 January 2026). It applies to naturalisation by decree and marriage."); ?>

                            </p>
                            <div class="mt-6 flex items-center gap-2 text-xs sm:text-sm text-[#0055A4] font-semibold">
                                <svg class="w-4 h-4 text-[#0055A4]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span><?php echo e($isFr ? 'Conforme aux décrets officiels du ministère de l\'Intérieur.' : 'Verified against official 2026 Ministry of the Interior specifications.'); ?></span>
                            </div>
                        </div>

                        <div class="lg:col-span-5">
                            <div class="rounded-3xl bg-[#002654] text-white overflow-hidden relative">
                                <div class="h-[3px] w-full flex">
                                    <span class="w-1/3 h-full bg-[#0055A4]"></span>
                                    <span class="w-1/3 h-full bg-white"></span>
                                    <span class="w-1/3 h-full bg-[#EF4135]"></span>
                                </div>
                                <div class="grid grid-cols-2 divide-x divide-y divide-white/10 p-6 sm:p-8">
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">B2</div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            <?php echo e($isFr ? 'Niveau CECRL exigé' : 'Required CEFR level'); ?>

                                        </div>
                                    </div>
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">4 épreuves</div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            <?php echo e($isFr ? 'Oral et écrit indissociables' : 'All tested equally'); ?>

                                        </div>
                                    </div>
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">3 tests</div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            <?php echo e($isFr ? 'TCF, TEF ou DELF' : 'TCF, TEF or DELF'); ?>

                                        </div>
                                    </div>
                                    <div class="p-4 sm:p-5">
                                        <div class="text-2xl sm:text-3xl font-extrabold text-white">2026</div>
                                        <div class="text-xs text-slate-300 mt-1 font-normal">
                                            <?php echo e($isFr ? 'En vigueur depuis le 1er janv.' : 'In force since 1 Jan'); ?>

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
                                    <?php echo e($isFr ? 'Naturalisation par décret' : 'Naturalisation by decree'); ?>

                                </span>
                                <span class="md:col-span-2 font-bold text-[#0055a4]">B2</span>
                                <span class="md:col-span-6 text-slate-600 font-normal">
                                    <?php echo e($isFr ? 'Remplace le niveau B1 exigé jusqu’au 31 décembre 2025' : 'Replaces the previous B1 requirement'); ?>

                                </span>
                            </div>
                            <div class="py-4 grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-6 text-sm">
                                <span class="md:col-span-4 font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Nationalité par mariage (déclaration)' : 'Citizenship by marriage (declaration)'); ?>

                                </span>
                                <span class="md:col-span-2 font-bold text-[#0055a4]">B2</span>
                                <span class="md:col-span-6 text-slate-600 font-normal">
                                    <?php echo e($isFr ? 'Relevé de B1 à B2 depuis le 1er janvier 2026' : 'Raised from B1 to B2 in 2026'); ?>

                                </span>
                            </div>
                            <div class="py-4 grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-6 text-sm">
                                <span class="md:col-span-4 font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Carte de résident (10 ans)' : '10-year resident card'); ?>

                                </span>
                                <span class="md:col-span-2 font-bold text-[#0055a4]">B1</span>
                                <span class="md:col-span-6 text-slate-600 font-normal">
                                    <?php echo e($isFr ? 'Relevé de A2 à B1 depuis le 1er janvier 2026' : 'Raised from A2 to B1 in 2026'); ?>

                                </span>
                            </div>
                            <div class="py-4 last:pb-0 grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-6 text-sm">
                                <span class="md:col-span-4 font-bold text-[#10233e]">
                                    <?php echo e($isFr ? 'Carte de séjour pluriannuelle' : 'Multi-year residence permit'); ?>

                                </span>
                                <span class="md:col-span-2 font-bold text-[#0055a4]">A2</span>
                                <span class="md:col-span-6 text-slate-600 font-normal">
                                    <?php echo e($isFr ? 'Relevé de A1 à A2 depuis le 1er janvier 2026' : 'Raised from A1 to A2 in 2026'); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 3: B1 OR B2? WHAT CHANGED IN 2026 ============ -->
            <section class="py-16 sm:py-24 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                        <div class="lg:col-span-7">
                            <div class="inline-flex items-center gap-1.5 mb-3">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span
                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                    <?php echo e($isFr ? 'DIFFÉRENCE FONDAMENTALE' : 'KEY DIFFERENCE'); ?>

                                </span>
                            </div>
                            <h2
                                class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                <?php echo e($isFr ? 'B1 ou B2 : ce qui a changé en 2026' : 'B1 or B2? What changed in 2026'); ?>

                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? 'Le niveau B1 validait une autonomie pratique : comprendre l’essentiel, faire face aux situations courantes et donner un avis simple.'
    : 'Level B1 demonstrated basic independence: handling everyday transactions, explaining problems simply, and giving personal opinions.'); ?>

                            </p>
                            <p class="mt-3 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? 'Le niveau B2 exige une réelle argumentation : défendre un point de vue nuancé, comprendre des discours complexes et rédiger un texte structuré sans répétitions syntaxiques.'
    : 'Level B2 requires genuine argument, nuance, and structural control: you must be able to defend an opinion, synthesize complex viewpoints, and write clear, structured arguments.'); ?>

                            </p>
                        </div>

                        <div class="lg:col-span-5">
                            <div
                                class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 flex items-center justify-around gap-4">
                                <div class="text-center">
                                    <div class="text-3xl sm:text-4xl font-extrabold text-slate-300">B1</div>
                                    <div class="text-xs text-slate-400 mt-1">
                                        <?php echo e($isFr ? 'Jusqu\'en 2025' : 'Until Dec 2025'); ?>

                                    </div>
                                    <div class="text-[11px] text-slate-400 font-medium">
                                        <?php echo e($isFr ? 'Utilisateur seuil' : 'Threshold user'); ?>

                                    </div>
                                </div>
                                <div class="text-slate-300 text-2xl font-bold">→</div>
                                <div class="text-center p-4 rounded-2xl bg-blue-50/70 border border-blue-100">
                                    <div class="text-3xl sm:text-4xl font-extrabold text-[#0055a4]">B2</div>
                                    <div class="text-xs text-[#0055a4] font-bold mt-1">
                                        <?php echo e($isFr ? 'Depuis 2026' : 'Since Jan 2026'); ?>

                                    </div>
                                    <div class="text-[11px] text-slate-600 font-semibold">
                                        <?php echo e($isFr ? 'Obligatoire' : 'Mandatory'); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 text-center text-xs text-slate-500 flex items-center justify-center gap-2">
                        <i class="fas fa-circle-info text-[#0055a4]"></i>
                        <span>
                            <?php echo e($isFr
    ? 'Les dossiers déposés avant le 1er janvier 2026 sont instruits selon l’ancienne règle (B1). Tout nouveau dossier exige le B2.'
    : 'Applications filed before 1 January 2026 remain processed under the former B1 rule. All applications filed since must show B2.'); ?>

                        </span>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 4: WHICH TEST SHOULD YOU TAKE? ============ -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'COMPARATIF DES EXAMENS' : 'OFFICIAL EXAMS COMPARED'); ?>

                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Quel test choisir pour la naturalisation ?' : 'Which test should you take for French citizenship?'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                            <?php echo e($isFr
    ? 'Trois tests officiels sont acceptés par le ministère de l’Intérieur pour justifier du niveau B2. Voici leurs caractéristiques comparées :'
    : 'The Ministry of the Interior accepts 3 official standardized tests for French nationality. Here is how they compare:'); ?>

                        </p>
                    </div>

                    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="bg-[#0055a4] text-white">
                                        <th class="py-4 px-6 font-semibold"><?php echo e($isFr ? 'Critère' : 'Criteria'); ?></th>
                                        <th class="py-4 px-6 font-semibold">TCF IRN</th>
                                        <th class="py-4 px-6 font-semibold">TEF IRN</th>
                                        <th class="py-4 px-6 font-semibold">DELF B2</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <?php echo e($isFr ? 'Format' : 'Format'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            <?php echo e($isFr ? '1 h 35 · 4 épreuves' : '1 h 35 · 4 sections'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            <?php echo e($isFr ? '1 h 40 · 4 épreuves' : '1 h 40 · 4 sections'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            <?php echo e($isFr ? '2 h 30 écrit + 20 min oral' : '2 h 30 written + 20 min oral'); ?>

                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <?php echo e($isFr ? 'Évaluation' : 'Scoring scale'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            <?php echo e($isFr ? 'Échelle A1 à B2' : 'A1 to B2 continuous'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            <?php echo e($isFr ? 'Échelle A1 à B2' : 'A1 to B2 continuous'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            <?php echo e($isFr ? 'Admis / Non admis (50/100)' : 'Pass/Fail (50/100 min)'); ?>

                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <?php echo e($isFr ? 'Délais des résultats' : 'Results turnaround'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            <?php echo e($isFr ? '15 à 20 jours ouvrés' : '15 to 20 working days'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            <?php echo e($isFr ? '1 à 10 jours ouvrés' : '1 to 10 working days'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            <?php echo e($isFr ? '4 à 6 semaines' : '4 to 6 weeks'); ?>

                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <?php echo e($isFr ? 'Validité' : 'Validity'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-slate-600"><?php echo e($isFr ? '2 ans' : '2 years'); ?></td>
                                        <td class="py-4 px-6 text-slate-600"><?php echo e($isFr ? '2 ans' : '2 years'); ?></td>
                                        <td class="py-4 px-6 text-slate-600 font-bold text-[#0055a4]">
                                            <?php echo e($isFr ? 'À vie (Diplôme)' : 'Lifelong (Diploma)'); ?>

                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            <?php echo e($isFr ? 'Idéal pour' : 'Best for'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            <?php echo e($isFr ? 'Évaluation multi-niveaux' : 'Multi-level scoring'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            <?php echo e($isFr ? 'Délais rapides' : 'Fast turnaround'); ?>

                                        </td>
                                        <td class="py-4 px-6 text-slate-600">
                                            <?php echo e($isFr ? 'Certificat définitif' : 'Permanent diploma'); ?>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 5: THE CIVIC EXAM ============ -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="rounded-3xl bg-[#002654] text-white p-8 sm:p-12 relative overflow-hidden">
                        <div class="absolute inset-x-0 top-0 h-1 flex">
                            <span class="w-1/3 bg-[#0055A4]"></span>
                            <span class="w-1/3 bg-white"></span>
                            <span class="w-1/3 bg-[#EF4135]"></span>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                            <div class="lg:col-span-7">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-blue-200 mb-4">
                                    <?php echo e($isFr ? 'EXAMEN CIVIQUE' : 'THE CIVIC KNOWLEDGE REQUIREMENT'); ?>

                                </span>
                                <h3
                                    class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white tracking-tight leading-tight">
                                    <?php echo e($isFr ? 'La langue ne suffit plus : l\'examen civique' : 'Language isn\'t the only test: the civic exam'); ?>

                                </h3>
                                <p class="mt-4 text-sm sm:text-base text-slate-200 leading-relaxed font-normal">
                                    <?php echo e($isFr
    ? 'En plus de l\'attestation B2, la naturalisation par décret impose la réussite d\'un examen civique portant sur l\'histoire, les institutions et les valeurs de la République française.'
    : 'Along with the B2 language requirement, candidates for naturalisation by decree must pass an official civic exam testing knowledge of French history, institutions, and Republican values.'); ?>

                                </p>
                            </div>
                            <div class="lg:col-span-5">
                                <div class="bg-white/10 backdrop-blur rounded-2xl p-6 border border-white/10">
                                    <div class="flex items-center gap-2 text-sm font-bold text-white mb-2">
                                        <i class="fas fa-shield-halved text-blue-300"></i>
                                        <span><?php echo e($isFr ? 'Inclus dans votre préparation' : 'Included in your preparation'); ?></span>
                                    </div>
                                    <p class="text-xs text-slate-200 leading-relaxed font-normal">
                                        <?php echo e($isFr
    ? 'Nous vous entraînons à la fois sur les 4 épreuves du B2 et sur les questions types de l\'entretien d\'assimilation en préfecture.'
    : 'We train you for both the B2 language test and the prefecture assimilation interview questions.'); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 6: HOW WE GET YOU TO B2 ============ -->
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
                            <?php echo e($isFr ? 'Notre préparation au B2' : 'How we get you to B2'); ?>

                        </h2>
                        <p
                            class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto font-normal">
                            <?php echo e($isFr
    ? 'Un accompagnement ciblé dès votre premier cours jusqu’à l’examen. De vraies simulations. Pas de programme générique.'
    : 'Targeted 1:1 or small-group live coaching from your initial benchmark to exam day.'); ?>

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
    ? 'Votre première séance situe vos 4 compétences face aux exigences du B2.'
    : 'Your first session benchmarks all 4 skills against the official B2 descriptors.'); ?>

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
                                <?php echo e($isFr ? 'Un calendrier calé sur votre dossier' : 'The roadmap to your timeline'); ?>

                            </h3>
                            <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? 'Nous planifions les heures nécessaires pour atteindre le B2 avant votre dépôt en préfecture.'
    : 'We calculate the exact hours needed to reach B2 before your prefecture submission date.'); ?>

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
    ? 'En particulier ou mini-groupe sur Google Meet, avec notes et devoirs après chaque séance.'
    : '1:1 or in mini-groups on Google Meet, with structured notes and homework after every class.'); ?>

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
                                <?php echo e($isFr ? 'Examens blancs et corrections' : 'Mocks exams and scoring'); ?>

                            </h3>
                            <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed font-normal">
                                <?php echo e($isFr
    ? 'Simulations complètes en conditions réelles du test choisi (TCF, TEF ou DELF B2).'
    : 'Full simulations under real exam conditions for your chosen test (TCF, TEF or DELF B2).'); ?>

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

            <!-- ============ SECTION 7: FAQ ============ -->
            <?php echo $__env->make('partials.faq-section', [
                'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                'title' => $isFr ? 'Naturalisation<br>FAQ' : 'French citizenship<br>FAQ',
                'subtitle' => $isFr
                    ? 'Tout ce que vous devez savoir sur le niveau B2 exigé pour la nationalité française.'
                    : 'Answers to the most common questions about the B2 language requirement and French nationality.',
                'faqs' => $faqs
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- ============ SECTION 8: FINAL CTA & SOURCES ============ -->
            <section class="py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <?php echo $__env->make('partials.cta-banner', [
                        'eyebrow' => $isFr ? 'ATTEIGNEZ VOTRE OBJECTIF' : "LET'S GET YOU THERE",
                        'title' => $isFr ? "Votre niveau. Le niveau exigé.<br>Nous comblons l'écart." : 'Your level. The required level.<br>We close the gap.',
                        'description' => $isFr
                            ? 'Votre premier cours définit votre niveau dans chaque compétence et le plan pour atteindre le B2 exigé par votre dossier. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                            : 'Your first class sets your level in each section and the plan to reach B2 for your citizenship dossier. You choose the pace: 2 or 4 hours a week.',
                        'primaryBtnText' => $isFr ? "S'inscrire" : 'Enroll now',
                        'primaryBtnUrl' => '/register',
                        'secondaryBtnText' => $isFr ? 'Nous contacter' : 'Talk to us',
                        'secondaryBtnUrl' => '/contact',
                        'helperText' => $isFr
                            ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                            : 'Live classes on Google Meet · 1:1 or small group · Student portal included'
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="mt-8 text-center text-xs sm:text-sm text-slate-500 font-normal">
                        <span><?php echo e($isFr ? 'Vous venez à deux ? 50 € de réduction par mois, pour vous et votre proche.' : 'Coming with a friend? €50 off per month, for you and for them.'); ?></span>
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
                        <a href="https://www.service-public.gouv.fr/particuliers/vosdroits/F2213" target="_blank"
                            rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span>Service-public.fr — Naturalisation</span>
                            <span class="text-[10px]">↗</span>
                        </a>
                        <span class="text-slate-300">·</span>
                        <a href="https://www.france-education-international.fr/test/tcf-irn" target="_blank"
                            rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span>France Éducation international — TCF IRN</span>
                            <span class="text-[10px]">↗</span>
                        </a>
                        <span class="text-slate-300">·</span>
                        <a href="https://www.lefrancaisdesaffaires.fr/tests-diplomes/test-evaluation-francais-tef/tef-irn/"
                            target="_blank" rel="noopener noreferrer"
                            class="hover:text-slate-600 transition-colors inline-flex items-center gap-1">
                            <span>Le français des affaires — TEF IRN</span>
                            <span class="text-[10px]">↗</span>
                        </a>
                    </div>
                </div>
            </section>
        </div>

        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

</body>

</html><?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/citizenship-language-test.blade.php ENDPATH**/ ?>