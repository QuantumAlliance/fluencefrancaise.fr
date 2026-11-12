<!DOCTYPE html>
<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    $isFr = request()->is('fr*');

    $title = $isFr
        ? 'Naturalisation française 2026 : conditions et étapes'
        : 'French Citizenship 2026: Requirements, Routes and Steps';

    $metaDesc = $isFr
        ? 'Nationalité française 2026 : résidence et niveau B2, examen civique pour la naturalisation par décret, entretien. Naturalisation et mariage, étape par étape.'
        : 'French citizenship in 2026: residence and B2 French, the civic exam for naturalisation by decree, and the interview. Naturalisation and marriage, step by step.';

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/naturalisation-francaise/' : 'https://fluencefrancaise.fr/en/french-citizenship/';
    $frUrl = 'https://fluencefrancaise.fr/fr/naturalisation-francaise/';
    $enUrl = 'https://fluencefrancaise.fr/en/french-citizenship/';

    $homeUrl = $isFr ? '/fr' : '/en';
    $langUrl = $isFr ? '/fr/test-francais-naturalisation/' : '/en/french-citizenship-language-test/';
    $civicUrl = $isFr ? '/fr/examen-civique/' : '/en/french-civic-exam/';
    $tcfUrl = $isFr ? '/fr/tcf-irn/' : '/en/tcf-irn/';
    $tefUrl = $isFr ? '/fr/tef-irn/' : '/en/tef-irn/';
    $delfB2Url = $isFr ? '/fr/delf-b2/' : '/en/delf-b2/';
    $compareUrl = $isFr ? '/fr/tcf-tef-ou-delf/' : '/en/tcf-vs-tef-vs-delf/';
    $contactUrl = $isFr ? '/fr/contact/' : '/en/contact/';
    $referralUrl = $isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral';
    $spNaturalisation = 'https://www.service-public.fr/particuliers/vosdroits/F2213';
    $spMarriage = 'https://www.service-public.fr/particuliers/vosdroits/F2726';
    $spLevel = 'https://www.service-public.fr/particuliers/vosdroits/F34717';

    $faqs = $isFr ? [
        [
            'q' => 'Combien de temps faut-il vivre en France pour demander la nationalité ?',
            'a' => 'En général 5 ans pour la naturalisation. Cette durée passe à 2 ans si vous avez obtenu un diplôme d\'enseignement supérieur français après 2 ans d\'études, et certains profils sont dispensés de durée.',
        ],
        [
            'q' => 'Quel niveau de français pour la nationalité française ?',
            'a' => "Le B2 à l'oral et à l'écrit, depuis le 1er janvier 2026, pour la naturalisation comme pour la nationalité par mariage. Tous les détails sur notre page <a href=\"{$langUrl}\" class=\"font-semibold text-[#0055A4] hover:underline\">test de français pour la naturalisation</a>.",
        ],
        [
            'q' => 'Combien coûte une demande de naturalisation ?',
            'a' => 'Un timbre fiscal de 255 € (127,50 € en Guyane). Tout test de langue nécessaire est facturé séparément, tout comme l\'examen civique pour la naturalisation par décret.',
        ],
        [
            'q' => 'Peut-on garder sa nationalité d\'origine ?',
            'a' => 'La France autorise la double nationalité. Mais votre pays d\'origine peut avoir ses propres règles : renseignez-vous auprès de ses autorités.',
        ],
        [
            'q' => 'L\'examen civique est-il obligatoire pour la nationalité par mariage ?',
            'a' => 'Non. Il est exigé pour la naturalisation par décret. La déclaration par mariage passe par un entretien d\'assimilation.',
        ],
    ] : [
        [
            'q' => 'How long do you have to live in France to get citizenship?',
            'a' => 'Usually 5 years for naturalisation. That drops to 2 years if you earned a French higher-education diploma after 2 years of study, and some profiles have no minimum.',
        ],
        [
            'q' => 'What level of French do you need for French citizenship?',
            'a' => "B2, spoken and written, since 1 January 2026, for naturalisation and for citizenship through marriage. Full details on our <a href=\"{$langUrl}\" class=\"font-semibold text-[#0055A4] hover:underline\">French citizenship language test</a> page.",
        ],
        [
            'q' => 'How much does a citizenship application cost?',
            'a' => 'A €255 tax stamp (€127.50 in French Guiana). Any required language test is charged separately, as is the civic exam for naturalisation by decree.',
        ],
        [
            'q' => 'Can I keep my original nationality?',
            'a' => 'France allows dual nationality. But your country of origin may have its own rules, so check with its authorities.',
        ],
        [
            'q' => 'Is the civic exam required for citizenship by marriage?',
            'a' => 'No. It\'s required for naturalisation by decree. Citizenship by marriage goes through an assimilation interview.',
        ],
    ];

    $routes = $isFr ? [
        ['Naturalisation par décret', '5 ans de résidence (parfois moins)', 'B2 depuis 2026', 'Oui', true],
        ['Déclaration par mariage', '4 ans de mariage (5 ans dans certains cas)', 'B2 depuis 2026', 'Non, mais un entretien d\'assimilation', false],
    ] : [
        ['Naturalisation by decree', '5 years of residence (sometimes less)', 'B2 since 2026', 'Yes', true],
        ['Declaration by marriage', '4 years of marriage (5 in some cases)', 'B2 since 2026', 'No, but an assimilation interview', false],
    ];

    $requirements = $isFr ? [
        ['Âge', '18 ans (demande possible dès 17 ans)', null],
        ['Résidence', '5 ans en France. 2 ans si vous avez obtenu un diplôme d\'enseignement supérieur français après 2 ans d\'études. Aucune durée minimale dans certains cas, par exemple pour les réfugiés.', null],
        ['Séjour régulier', 'Un titre de séjour valide au moment de la demande (sauf ressortissants de l\'UE, de l\'EEE et suisses).', null],
        ['Langue', 'Niveau B2 à l\'oral et à l\'écrit.', $langUrl],
        ['Examen civique', 'Réussite obligatoire : 32 bonnes réponses sur 40.', $civicUrl],
        ['Valeurs', 'Signature de la charte des droits et devoirs du citoyen français.', null],
        ['Insertion professionnelle', 'Des revenus stables et suffisants pour votre foyer.', null],
        ['Conduite', 'Pas de condamnation qui fasse obstacle.', null],
        ['Coût', 'Timbre fiscal de 255 € (127,50 € en Guyane).', null],
    ] : [
        ['Age', '18 (you can apply from 17).', null],
        ['Residence', '5 years in France. 2 years if you earned a French higher-education diploma after 2 years of study. No minimum in some cases, such as for refugees.', null],
        ['Legal stay', 'A valid residence permit when you apply (except EU, EEA and Swiss nationals).', null],
        ['Language', 'B2, spoken and written.', $langUrl],
        ['Civic exam', 'Must pass: 32 correct answers out of 40.', $civicUrl],
        ['Values', 'Sign the charter of rights and duties of the French citizen.', null],
        ['Work', 'Stable income that\'s sufficient for your household.', null],
        ['Conduct', 'No conviction that bars you.', null],
        ['Cost', 'A €255 tax stamp (€127.50 in French Guiana).', null],
    ];

    $steps = $isFr ? [
        ['Vérifier votre éligibilité', 'Durée de résidence, titre de séjour, revenus : relisez les conditions ci-dessus sur service-public.fr.', 'sp'],
        ['Prouver votre niveau B2', 'Avec le TCF IRN, le TEF IRN ou le DELF B2. Le TCF et le TEF sont valables 2 ans ; le DELF, à vie.', 'tests'],
        ['Réussir l\'examen civique', '40 questions en français, 45 minutes, 32 bonnes réponses.', null],
        ['Constituer et déposer votre dossier', 'La demande peut se faire en ligne.', null],
        ['Passer l\'entretien', 'La préfecture vérifie votre assimilation, en français. Vous y signez la charte des droits et devoirs du citoyen français.', null],
        ['Attendre la décision', 'En cas d\'accord, le décret de naturalisation est publié au Journal officiel.', null],
        ['La cérémonie d\'accueil', 'Vous êtes invité à une cérémonie d\'accueil dans la citoyenneté française.', null],
    ] : [
        ['Check you\'re eligible', 'Residence, permit, income: go through the conditions above on service-public.fr.', 'sp'],
        ['Prove B2 French', 'With the TCF IRN, the TEF IRN or the DELF B2. TCF and TEF results last 2 years; the DELF lasts for life.', 'tests'],
        ['Pass the civic exam', '40 questions in French, 45 minutes, 32 correct.', null],
        ['Build and file your application', 'You can apply online.', null],
        ['Attend the interview', 'The préfecture checks your assimilation, in French. You sign the charter of rights and duties there.', null],
        ['Wait for the decision', 'If approved, your naturalisation decree is published in the Journal officiel.', null],
        ['The welcome ceremony', 'You\'re invited to a ceremony welcoming you into French citizenship.', null],
    ];

    $prep = $isFr ? [
        ['fa-language', 'Le niveau B2', 'Le test de langue, avec le TCF IRN, le TEF IRN ou le DELF B2.'],
        ['fa-landmark', 'L\'examen civique', '40 questions en français, à préparer en même temps que la langue.'],
        ['fa-comments', 'L\'entretien', 'L\'aisance à l\'oral pour répondre à la préfecture, en français.'],
        ['fa-route', 'Votre plan', 'Dès le premier cours, vos 4 compétences sont mesurées face au B2. Puis 2 ou 4 heures par semaine.'],
    ] : [
        ['fa-language', 'The B2 language test', 'TCF IRN, TEF IRN or DELF B2: the certificate your application needs.'],
        ['fa-landmark', 'The civic exam', '40 questions in French, prepared alongside the language.'],
        ['fa-comments', 'The interview', 'Confident speaking, in French, for the préfecture.'],
        ['fa-route', 'Your plan', 'In your first class, all 4 skills are measured against B2. Then 2 or 4 hours a week.'],
    ];

    $jumps = $isFr ? [
        ['#routes', 'Voies'],
        ['#requirements', 'Conditions'],
        ['#steps', '7 étapes'],
        ['#marriage', 'Mariage'],
        ['#prepare', 'Préparation'],
        ['#faq', 'FAQ'],
    ] : [
        ['#routes', 'Routes'],
        ['#requirements', 'Requirements'],
        ['#steps', '7 steps'],
        ['#marriage', 'Marriage'],
        ['#prepare', 'Preparation'],
        ['#faq', 'FAQ'],
    ];

    $crumb = $isFr ? 'Naturalisation française' : 'French citizenship';
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
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo e($title); ?> | <?php echo e($siteName); ?>">
    <meta property="og:description" content="<?php echo e($metaDesc); ?>">
    <meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
    <meta property="og:site_name" content="<?php echo e($siteName); ?>">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "Article",
                "headline": "<?php echo e($title); ?>",
                "description": "<?php echo e($metaDesc); ?>",
                "author": { "@type": "Organization", "name": "<?php echo e($siteName); ?>", "url": "https://fluencefrancaise.fr" },
                "mainEntityOfPage": "<?php echo e($canonicalUrl); ?>",
                "inLanguage": "<?php echo e($isFr ? 'fr' : 'en'); ?>"
            },
            {
                "@type": "HowTo",
                "name": "<?php echo e($isFr ? 'La naturalisation en 7 étapes' : 'French naturalisation in 7 steps'); ?>",
                "step": [
                    <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    {
                        "@type": "HowToStep",
                        "position": <?php echo e($index + 1); ?>,
                        "name": "<?php echo e($step[0]); ?>",
                        "text": "<?php echo e(trim(preg_replace('/\s+/', ' ', $step[1]))); ?>"
                    }<?php if(!$loop->last): ?>,<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ]
            },
            {
                "@type": "BreadcrumbList",
                "itemListElement": [
                    { "@type": "ListItem", "position": 1, "name": "<?php echo e($isFr ? 'Accueil' : 'Home'); ?>", "item": "<?php echo e($isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en'); ?>" },
                    { "@type": "ListItem", "position": 2, "name": "Guides", "item": "<?php echo e($isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en'); ?>" },
                    { "@type": "ListItem", "position": 3, "name": "<?php echo e($crumb); ?>", "item": "<?php echo e($canonicalUrl); ?>" }
                ]
            },
            {
                "@type": "FAQPage",
                "mainEntity": [
                    <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        {
                            "@type": "Question",
                            "name": "<?php echo e($faq['q']); ?>",
                            "acceptedAnswer": { "@type": "Answer", "text": "<?php echo e(trim(preg_replace('/\s+/', ' ', strip_tags($faq['a'])))); ?>" }
                        }<?php if(!$loop->last): ?>,<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ]
            }
        ]
    }
    </script>

    <?php echo $__env->make('partials.favicon', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.custom-scripts-head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body class="antialiased bg-white text-ink">
    <?php echo $__env->make('partials.custom-scripts-body', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="landing-page min-h-screen flex flex-col">
        <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <main class="flex-grow">
            <section class="relative overflow-hidden bg-white pt-28 sm:pt-36 md:pt-40 pb-12 border-b border-slate-100">
                <div class="hero-aurora"></div>
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="max-w-3xl mx-auto text-center">
                        <nav aria-label="Breadcrumb" class="mb-5 flex justify-center text-xs text-slate-400 font-medium items-center gap-2">
                            <a href="<?php echo e($homeUrl); ?>" class="hover:text-[#0055A4] transition-colors"><?php echo e($isFr ? 'Accueil' : 'Home'); ?></a>
                            <span class="text-slate-300">›</span>
                            <span>Guides</span>
                            <span class="text-slate-300">›</span>
                            <span class="text-slate-700 font-semibold"><?php echo e($crumb); ?></span>
                        </nav>

                        <div class="inline-flex items-center justify-center gap-1.5 mb-5">
                            <span class="inline-block w-3.5 h-[3px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[3px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                <?php echo e($isFr ? 'LE PARCOURS 2026' : 'THE 2026 PATH'); ?>

                            </span>
                        </div>

                        <h1 class="text-3xl sm:text-5xl lg:text-[48px] font-bold text-[#0c1e38] tracking-tight leading-[1.15]">
                            <?php echo e($isFr ? 'Naturalisation française :' : 'French Citizenship:'); ?>

                            <span class="block w-fit max-w-full mx-auto text-[#0055A4] mt-1">
                                <?php echo e($isFr ? 'les conditions, étape par étape' : 'The Requirements, Step by Step'); ?>

                                <span class="block h-[3px] mt-2 rounded-full" style="background: linear-gradient(90deg, #0055A4 0%, #0055A4 33.3%, #eaebf0 33.3%, #eaebf0 66.6%, #EF4135 66.6%, #EF4135 100%);"></span>
                            </span>
                        </h1>

                        <p class="mt-6 text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
                            <?php echo e($isFr
                                ? 'Résidence, langue, examen civique, entretien : voici tout ce que demande la nationalité française en 2026, dans l\'ordre où vous le ferez.'
                                : "Residence, language, the civic exam, the interview: everything French citizenship asks of you in 2026, in the order you'll do it."); ?>

                        </p>

                        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                            <a href="#steps" class="inline-flex items-center justify-center px-7 py-3.5 rounded-full text-sm sm:text-base font-semibold text-white bg-[#0055A4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all">
                                <?php echo e($isFr ? 'Voir les étapes' : 'Check the steps'); ?>

                                <i class="fas fa-arrow-down text-xs ml-2.5"></i>
                            </a>
                            <a href="<?php echo e($langUrl); ?>" class="inline-flex items-center justify-center px-7 py-3.5 rounded-full text-sm sm:text-base font-semibold text-[#0055A4] bg-white border border-[#0055A4]/30 hover:bg-blue-50 transition-all">
                                <?php echo e($isFr ? 'Voir le niveau de langue' : 'See the language level'); ?>

                            </a>
                        </div>

                        <p class="mt-5 text-xs font-semibold text-slate-400">
                            <span class="text-[#0055A4] text-[8px] align-middle mr-1">●</span>
                            <?php echo e($isFr ? 'Exigences 2026' : '2026 requirements'); ?>

                            <span class="text-slate-300 mx-1.5">/</span>
                            <?php echo e($isFr ? 'Mis à jour le 12 novembre 2026' : 'Updated 12 November 2026'); ?>

                        </p>
                    </div>

                    <div class="mt-8 flex flex-wrap items-center justify-center gap-2.5">
                        <?php $__currentLoopData = $jumps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jump): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($jump[0]); ?>" class="inline-flex items-center px-4 py-2 rounded-full border border-slate-200 bg-white text-xs sm:text-sm font-medium text-slate-600 hover:text-[#0055A4] hover:border-[#0055A4]/30 transition-colors">
                                <?php echo e($jump[1]); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <section id="routes" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <div class="lg:col-span-7">
                            <div class="inline-flex items-center gap-1.5 mb-3">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'DEUX VOIES' : 'TWO ROUTES'); ?></span>
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight leading-[1.15]">
                                <?php echo e($isFr ? 'Comment obtenir la nationalité française ?' : 'How do you get French citizenship?'); ?>

                            </h2>
                            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <?php echo e($isFr
                                    ? 'Un adulte étranger devient français de 2 façons principales. La naturalisation par décret demande en général 5 ans de résidence en France. La déclaration par mariage demande au moins 4 ans de mariage avec une personne française. Dans les 2 cas, il faut prouver votre niveau de français.'
                                    : 'An adult foreigner becomes French in 2 main ways. Naturalisation by decree usually requires 5 years of residence in France. Declaration by marriage requires at least 4 years of marriage to a French citizen. Either way, you have to prove your level of French.'); ?>

                            </p>
                        </div>
                        <div class="lg:col-span-5">
                            <div class="rounded-3xl bg-[#002654] text-white overflow-hidden">
                                <div class="h-[3px] w-full flex">
                                    <span class="w-1/3 bg-[#0055A4]"></span>
                                    <span class="w-1/3 bg-white"></span>
                                    <span class="w-1/3 bg-[#EF4135]"></span>
                                </div>
                                <div class="grid grid-cols-2 divide-x divide-y divide-white/10">
                                    <div class="p-6">
                                        <div class="text-3xl font-extrabold">5 <?php echo e($isFr ? 'ans' : 'years'); ?></div>
                                        <div class="text-xs text-blue-100/80 mt-1"><?php echo e($isFr ? 'Résidence habituelle' : 'Usual residence'); ?></div>
                                    </div>
                                    <div class="p-6">
                                        <div class="text-3xl font-extrabold">B2</div>
                                        <div class="text-xs text-blue-100/80 mt-1"><?php echo e($isFr ? 'Oral et écrit depuis 2026' : 'Spoken and written since 2026'); ?></div>
                                    </div>
                                    <div class="p-6">
                                        <div class="text-3xl font-extrabold">2026</div>
                                        <div class="text-xs text-blue-100/80 mt-1"><?php echo e($isFr ? 'Le niveau est passé de B1 à B2' : 'The level rose from B1 to B2'); ?></div>
                                    </div>
                                    <div class="p-6">
                                        <div class="text-3xl font-extrabold">7</div>
                                        <div class="text-xs text-blue-100/80 mt-1"><?php echo e($isFr ? 'Étapes, dans l\'ordre' : 'Steps, in order'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 rounded-2xl border border-slate-200/80 overflow-hidden bg-white">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[680px] text-sm text-left">
                                <thead>
                                    <tr class="bg-[#0055A4] text-white">
                                        <th class="px-5 py-3.5 font-semibold"><?php echo e($isFr ? 'Voie' : 'Route'); ?></th>
                                        <th class="px-5 py-3.5 font-semibold"><?php echo e($isFr ? 'Condition principale' : 'Main condition'); ?></th>
                                        <th class="px-5 py-3.5 font-semibold"><?php echo e($isFr ? 'Langue' : 'Language'); ?></th>
                                        <th class="px-5 py-3.5 font-semibold"><?php echo e($isFr ? 'Examen civique' : 'Civic exam'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $routes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="<?php echo e($index % 2 === 0 ? 'bg-white' : 'bg-[#f7f9fc]'); ?> border-t border-slate-100">
                                            <th class="px-5 py-4 font-semibold text-[#10233e]"><?php echo e($route[0]); ?></th>
                                            <td class="px-5 py-4 text-slate-600"><?php echo e($route[1]); ?></td>
                                            <td class="px-5 py-4 font-semibold text-[#0055A4]"><?php echo e($route[2]); ?></td>
                                            <td class="px-5 py-4 font-semibold <?php echo e($route[4] ? 'text-emerald-600' : 'text-slate-600'); ?>"><?php echo e($route[3]); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section id="requirements" class="scroll-mt-28 py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="max-w-3xl mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'CE QUI EST DEMANDÉ' : "WHAT'S ASKED"); ?></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            <?php echo e($isFr ? 'Les conditions de la naturalisation par décret' : 'Requirements for naturalisation by decree'); ?>

                        </h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php $__currentLoopData = $requirements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6">
                                <h3 class="font-bold text-[#10233e]">
                                    <?php if($item[2]): ?>
                                        <a href="<?php echo e($item[2]); ?>" class="text-[#0055A4] hover:underline"><?php echo e($item[0]); ?> →</a>
                                    <?php else: ?>
                                        <?php echo e($item[0]); ?>

                                    <?php endif; ?>
                                </h3>
                                <p class="mt-1.5 text-sm text-slate-500 leading-relaxed"><?php echo e($item[1]); ?></p>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <p class="mt-6 text-sm sm:text-base text-slate-500 leading-relaxed max-w-3xl">
                        <?php echo e($isFr
                            ? 'Certaines de ces conditions ne dépendent pas de vous aujourd\'hui. 2 d\'entre elles, si : la langue et l\'examen civique. Ce sont aussi les 2 qui se préparent.'
                            : "Some of these conditions aren't in your hands today. 2 of them are: the language and the civic exam. They're also the 2 you can prepare for."); ?>

                    </p>
                </div>
            </section>

            <section id="steps" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                    <div class="mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? "DANS L'ORDRE" : "IN THE ORDER YOU'LL DO IT"); ?></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            <?php echo e($isFr ? 'La naturalisation en 7 étapes' : 'French naturalisation in 7 steps'); ?>

                        </h2>
                    </div>
                    <ol class="relative space-y-8 before:absolute before:left-5 before:top-2 before:bottom-2 before:w-px before:bg-slate-200">
                        <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="relative pl-16">
                                <span class="absolute left-0 top-0 w-10 h-10 rounded-full bg-white border border-blue-100 text-[#0055A4] text-sm font-bold flex items-center justify-center"><?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                                <h3 class="font-bold text-[#10233e] text-lg"><?php echo e($step[0]); ?></h3>
                                <p class="mt-1.5 text-sm text-slate-500 leading-relaxed">
                                    <?php if($step[2] === 'sp'): ?>
                                        <?php if($isFr): ?>
                                            Durée de résidence, titre de séjour, revenus : relisez les conditions ci-dessus sur <a href="<?php echo e($spNaturalisation); ?>" target="_blank" rel="noopener" class="font-semibold text-[#0055A4] hover:underline">service-public.fr</a>.
                                        <?php else: ?>
                                            Residence, permit, income: go through the conditions above on <a href="<?php echo e($spNaturalisation); ?>" target="_blank" rel="noopener" class="font-semibold text-[#0055A4] hover:underline">service-public.fr</a>.
                                        <?php endif; ?>
                                    <?php elseif($step[2] === 'tests'): ?>
                                        <?php if($isFr): ?>
                                            Avec le <a href="<?php echo e($tcfUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">TCF IRN</a>, le <a href="<?php echo e($tefUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">TEF IRN</a> ou le <a href="<?php echo e($delfB2Url); ?>" class="font-semibold text-[#0055A4] hover:underline">DELF B2</a>. Le TCF et le TEF sont valables 2 ans ; le DELF, à vie.
                                        <?php else: ?>
                                            With the <a href="<?php echo e($tcfUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">TCF IRN</a>, the <a href="<?php echo e($tefUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">TEF IRN</a> or the <a href="<?php echo e($delfB2Url); ?>" class="font-semibold text-[#0055A4] hover:underline">DELF B2</a>. TCF and TEF results last 2 years; the DELF lasts for life.
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php echo e($step[1]); ?>

                                    <?php endif; ?>
                                </p>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ol>
                </div>
            </section>

            <section class="py-6 sm:py-10 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="rounded-3xl bg-[#002654] text-white px-6 sm:px-10 py-10 sm:py-12">
                        <div class="max-w-3xl">
                            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight">
                                <?php echo e($isFr ? 'Le français, au cœur de 3 étapes sur 7' : 'French sits at the heart of 3 of the 7 steps'); ?>

                            </h2>
                            <div class="mt-4 space-y-3 text-sm sm:text-base text-blue-100/90 leading-relaxed">
                                <p>
                                    <?php echo e($isFr
                                        ? 'Le test de langue, l\'examen civique et l\'entretien se déroulent tous en français. Un niveau B2 solide ne sert donc pas qu\'à cocher une case. Il vous aide à lire vite les 40 questions civiques, puis à répondre avec aisance pendant l\'entretien.'
                                        : "The language test, the civic exam and the interview all happen in French. So solid B2 French isn't just a box to tick. It helps you read the 40 civic questions fast, then answer comfortably at the interview."); ?>

                                </p>
                                <p>
                                    <?php echo e($isFr
                                        ? 'Depuis 2026, le niveau exigé est passé du B1 au B2. Si vous lisez encore « B1 » ailleurs, l\'information date d\'avant la réforme.'
                                        : 'Since 2026, the required level has risen from B1 to B2. If you still see “B1” elsewhere, that information predates the reform.'); ?>

                                </p>
                            </div>
                        </div>
                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <a href="<?php echo e($langUrl); ?>" class="rounded-2xl bg-white/10 hover:bg-white/15 px-5 py-4 transition-colors">
                                <p class="text-[11px] font-bold tracking-[0.16em] uppercase text-blue-200/80">01</p>
                                <p class="mt-1 font-semibold"><?php echo e($isFr ? 'Test de langue B2' : 'B2 language test'); ?></p>
                            </a>
                            <a href="<?php echo e($civicUrl); ?>" class="rounded-2xl bg-white/10 hover:bg-white/15 px-5 py-4 transition-colors">
                                <p class="text-[11px] font-bold tracking-[0.16em] uppercase text-blue-200/80">02</p>
                                <p class="mt-1 font-semibold"><?php echo e($isFr ? 'Examen civique' : 'Civic exam'); ?></p>
                            </a>
                            <div class="rounded-2xl bg-white/10 px-5 py-4">
                                <p class="text-[11px] font-bold tracking-[0.16em] uppercase text-blue-200/80">03</p>
                                <p class="mt-1 font-semibold"><?php echo e($isFr ? 'Entretien' : 'Interview'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="marriage" class="scroll-mt-28 py-16 sm:py-20 bg-[#f4f7fb]">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <div class="lg:col-span-4">
                            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 text-center">
                                <p class="text-7xl font-extrabold text-[#0055A4] leading-none">4</p>
                                <p class="mt-3 text-sm font-semibold text-[#10233e]"><?php echo e($isFr ? 'ans de mariage' : 'years of marriage'); ?></p>
                                <p class="mt-1 text-xs text-slate-500"><?php echo e($isFr ? '5 ans dans certains cas' : '5 in some cases'); ?></p>
                            </div>
                        </div>
                        <div class="lg:col-span-8">
                            <div class="inline-flex items-center gap-1.5 mb-3">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? "L'AUTRE VOIE" : 'THE OTHER ROUTE'); ?></span>
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                                <?php echo e($isFr ? 'Nationalité française par mariage' : 'French citizenship by marriage'); ?>

                            </h2>
                            <div class="mt-4 space-y-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                                <p>
                                    <?php echo e($isFr
                                        ? 'Vous pouvez demander la nationalité par déclaration après 4 ans de mariage avec une personne française, si vous avez résidé en France de façon régulière et ininterrompue pendant au moins 3 ans depuis le mariage, ou si votre conjoint français était inscrit au registre des Français établis hors de France pendant votre résidence à l\'étranger. Sinon, il faut en général 5 ans de mariage. La communauté de vie doit être continue depuis le mariage.'
                                        : "You can apply for citizenship by declaration after 4 years of marriage to a French citizen, if you've lived legally and continuously in France for at least 3 years since the marriage, or if your French spouse was registered with the French consulate while you lived abroad. Otherwise, it generally takes 5 years of marriage. You must have lived together continuously since the wedding."); ?>

                                </p>
                                <p>
                                    <?php echo e($isFr
                                        ? 'Depuis le 1er janvier 2026, vous devez aussi prouver le niveau B2 à l\'oral et à l\'écrit, par un diplôme ou une attestation de test reconnus. L\'examen civique n\'est pas exigé, mais un entretien vérifie votre assimilation.'
                                        : "Since 1 January 2026, you also need to prove B2 French, spoken and written, with an accepted diploma or test certificate. The civic exam isn't required, but an interview checks your assimilation."); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="prepare" class="scroll-mt-28 py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500"><?php echo e($isFr ? 'LES 3 ÉTAPES EN FRANÇAIS' : 'THE 3 FRENCH STEPS'); ?></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#10233e] tracking-tight">
                            <?php echo e($isFr ? 'Comment nous vous préparons' : 'How we prepare you'); ?>

                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed">
                            <?php if($isFr): ?>
                                Pour la naturalisation par décret, nous préparons les 3 étapes en français de votre dossier : le <a href="<?php echo e($compareUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">test de langue</a> au niveau B2, l'examen civique, et l'aisance à l'oral pour l'entretien. Dès le premier cours, votre tuteur mesure vos 4 compétences face au B2 et construit votre plan. Cours en direct, 2 ou 4 heures par semaine.
                            <?php else: ?>
                                For naturalisation by decree, we prepare the 3 French-language steps of your application: the <a href="<?php echo e($compareUrl); ?>" class="font-semibold text-[#0055A4] hover:underline">B2 language test</a>, the civic exam, and confident speaking for the interview. In your first class, your tutor measures your 4 skills against B2 and builds your plan. Live classes, 2 or 4 hours a week.
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <?php $__currentLoopData = $prep; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="rounded-2xl border border-slate-200/80 bg-white p-5">
                                <span class="w-10 h-10 rounded-full bg-blue-50 text-[#0055A4] flex items-center justify-center">
                                    <i class="fas <?php echo e($item[0]); ?> text-sm"></i>
                                </span>
                                <h3 class="mt-4 font-bold text-[#10233e]"><?php echo e($item[1]); ?></h3>
                                <p class="mt-1.5 text-sm text-slate-500 leading-relaxed"><?php echo e($item[2]); ?></p>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="mt-10 text-center">
                        <a href="/register" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055A4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all">
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
                'title' => $isFr ? 'Questions fréquentes<br>sur la nationalité' : 'French<br>citizenship FAQ',
                'description' => $isFr ? 'Des réponses claires avant de passer à l\'étape suivante.' : 'Clear answers before you take your next step.',
                'contactText' => $isFr ? 'Une autre question ?' : 'Have another question?',
                'contactUrl' => $contactUrl,
                'faqs' => $faqs,
                'sectionClass' => 'py-16 sm:py-20 bg-[#f4f7fb]',
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div id="enroll" class="scroll-mt-28">
                <?php echo $__env->make('partials.cta-banner', [
                    'sectionClass' => 'pt-4 pb-6 bg-white',
                    'eyebrow' => $isFr ? 'NOUS VOUS ACCOMPAGNONS' : "LET'S GET YOU THERE",
                    'title' => $isFr
                        ? "Votre niveau. Le niveau exigé.<br class='hidden sm:inline'> Nous comblons l'écart."
                        : "Your level. The required level.<br class='hidden sm:inline'> We close the gap.",
                    'description' => $isFr
                        ? 'Pour la naturalisation par décret, la langue et l\'examen civique sont 2 conditions que vous pouvez préparer dès aujourd\'hui. Commencez par votre premier cours.'
                        : 'For naturalisation by decree, language and the civic exam are 2 conditions you can start preparing today. Start with your first class.',
                    'primaryButtonText' => $isFr ? "S'inscrire" : 'Enroll now',
                    'primaryButtonUrl' => '/register',
                    'showSecondaryButton' => true,
                    'secondaryButtonText' => $isFr ? 'Contactez-nous' : 'Talk to us',
                    'secondaryButtonUrl' => $contactUrl,
                    'helperText' => $isFr
                        ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                        : 'Live classes on Google Meet · 1:1 or small group · Student portal included',
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <div class="bg-white pb-16">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl text-center">
                    <p class="text-sm text-slate-500">
                        <?php echo e($isFr ? 'Vous venez à deux ? 50 € de remise par mois, pour vous et pour votre ami.' : 'Coming with a friend? €50 off per month, for you and for them.'); ?>

                        <a href="<?php echo e($referralUrl); ?>" class="text-[#0055A4] font-semibold hover:underline ml-1"><?php echo e($isFr ? 'Contactez-nous' : 'Contact us'); ?> →</a>
                    </p>
                    <p class="mt-3 text-xs sm:text-sm text-slate-400 max-w-2xl mx-auto leading-relaxed">
                        <?php echo e($isFr
                            ? 'Cette page donne une information générale. Pour une situation particulière, consultez service-public.fr ou un professionnel du droit.'
                            : 'This page gives general information. For a specific situation, check service-public.fr or speak to a legal professional.'); ?>

                    </p>
                    <div class="my-6 border-t border-slate-100"></div>
                    <div class="flex flex-wrap items-center justify-center gap-y-2 text-xs sm:text-[13px] text-slate-400">
                        <span class="font-bold tracking-[0.18em] uppercase mr-3">SOURCES</span>
                        <a href="<?php echo e($spNaturalisation); ?>" target="_blank" rel="noopener" class="hover:text-slate-600">Service-public.fr – <?php echo e($isFr ? 'naturalisation' : 'naturalisation'); ?> ↗</a>
                        <span class="mx-2.5 text-slate-300">·</span>
                        <a href="<?php echo e($spMarriage); ?>" target="_blank" rel="noopener" class="hover:text-slate-600">Service-public.fr – <?php echo e($isFr ? 'nationalité par mariage' : 'citizenship by marriage'); ?> ↗</a>
                        <span class="mx-2.5 text-slate-300">·</span>
                        <a href="<?php echo e($spLevel); ?>" target="_blank" rel="noopener" class="hover:text-slate-600">Service-public.fr – <?php echo e($isFr ? 'niveau de français' : 'French level'); ?> ↗</a>
                    </div>
                </div>
            </div>
        </main>

        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</body>

</html>
<?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/french-citizenship.blade.php ENDPATH**/ ?>