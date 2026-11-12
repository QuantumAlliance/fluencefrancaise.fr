<!DOCTYPE html>
@php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';

    // Determine language based on current URL prefix
    $isFr = request()->is('fr*');

    // Page metadata
    $title = $isFr
        ? "Naturalisation suisse : exigences de langue B1/A2 et préparation"
        : "Swiss Citizenship: Language Requirements & Online Prep (B1/A2)";

    $metaDesc = $isFr
        ? "Exigences linguistiques pour la nationalité suisse : B1 oral et A2 écrit en français pour permis C et naturalisation ordinaire ou facilitée. Préparation en direct."
        : "Swiss citizenship language requirements: B1 oral and A2 written in French for ordinary and simplified naturalisation. Live online preparation.";

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/naturalisation-suisse/' : 'https://fluencefrancaise.fr/en/swiss-citizenship/';
    $frUrl = 'https://fluencefrancaise.fr/fr/naturalisation-suisse/';
    $enUrl = 'https://fluencefrancaise.fr/en/swiss-citizenship/';

    // FAQ Data
    $faqs = $isFr ? [
        [
            'q' => "Quel niveau de français est exigé pour la nationalité suisse ?",
            'a' => "La loi fédérale sur la nationalité suisse (LN) exige d'attester d'un niveau minimum B1 à l'oral (parler et comprendre) et A2 à l'écrit (lire et écrire) dans la langue officielle du canton de résidence."
        ],
        [
            'q' => "Test fide ou DELF : lequel est préférable pour la naturalisation ?",
            'a' => "Le test fide est spécifiquement conçu pour les situations de la vie en Suisse et constitue souvent la solution la plus rapide. Néanmoins, les diplômes DELF (DELF B1 pour l'oral et A2 pour l'écrit) sont reconnus par tous les cantons et ont une validité illimitée à vie."
        ],
        [
            'q' => "Peut-on être dispensé du test de langue pour la naturalisation ?",
            'a' => "Oui. Vous pouvez être dispensé si le français est votre langue maternelle, si vous avez suivi au moins 3 années d'école obligatoire en français en Suisse, ou si vous êtes titulaire d'un diplôme du degré secondaire II ou tertiaire obtenu en français."
        ],
        [
            'q' => "Que demande-t-on lors de l'entretien d'intégration cantonal ?",
            'a' => "Les commissions communales et cantonales vous interrogent sur les institutions politiques suisses, l'histoire, la géographie, l'actualité locale, et évaluent votre aisance conversationnelle spontanée en français."
        ],
        [
            'q' => "Combien de temps faut-il pour atteindre le niveau B1 oral et A2 écrit ?",
            'a' => "À partir d'un niveau A2 de départ, la plupart de nos apprenants atteignent le niveau B1 oral et A2 écrit requis en 8 à 12 semaines avec 2 à 4 heures de cours en direct par semaine."
        ]
    ] : [
        [
            'q' => "What level of French is required for Swiss citizenship?",
            'a' => "Under the Swiss Federal Act on Citizenship (LN), you must demonstrate certified language proficiency of at least level B1 spoken (speaking & comprehension) and A2 written (reading & writing) in the local national language."
        ],
        [
            'q' => "fide or DELF: which is better for Swiss naturalisation?",
            'a' => "The fide test is designed specifically for daily Swiss life and is usually the quickest option. However, a DELF B1 diploma is also fully accepted by all cantons and has lifelong validity."
        ],
        [
            'q' => "Can I be exempt from the language test?",
            'a' => "Yes. You may be exempt if French is your native language, if you completed at least 3 years of compulsory schooling in French in Switzerland, or if you hold an upper secondary or tertiary degree taught in French."
        ],
        [
            'q' => "What is asked in the cantonal naturalisation interview?",
            'a' => "The communal and cantonal commissions ask about Swiss federal and cantonal institutions, history, geography, current affairs, and evaluate your ability to converse spontaneously and comfortably in French."
        ],
        [
            'q' => "How long does it take to prepare for B1 oral and A2 written?",
            'a' => "From an A2 base, most candidates reach the required B1 oral standard in 8 to 12 weeks with 2 to 4 hours of live instruction per week and targeted practice."
        ]
    ];
