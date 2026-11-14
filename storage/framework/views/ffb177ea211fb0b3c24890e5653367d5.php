<?php
    $isFr = request()->is('fr*') || request()->is('/');
    $pageTitle = $isFr
        ? 'Fluence Française : préparation TCF, TEF, DELF et fide'
        : 'Fluence Française: TCF, TEF, DELF & fide Prep Online';
    $pageDesc = $isFr
        ? 'Cours de français en ligne, en direct avec un tuteur : TCF IRN, TEF IRN, DELF et test fide, examen civique inclus. France et Suisse, du A1 au B2.'
        : 'Live online French classes with a tutor: TCF IRN, TEF IRN, DELF and fide test prep, civic exam included. For France and Switzerland, from A1 to B2.';
    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/' : 'https://fluencefrancaise.fr/en/';
?>
<!DOCTYPE html>
<html lang="<?php echo e($isFr ? 'fr' : 'en'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta Tags -->
    <title><?php echo e($pageTitle); ?></title>
    <meta name="description" content="<?php echo e($pageDesc); ?>">
    <meta name="robots" content="<?php echo e($settings['robots'] ?? 'index, follow'); ?>">
    <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
    <link rel="alternate" hreflang="fr" href="https://fluencefrancaise.fr/">
    <link rel="alternate" hreflang="en" href="https://fluencefrancaise.fr/en/">
    <link rel="alternate" hreflang="x-default" href="https://fluencefrancaise.fr/en/">

    <!-- Social Media Meta Tags -->
    <?php echo $__env->make('partials.social-meta', [
        'ogType' => 'website',
        'ogUrl' => $canonicalUrl,
        'ogTitle' => $pageTitle,
        'ogDescription' => $pageDesc,
        'ogSiteName' => $settings['site_name'] ?? 'Fluence Française',
        'ogImage' => isset($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : null,
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Schema.org Structured Data (EducationalOrganization + FAQPage) -->
    <?php
        $footerSettings = json_decode($settings['footer_settings'] ?? '{}', true);
        $socialLinks = array_values(array_filter($footerSettings['social'] ?? []));

        $faqsData = $isFr ? [
            [
                'question' => 'Les cours sont-ils en ligne ?',
                'answer' => 'Oui. Tous les cours ont lieu en direct sur Google Meet, avec votre tuteur. Seul l\'examen se passe dans un centre agréé.'
            ],
            [
                'question' => 'Quels examens préparez-vous ?',
                'answer' => 'Le TCF IRN, le TEF IRN, le DELF (A1 à B2) et le test fide. La préparation à l\'examen civique est incluse dans toutes les formules, sans supplément. Vous hésitez ? Comparez le TCF, le TEF et le DELF : https://fluencefrancaise.fr/fr/tcf-tef-ou-delf/ .'
            ],
            [
                'question' => 'Préparez-vous aux démarches en Suisse ?',
                'answer' => 'Oui. Nous préparons le test fide et le DELF pour le permis C et la naturalisation suisse, avec des mises en situation et le vocabulaire de Suisse romande.'
            ],
            [
                'question' => 'Par quel niveau commencer ?',
                'answer' => 'Par votre premier cours : votre tuteur mesure vos 4 compétences face au niveau exigé par votre démarche. Si vous partez de zéro, vous démarrez directement le programme A1.'
            ],
            [
                'question' => 'Les élèves passent-ils des évaluations ?',
                'answer' => 'Oui. Des examens blancs réguliers, au format de votre examen, montrent vos progrès et vous disent quand vous inscrire au test.'
            ],
            [
                'question' => 'Fluence Française fait-elle passer les examens ?',
                'answer' => 'Non. Nous sommes une école de préparation indépendante. Vous vous inscrivez à l\'examen auprès d\'un centre agréé.'
            ]
        ] : [
            [
                'question' => 'Are the classes online?',
                'answer' => 'Yes. Every class runs live on Google Meet with your tutor. Only the exam itself is taken at an approved centre.'
            ],
            [
                'question' => 'Which exams do you prepare for?',
                'answer' => 'The TCF IRN, TEF IRN, DELF (A1 to B2) and fide test. Civic-exam prep is included in every plan at no extra cost. Not sure which one? Compare the TCF, TEF and DELF at https://fluencefrancaise.fr/en/tcf-vs-tef-vs-delf/ .'
            ],
            [
                'question' => 'Do you prepare for Swiss applications?',
                'answer' => 'Yes. We prepare the fide test and the DELF for the C permit and Swiss citizenship, with Swiss scenarios and the vocabulary of French-speaking Switzerland.'
            ],
            [
                'question' => 'Which level should I start from?',
                'answer' => 'Your first class decides: your tutor measures your 4 skills against the level your application requires. If you\'re starting from zero, you go straight into the A1 programme.'
            ],
            [
                'question' => 'Do students take assessments?',
                'answer' => 'Yes. Regular mock exams in your exam\'s format show your progress and tell you when to book the test.'
            ],
            [
                'question' => 'Does Fluence Française run the exams?',
                'answer' => 'No. We\'re an independent prep school. You register for the exam with an approved centre.'
            ]
        ];

        $faqSchema = [
            '@type' => 'FAQPage',
            'mainEntity' => array_map(function ($f) {
                return [
                    '@type' => 'Question',
                    'name' => $f['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $f['answer']
                    ]
                ];
            }, $faqsData)
        ];

        $orgSchema = [
            '@type' => 'EducationalOrganization',
            'name' => $settings['site_name'] ?? 'Fluence Française',
            'url' => $canonicalUrl,
            'description' => $pageDesc,
            'logo' => isset($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : null,
            'sameAs' => $socialLinks,
        ];

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@graph' => [
                $orgSchema,
                $faqSchema
            ]
        ];
    ?>
    <script type="application/ld+json"><?php echo json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>

    <?php echo $__env->make('partials.favicon', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Shared tricolor theme (fonts, tailwind config, components) -->
    <?php echo $__env->make('partials.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(isset($settings['custom_scripts'])): ?>
        <?php
            $scripts = json_decode($settings['custom_scripts'], true);
            $hasHeadScripts = false;
            if (is_array($scripts)) {
                foreach ($scripts as $script) {
                    $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                    $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                    if (!empty(trim($scriptCode)) && $placement === 'head') { $hasHeadScripts = true; break; }
                }
            }
        ?>
        <?php if($hasHeadScripts): ?>
            <?php
                foreach ($scripts as $script) {
                    $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                    $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                    if (!empty(trim($scriptCode)) && $placement === 'head') {
                        $trimmedCode = trim($scriptCode);
                        echo (stripos($trimmedCode, '<script') === 0) ? $scriptCode : '<script>' . "\n" . $scriptCode . "\n" . '</script>';
                    }
                }
            ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php
        $skills = $isFr ? [
            [
                'icon' => 'fa-comments',
                'title' => 'Parler',
                'fr' => 'Expression orale',
                'desc' => 'Argumenter, nuancer et tenir un entretien sans chercher vos mots.'
            ],
            [
                'icon' => 'fa-headphones',
                'title' => 'Écouter',
                'fr' => 'Compréhension orale',
                'desc' => 'Du français à vitesse native, comme dans les audios du test.'
            ],
            [
                'icon' => 'fa-book-open',
                'title' => 'Lire',
                'fr' => 'Compréhension écrite',
                'desc' => 'Lire vite et juste, au rythme de l\'examen et de l\'examen civique.'
            ],
            [
                'icon' => 'fa-pen-nib',
                'title' => 'Écrire',
                'fr' => 'Expression écrite',
                'desc' => 'Des textes structurés qui répondent à toute la consigne.'
            ],
            [
                'icon' => 'fa-award',
                'title' => 'Préparer l\'examen',
                'fr' => 'Préparation aux examens',
                'desc' => 'TCF IRN, TEF IRN, DELF, test fide et examen civique, au format réel.'
            ],
        ] : [
            [
                'icon' => 'fa-comments',
                'title' => 'Speaking',
                'fr' => 'Expression orale',
                'desc' => 'Argue, nuance and handle an interview without hunting for words.'
            ],
            [
                'icon' => 'fa-headphones',
                'title' => 'Listening',
                'fr' => 'Compréhension orale',
                'desc' => 'French at native speed, like the test audio.'
            ],
            [
                'icon' => 'fa-book-open',
                'title' => 'Reading',
                'fr' => 'Compréhension écrite',
                'desc' => 'Read fast and accurately, at the pace of the exam and the civic exam.'
            ],
            [
                'icon' => 'fa-pen-nib',
                'title' => 'Writing',
                'fr' => 'Expression écrite',
                'desc' => 'Structured texts that answer the whole task.'
            ],
            [
                'icon' => 'fa-award',
                'title' => 'Exam preparation',
                'fr' => 'Préparation aux examens',
                'desc' => 'TCF IRN, TEF IRN, DELF, fide test and civic exam, in the real format.'
            ],
        ];

        $taglines = $isFr ? [
            [
                'title' => 'Chaque écart est mesuré',
                'desc' => 'Dès le premier cours, vos 4 compétences sont situées face au niveau exigé.'
            ],
            [
                'title' => 'Chaque heure a une cible',
                'desc' => 'Les heures vont là où vous êtes le plus loin du but.'
            ],
            [
                'title' => 'Chaque progrès se prouve',
                'desc' => 'Des examens blancs réguliers, pour savoir quand vous êtes prêt.'
            ],
        ] : [
            [
                'title' => 'Every gap is measured',
                'desc' => 'In your first class, your 4 skills are placed against the required level.'
            ],
            [
                'title' => 'Every hour has a target',
                'desc' => 'Your hours go where you\'re furthest from the goal.'
            ],
            [
                'title' => 'Every step is proven',
                'desc' => 'Regular mock exams, so you know when you\'re ready.'
            ],
        ];

        $tiers = $isFr ? [
            [
                'name' => 'Particulier standard (4 h par semaine)',
                'symbol' => '€',
                'price' => '299',
                'symbol_after' => true,
                'cadence' => '/ mois',
                'badge' => 'Recommandé',
                'popular' => true,
                'blurb' => 'Des cours sur mesure, construits sur votre écart et sur la date de votre examen ou de votre dossier.',
                'features' => [
                    'Horaires flexibles',
                    '4 h par semaine : 1 h sur 4 jours ou 2 h sur 2 jours',
                    'Premier cours fixé 2 à 5 jours après votre inscription',
                    'Programme construit sur votre objectif : TCF IRN, TEF IRN, DELF ou test fide',
                    'Un tuteur pour vous seul',
                ],
                'icon' => 'fa-user',
                'batch_full' => false,
                'enroll_url' => '/register',
            ],
            [
                'name' => 'Mini-groupe (4 h par semaine)',
                'symbol' => '€',
                'price' => '249',
                'symbol_after' => true,
                'cadence' => '/ mois',
                'badge' => 'Complet',
                'popular' => false,
                'blurb' => '2 à 5 personnes du même niveau : l\'attention d\'un petit groupe, à un coût plus doux.',
                'features' => [
                    'Groupes de 2 à 5 personnes',
                    '4 h par semaine : 1 h sur 4 jours ou 2 h sur 2 jours',
                    'Groupes ouverts par niveau, du A1 au B2',
                    'Attention individuelle au sein du groupe',
                    'Apprentissage interactif',
                ],
                'icon' => 'fa-users',
                'batch_full' => true,
                'batch_full_note' => 'Ce groupe est complet.',
                'batch_full_link' => 'Être prévenu de la prochaine session',
                'batch_full_url' => '/fr/contact/',
            ],
            [
                'name' => 'Particulier 2 heures (2 h par semaine)',
                'symbol' => '€',
                'price' => '199',
                'symbol_after' => true,
                'cadence' => '/ mois',
                'badge' => null,
                'popular' => false,
                'blurb' => 'Pour un emploi du temps chargé : un rythme plus doux, sans arrêter d\'avancer.',
                'features' => [
                    'Horaires flexibles',
                    '2 h par semaine : 1 h sur 2 jours ou 2 h sur 1 jour',
                    'Adapté aux emplois du temps chargés',
                    'Rythme plus progressif',
                    'Attention personnalisée',
                ],
                'icon' => 'fa-clock',
                'batch_full' => false,
                'enroll_url' => '/register',
            ],
        ] : [
            [
                'name' => 'Standard one-on-one (4 hours a week)',
                'symbol' => '€',
                'price' => '299',
                'cadence' => '/ month',
                'badge' => 'Recommended',
                'popular' => true,
                'blurb' => 'Tailored classes, built around your gap and your exam or application date.',
                'features' => [
                    'Flexible scheduling',
                    '4 hours a week: 1 hour on 4 days or 2 hours on 2 days',
                    'First class set up 2–5 days after you enroll',
                    'A plan built on your goal: TCF IRN, TEF IRN, DELF or fide test',
                    'A tutor just for you',
                ],
                'icon' => 'fa-user',
                'batch_full' => false,
                'enroll_url' => '/register',
            ],
            [
                'name' => 'Small group (4 hours a week)',
                'symbol' => '€',
                'price' => '249',
                'cadence' => '/ month',
                'badge' => 'Batch full',
                'popular' => false,
                'blurb' => '2–5 people at the same level: small-group attention at a lower cost.',
                'features' => [
                    'Groups of 2–5 people',
                    '4 hours a week: 1 hour on 4 days or 2 hours on 2 days',
                    'Groups open by level, A1 to B2',
                    'Individual attention within the group',
                    'Interactive learning',
                ],
                'icon' => 'fa-users',
                'batch_full' => true,
                'batch_full_note' => 'This batch is full.',
                'batch_full_link' => 'Get notified about the next batch',
                'batch_full_url' => '/en/contact/',
            ],
            [
                'name' => '2-hour one-on-one (2 hours a week)',
                'symbol' => '€',
                'price' => '199',
                'cadence' => '/ month',
                'badge' => null,
                'popular' => false,
                'blurb' => 'For a busy schedule: a gentler pace, without losing momentum.',
                'features' => [
                    'Flexible scheduling',
                    '2 hours a week: 1 hour on 2 days or 2 hours on 1 day',
                    'Built for busy schedules',
                    'A more gradual pace',
                    'Personal attention',
                ],
                'icon' => 'fa-clock',
                'batch_full' => false,
                'enroll_url' => '/register',
            ],
        ];

        $resourceCards = $isFr ? [
            ['fa-book-open', 'Livres numériques et supports gratuits', 'Des PDF pour chaque niveau'],
            ['fa-pencil', 'Notes de cours', 'Un récapitulatif après chaque séance'],
            ['fa-headphones', 'Lecture et écoute', 'Textes et audios à vitesse native'],
            ['fa-layer-group', 'Banques d\'exercices', 'Des questions classées par compétence'],
            ['fa-clock', 'Examens blancs', 'Au format TCF, TEF, DELF et fide, au chrono'],
            ['fa-globe', 'Ressources A1 à B2', 'Classées par niveau du CECRL'],
            ['fa-desktop', 'Espace élève', 'Tous vos supports au même endroit'],
        ] : [
            ['fa-book-open', 'Free ebooks & study materials', 'PDFs for every level'],
            ['fa-pencil', 'Class notes', 'A recap after every session'],
            ['fa-headphones', 'Reading & listening', 'Texts and audio at native speed'],
            ['fa-layer-group', 'Practice banks', 'Questions sorted by skill'],
            ['fa-clock', 'Mock exams', 'TCF, TEF, DELF and fide format, timed'],
            ['fa-globe', 'A1–B2 resources', 'Sorted by CEFR level'],
            ['fa-desktop', 'Student portal', 'All your materials in one place'],
        ];
    ?>
</head>
<body class="antialiased bg-white">
    <?php if(isset($settings['custom_scripts'])): ?>
        <?php
            $scripts = json_decode($settings['custom_scripts'], true);
            $hasBodyScripts = false;
            if (is_array($scripts)) {
                foreach ($scripts as $script) {
                    $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                    $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                    if (!empty(trim($scriptCode)) && $placement === 'body') { $hasBodyScripts = true; break; }
                }
            }
        ?>
        <?php if($hasBodyScripts): ?>
            <?php
                foreach ($scripts as $script) {
                    $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                    $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                    if (!empty(trim($scriptCode)) && $placement === 'body') {
                        $trimmedCode = trim($scriptCode);
                        echo (stripos($trimmedCode, '<script') === 0) ? $scriptCode : '<script>' . "\n" . $scriptCode . "\n" . '</script>';
                    }
                }
            ?>
        <?php endif; ?>
    <?php endif; ?>

    <div class="landing-page">
        <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- hero -->
        <section id="home" class="relative overflow-hidden bg-white pt-32 md:pt-40 pb-16 md:pb-24">
            <div class="hero-aurora-strong"></div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="max-w-3xl mx-auto text-center">
                    <div class="reveal">
                        <div class="inline-flex flex-col items-center justify-center gap-1.5 mb-2">
                            <span class="text-xs sm:text-[13px] font-bold tracking-[0.22em] uppercase text-primary-600"><?php echo e($isFr ? 'Cours en direct' : 'LIVE ONLINE CLASSES'); ?></span>
                            <span class="tricolor my-1" style="height:3.5px;width:48px;"><i></i><i></i><i></i></span>
                            <span class="text-xs sm:text-sm font-semibold tracking-wide text-primary-700/85"><?php echo e($isFr ? 'TCF IRN · TEF IRN · DELF · Test fide · Examen civique' : 'TCF IRN · TEF IRN · DELF · fide test · Civic exam'); ?></span>
                        </div>
                        <h1 class="mt-5 text-[2.7rem] leading-[1.04] md:text-6xl <?php echo e($isFr ? 'lg:text-[4.25rem]' : 'lg:text-7xl'); ?> font-semibold text-ink tracking-tight">
                            <?php if($isFr): ?>
                                <span class="md:block">Des cours de français pour </span><span class="md:block">la vie que vous construisez </span><span class="md:block">en <span class="swish text-primary-600">France ou en Suisse</span>.</span>
                            <?php else: ?>
                                French classes for the life you're building in <span class="swish text-primary-600">France or Switzerland</span>.
                            <?php endif; ?>
                        </h1>
                        <p class="mt-6 text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
                            <?php echo e($isFr
                                ? 'Titre de séjour, naturalisation, permis C : chaque démarche exige un niveau précis. Nous mesurons le vôtre dès le premier cours, puis nous comblons l\'écart, en direct avec un tuteur.'
                                : 'Residence card, citizenship, C permit: every application requires a specific level. We measure yours in your first class, then close the gap, live with a tutor.'); ?>

                        </p>
                        <div class="mt-9 flex flex-wrap items-center justify-center gap-4">
                            <a href="/register" class="btn btn-primary btn-lg"><?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?> <i class="fas fa-arrow-right text-xs"></i></a>
                            <a href="<?php echo e($isFr ? '#tarifs' : '#pricing'); ?>" class="btn btn-ghost btn-lg"><?php echo e($isFr ? 'Voir les tarifs' : 'See pricing'); ?></a>
                        </div>
                        <div class="mt-10 flex flex-wrap justify-center gap-x-8 gap-y-3 text-sm text-slate-600">
                            <span class="inline-flex items-center gap-2"><i class="fas fa-user-group text-primary-600"></i> <?php echo e($isFr ? 'Particulier ou mini-groupe de 2 à 5' : '1:1 or groups of 2–5'); ?></span>
                            <span class="inline-flex items-center gap-2"><i class="fas fa-video text-primary-600"></i> <?php echo e($isFr ? 'En direct sur Google Meet' : 'Live on Google Meet'); ?></span>
                            <span class="inline-flex items-center gap-2"><i class="fas fa-route text-primary-600"></i> <?php echo e($isFr ? 'A1 → B2 + TCF, TEF, DELF, fide' : 'A1 → B2 + TCF, TEF, DELF, fide'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- what we do -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> <?php echo e($isFr ? 'Ce que nous faisons' : 'What we do'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">
                        <?php if($isFr): ?>
                            Du français de base au niveau exigé par <span class="swish text-primary-600">votre dossier</span>
                        <?php else: ?>
                            From basic French to the level your <span class="swish text-primary-600">application requires</span>
                        <?php endif; ?>
                    </h2>
                    <p class="mt-5 text-lg text-slate-600 leading-relaxed"><?php echo e($isFr ? 'Que vous partiez de zéro ou que vous soyez proche du B2, chaque cours sert un seul objectif : le niveau qu\'exige votre titre de séjour, votre naturalisation ou votre permis.' : 'Whether you\'re starting from zero or close to B2, every class serves one goal: the level your residence card, citizenship or permit requires.'); ?></p>
                </div>

                <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <?php $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-7 <?php echo e($i === 4 ? 'lg:col-span-1 sm:col-span-2' : ''); ?>" style="transition-delay:<?php echo e($i * 60); ?>ms">
                        <span class="w-12 h-12 rounded-2xl grid place-content-center <?php echo e($i === 4 ? 'bg-[#EF4135] text-white' : 'bg-primary-50 text-primary-600'); ?> text-lg"><i class="fas <?php echo e($s['icon']); ?>"></i></span>
                        <h3 class="mt-5 text-xl font-bold text-ink"><?php echo e($s['title']); ?></h3>
                        <?php if(!empty($s['fr'])): ?><p class="mt-1 text-sm font-semibold italic text-primary-600"><?php echo e($s['fr']); ?></p><?php endif; ?>
                        <p class="mt-2 text-slate-600 leading-relaxed"><?php echo e($s['desc']); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-12 grid sm:grid-cols-3 gap-6 reveal">
                    <?php $__currentLoopData = $taglines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                        <p class="text-ink font-bold text-base"><i class="fas fa-check-circle text-primary-600 mr-2"></i><?php echo e($t['title']); ?></p>
                        <p class="mt-2 text-sm text-slate-600 leading-relaxed"><?php echo e($t['desc']); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>

        <!-- programs & pricing -->
        <section id="<?php echo e($isFr ? 'tarifs' : 'pricing'); ?>" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> <?php echo e($isFr ? 'Formules et tarifs' : 'Programs & pricing'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink"><?php echo e($isFr ? 'Choisissez la formule qui suit votre emploi du temps' : 'Choose the format that fits your schedule'); ?></h2>
                </div>

                <!-- Mobile swipe hint -->
                <div class="lg:hidden flex justify-center mt-6 -mb-4">
                    <dotlottie-player
                        src="/images/Swiper-Gesture-Left.json"
                        background="transparent"
                        speed="1"
                        style="width: 110px; height: 110px;"
                        loop
                        autoplay>
                    </dotlottie-player>
                </div>

                <div class="mt-12 flex overflow-x-auto snap-x snap-mandatory gap-6 px-4 -mx-4 pb-4 sm:px-6 sm:-mx-6 lg:grid lg:grid-cols-3 lg:overflow-visible lg:mx-0 lg:px-0 lg:pb-0 items-stretch no-scrollbar">
                    <?php $__currentLoopData = $tiers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="reveal relative flex flex-col rounded-4xl p-8 w-[85%] sm:w-[60%] lg:w-auto lg:min-w-0 snap-center shrink-0 <?php echo e($t['popular'] ? 'bg-[#002654] text-white shadow-lift lg:-mt-4 lg:mb-4' : 'bg-white text-ink border border-slate-100 shadow-card'); ?>" style="transition-delay:<?php echo e($i*70); ?>ms">
                        <?php if(!empty($t['badge'])): ?>
                            <span class="absolute -top-3 left-8 bg-[#EF4135] text-white text-[11px] font-bold uppercase tracking-wider px-4 py-1.5 rounded-full shadow"><?php echo e($t['badge']); ?></span>
                        <?php endif; ?>
                        <div class="flex items-center gap-3">
                            <span class="w-11 h-11 rounded-2xl grid place-content-center <?php echo e($t['popular'] ? 'bg-white/15 text-white' : 'bg-primary-50 text-primary-600'); ?>"><i class="fas <?php echo e($t['icon']); ?>"></i></span>
                            <h3 class="text-xl font-bold <?php echo e($t['popular'] ? 'text-white' : 'text-ink'); ?>"><?php echo e($t['name']); ?></h3>
                        </div>
                        <div class="mt-6 flex items-end gap-1">
                            <span class="text-5xl font-semibold <?php echo e($t['popular'] ? 'text-white' : 'text-ink'); ?>" style="font-family:'Urbanist',sans-serif"><?php echo e(!empty($t['symbol_after']) ? $t['price'] . ' ' . $t['symbol'] : $t['symbol'] . $t['price']); ?></span>
                            <span class="<?php echo e($t['popular'] ? 'text-slate-300' : 'text-slate-400'); ?> font-medium mb-2"><?php echo e($t['cadence']); ?></span>
                        </div>
                        <p class="mt-4 text-sm leading-relaxed <?php echo e($t['popular'] ? 'text-slate-300' : 'text-slate-600'); ?>"><?php echo e($t['blurb']); ?></p>

                        <ul class="mt-6 space-y-3 text-sm flex-1">
                            <?php $__currentLoopData = $t['features']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-check mt-0.5 <?php echo e($t['popular'] ? 'text-[#EF4135]' : 'text-primary-600'); ?>"></i>
                                <span class="<?php echo e($t['popular'] ? 'text-slate-200' : 'text-slate-600'); ?>"><?php echo e($f); ?></span>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>

                        <?php if($t['batch_full']): ?>
                            <div class="mt-8 pt-4 flex flex-col gap-2">
                                <p class="text-sm text-slate-500 font-normal"><?php echo e($t['batch_full_note']); ?></p>
                                <a href="<?php echo e($t['batch_full_url']); ?>" class="flex items-center justify-between text-primary-600 hover:text-primary-700 font-semibold text-sm group transition-colors py-1">
                                    <span><?php echo e($t['batch_full_link']); ?></span>
                                    <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                                </a>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo e($t['enroll_url'] ?? '/register'); ?>" class="btn btn-lg mt-8 w-full <?php echo e($t['popular'] ? 'btn-onnavy' : 'btn-primary'); ?>"><?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?></a>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <p class="mt-8 text-center text-sm text-slate-600 max-w-2xl mx-auto"><?php echo e($isFr ? 'Toutes les formules incluent les cours en direct, un retour sur chaque devoir, l\'accès complet à l\'espace élève et la préparation à l\'examen civique.' : 'All plans include live teaching, feedback on every assignment, full student-portal access and civic-exam prep.'); ?></p>
            </div>
        </section>

        <!-- resource cards & everything you need between classes -->
        <section id="portal" class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div class="reveal order-2 lg:order-1">
                        <div class="grid grid-cols-2 gap-4">
                            <?php $__currentLoopData = $resourceCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($loop->last): ?>
                            <div class="card-glow group bg-white rounded-2xl border border-slate-100 p-5 hover:border-primary-200 col-span-2 flex items-center gap-4" style="transition-delay:<?php echo e($i * 55); ?>ms">
                                <span class="w-11 h-11 rounded-xl grid place-content-center text-lg transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-6 bg-primary-50 text-primary-600 shrink-0">
                                    <i class="fas <?php echo e($p[0]); ?>"></i>
                                </span>
                                <div>
                                    <p class="font-bold text-ink text-sm leading-snug"><?php echo e($p[1]); ?></p>
                                    <p class="mt-0.5 text-xs text-slate-500 leading-snug"><?php echo e($p[2]); ?></p>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="card-glow group bg-white rounded-2xl border border-slate-100 p-5 hover:border-primary-200" style="transition-delay:<?php echo e($i * 55); ?>ms">
                                <span class="w-11 h-11 rounded-xl grid place-content-center text-lg transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-6 <?php echo e($i % 2 ? 'bg-accent-soft text-[#EF4135]' : 'bg-primary-50 text-primary-600'); ?>">
                                    <i class="fas <?php echo e($p[0]); ?>"></i>
                                </span>
                                <p class="mt-3 font-bold text-ink text-sm leading-snug"><?php echo e($p[1]); ?></p>
                                <p class="mt-1 text-xs text-slate-500 leading-snug"><?php echo e($p[2]); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="reveal order-1 lg:order-2" style="transition-delay:.1s">
                        <?php if (! ($isFr)): ?>
                        <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> Student portal</span>
                        <?php endif; ?>
                        <h2 class="<?php echo e($isFr ? '' : 'mt-4'); ?> text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink"><?php echo e($isFr ? 'Tout ce qu\'il vous faut entre 2 cours' : 'Everything you need between classes'); ?></h2>
                        <div class="mt-5 rounded-2xl border-l-4 border-primary-600 bg-primary-50/70 p-5 shadow-sm">
                            <p class="text-lg text-slate-700 leading-relaxed"><?php echo e($isFr ? 'Votre apprentissage continue après le cours. Chaque élève a accès à toute la bibliothèque de ressources : notes de cours, exercices à vitesse native, banques d\'exercices et examens blancs.' : 'Your learning keeps going after class. Every student gets the full resource library: class notes, native-speed practice, practice banks and mock exams.'); ?></p>
                        </div>
                        <div class="mt-6 rounded-2xl bg-white border border-primary-100 p-5 flex gap-4">
                            <span class="w-10 h-10 rounded-xl bg-accent-soft text-[#EF4135] grid place-content-center shrink-0"><i class="fas fa-star"></i></span>
                            <p class="text-sm text-slate-600">
                                <?php if($isFr): ?>
                                    Après 6 mois d'inscription, vous recevez un accès gratuit à <strong class="text-ink">PrepMyFuture</strong> pour vous entraîner encore davantage.
                                <?php else: ?>
                                    After 6 months of enrolment, you get free access to <strong class="text-ink">PrepMyFuture</strong> for even more practice.
                                <?php endif; ?>
                            </p>
                        </div>
                        <a href="/register" class="btn btn-primary btn-lg mt-7"><?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?> <i class="fas fa-arrow-right text-xs"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- referral offer -->
        <section id="referral" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal relative overflow-hidden rounded-5xl bg-[#002654] text-white p-8 md:p-14">
                    <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                    <div class="absolute -bottom-24 -right-16 w-80 h-80 rounded-full bg-[#EF4135]/20 blur-3xl"></div>
                    <div class="relative grid md:grid-cols-2 gap-10 items-center">
                        <div>
                            <span class="eyebrow text-[#8eb8e1]"><span class="tricolor"><i></i><i></i><i></i></span> <?php echo e($isFr ? 'Parrainage' : 'Referral offer'); ?></span>
                            <h2 class="mt-4 text-3xl md:text-5xl font-semibold leading-tight">
                                <?php if($isFr): ?>
                                    50 € de remise chaque mois, pour vous <span class="text-[#EF4135]">et</span> votre ami
                                <?php else: ?>
                                    €50 off every month — for you <span class="text-[#EF4135]">and</span> your friend
                                <?php endif; ?>
                            </h2>
                            <p class="mt-5 text-lg text-slate-300 leading-relaxed max-w-lg"><?php echo e($isFr ? 'Parrainez un ami : vous bénéficiez tous les 2 de 50 € de remise, chaque mois où vous êtes inscrits.' : 'Refer a friend and you both get €50 off, every month you\'re enrolled.'); ?></p>
                            <a href="<?php echo e($isFr ? '/fr/contact/#parrainage' : '/en/contact/#referral'); ?>" class="btn btn-onnavy btn-lg mt-8"><?php echo e($isFr ? 'Contactez-nous pour parrainer' : 'Contact us to refer'); ?></a>
                        </div>
                        <div class="md:justify-self-end w-full max-w-sm bg-white/5 backdrop-blur-sm border border-white/10 rounded-4xl p-7">
                            <p class="text-7xl font-semibold text-white" style="font-family:'Urbanist',sans-serif"><?php echo e($isFr ? '50 €' : '€50'); ?><span class="text-2xl text-slate-300 align-top"><?php echo e($isFr ? '/mois' : '/mo'); ?></span></p>
                            <p class="mt-3 text-slate-300"><?php echo e($isFr ? 'La remise s\'applique aux 2 personnes, chaque mois d\'inscription.' : 'The discount applies to both of you, every month of enrolment.'); ?></p>
                            <p class="mt-6 text-xs text-slate-400 border-t border-white/10 pt-4"><?php echo e($isFr ? 'Offre valable une fois par compte élève, non cumulable avec d\'autres offres.' : 'Valid once per student account, not combinable with other offers.'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- who this is for -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> <?php echo e($isFr ? 'Pour qui' : 'Who this is for'); ?></span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink"><?php echo e($isFr ? 'Fluence Française est faite pour vous si…' : 'Fluence Française is for you if…'); ?></h2>
                </div>
                <div class="mt-12 grid md:grid-cols-2 gap-4 max-w-4xl mx-auto">
                    <?php
                        $link = 'class="text-primary-600 underline font-semibold hover:text-primary-700"';
                        $who = $isFr ? [
                            'Vous demandez une <a href="/fr/titre-de-sejour-niveau-francais/" ' . $link . '>carte de séjour pluriannuelle ou une carte de résident</a> et devez prouver l\'A2 ou le B1.',
                            'Vous visez la <a href="/fr/test-francais-naturalisation/" ' . $link . '>nationalité française</a> : le B2 à l\'oral et à l\'écrit et, pour la naturalisation par décret, l\'<a href="/fr/examen-civique/" ' . $link . '>examen civique</a>.',
                            'Vous vivez en Suisse romande et préparez votre <a href="/fr/permis-c-suisse/" ' . $link . '>permis C</a> ou votre <a href="/fr/naturalisation-suisse/" ' . $link . '>naturalisation suisse</a>.',
                            'Vous préparez le <a href="/fr/tcf-irn/" ' . $link . '>TCF IRN</a>, le <a href="/fr/tef-irn/" ' . $link . '>TEF IRN</a>, le <a href="/fr/delf/" ' . $link . '>DELF</a> ou le <a href="/fr/test-fide/" ' . $link . '>test fide</a>.',
                            'Vous en avez assez des applis, des tuteurs irréguliers et des cours sans fil conducteur.',
                            'Vous voulez des cours en direct, un vrai suivi, et quelqu\'un qui vous dit où vous en êtes.',
                        ] : [
                            "You're applying for a <a href=\"/en/french-residence-permit-language/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">multi-year residence permit or a 10-year resident card</a> and need to prove A2 or B1.",
                            "You're going for <a href=\"/en/french-citizenship-language-test/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">French citizenship</a> : B2 spoken and written and, for citizenship by decree, the <a href=\"/en/french-civic-exam/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">civic exam</a>.",
                            "You live in French-speaking Switzerland and are preparing your <a href=\"/en/c-permit-switzerland/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">C permit</a> or <a href=\"/en/swiss-citizenship/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">Swiss citizenship</a>.",
                            "You're preparing for the <a href=\"/en/tcf-irn/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">TCF IRN</a>, <a href=\"/en/tef-irn/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">TEF IRN</a>, <a href=\"/en/delf/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">DELF</a> or <a href=\"/en/fide-test/\" class=\"text-primary-600 underline font-semibold hover:text-primary-700\">fide test</a>.",
                            "You're tired of apps, irregular tutors and lessons with no clear thread.",
                            "You want live classes, real follow-up, and someone who tells you where you stand.",
                        ];
                    ?>
                    <?php $__currentLoopData = $who; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="reveal card-glow flex items-start gap-4 bg-white rounded-2xl border border-slate-100 p-5" style="transition-delay:<?php echo e($i*50); ?>ms">
                        <span class="w-8 h-8 rounded-full bg-primary-600 text-white grid place-content-center text-sm font-bold shrink-0"><?php echo e($i+1); ?></span>
                        <p class="text-ink font-medium leading-relaxed"><?php echo $w; ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="mt-10 text-center reveal">
                    <a href="/register" class="btn btn-primary btn-lg"><?php echo e($isFr ? "S'inscrire" : 'Enroll now'); ?> <i class="fas fa-arrow-right text-xs"></i></a>
                </div>
            </div>
        </section>

        <!-- faq -->
        <section id="faq" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                <div class="text-center mb-12 reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> FAQ</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink"><?php echo e($isFr ? 'Vos questions, nos réponses' : 'Questions, answered'); ?></h2>
                </div>
                <?php
                    $faqItems = $isFr ? [
                        [
                            'Les cours sont-ils en ligne ?',
                            'Oui. Tous les cours ont lieu en direct sur Google Meet, avec votre tuteur. Seul l\'examen se passe dans un centre agréé.'
                        ],
                        [
                            'Quels examens préparez-vous ?',
                            'Le TCF IRN, le TEF IRN, le DELF (A1 à B2) et le test fide. La préparation à l\'examen civique est incluse dans toutes les formules, sans supplément. Vous hésitez ? <a href="/fr/tcf-tef-ou-delf/" class="text-primary-600 underline font-semibold hover:text-primary-700">Comparez le TCF, le TEF et le DELF</a>.'
                        ],
                        [
                            'Préparez-vous aux démarches en Suisse ?',
                            'Oui. Nous préparons le test fide et le DELF pour le permis C et la naturalisation suisse, avec des mises en situation et le vocabulaire de Suisse romande.'
                        ],
                        [
                            'Par quel niveau commencer ?',
                            'Par votre premier cours : votre tuteur mesure vos 4 compétences face au niveau exigé par votre démarche. Si vous partez de zéro, vous démarrez directement le programme A1.'
                        ],
                        [
                            'Les élèves passent-ils des évaluations ?',
                            'Oui. Des examens blancs réguliers, au format de votre examen, montrent vos progrès et vous disent quand vous inscrire au test.'
                        ],
                        [
                            'Fluence Française fait-elle passer les examens ?',
                            'Non. Nous sommes une école de préparation indépendante. Vous vous inscrivez à l\'examen auprès d\'un centre agréé.'
                        ],
                    ] : [
                        [
                            'Are the classes online?',
                            'Yes. Every class runs live on Google Meet with your tutor. Only the exam itself is taken at an approved centre.'
                        ],
                        [
                            'Which exams do you prepare for?',
                            'The TCF IRN, TEF IRN, DELF (A1 to B2) and fide test. Civic-exam prep is included in every plan at no extra cost. Not sure which one? <a href="/en/tcf-vs-tef-vs-delf/" class="text-primary-600 underline font-semibold hover:text-primary-700">Compare the TCF, TEF and DELF</a>.'
                        ],
                        [
                            'Do you prepare for Swiss applications?',
                            'Yes. We prepare the fide test and the DELF for the C permit and Swiss citizenship, with Swiss scenarios and the vocabulary of French-speaking Switzerland.'
                        ],
                        [
                            'Which level should I start from?',
                            'Your first class decides: your tutor measures your 4 skills against the level your application requires. If you\'re starting from zero, you go straight into the A1 programme.'
                        ],
                        [
                            'Do students take assessments?',
                            'Yes. Regular mock exams in your exam\'s format show your progress and tell you when to book the test.'
                        ],
                        [
                            'Does Fluence Française run the exams?',
                            'No. We\'re an independent prep school. You register for the exam with an approved centre.'
                        ],
                    ];
                ?>
                <div class="space-y-3 reveal">
                    <?php $__currentLoopData = $faqItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-mist rounded-2xl border border-slate-100 overflow-hidden">
                        <button class="faq-toggle w-full px-6 py-5 flex justify-between items-center text-left gap-4">
                            <span class="font-bold text-ink"><?php echo e($q[0]); ?></span>
                            <i class="fas fa-plus text-primary-600 text-sm transition-transform duration-300 shrink-0"></i>
                        </button>
                        <div class="faq-content hidden px-6 pb-6 text-slate-600 leading-relaxed"><?php echo $q[1]; ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>

        <!-- final cta -->
        <?php echo $__env->make('partials.cta-banner', [
            'isFr' => $isFr,
            'eyebrow' => $isFr ? '' : "LET'S GET YOU THERE",
            'title' => $isFr
                ? "Votre niveau. Le niveau exigé.<br class='hidden sm:inline'> Nous comblons l'écart."
                : "Your level. The required level.<br class='hidden sm:inline'> We close the gap.",
            'description' => $isFr
                ? 'Votre premier cours fixe votre niveau et votre plan. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                : 'Your first class sets your level in each section and the plan to reach the one your application needs. You choose the pace: 2 or 4 hours a week.',
            'primaryButtonText' => $isFr ? "S'inscrire" : 'Enroll now',
            'primaryButtonUrl' => '/register',
            'secondaryButtonText' => $isFr ? 'Nous parler' : 'Talk to us',
            'secondaryButtonUrl' => $isFr ? '/fr/contact/' : '/en/contact/',
            'helperText' => $isFr
                ? ''
                : 'Live classes on Google Meet · 1:1 or small group · Student portal included'
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
                if (icon) icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(135deg)';
            });
        });
    </script>
</body>
</html>
<?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/homepage.blade.php ENDPATH**/ ?>