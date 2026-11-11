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
    
    // FAQ Data
    $faqs = $isFr ? [
        [
            'q' => "Le TCF IRN est-il accepté pour la naturalisation ?",
            'a' => "Oui. L'attestation doit avoir moins de 2 ans et montrer un niveau global B2, selon les règles de notation du TCF IRN. Pour les dossiers déposés avant le 1er janvier 2026, l'ancien niveau B1 s'applique."
        ],
        [
            'q' => "Combien de temps l'attestation est-elle valable ?",
            'a' => "2 ans à compter de la délivrance de votre attestation. Si votre calendrier est incertain, regardez aussi le DELF B2 : c'est un diplôme, et il n'expire jamais."
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
            'a' => "2 years from the date your attestation is issued. If your timeline is uncertain, look at the DELF B2 too: it's a diploma, and it never expires."
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
                "text": "<?php echo e(addslashes($faq['a'])); ?>"
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
            <!-- ============ SECTION 1: HERO (HOMEPAGE AURORA STYLE) ============ -->
            <section class="relative overflow-hidden bg-white pt-28 sm:pt-36 md:pt-40 pb-16 md:pb-20 border-b border-slate-100">
                <div class="hero-aurora-strong"></div>

                <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="max-w-3xl mx-auto text-center reveal">
                        <!-- Centered Breadcrumb (Inside hero banner, no awkward top gap) -->
                        <div class="mb-5 flex justify-center">
                            <nav aria-label="Breadcrumb" class="text-xs text-slate-400 font-medium flex items-center gap-2">
                                <a href="<?php echo e($isFr ? '/fr' : '/en'); ?>" class="hover:text-primary-600 transition-colors"><?php echo e($isFr ? 'Accueil' : 'Home'); ?></a>
                                <span class="text-slate-300">›</span>
                                <a href="<?php echo e($isFr ? '/fr#exams' : '/en#exams'); ?>" class="hover:text-primary-600 transition-colors"><?php echo e($isFr ? 'Examens' : 'Exams'); ?></a>
                                <span class="text-slate-300">›</span>
                                <span class="text-slate-700 font-semibold">TCF IRN</span>
                            </nav>
                        </div>

                        <!-- Centered Eyebrow with Blue & Red Dashes (Image 1 Style) -->
                        <div class="inline-flex items-center justify-center gap-1.5 mb-5">
                            <span class="inline-block w-3.5 h-[3px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[3px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'VOTRE PROCHAIN CHAPITRE COMMENCE PAR LA BONNE PRÉPARATION' : 'YOUR NEXT CHAPTER STARTS WITH THE RIGHT PREPARATION'); ?>

                            </span>
                        </div>

                        <!-- H1 Title -->
                        <h1 class="text-3xl sm:text-5xl lg:text-[56px] font-extrabold text-[#002654] tracking-tight leading-[1.12]">
                            <?php if($isFr): ?>
                                TCF IRN : préparez exactement<br class="hidden sm:inline" />
                                <span class="text-[#0055a4]">le niveau exigé</span>
                            <?php else: ?>
                                TCF IRN: Prepare for the<br class="hidden sm:inline" />
                                <span class="text-[#0055a4]">Exact Level You Need</span>
                            <?php endif; ?>
                        </h1>

                        <!-- French Tricolor Underline Bar (Centered under title, Image 1 Style) -->
                        <div class="flex items-center justify-center my-5">
                            <div class="flex items-center gap-2 w-64 sm:w-80">
                                <span class="flex-1 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="flex-1 h-[2.5px] bg-[#EF4135] rounded-full"></span>
                            </div>
                        </div>

                        <!-- Subhead -->
                        <p class="mt-4 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed font-normal">
                            <?php echo e($isFr 
                                ? 'Le TCF IRN mesure chaque compétence séparément : nous travaillons celles qui vous séparent du niveau exigé par votre dossier.' 
                                : 'The TCF IRN scores each skill separately, so we train the skills standing between you and the level your application requires.'); ?>

                        </p>

                        <!-- CTA Button & Under-button text -->
                        <div class="mt-8 flex flex-col items-center gap-3">
                            <a href="/register" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055a4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5">
                                <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                                <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                            </a>
                            <p class="text-xs sm:text-[13px] text-slate-500 font-medium">
                                <?php echo e($isFr 
                                    ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' 
                                    : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                            </p>
                        </div>

                        <!-- 2026 requirements / Updated date tag (Image 1 Style) -->
                        <div class="mt-6 flex items-center justify-center gap-2 text-xs font-semibold text-slate-400">
                            <span class="text-slate-400 text-[9px]">●</span>
                            <span><?php echo e($isFr ? 'Exigences 2026' : '2026 requirements'); ?></span>
                            <span class="text-slate-300">/</span>
                            <span><?php echo e($isFr ? 'Mis à jour le 11 novembre 2026' : 'Updated 11 November 2026'); ?></span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 2: THE TCF IRN AT A GLANCE ============ -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <!-- Left Column: Title & Intro -->
                        <div class="lg:col-span-6 space-y-4">
                            <div class="inline-flex items-center gap-2 text-xs font-bold tracking-wider uppercase text-primary-600">
                                <span class="w-2 h-2 rounded-full bg-[#0055a4]"></span>
                                <?php echo e($isFr ? "Vue d'ensemble" : 'Exam Overview'); ?>

                            </div>
                            <h2 class="text-2xl sm:text-4xl font-extrabold text-[#002654] tracking-tight">
                                <?php echo e($isFr ? 'Le TCF IRN en bref' : 'The TCF IRN at a glance'); ?>

                            </h2>
                            <p class="text-base text-slate-600 leading-relaxed font-normal">
                                <?php echo e($isFr 
                                    ? 'Le TCF IRN (Test de connaissance du français – Intégration, Résidence et Nationalité) est accepté pour la carte de séjour pluriannuelle, la carte de résident et la naturalisation. Il dure 1 h 35 et compte 4 épreuves. Son attestation est valable 2 ans.'
                                    : 'The TCF IRN (Test de connaissance du français – Intégration, Résidence et Nationalité) is a French test accepted for a multi-year residence permit, a 10-year resident card and French citizenship. It takes 1 h 35 and has 4 sections. Your results are valid for 2 years.'); ?>

                            </p>
                            <div class="pt-2">
                                <a href="https://www.france-education-international.fr/article/tcf-integration-residence-et-nationalite" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm font-semibold text-[#0055a4] hover:text-[#002654] group transition-colors">
                                    <span><?php echo e($isFr ? 'Règlement officiel France Éducation international' : 'Official France Éducation international guidelines'); ?></span>
                                    <i class="fas fa-arrow-up-right-from-square text-xs group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Right Column: 2x2 Dark Navy Stats Card Grid -->
                        <div class="lg:col-span-6">
                            <div class="bg-[#002654] text-white rounded-3xl p-6 sm:p-8 shadow-xl shadow-navy-900/10 border border-navy-800">
                                <div class="grid grid-cols-2 gap-6">
                                    <!-- Stat 1 -->
                                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                                        <div class="text-3xl sm:text-4xl font-black text-white tracking-tight">4</div>
                                        <div class="mt-1 text-xs sm:text-sm font-medium text-blue-200">
                                            <?php echo e($isFr ? 'épreuves obligatoires' : 'compulsory sections'); ?>

                                        </div>
                                    </div>
                                    <!-- Stat 2 -->
                                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                                        <div class="text-3xl sm:text-4xl font-black text-white tracking-tight">1 h 35</div>
                                        <div class="mt-1 text-xs sm:text-sm font-medium text-blue-200">
                                            <?php echo e($isFr ? 'durée totale' : 'total duration'); ?>

                                        </div>
                                    </div>
                                    <!-- Stat 3 -->
                                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                                        <div class="text-3xl sm:text-4xl font-black text-white tracking-tight">A1–B2</div>
                                        <div class="mt-1 text-xs sm:text-sm font-medium text-blue-200">
                                            <?php echo e($isFr ? 'niveaux évalués' : 'levels evaluated'); ?>

                                        </div>
                                    </div>
                                    <!-- Stat 4 -->
                                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                                        <div class="text-3xl sm:text-4xl font-black text-white tracking-tight">2 <?php echo e($isFr ? 'ans' : 'years'); ?></div>
                                        <div class="mt-1 text-xs sm:text-sm font-medium text-blue-200">
                                            <?php echo e($isFr ? "validité attestation" : 'attestation validity'); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Clean Summary Table (no header row) -->
                    <div class="mt-12 bg-slate-50/70 border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
                        <table class="w-full text-left text-xs sm:text-sm">
                            <tbody class="divide-y divide-slate-200/80">
                                <tr>
                                    <td class="py-4 px-6 font-bold text-[#002654] bg-slate-100/60 w-1/4 sm:w-1/3">
                                        <?php echo e($isFr ? 'Pour qui' : "Who it's for"); ?>

                                    </td>
                                    <td class="py-4 px-6 text-slate-700 leading-relaxed">
                                        <?php echo e($isFr 
                                            ? "Demandeurs d'une carte pluriannuelle, d'une carte de résident ou de la nationalité française"
                                            : "Anyone applying for a carte de séjour pluriannuelle (multi-year permit), a carte de résident (10-year card) or French citizenship"); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-[#002654] bg-slate-100/60">
                                        <?php echo e($isFr ? 'Niveaux évalués' : 'Levels tested'); ?>

                                    </td>
                                    <td class="py-4 px-6 font-semibold text-[#0055a4]">
                                        A1 <?php echo e($isFr ? 'à' : 'to'); ?> B2
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-[#002654] bg-slate-100/60">
                                        <?php echo e($isFr ? 'Épreuves' : 'Sections'); ?>

                                    </td>
                                    <td class="py-4 px-6 text-slate-700">
                                        <?php echo e($isFr 
                                            ? 'Compréhension orale, compréhension écrite, expression écrite, expression orale'
                                            : 'Listening, reading, writing, speaking'); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-[#002654] bg-slate-100/60">
                                        <?php echo e($isFr ? 'Durée' : 'Duration'); ?>

                                    </td>
                                    <td class="py-4 px-6 text-slate-700 font-semibold">
                                        <?php echo e($isFr ? '1 h 35 au total' : '1 h 35 in total'); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 font-bold text-[#002654] bg-slate-100/60">
                                        <?php echo e($isFr ? 'Validité' : 'Validity'); ?>

                                    </td>
                                    <td class="py-4 px-6 text-slate-700">
                                        <?php echo e($isFr 
                                            ? "2 ans à compter de la délivrance de l'attestation"
                                            : '2 years from the date the attestation is issued'); ?>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 3: YOUR GAP IS NEVER THE SAME ============ -->
            <section class="py-16 sm:py-24 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <!-- Left Column: Strategy & Gap explanation -->
                        <div class="lg:col-span-6 space-y-5">
                            <div class="inline-flex items-center gap-2 text-xs font-bold tracking-wider uppercase text-primary-600">
                                <span class="w-2 h-2 rounded-full bg-[#0055a4]"></span>
                                <?php echo e($isFr ? 'Diagnostic personnalisé' : 'Diagnostic Strategy'); ?>

                            </div>
                            <h2 class="text-2xl sm:text-4xl font-extrabold text-[#002654] tracking-tight">
                                <?php echo e($isFr ? "Votre écart n'est jamais le même partout" : 'Your gap is never the same in every skill'); ?>

                            </h2>
                            <p class="text-base text-slate-600 leading-relaxed font-normal">
                                <?php echo e($isFr
                                    ? "Votre attestation donne un niveau pour chaque compétence. Pour la naturalisation, l'attestation doit indiquer un niveau global B2, calculé à partir des 4 compétences selon les règles de notation du TCF IRN."
                                    : "Your results show a level for each skill. For citizenship, the attestation must show an overall B2 level, calculated from all 4 skills under the TCF IRN scoring rules."); ?>

                            </p>
                            <p class="text-base text-slate-600 leading-relaxed font-normal">
                                <?php echo e($isFr
                                    ? "Or l'écart est rarement uniforme. On peut lire au niveau B2 et parler au niveau B1. On peut tout comprendre à l'oral et perdre ses moyens devant une page blanche."
                                    : "And the gap is rarely even. Plenty of people read at B2 and speak at B1. Others understand everything they hear, then freeze in front of a blank page."); ?>

                            </p>
                            <div class="bg-white rounded-2xl p-5 border-l-4 border-[#0055a4] border border-slate-200/80 shadow-sm text-sm text-[#002654] font-medium leading-relaxed">
                                <?php echo e($isFr
                                    ? "Repasser le test, c'est repayer l'inscription et attendre une nouvelle session. Notre préparation commence donc par mesurer votre niveau dans chacune des 4 épreuves. Les heures vont ensuite là où l'écart est le plus grand."
                                    : "A retake means paying the fee again and waiting for another session. So we start by measuring your level in each of the 4 sections. Then your hours go where the gap is widest."); ?>

                            </div>
                        </div>

                        <!-- Right Column: Diagnostic Score Card Graphic -->
                        <div class="lg:col-span-6">
                            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl shadow-slate-200/50">
                                <div class="flex items-center justify-between pb-5 border-b border-slate-100 mb-6">
                                    <div>
                                        <div class="text-[11px] font-bold uppercase tracking-wider text-primary-600">
                                            <?php echo e($isFr ? 'Évaluation diagnostique initiale' : 'Initial Diagnostic Evaluation'); ?>

                                        </div>
                                        <h4 class="text-base font-bold text-[#002654]">
                                            <?php echo e($isFr ? 'Profil type candidat' : 'Candidate Diagnostic Profile'); ?>

                                        </h4>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#0055a4] border border-blue-100">
                                        <?php echo e($isFr ? 'Objectif B2' : 'Target: B2'); ?>

                                    </span>
                                </div>

                                <!-- Skill Bars -->
                                <div class="space-y-4">
                                    <!-- Listening -->
                                    <div>
                                        <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                                            <span class="text-slate-700 flex items-center gap-2">
                                                <i class="fas fa-headphones text-primary-600 w-4"></i>
                                                <?php echo e($isFr ? 'Compréhension orale' : 'Listening'); ?>

                                            </span>
                                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">B2 (510/699)</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-emerald-500 h-2.5 rounded-full" style="width: 82%"></div>
                                        </div>
                                    </div>

                                    <!-- Reading -->
                                    <div>
                                        <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                                            <span class="text-slate-700 flex items-center gap-2">
                                                <i class="fas fa-book-open text-primary-600 w-4"></i>
                                                <?php echo e($isFr ? 'Compréhension écrite' : 'Reading'); ?>

                                            </span>
                                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">B2 (495/699)</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-emerald-500 h-2.5 rounded-full" style="width: 78%"></div>
                                        </div>
                                    </div>

                                    <!-- Writing (Gap) -->
                                    <div>
                                        <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                                            <span class="text-slate-700 flex items-center gap-2">
                                                <i class="fas fa-pen-nib text-amber-600 w-4"></i>
                                                <?php echo e($isFr ? 'Expression écrite' : 'Writing'); ?>

                                            </span>
                                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">B1 (360/699) · <?php echo e($isFr ? 'Écart' : 'Gap'); ?></span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-amber-500 h-2.5 rounded-full" style="width: 50%"></div>
                                        </div>
                                    </div>

                                    <!-- Speaking (Gap) -->
                                    <div>
                                        <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                                            <span class="text-slate-700 flex items-center gap-2">
                                                <i class="fas fa-comments text-amber-600 w-4"></i>
                                                <?php echo e($isFr ? 'Expression orale' : 'Speaking'); ?>

                                            </span>
                                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">B1 (380/699) · <?php echo e($isFr ? 'Écart' : 'Gap'); ?></span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-amber-500 h-2.5 rounded-full" style="width: 54%"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Diagnostic note -->
                                <div class="mt-6 p-4 rounded-xl bg-amber-50/80 border border-amber-200/80 text-xs text-amber-900 flex items-start gap-3">
                                    <i class="fas fa-bullseye text-amber-600 text-sm mt-0.5 shrink-0"></i>
                                    <div>
                                        <strong><?php echo e($isFr ? 'Plan de cours personnalisé :' : 'Targeted Study Plan:'); ?></strong>
                                        <?php echo e($isFr 
                                            ? "70% du temps concentré sur l'expression écrite et orale pour sécuriser le B2 global sans perdre de temps sur les acquis."
                                            : "70% of tutoring hours focused on Writing & Speaking to bridge the gap to B2, with zero wasted time on skills you've already mastered."); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 4: TCF IRN FORMAT (THE 4 SECTIONS) ============ -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <!-- Centered Heading -->
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-2 text-xs font-bold tracking-wider uppercase text-primary-600 mb-2">
                            <span class="w-2 h-2 rounded-full bg-[#0055a4]"></span>
                            <?php echo e($isFr ? 'Structure des épreuves' : 'Exam Structure'); ?>

                        </div>
                        <h2 class="text-2xl sm:text-4xl font-extrabold text-[#002654] tracking-tight">
                            <?php echo e($isFr ? 'Format du TCF IRN : les 4 épreuves' : 'TCF IRN format: the 4 sections'); ?>

                        </h2>
                        <p class="mt-4 text-base text-slate-600 leading-relaxed max-w-2xl mx-auto">
                            <?php echo e($isFr
                                ? "Le TCF IRN comprend 4 épreuves obligatoires, passées le même jour. Les 2 épreuves de compréhension sont des questionnaires à choix multiple. Les 2 épreuves d'expression sont évaluées par des correcteurs et des examinateurs. Ce format, en vigueur depuis le 12 mai 2025, évalue jusqu'au niveau B2."
                                : "The TCF IRN has 4 compulsory sections, all taken on the same day. Listening and reading are multiple choice. Writing and speaking are marked by examiners. This format has applied since 12 May 2025 and tests up to B2."); ?>

                        </p>
                    </div>

                    <!-- 4 Cards Grid (4 columns) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Card 1: Listening -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="w-11 h-11 rounded-2xl bg-blue-50 text-[#0055a4] grid place-content-center text-lg">
                                        <i class="fas fa-headphones"></i>
                                    </span>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-[#002654]">
                                        20 min
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-[#002654]">
                                    <?php echo e($isFr ? 'Compréhension orale' : 'Listening'); ?>

                                </h3>
                                <p class="mt-2 text-xs font-semibold text-[#0055a4]">
                                    <?php echo e($isFr ? '25 questions à choix multiple' : '25 multiple-choice questions'); ?>

                                </p>
                                <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                    <?php echo e($isFr 
                                        ? 'Extraits audio courts parlés à vitesse naturelle de locuteurs natifs.'
                                        : 'Short audio clips recorded at natural native French speaking speed.'); ?>

                                </p>
                            </div>
                        </div>

                        <!-- Card 2: Reading -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="w-11 h-11 rounded-2xl bg-blue-50 text-[#0055a4] grid place-content-center text-lg">
                                        <i class="fas fa-book-open"></i>
                                    </span>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-[#002654]">
                                        35 min
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-[#002654]">
                                    <?php echo e($isFr ? 'Compréhension écrite' : 'Reading'); ?>

                                </h3>
                                <p class="mt-2 text-xs font-semibold text-[#0055a4]">
                                    <?php echo e($isFr ? '25 questions à choix multiple' : '25 multiple-choice questions'); ?>

                                </p>
                                <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                    <?php echo e($isFr 
                                        ? '84 secondes par question en moyenne, temps de lecture compris.'
                                        : '84 seconds per question on average, text reading time included.'); ?>

                                </p>
                            </div>
                        </div>

                        <!-- Card 3: Writing -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="w-11 h-11 rounded-2xl bg-blue-50 text-[#0055a4] grid place-content-center text-lg">
                                        <i class="fas fa-pen-nib"></i>
                                    </span>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-[#002654]">
                                        30 min
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-[#002654]">
                                    <?php echo e($isFr ? 'Expression écrite' : 'Writing'); ?>

                                </h3>
                                <p class="mt-2 text-xs font-semibold text-[#0055a4]">
                                    <?php echo e($isFr ? '3 exercices' : '3 tasks'); ?>

                                </p>
                                <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                    <?php echo e($isFr 
                                        ? 'Message court, lettre personnelle et texte argumentatif nuancé.'
                                        : 'Short message, personal letter, and structured opinion essay.'); ?>

                                </p>
                            </div>
                        </div>

                        <!-- Card 4: Speaking -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="w-11 h-11 rounded-2xl bg-blue-50 text-[#0055a4] grid place-content-center text-lg">
                                        <i class="fas fa-comments"></i>
                                    </span>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-[#002654]">
                                        10 min
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-[#002654]">
                                    <?php echo e($isFr ? 'Expression orale' : 'Speaking'); ?>

                                </h3>
                                <p class="mt-2 text-xs font-semibold text-[#0055a4]">
                                    <?php echo e($isFr ? 'Entretien individuel' : '1-to-1 interview'); ?>

                                </p>
                                <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                    <?php echo e($isFr 
                                        ? 'Face-à-face avec un examinateur certifié en 3 parties progressives.'
                                        : 'Face-to-face with a certified examiner in 3 progressive parts.'); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Total Duration Bar -->
                    <div class="mt-8 max-w-xl mx-auto">
                        <div class="flex items-center justify-center gap-3 py-3.5 px-6 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-semibold text-[#002654] text-center shadow-sm">
                            <i class="fas fa-clock text-[#0055a4]"></i>
                            <span><?php echo e($isFr ? 'Total : 1 h 35 · Les 4 épreuves sont obligatoires et passées le même jour' : 'Total: 1 h 35 · All 4 compulsory sections are taken on the same day'); ?></span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 5: WHERE CANDIDATES LOSE POINTS ============ -->
            <section class="py-16 sm:py-24 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <!-- Centered Heading -->
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-2 text-xs font-bold tracking-wider uppercase text-primary-600 mb-2">
                            <span class="w-2 h-2 rounded-full bg-[#0055a4]"></span>
                            <?php echo e($isFr ? 'Pièges fréquents' : 'Common Pitfalls'); ?>

                        </div>
                        <h2 class="text-2xl sm:text-4xl font-extrabold text-[#002654] tracking-tight">
                            <?php echo e($isFr ? 'Où les candidats perdent des points au TCF IRN' : 'Where TCF IRN candidates lose points'); ?>

                        </h2>
                    </div>

                    <!-- 2x2 Traps Grid -->
                    <div class="grid sm:grid-cols-2 gap-6 lg:gap-8">
                        <!-- Trap 1 -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <div class="w-9 h-9 rounded-xl bg-red-50 text-[#ef4135] grid place-content-center text-sm font-bold">
                                    <i class="fas fa-stopwatch"></i>
                                </div>
                                <h3 class="text-lg sm:text-xl font-bold text-[#002654]">
                                    <?php echo e($isFr ? 'Le chrono, plus que la grammaire' : 'The clock, more than the grammar'); ?>

                                </h3>
                                <p class="text-sm text-slate-600 leading-relaxed font-normal">
                                    <?php echo e($isFr 
                                        ? "En compréhension écrite, vous avez 35 minutes pour 25 questions. Cela fait 84 secondes par question, lecture comprise. Bloquer 3 minutes sur une question, c'est utiliser le temps de 2."
                                        : "Reading gives you 35 minutes for 25 questions. That's 84 seconds a question, reading included. Spend 3 minutes stuck on 1 question, and you've used the time of 2."); ?>

                                </p>
                            </div>
                            <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200/70 text-xs sm:text-sm font-semibold text-[#003f7a] flex items-center gap-2.5">
                                <i class="fas fa-check-circle text-[#0055a4] shrink-0"></i>
                                <span><?php echo e($isFr ? 'Ce que nous faisons : des séries chronométrées, jusqu\'à ce que le rythme devienne automatique.' : 'What we do: timed drills until the pace is automatic.'); ?></span>
                            </div>
                        </div>

                        <!-- Trap 2 -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <div class="w-9 h-9 rounded-xl bg-red-50 text-[#ef4135] grid place-content-center text-sm font-bold">
                                    <i class="fas fa-volume-high"></i>
                                </div>
                                <h3 class="text-lg sm:text-xl font-bold text-[#002654]">
                                    <?php echo e($isFr ? "L'oreille habituée au français lent" : 'Ears trained on slow French'); ?>

                                </h3>
                                <p class="text-sm text-slate-600 leading-relaxed font-normal">
                                    <?php echo e($isFr 
                                        ? "Les documents audio du test sont courts et parlés à vitesse naturelle. Les méthodes d'apprentissage, elles, parlent souvent lentement. L'écart se découvre le jour J."
                                        : "The audio is short and spoken at natural speed. Most courses speak slowly. People find out the difference on exam day."); ?>

                                </p>
                            </div>
                            <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200/70 text-xs sm:text-sm font-semibold text-[#003f7a] flex items-center gap-2.5">
                                <i class="fas fa-check-circle text-[#0055a4] shrink-0"></i>
                                <span><?php echo e($isFr ? "Ce que nous faisons : des exercices d'écoute à vitesse native dans votre espace élève, dès la première semaine." : 'What we do: native-speed listening practice in your student portal from week 1.'); ?></span>
                            </div>
                        </div>

                        <!-- Trap 3 -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <div class="w-9 h-9 rounded-xl bg-red-50 text-[#ef4135] grid place-content-center text-sm font-bold">
                                    <i class="fas fa-pen-ruler"></i>
                                </div>
                                <h3 class="text-lg sm:text-xl font-bold text-[#002654]">
                                    <?php echo e($isFr ? '3 textes en 30 minutes' : '3 texts in 30 minutes'); ?>

                                </h3>
                                <p class="text-sm text-slate-600 leading-relaxed font-normal">
                                    <?php echo e($isFr 
                                        ? "L'expression écrite demande 3 productions en 30 minutes. Le piège classique : soigner la première et bâcler la dernière. Le correcteur, lui, lit les 3."
                                        : "The writing section asks for 3 pieces in 30 minutes. The classic trap: polishing the first and rushing the last. The examiner reads all 3."); ?>

                                </p>
                            </div>
                            <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200/70 text-xs sm:text-sm font-semibold text-[#003f7a] flex items-center gap-2.5">
                                <i class="fas fa-check-circle text-[#0055a4] shrink-0"></i>
                                <span><?php echo e($isFr ? 'Ce que nous faisons : un plan de temps par exercice et des structures de phrases à adapter, pas à réciter.' : 'What we do: a time plan for each task and sentence structures you adapt rather than memorise.'); ?></span>
                            </div>
                        </div>

                        <!-- Trap 4 -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <div class="w-9 h-9 rounded-xl bg-red-50 text-[#ef4135] grid place-content-center text-sm font-bold">
                                    <i class="fas fa-comment-dots"></i>
                                </div>
                                <h3 class="text-lg sm:text-xl font-bold text-[#002654]">
                                    <?php echo e($isFr ? 'Des réponses trop courtes à l\'oral' : 'Answers that are too short'); ?>

                                </h3>
                                <p class="text-sm text-slate-600 leading-relaxed font-normal">
                                    <?php echo e($isFr 
                                        ? "En 10 minutes, l'examinateur doit entendre ce qui fait un B2 : argumenter, nuancer, illustrer. Une réponse de 2 phrases ne lui en laisse pas l'occasion."
                                        : "In 10 minutes, the examiner needs to hear B2 skills: arguing, nuancing, giving examples. A 2-sentence answer gives them nothing to mark."); ?>

                                </p>
                            </div>
                            <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200/70 text-xs sm:text-sm font-semibold text-[#003f7a] flex items-center gap-2.5">
                                <i class="fas fa-check-circle text-[#0055a4] shrink-0"></i>
                                <span><?php echo e($isFr ? 'Ce que nous faisons : une méthode simple pour chaque réponse — une idée, un exemple, une nuance.' : 'What we do: a simple pattern for every answer — 1 idea, 1 example, 1 nuance.'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 6: HOW WE PREPARE YOU ============ -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <!-- Centered Heading -->
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-2 text-xs font-bold tracking-wider uppercase text-primary-600 mb-2">
                            <span class="w-2 h-2 rounded-full bg-[#0055a4]"></span>
                            <?php echo e($isFr ? 'Méthodologie' : 'Methodology'); ?>

                        </div>
                        <h2 class="text-2xl sm:text-4xl font-extrabold text-[#002654] tracking-tight">
                            <?php echo e($isFr ? 'Notre préparation au TCF IRN' : 'How we prepare you for the TCF IRN'); ?>

                        </h2>
                    </div>

                    <!-- 4 Numbered Step Cards Side-by-Side -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Step 1 -->
                        <div class="bg-slate-50/70 rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-start space-y-4">
                            <div class="w-10 h-10 rounded-full bg-[#0055a4] text-white font-extrabold text-sm grid place-content-center shadow-md shadow-blue-500/20">
                                1
                            </div>
                            <h3 class="text-base font-bold text-[#002654] leading-snug">
                                <?php echo e($isFr ? 'Votre niveau, épreuve par épreuve' : 'Your level, section by section'); ?>

                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                <?php echo e($isFr 
                                    ? 'Dès le premier cours, votre tuteur situe chaque compétence face au niveau exigé par votre démarche.'
                                    : 'In your first class, your tutor places each skill against the level your application needs.'); ?>

                            </p>
                        </div>

                        <!-- Step 2 -->
                        <div class="bg-slate-50/70 rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-start space-y-4">
                            <div class="w-10 h-10 rounded-full bg-[#0055a4] text-white font-extrabold text-sm grid place-content-center shadow-md shadow-blue-500/20">
                                2
                            </div>
                            <h3 class="text-base font-bold text-[#002654] leading-snug">
                                <?php echo e($isFr ? 'Un plan construit sur votre écart' : 'A plan built on your gap'); ?>

                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                <?php echo e($isFr 
                                    ? 'Les heures vont aux épreuves où vous êtes le plus loin du but. Pas de programme générique.'
                                    : "Your hours go to the sections where you're furthest from the target. No generic syllabus."); ?>

                            </p>
                        </div>

                        <!-- Step 3 -->
                        <div class="bg-slate-50/70 rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-start space-y-4">
                            <div class="w-10 h-10 rounded-full bg-[#0055a4] text-white font-extrabold text-sm grid place-content-center shadow-md shadow-blue-500/20">
                                3
                            </div>
                            <h3 class="text-base font-bold text-[#002654] leading-snug">
                                <?php echo e($isFr ? 'Des cours en direct, 2 ou 4 h par semaine' : 'Live classes, 2 or 4 hours a week'); ?>

                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                <?php echo e($isFr 
                                    ? 'En particulier ou en mini-groupe de 2 à 5 personnes, sur Google Meet. Vous recevez les notes après chaque séance.'
                                    : '1:1 or in a group of 2–5, on Google Meet. You get the class notes after every session.'); ?>

                            </p>
                        </div>

                        <!-- Step 4 -->
                        <div class="bg-slate-50/70 rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-start space-y-4">
                            <div class="w-10 h-10 rounded-full bg-[#0055a4] text-white font-extrabold text-sm grid place-content-center shadow-md shadow-blue-500/20">
                                4
                            </div>
                            <h3 class="text-base font-bold text-[#002654] leading-snug">
                                <?php echo e($isFr ? 'Des examens blancs au chrono' : 'Timed mock exams'); ?>

                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                <?php echo e($isFr 
                                    ? 'Votre espace élève contient des tests au format de l\'examen et des banques d\'exercices par compétence. Chaque devoir reçoit un retour.'
                                    : 'Your student portal has exam-format tests and practice banks for each skill. Every piece of homework gets feedback.'); ?>

                            </p>
                        </div>
                    </div>

                    <!-- Centered CTA Button -->
                    <div class="mt-12 flex flex-col items-center gap-3 text-center">
                        <a href="/register" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055a4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5">
                            <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                            <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                        </a>
                        <p class="text-xs sm:text-[13px] text-slate-500 font-medium">
                            <?php echo e($isFr 
                                ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' 
                                : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                        </p>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 7: WHAT TCF IRN LEVEL DO YOU NEED? ============ -->
            <section class="py-16 sm:py-24 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <!-- Centered Heading -->
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-2 text-xs font-bold tracking-wider uppercase text-primary-600 mb-2">
                            <span class="w-2 h-2 rounded-full bg-[#0055a4]"></span>
                            <?php echo e($isFr ? 'Niveaux requis 2026' : '2026 Legal Requirements'); ?>

                        </div>
                        <h2 class="text-2xl sm:text-4xl font-extrabold text-[#002654] tracking-tight">
                            <?php echo e($isFr ? 'Quel niveau viser au TCF IRN ?' : 'What TCF IRN level do you need?'); ?>

                        </h2>
                        <p class="mt-4 text-base text-slate-600 leading-relaxed max-w-2xl mx-auto">
                            <?php echo e($isFr 
                                ? "Tout dépend de votre démarche : A2 pour une première carte de séjour pluriannuelle, B1 pour la carte de résident, B2 pour la naturalisation. Ces niveaux s'appliquent depuis le 1er janvier 2026. Vérifiez celui de votre dossier avant de vous inscrire au test."
                                : 'It depends on your application: A2 for a first multi-year residence permit, B1 for the 10-year resident card, B2 for citizenship. These levels have applied since 1 January 2026. Check which one applies to you before you book the test.'); ?>

                        </p>
                    </div>

                    <!-- 3 Level Cards Side-by-Side (A2, B1, and Highlighted B2) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 items-stretch">
                        <!-- Card 1: A2 -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="text-4xl sm:text-5xl font-black text-[#0055a4] tracking-tight mb-3">
                                    A2
                                </div>
                                <h3 class="text-lg font-bold text-[#002654]">
                                    <?php echo e($isFr ? 'Carte de séjour pluriannuelle' : 'Multi-year residence permit'); ?>

                                </h3>
                                <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                                    <?php echo e($isFr 
                                        ? 'Pour le premier renouvellement pluriannuel de titre de séjour en France.'
                                        : 'Required for first multi-year renewal of French residence cards.'); ?>

                                </p>
                            </div>
                            <div class="pt-6 border-t border-slate-100 mt-6">
                                <a href="<?php echo e($isFr ? '/fr/titre-de-sejour-niveau-francais/' : '/en/french-residence-permit-language/'); ?>" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-[#0055a4] hover:text-[#002654] group">
                                    <span><?php echo e($isFr ? 'Niveau de français pour un titre de séjour' : 'French level for residence permits'); ?></span>
                                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Card 2: B1 -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between hover:border-slate-300 transition-all">
                            <div>
                                <div class="text-4xl sm:text-5xl font-black text-[#0055a4] tracking-tight mb-3">
                                    B1
                                </div>
                                <h3 class="text-lg font-bold text-[#002654]">
                                    <?php echo e($isFr ? 'Carte de résident (10 ans)' : '10-year resident card'); ?>

                                </h3>
                                <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                                    <?php echo e($isFr 
                                        ? "Exigé pour l'obtention de la carte de résident de longue durée en France."
                                        : 'Required for the long-term 10-year resident card in France.'); ?>

                                </p>
                            </div>
                            <div class="pt-6 border-t border-slate-100 mt-6">
                                <a href="<?php echo e($isFr ? '/fr/titre-de-sejour-niveau-francais/' : '/en/french-residence-permit-language/'); ?>" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-[#0055a4] hover:text-[#002654] group">
                                    <span><?php echo e($isFr ? 'Niveau de français pour un titre de séjour' : 'French level for residence permits'); ?></span>
                                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Card 3: B2 (DARK NAVY HIGHLIGHT CARD) -->
                        <div class="bg-[#002654] text-white rounded-3xl p-6 sm:p-8 shadow-xl shadow-navy-900/15 border border-navy-800 flex flex-col justify-between transform md:-translate-y-2">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="text-4xl sm:text-5xl font-black text-white tracking-tight">
                                        B2
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-[#0055a4] text-white border border-blue-400/30">
                                        <?php echo e($isFr ? 'Loi 2026' : '2026 Law'); ?>

                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-white">
                                    <?php echo e($isFr ? 'Naturalisation française' : 'French citizenship'); ?>

                                </h3>
                                <p class="mt-2 text-xs sm:text-sm text-blue-200/90 leading-relaxed">
                                    <?php echo e($isFr 
                                        ? 'Niveau B2 obligatoire pour les demandes de nationalité déposées depuis le 1er janvier 2026.'
                                        : 'B2 overall level mandatory for all citizenship files submitted since 1 January 2026.'); ?>

                                </p>
                            </div>
                            <div class="pt-6 border-t border-white/10 mt-6">
                                <a href="<?php echo e($isFr ? '/fr/test-francais-naturalisation/' : '/en/french-citizenship-language-test/'); ?>" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-white hover:text-blue-200 group">
                                    <span><?php echo e($isFr ? 'Test de français pour la naturalisation' : 'The French citizenship language test'); ?></span>
                                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Text with Links -->
                    <div class="mt-12 text-center text-xs sm:text-sm text-slate-600 max-w-3xl mx-auto leading-relaxed">
                        <?php echo e($isFr 
                            ? 'Depuis 2026, un examen civique est aussi exigé pour la naturalisation par décret et pour les titres de séjour concernés par les règles de 2026.'
                            : 'Citizenship by decree and the residence permit applications covered by the 2026 rules also need the French civic exam.'); ?>

                        <a href="<?php echo e($isFr ? '/fr/examen-civique/' : '/en/french-civic-exam/'); ?>" class="font-semibold text-[#0055a4] underline hover:text-[#002654]">
                            <?php echo e($isFr ? "En savoir plus sur l'examen civique" : 'French civic exam'); ?>

                        </a>.
                        <?php echo e($isFr ? 'Vous hésitez encore sur le test ?' : 'Not sure which test to take?'); ?>

                        <a href="<?php echo e($isFr ? '/fr/tcf-tef-ou-delf/' : '/en/tcf-vs-tef-vs-delf/'); ?>" class="font-semibold text-[#0055a4] underline hover:text-[#002654]">
                            <?php echo e($isFr ? 'Comparez le TCF, le TEF et le DELF' : 'Compare the TCF, TEF and DELF'); ?>

                        </a>.
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 8: FAQ (SPLIT 2-COLUMN) ============ -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100" id="faq">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12">
                        <!-- Left Column: FAQ Header -->
                        <div class="lg:col-span-4 space-y-4">
                            <div class="inline-flex items-center gap-2 text-xs font-bold tracking-wider uppercase text-primary-600">
                                <span class="w-2 h-2 rounded-full bg-[#0055a4]"></span>
                                FAQ
                            </div>
                            <h2 class="text-2xl sm:text-4xl font-extrabold text-[#002654] tracking-tight">
                                <?php echo e($isFr ? 'Questions fréquentes sur le TCF IRN' : 'TCF IRN FAQ'); ?>

                            </h2>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                <?php echo e($isFr 
                                    ? "Tout ce qu'il faut savoir sur l'examen, sa durée, sa validité et notre préparation personnalisée."
                                    : 'Everything you need to know about test format, rules, validity and our live online tutoring.'); ?>

                            </p>
                            <div class="pt-2">
                                <a href="<?php echo e($isFr ? '/fr/contact/' : '/en/contact/'); ?>" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-[#0055a4] hover:text-[#002654] group">
                                    <span><?php echo e($isFr ? 'Une question sur votre dossier ? Contactez-nous' : 'Have a specific question? Contact us'); ?></span>
                                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Right Column: Accordion List -->
                        <div class="lg:col-span-8 space-y-3">
                            <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-slate-200/90 rounded-2xl overflow-hidden bg-white hover:border-slate-300 transition-colors">
                                    <button 
                                        type="button"
                                        onclick="toggleFaq(<?php echo e($index); ?>)"
                                        class="w-full py-4 px-6 text-left flex items-center justify-between gap-4 font-bold text-sm sm:text-base text-[#002654] focus:outline-none"
                                        aria-expanded="false"
                                        id="faq-btn-<?php echo e($index); ?>"
                                    >
                                        <span><?php echo e($faq['q']); ?></span>
                                        <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500 transition-transform duration-200" id="faq-icon-<?php echo e($index); ?>">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </span>
                                    </button>
                                    <div 
                                        id="faq-content-<?php echo e($index); ?>" 
                                        class="hidden px-6 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3"
                                    >
                                        <?php echo e($faq['a']); ?>

                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ SECTION 9: FINAL CTA & SOURCES ============ -->
            <section class="py-16 sm:py-24 bg-[#f8fafc]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <!-- Large Blue Gradient CTA Card -->
                    <div class="bg-gradient-to-br from-[#0055a4] via-[#004382] to-[#002654] rounded-3xl p-8 sm:p-14 text-white shadow-2xl shadow-blue-900/20 text-center relative overflow-hidden">
                        <!-- Background glow effect -->
                        <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-blue-400/20 blur-3xl pointer-events-none"></div>
                        <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-red-400/10 blur-3xl pointer-events-none"></div>

                        <div class="relative z-10 max-w-2xl mx-auto">
                            <!-- Tricolor bar -->
                            <div class="inline-flex items-center justify-center mb-4">
                                <span class="tricolor" style="height:3.5px;width:44px;"><i></i><i></i><i></i></span>
                            </div>

                            <!-- H2 Title -->
                            <h2 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                                <?php echo e($isFr 
                                    ? "Votre niveau. Le niveau exigé. Nous comblons l'écart."
                                    : 'Your level. The required level. We close the gap.'); ?>

                            </h2>

                            <!-- Text -->
                            <p class="mt-4 text-sm sm:text-base text-blue-100/90 leading-relaxed font-normal">
                                <?php echo e($isFr 
                                    ? 'Votre premier cours fixe votre niveau dans chaque épreuve et le plan pour atteindre celui de votre dossier. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                                    : 'Your first class sets your level in each section and the plan to reach the one your application needs. You choose the pace: 2 or 4 hours a week.'); ?>

                            </p>

                            <!-- CTA Button -->
                            <div class="mt-8 flex flex-col items-center gap-3">
                                <a href="/register" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-[#002654] bg-white hover:bg-slate-100 shadow-xl transition-all transform hover:-translate-y-0.5">
                                    <?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?>

                                    <i class="fas fa-arrow-right text-xs ml-2.5 text-[#0055a4]"></i>
                                </a>
                                <p class="text-xs text-blue-200/80 font-medium">
                                    <?php echo e($isFr 
                                        ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus' 
                                        : 'Live classes on Google Meet · 1:1 or small group · Student portal included'); ?>

                                </p>
                            </div>

                            <!-- Referral Note -->
                            <div class="mt-8 pt-6 border-t border-white/15 text-xs text-blue-100">
                                <?php echo e($isFr ? 'Vous venez à deux ? 50 € de remise par mois, pour vous et pour votre ami.' : 'Coming with a friend? €50 off per month, for you and for them.'); ?>

                                <a href="<?php echo e($isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral'); ?>" class="underline font-semibold text-white hover:text-blue-200 ml-1">
                                    <?php echo e($isFr ? 'Contactez-nous' : 'Contact us'); ?>

                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Sources Line -->
                    <div class="mt-10 text-center text-xs text-slate-400 space-x-1">
                        <span><?php echo e($isFr ? 'Sources :' : 'Sources:'); ?></span>
                        <a href="https://www.france-education-international.fr/article/tcf-integration-residence-et-nationalite" target="_blank" rel="noopener" class="underline hover:text-slate-600">
                            France Éducation international – TCF IRN
                        </a>
                        <span>·</span>
                        <a href="https://www.service-public.fr/particuliers/vosdroits/F2213" target="_blank" rel="noopener" class="underline hover:text-slate-600">
                            Service-public.fr – <?php echo e($isFr ? 'naturalisation' : 'citizenship'); ?>

                        </a>
                        <span>·</span>
                        <a href="https://www.service-public.fr/particuliers/vosdroits/N110" target="_blank" rel="noopener" class="underline hover:text-slate-600">
                            Service-public.fr – <?php echo e($isFr ? 'cartes de séjour' : 'residence cards'); ?>

                        </a>
                    </div>
                </div>
            </section>
        </main>

        <!-- FOOTER: Standard Unified Component -->
        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <!-- Interactive FAQ Script -->
    <script>
        function toggleFaq(index) {
            const content = document.getElementById('faq-content-' + index);
            const icon = document.getElementById('faq-icon-' + index);
            const btn = document.getElementById('faq-btn-' + index);
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
                btn.setAttribute('aria-expanded', 'true');
            } else {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
                btn.setAttribute('aria-expanded', 'false');
            }
        }
    </script>
</body>
</html><?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/tcf-irn.blade.php ENDPATH**/ ?>