@endphp
<html lang="{{ $isFr ? 'fr' : 'en' }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | {{ $siteName }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="fr" href="{{ $frUrl }}">
    <link rel="alternate" hreflang="en" href="{{ $enUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $enUrl }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }} | {{ $siteName }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ $siteName }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (Vite build) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('partials.theme')
</head>

<body class="bg-white text-slate-800 font-sans antialiased selection:bg-[#0055A4] selection:text-white">

    <div class="min-h-screen flex flex-col justify-between">
        <div>
            @include('partials.header')

            <!-- hero banner -->
            <section
                class="relative  pt-32 md:pt-40 pb-16 md:pb-24overflow-hidden bg-gradient-to-b from-[#f0f6ff]/70 via-white to-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl relative z-10">

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                        <div class="lg:col-span-7">
                            <nav class="flex items-center gap-1.5 text-xs text-slate-500 mb-6 font-medium">
                                <a href="{{ $isFr ? '/fr' : '/en' }}"
                                    class="hover:text-[#0055A4] transition-colors">{{ $isFr ? 'Accueil' : 'Home' }}</a>
                                <span>›</span>
                                <a href="{{ $isFr ? '/fr/cours' : '/en/courses' }}"
                                    class="hover:text-[#0055A4] transition-colors">{{ $isFr ? 'Suisse' : 'Switzerland' }}</a>
                                <span>›</span>
                                <span
                                    class="text-slate-800 font-semibold">{{ $isFr ? 'Naturalisation suisse' : 'Swiss Citizenship' }}</span>
                            </nav>

                            <div class="inline-flex items-center gap-1.5 mb-5">
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                                <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                                <span
                                    class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                    {{ $isFr ? 'SUISSE · NATURALISATION ORDINAIRE & FACILITÉE' : 'SWISS CITIZENSHIP · LANGUAGE REQUIREMENTS' }}
                                </span>
                            </div>

                            <h1
                                class="text-3xl sm:text-5xl lg:text-[52px] font-bold text-[#0c1e38] tracking-tight leading-[1.2]">
                                {{ $isFr ? 'Naturalisation suisse :' : 'Swiss Citizenship: Reach' }}
                                <br>
                                <span class="text-[#0055a4]">
                                    {{ $isFr ? 'Atteignez le niveau de langue' : 'the Language Level' }}
                                </span>
                                <br>
                                <span class="relative inline-block">
                                    {{ $isFr ? 'exigé' : 'Required' }}
                                    <span
                                        class="absolute bottom-1 left-0 right-0 h-1 bg-gradient-to-r from-[#0055A4] to-[#EF4135] rounded-full"></span>
                                </span>
                            </h1>

                            <p class="mt-6 text-sm sm:text-base text-slate-500 leading-relaxed font-normal max-w-xl">
                                {{ $isFr
    ? 'La naturalisation suisse exige un niveau certifié B1 oral et A2 écrit (ainsi que l\'entretien d\'intégration cantonal). Nous vous préparons au niveau exact exigé dans votre canton.'
    : 'Swiss naturalisation requires certified French level B1 oral and A2 written (plus cantonal interview). We prepare you for the exact level, spoken in your canton.'
                                }}
                            </p>

                            <div class="mt-8 flex flex-wrap items-center gap-4">
                                <a href="/register"
                                    class="inline-flex items-center justify-center px-7 py-3.5 rounded-full text-base font-semibold text-white bg-[#0055a4] hover:bg-[#003f7a] shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5">
                                    {{ $isFr ? "S'inscrire" : 'Enroll now' }}
                                    <i class="fas fa-arrow-right text-xs ml-2.5"></i>
                                </a>
                                <a href="{{ $isFr ? '/fr/contact' : '/en/contact' }}"
                                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#0055a4] hover:text-[#003f7a] px-4 py-3 transition-colors">
                                    <span>{{ $isFr ? 'Mesurer votre écart' : 'Calculate your gap' }}</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>

                            <div class="mt-6 flex items-center gap-2 text-xs text-slate-500 font-medium">
                                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>{{ $isFr ? 'Cours particuliers sur Google Meet · Préparation entretien cantonal · Garantie conformité SEM' : 'Live classes on Google Meet · Cantonal interview prep · SEM compliant training' }}</span>
                            </div>
                        </div>

                        <div class="lg:col-span-5 flex justify-center">
                            <div class="relative w-full max-w-[360px] aspect-square flex items-center justify-center">
                                <div class="absolute inset-0 rounded-full bg-blue-100/40 blur-2xl"></div>
                                <div class="absolute inset-8 rounded-full border border-blue-100"></div>
                                <div class="absolute inset-16 rounded-full border border-blue-50"></div>

                                <div
                                    class="relative w-44 h-44 rounded-3xl bg-[#002654] text-white flex flex-col items-center justify-center shadow-xl z-10">
                                    <div class="text-3xl font-extrabold tracking-tight text-white">B1 + A2</div>
                                    <div class="text-[11px] text-blue-200 mt-1 uppercase tracking-wider font-semibold">
                                        {{ $isFr ? 'Naturalisation suisse' : 'Swiss naturalisation' }}
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
                                            {{ $isFr ? 'Taux de réussite' : 'Pass rate' }}
                                        </div>
                                        <div class="text-[10px] text-slate-400">
                                            {{ $isFr ? '99 % dès le 1er passage' : '99% first attempt' }}
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
                                            {{ $isFr ? 'fide & DELF' : 'fide & DELF' }}
                                        </div>
                                        <div class="text-[10px] text-slate-400">
                                            {{ $isFr ? 'B1 Oral · A2 Écrit' : 'B1 Oral · A2 Written' }}
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
                                        {{ $isFr ? 'Naturalisation ordinaire (10 ans)' : 'Standard naturalisation (10 years)' }}
                                    </div>
                                    <div class="text-xs text-slate-500 font-normal mt-0.5">
                                        {{ $isFr ? 'Exige B1 oral + A2 écrit' : 'Requires B1 oral + A2 written' }}
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 rounded-2xl bg-[#f8fafc] border border-slate-200/70 flex items-start gap-3">
                                <i class="fas fa-check text-[#0055a4] mt-1 text-xs"></i>
                                <div>
                                    <div class="text-xs font-bold text-[#10233e]">
                                        {{ $isFr ? 'Naturalisation facilitée (mariage)' : 'Simplified naturalisation (marriage)' }}
                                    </div>
                                    <div class="text-xs text-slate-500 font-normal mt-0.5">
                                        {{ $isFr ? '5 ans de séjour en Suisse' : '5 years in Switzerland' }}
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 rounded-2xl bg-[#f8fafc] border border-slate-200/70 flex items-start gap-3">
                                <i class="fas fa-check text-[#0055a4] mt-1 text-xs"></i>
                                <div>
                                    <div class="text-xs font-bold text-[#10233e]">
                                        {{ $isFr ? 'Examens officiels reconnus' : 'Official tests recognized' }}
                                    </div>
                                    <div class="text-xs text-slate-500 font-normal mt-0.5">
                                        {{ $isFr ? 'fide, DELF, TCF, TEF' : 'fide, DELF, TCF, TEF' }}
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

            <!-- swiss citizenship requirements -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                {{ $isFr ? 'CONDITIONS D\'ÉLIGIBILITÉ' : 'ELIGIBILITY CRITERIA' }}
                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            {{ $isFr ? 'Conditions pour la nationalité suisse' : 'Swiss citizenship requirements' }}
                        </h2>
                        <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal">
                            {{ $isFr
    ? 'Pour obtenir la nationalité suisse par voie ordinaire, la loi fédérale sur la nationalité suisse (LN) fixe des critères stricts. Les cantons et communes peuvent y ajouter des règles spécifiques.'
    : 'To obtain Swiss citizenship through ordinary naturalisation, federal law sets strict conditions. Cantons and communes may add further requirements.'
                            }}
                        </p>
                    </div>

                    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="bg-[#0055a4] text-white">
                                        <th class="py-4 px-6 font-semibold w-1/3">
                                            {{ $isFr ? 'Critère fédéral' : 'Requirement' }}
                                        </th>
                                        <th class="py-4 px-6 font-semibold">
                                            {{ $isFr ? 'Règle fédérale et cantonale' : 'Federal and cantonal rule' }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            {{ $isFr ? 'Durée de séjour' : 'Residency' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600 font-normal">
                                            {{ $isFr
    ? '10 ans de résidence en Suisse (les années vécues entre 8 et 18 ans comptent double, sous réserve d\'au moins 6 ans réels).'
    : '10 years of permanent residence in Switzerland (years between ages 8 and 18 count double, with a minimum of 6 actual years).'
                                            }}
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            {{ $isFr ? 'Titre de séjour requis' : 'Permit type' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600 font-normal">
                                            {{ $isFr
    ? 'Être titulaire d\'un permis d\'établissement C en cours de validité au moment du dépôt de la demande.'
    : 'Must hold a valid C settlement permit (Permis C) at the time of application.'
                                            }}
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            {{ $isFr ? 'Séjour cantonal et communal' : 'Cantonal & communal residency' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600 font-normal">
                                            {{ $isFr
    ? '2 à 5 ans selon le canton de domicile (par ex. 2 ans dans le canton de Genève, 2 ans dans le canton de Vaud).'
    : '2 to 5 years depending on the canton (e.g. 2 years in Geneva, 2 years in Vaud).'
                                            }}
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            {{ $isFr ? 'Compétences linguistiques' : 'Language level' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600 font-normal">
                                            {{ $isFr
    ? 'Niveau B1 à l\'oral (parler et comprendre) et A2 à l\'écrit (lire et écrire) dans la langue du canton.'
    : 'B1 spoken (oral) and A2 written (reading & writing) in the local official language.'
                                            }}
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            {{ $isFr ? 'Autonomie financière' : 'Financial independence' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600 font-normal">
                                            {{ $isFr
    ? 'Aucune aide sociale perçue sur les 3 dernières années, impôts à jour et absence d\'actes de défaut de biens.'
    : 'No social assistance in the last 3 years, up to date on taxes, and no debt enforcement certificates.'
                                            }}
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-[#10233e]">
                                            {{ $isFr ? 'Intégration civique et sécurité' : 'Civic integration & security' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600 font-normal">
                                            {{ $isFr
    ? 'Casier judiciaire vierge, respect de l\'ordre constitutionnel suisse et participation à la vie sociale locale.'
    : 'No criminal record, respect for Swiss legal order, and active integration into local community life.'
                                            }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- swiss citizenship language requirements -->
            <section class="py-16 sm:py-24 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl text-center">
                    <div class="inline-flex items-center gap-1.5 mb-3">
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                        <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                            {{ $isFr ? 'NORMES LINGUISTIQUES' : 'LANGUAGE STANDARDS (LN & LEI)' }}
                        </span>
                    </div>
                    <h2
                        class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                        {{ $isFr ? 'Exigences linguistiques pour la naturalisation suisse' : 'Swiss citizenship language requirements' }}
                    </h2>
                    <p class="mt-6 text-sm sm:text-base text-slate-600 leading-relaxed font-normal max-w-2xl mx-auto">
                        {{ $isFr
    ? 'La loi fédérale exige un niveau de langue attesté B1 à l\'oral (parler et comprendre) et A2 à l\'écrit (lire et écrire) dans la langue officielle du canton de domicile (le français en Suisse romande).'
    : 'Federal law requires language level B1 spoken (speaking and comprehension) and A2 written (reading and writing) in the language spoken in your canton of residence (French in Romandie).'
                        }}
                    </p>
                    <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal max-w-2xl mx-auto">
                        {{ $isFr
    ? 'L\'ensemble des cantons romands (Genève, Vaud, Fribourg, Neuchâtel, Valais, Jura) appliquent ce socle fédéral. Les attestations officielles reconnues sont le passeport des langues fide, les diplômes DELF (B1 oral / A2 écrit), ainsi que les tests TCF et TEF de moins de 2 ans.'
    : 'All French-speaking cantons (Geneva, Vaud, Fribourg, Neuchâtel, Valais, Jura) follow the federal standard: B1 oral + A2 written. Officially recognized proof includes the fide Language Passport, DELF diplomas, or accredited TCF/TEF certificates under 2 years old.'
                        }}
                    </p>
                </div>
            </section>

            <!-- language counts twice: test and interview -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl text-center">
                    <div class="inline-flex items-center gap-1.5 mb-3">
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                        <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                            {{ $isFr ? 'LA DOUBLE ÉVALUATION' : 'THE DUAL EVALUATION' }}
                        </span>
                    </div>
                    <h2
                        class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                        {{ $isFr ? 'La langue compte deux fois : le test et l\'entretien' : 'Language counts twice: test and interview' }}
                    </h2>
                    <p class="mt-6 text-sm sm:text-base text-slate-600 leading-relaxed font-normal max-w-2xl mx-auto">
                        {{ $isFr
    ? 'Réussir votre test fide ou votre diplôme DELF n\'est que la première étape. Lors de l\'instruction de votre dossier, vous êtes convoqué à un entretien d\'intégration en préfecture ou auprès de la commission communale.'
    : 'Passing your fide test or DELF exam is only the first step. When you submit your dossier, your cantonal and communal authorities invite you to an in-person integration interview.'
                        }}
                    </p>
                    <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal max-w-2xl mx-auto">
                        {{ $isFr
    ? 'Durant cet échange, la commission évalue vos connaissances de la Suisse mais aussi votre aisance à vous exprimer spontanément en français. Notre programme associe préparation au test B1 et simulations de l\'entretien d\'assimilation cantonal.'
    : 'During this interview, the commission evaluates not only your Swiss knowledge (geography, history, institutions) but also how naturally and confidently you express yourself in French. Our training combines B1 test prep with cantonal interview simulation.'
                        }}
                    </p>
                </div>
            </section>

            <!-- swiss citizenship by marriage -->
            <section class="py-16 sm:py-24 bg-[#f8fafc] border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl text-center">
                    <div class="inline-flex items-center gap-1.5 mb-3">
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                        <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                        <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                            {{ $isFr ? 'NATURALISATION FACILITÉE (5 ANS)' : 'SIMPLIFIED NATURALISATION (5 YEARS)' }}
                        </span>
                    </div>
                    <h2
                        class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                        {{ $isFr ? 'Naturalisation suisse par mariage' : 'Swiss citizenship by marriage' }}
                    </h2>
                    <p class="mt-6 text-sm sm:text-base text-slate-600 leading-relaxed font-normal max-w-2xl mx-auto">
                        {{ $isFr
    ? 'Si vous êtes marié à un citoyen suisse, vous pouvez demander la naturalisation facilitée sous la compétence directe du Secrétariat d\'État aux migrations (SEM) à Berne.'
    : 'If you are married to a Swiss citizen, you can apply through simplified naturalisation under direct federal jurisdiction (SEM in Bern).'
                        }}
                    </p>
                    <p class="mt-4 text-sm sm:text-base text-slate-500 leading-relaxed font-normal max-w-2xl mx-auto">
                        {{ $isFr
    ? 'Les conditions sont allégées : 3 ans de mariage et 5 ans de résidence totale en Suisse (dont l\'année précédant le dépôt). L\'exigence linguistique reste toutefois identique : niveau B1 oral et A2 écrit obligatoire.'
    : 'Key requirements: 3 years of marriage and a total of 5 years of residence in Switzerland (including the 12 months immediately preceding the application). The language standard remains identical: B1 spoken and A2 written.'
                        }}
                    </p>
                </div>
            </section>

            <!-- how we prepare you -->
            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                        <div class="inline-flex items-center gap-1.5 mb-3">
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                            <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                                {{ $isFr ? 'NOTRE MÉTHODE' : 'OUR METHOD' }}
                            </span>
                        </div>
                        <h2
                            class="text-3xl sm:text-4xl md:text-[40px] font-bold text-[#10233e] tracking-tight leading-[1.15]">
                            {{ $isFr ? 'Comment nous vous préparons' : 'How we prepare you' }}
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
                                    {{ $isFr ? 'Diagnostic ciblé' : 'Targeted diagnostic' }}
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    {{ $isFr
    ? 'Votre tuteur évalue précisément votre écart face aux critères suisses B1/A2 dès la première séance.'
    : 'We benchmark your oral and written skills against the Swiss B1/A2 criteria in your first session.'
                                    }}
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
                                    {{ $isFr ? 'Un plan personnalisé' : 'A personalized plan' }}
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    {{ $isFr
    ? 'Vos heures se concentrent sur l\'expression orale et la fluidité pour garantir votre réussite au test et à l\'entretien.'
    : 'Your hours focus directly on your gap, balancing exam preparation with conversational fluency.'
                                    }}
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
                                    {{ $isFr ? '100 % en direct sur Google Meet' : '100% live online' }}
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    {{ $isFr
    ? 'Séances en direct avec votre professeur dédié, programmées selon vos disponibilités professionnelles.'
    : '1:1 sessions on Google Meet with your dedicated Swiss-fluent tutor, at times that suit your workday.'
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200/80 p-7 flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-2xl bg-[#002654] text-white flex items-center justify-center flex-shrink-0 text-sm">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#10233e] text-base mb-1">
                                    {{ $isFr ? 'Préparation à l\'entretien cantonal' : 'Interview preparation' }}
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                                    {{ $isFr
    ? 'Simulations d\'examen officiel (fide / DELF) et répétition des questions types de l\'entretien de naturalisation.'
    : 'Simulations of both the official language exam (fide/DELF) and typical cantonal assimilation questions.'
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl bg-[#002654] text-white p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <div class="text-base sm:text-lg font-bold text-white">
                                {{ $isFr ? 'Prêt à évaluer votre niveau ?' : 'Ready to test your level?' }}
                            </div>
                            <div class="text-xs sm:text-sm text-slate-300 font-normal mt-0.5">
                                {{ $isFr
    ? 'Réservez votre séance d\'évaluation et mesurez votre écart dès aujourd\'hui.'
    : 'Book your introductory session and measure your gap today.'
                                }}
                            </div>
                        </div>
                        <a href="/register"
                            class="inline-flex items-center justify-center px-6 py-3 rounded-full text-sm font-semibold text-[#002654] bg-white hover:bg-slate-100 transition-all flex-shrink-0">
                            {{ $isFr ? "S'inscrire" : 'Enroll now' }}
                            <i class="fas fa-arrow-right text-xs ml-2"></i>
                        </a>
                    </div>
                </div>
            </section>

            <!-- swiss citizenship faq -->
            @include('partials.faq-section', [
                'eyebrow' => $isFr ? 'VOS QUESTIONS, NOS RÉPONSES' : 'YOUR QUESTIONS, ANSWERED',
                'title' => $isFr ? 'Naturalisation<br>suisse<br>FAQ' : 'Swiss<br>Citizenship<br>FAQ',
                'subtitle' => $isFr
                    ? 'Tout ce que vous devez savoir sur les exigences linguistiques pour la nationalité suisse.'
                    : 'Everything you need to know about Swiss citizenship language requirements and exams.',
                'faqs' => $faqs
            ])

            <!-- final cta & sources -->
            <section class="py-16 sm:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                    @include('partials.cta-banner', [
                        'eyebrow' => $isFr ? 'ATTEIGNEZ VOTRE OBJECTIF' : "LET'S GET YOU THERE",
                        'title' => $isFr ? "Votre niveau. Le niveau exigé.<br>Nous comblons l'écart." : 'Your level. The required level.<br>We close the gap.',
                        'description' => $isFr
                            ? 'Votre premier cours définit votre niveau dans chaque compétence et le plan pour atteindre le niveau B1/A2 exigé par votre dossier. Vous choisissez le rythme : 2 ou 4 heures par semaine.'
                            : 'Your first class sets your level in each section and the plan to reach B1/A2 for your naturalisation dossier. You choose the pace: 2 or 4 hours a week.',
                        'primaryBtnText' => $isFr ? "S'inscrire" : 'Enroll now',
                        'primaryBtnUrl' => '/register',
                        'secondaryBtnText' => $isFr ? 'Découvrir nos cours' : 'Explore our courses',
                        'secondaryBtnUrl' => '/courses',
                        'helperText' => $isFr
                            ? 'Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus'
                            : 'Live classes on Google Meet · 1:1 or small group · Student portal included'
                    ])

                    <div class="mt-8 text-center text-xs sm:text-sm text-slate-500 font-normal">
                        <span>{{ $isFr ? 'Vous venez à deux ? 50 CHF de réduction par mois, pour vous et votre proche.' : 'Coming with a friend? CHF 50 off per month, for you and for them.' }}</span>
                        <a href="{{ $isFr ? '/fr/contact' : '/en/contact' }}"
                            class="text-[#0055a4] font-semibold hover:underline inline-flex items-center gap-1 ml-1.5">
                            <span>{{ $isFr ? 'Contactez-nous' : 'Contact us' }}</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <div class="border-t border-slate-200/80 my-8"></div>

                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-slate-400">
                        <span
                            class="font-bold uppercase tracking-wider text-slate-500">{{ $isFr ? 'SOURCES' : 'SOURCES' }}</span>
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

        @include('partials.footer')
    </div>

</body>

</html>