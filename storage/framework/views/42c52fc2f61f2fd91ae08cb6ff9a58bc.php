<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    
    // Determine language based on current URL prefix
    $isFr = request()->is('fr*');
    $currentPath = request()->path();
    
    // Clean URLs for language toggle
    if ($isFr) {
        $cleanPath = preg_replace('#^fr/?#', '', $currentPath);
        $frUrl = '/' . ltrim($currentPath, '/');
        $enUrl = $cleanPath ? '/en/' . ltrim($cleanPath, '/') : '/en';
    } elseif (request()->is('en*')) {
        $cleanPath = preg_replace('#^en/?#', '', $currentPath);
        $enUrl = '/' . ltrim($currentPath, '/');
        $frUrl = $cleanPath ? '/fr/' . ltrim($cleanPath, '/') : '/fr';
    } else {
        $enUrl = '/en';
        $frUrl = '/fr';
    }
?>

<style>
    .header-pill {
        height: 60px !important;
        background-color: #ffffff !important;
        box-shadow: 0 4px 20px -2px rgba(0, 38, 84, 0.08) !important;
        border: 1px solid #f1f5f9 !important;
        border-radius: 9999px !important;
    }

    .brand-wordmark {
        font-family: 'Urbanist', system-ui, sans-serif;
        font-weight: 500;
        font-size: 18px;
        letter-spacing: -0.01em;
        color: #002654;
        line-height: 1;
        white-space: nowrap;
    }

    .brand-wordmark .accent {
        color: #EF4135;
        font-weight: 700;
    }

    .brand-mark-img {
        height: 36px;
    }

    .nav-pill-link {
        font-family: 'DM Sans', system-ui, sans-serif;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        color: #002654 !important;
        transition: all 0.2s ease;
        display: inline-flex !important;
        align-items: center !important;
        text-decoration: none !important;
        white-space: nowrap;
    }

    .nav-pill-link:hover,
    .nav-item-btn:hover {
        color: #0055A4 !important;
    }

    .nav-item-btn {
        font-family: 'DM Sans', system-ui, sans-serif;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        color: #002654 !important;
        transition: all 0.2s ease;
        background: transparent;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        padding: 0;
    }

    .dropdown-card {
        background-color: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 16px 36px -4px rgba(0, 38, 84, 0.12), 0 4px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid #f1f5f9;
        padding: 1.125rem 1.25rem;
        min-width: 275px;
    }

    .dropdown-item-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.55rem 0.25rem;
        font-size: 13.5px;
        font-weight: 500;
        color: #1e293b;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .dropdown-item-link:hover {
        color: #0055A4;
    }

    .dropdown-item-link:hover .arrow-icon {
        color: #0055A4;
        transform: translate(2px, -2px);
    }

    .btn-enroll-pill {
        font-family: 'DM Sans', system-ui, sans-serif;
        font-size: 13px !important;
        font-weight: 700 !important;
        background-color: #002654 !important;
        color: #ffffff !important;
        padding: 9px 24px !important;
        border-radius: 9999px !important;
        transition: all 0.25s ease !important;
        box-shadow: 0 4px 14px -2px rgba(0, 38, 84, 0.4) !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
        white-space: nowrap;
    }

    .btn-enroll-pill:hover {
        background-color: #003d7a !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px -2px rgba(0, 38, 84, 0.5) !important;
    }

    .header-container {
        position: absolute !important;
        top: 24px !important;
        width: 100% !important;
    }

    @media (min-width: 1200px) {
        .navbar-desktop-nav {
            display: flex !important;
        }
        .navbar-mobile-toggle {
            display: none !important;
        }
    }

    @media (max-width: 1199px) {
        .navbar-desktop-nav {
            display: none !important;
        }
        .navbar-mobile-toggle {
            display: block !important;
        }
    }

    @media (max-width: 768px) {
        .header-container {
            top: 16px !important;
        }
        .brand-wordmark {
            font-size: 0.9375rem;
        }
        .brand-mark-img {
            height: 30px;
        }
    }
</style>

<div class="header-container global-header absolute w-full z-50 px-4 sm:px-6 lg:px-8">
    <header class="header-pill max-w-7xl mx-auto px-5 sm:px-6 flex items-center justify-between">
        <!-- Left: Logo & Wordmark -->
        <div class="flex items-center shrink-0">
            <a href="<?php echo e($isFr ? '/fr' : '/'); ?>" class="flex items-center gap-2.5">
                <img src="<?php echo e(asset('images/brand-mark.png')); ?>" alt="Fluence Française" class="brand-mark-img w-auto shrink-0">
                <span class="brand-wordmark">Fluence <span class="accent">Française</span></span>
            </a>
            <!-- Divider after Logo -->
            <div class="h-5 w-[1px] bg-slate-200 ml-4 mr-1 hidden xl:block"></div>
        </div>

        <!-- Middle: Nav Links with Dropdowns (Desktop) -->
        <nav class="navbar-desktop-nav items-center gap-5 xl:gap-6 mx-auto h-full hidden">
            <!-- 1. France Dropdown -->
            <div class="relative group h-full flex items-center">
                <button type="button" class="nav-item-btn gap-1 py-4">
                    <span>France</span>
                    <svg class="w-3 h-3 text-slate-600 group-hover:text-[#0055A4] transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <!-- Dropdown Card -->
                <div class="absolute top-full left-0 pt-2 z-50 opacity-0 invisible translate-y-1.5 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-200 ease-out">
                    <div class="dropdown-card">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 text-slate-900 font-bold text-[14px]">
                            <span>France</span>
                            <svg class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <!-- Card Items -->
                        <div class="mt-2.5 flex flex-col">
                            <a href="<?php echo e($isFr ? '/fr/test-francais-naturalisation' : '/en/french-citizenship-language-test'); ?>" class="dropdown-item-link group/item">
                                <span><?php echo e($isFr ? 'Naturalisation B2' : 'Citizenship B2'); ?></span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/titre-de-sejour-niveau-francais#carte-de-resident' : '/en/french-residence-permit-language#resident-card'); ?>" class="dropdown-item-link group/item">
                                <span><?php echo e($isFr ? 'Carte de résident B1' : 'Resident card B1'); ?></span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/titre-de-sejour-niveau-francais#carte-pluriannuelle' : '/en/french-residence-permit-language#multi-year-permit'); ?>" class="dropdown-item-link group/item">
                                <span><?php echo e($isFr ? 'Carte pluriannuelle A2' : 'Multi-year permit A2'); ?></span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/examen-civique' : '/en/french-civic-exam'); ?>" class="dropdown-item-link group/item">
                                <span><?php echo e($isFr ? 'Examen civique' : 'Civic exam'); ?></span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/naturalisation-francaise' : '/en/french-citizenship'); ?>" class="dropdown-item-link group/item">
                                <span><?php echo e($isFr ? 'Guide naturalisation' : 'Citizenship guide'); ?></span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Switzerland Dropdown -->
            <div class="relative group h-full flex items-center">
                <button type="button" class="nav-item-btn gap-1 py-4">
                    <span><?php echo e($isFr ? 'Suisse' : 'Switzerland'); ?></span>
                    <svg class="w-3 h-3 text-slate-600 group-hover:text-[#0055A4] transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <!-- Dropdown Card -->
                <div class="absolute top-full left-0 pt-2 z-50 opacity-0 invisible translate-y-1.5 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-200 ease-out">
                    <div class="dropdown-card">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 text-slate-900 font-bold text-[14px]">
                            <span><?php echo e($isFr ? 'Suisse' : 'Switzerland'); ?></span>
                            <svg class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <!-- Card Items -->
                        <div class="mt-2.5 flex flex-col">
                            <a href="<?php echo e($isFr ? '/fr/permis-c-suisse' : '/en/c-permit-switzerland'); ?>" class="dropdown-item-link group/item">
                                <span><?php echo e($isFr ? 'Permis C (A2 / B1)' : 'C permit (A2 / B1)'); ?></span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/naturalisation-suisse' : '/en/swiss-citizenship'); ?>" class="dropdown-item-link group/item">
                                <span><?php echo e($isFr ? 'Naturalisation suisse (B1)' : 'Swiss citizenship (B1)'); ?></span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/test-fide' : '/en/fide-test'); ?>" class="dropdown-item-link group/item">
                                <span><?php echo e($isFr ? 'Test fide' : 'fide test'); ?></span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Exams Dropdown -->
            <div class="relative group h-full flex items-center">
                <button type="button" class="nav-item-btn gap-1 py-4">
                    <span><?php echo e($isFr ? 'Examens' : 'Exams'); ?></span>
                    <svg class="w-3 h-3 text-slate-600 group-hover:text-[#0055A4] transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <!-- Dropdown Card -->
                <div class="absolute top-full left-0 pt-2 z-50 opacity-0 invisible translate-y-1.5 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-200 ease-out">
                    <div class="dropdown-card">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 text-slate-900 font-bold text-[14px]">
                            <span><?php echo e($isFr ? 'Examens' : 'Exams'); ?></span>
                            <svg class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <!-- Card Items -->
                        <div class="mt-2.5 flex flex-col">
                            <a href="<?php echo e($isFr ? '/fr/tcf-irn' : '/en/tcf-irn'); ?>" class="dropdown-item-link group/item">
                                <span>TCF IRN</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/tef-irn' : '/en/tef-irn'); ?>" class="dropdown-item-link group/item">
                                <span>TEF IRN</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/test-fide' : '/en/fide-test'); ?>" class="dropdown-item-link group/item">
                                <span><?php echo e($isFr ? 'Test fide' : 'fide test'); ?></span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/delf-a2' : '/en/delf-a2'); ?>" class="dropdown-item-link group/item">
                                <span>DELF A2</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/delf-b1' : '/en/delf-b1'); ?>" class="dropdown-item-link group/item">
                                <span>DELF B1</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/delf-b2' : '/en/delf-b2'); ?>" class="dropdown-item-link group/item">
                                <span>DELF B2</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/delf' : '/en/delf'); ?>" class="dropdown-item-link group/item">
                                <span><?php echo e($isFr ? 'Tous les niveaux DELF' : 'All DELF levels'); ?></span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/tcf-tef-ou-delf' : '/en/tcf-vs-tef-vs-delf'); ?>" class="dropdown-item-link group/item">
                                <span><?php echo e($isFr ? 'TCF, TEF ou DELF ?' : 'TCF vs TEF vs DELF'); ?></span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Courses (Direct Link) -->
            <a href="<?php echo e($isFr ? '/fr/cours-de-francais-en-ligne' : '/our-courses'); ?>" class="nav-pill-link">
                <?php echo e($isFr ? 'Cours' : 'Courses'); ?>

            </a>

            <!-- 5. Guides Dropdown -->
            <div class="relative group h-full flex items-center">
                <button type="button" class="nav-item-btn gap-1 py-4">
                    <span>Guides</span>
                    <svg class="w-3 h-3 text-slate-600 group-hover:text-[#0055A4] transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <!-- Dropdown Card -->
                <div class="absolute top-full left-0 pt-2 z-50 opacity-0 invisible translate-y-1.5 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-200 ease-out">
                    <div class="dropdown-card">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 text-slate-900 font-bold text-[14px]">
                            <span>Guides</span>
                            <svg class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <!-- Card Items -->
                        <div class="mt-2.5 flex flex-col">
                            <a href="<?php echo e($isFr ? '/fr/naturalisation-francaise' : '/en/french-citizenship'); ?>" class="dropdown-item-link group/item">
                                <span><?php echo e($isFr ? 'Guide naturalisation' : 'Citizenship guide'); ?></span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/tcf-tef-ou-delf' : '/en/tcf-vs-tef-vs-delf'); ?>" class="dropdown-item-link group/item">
                                <span><?php echo e($isFr ? 'TCF, TEF ou DELF ?' : 'TCF vs TEF vs DELF'); ?></span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="<?php echo e($isFr ? '/fr/delf' : '/en/delf'); ?>" class="dropdown-item-link group/item">
                                <span><?php echo e($isFr ? 'Guide DELF' : 'DELF guide'); ?></span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 6. About (Direct Link) -->
            <a href="<?php echo e($isFr ? '/fr/a-propos' : '/about-us'); ?>" class="nav-pill-link">
                <?php echo e($isFr ? 'À propos' : 'About'); ?>

            </a>
        </nav>

        <!-- Right: Divider | Contact | FR | EN | Student Portal | CTA Button -->
        <div class="navbar-desktop-nav items-center gap-4 xl:gap-5 shrink-0 hidden">
            <!-- Vertical Divider -->
            <div class="h-5 w-[1px] bg-slate-200 mr-1 hidden xl:block"></div>

            <!-- Contact Link -->
            <a href="<?php echo e($isFr ? '/fr/contact' : '/contact-us'); ?>" class="nav-pill-link">Contact</a>

            <!-- FR | EN Language Switcher -->
            <div class="text-[13px] font-semibold text-slate-700 flex items-center gap-1 select-none">
                <a href="<?php echo e($frUrl); ?>" class="<?php echo e($isFr ? 'font-bold text-[#002654]' : 'text-slate-400 hover:text-[#0055A4]'); ?>">FR</a>
                <span class="text-slate-300 font-normal">|</span>
                <a href="<?php echo e($enUrl); ?>" class="<?php echo e(!$isFr ? 'font-bold text-[#002654]' : 'text-slate-400 hover:text-[#0055A4]'); ?>">EN</a>
            </div>

            <!-- Student Portal Link -->
            <a href="/demo" class="nav-pill-link whitespace-nowrap"><?php echo e($isFr ? 'Espace élève' : 'Student portal'); ?></a>

            <!-- Enroll Now / Dashboard CTA Button -->
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'super_admin' ? '/admin/dashboard' :
                    (auth()->user()->user_type === 'tutor' ? '/tutor/dashboard' : '/student/dashboard')); ?>" class="btn-enroll-pill">
                    DASHBOARD
                </a>
            <?php else: ?>
                <a href="/register" class="btn-enroll-pill">
                    <?php echo e($isFr ? 'S\'inscrire' : 'Enroll now'); ?>

                </a>
            <?php endif; ?>
        </div>

        <!-- Mobile Hamburger Button -->
        <button id="mobileMenuBtn" class="navbar-mobile-toggle p-2 text-[#002654] hover:text-[#0055A4] transition" aria-label="Toggle menu">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </header>
</div>

<!-- Mobile Menu Overlay -->
<div id="mobileMenu" class="fixed inset-0 hidden" style="z-index: 9999;">
    <!-- Backdrop -->
    <div class="absolute inset-0 backdrop-blur-md bg-[#001632]/60" id="mobileMenuBackdrop"></div>

    <!-- Drawer Content -->
    <div class="relative w-full h-full flex flex-col p-6 sm:p-8 bg-white overflow-y-auto" id="mobileMenuContent">
        <!-- Top French Tricolor Ribbon -->
        <div class="h-1.5 absolute top-0 left-0 right-0" style="background:linear-gradient(90deg,#0055A4 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>

        <!-- Header: Logo & Close Button -->
        <div class="flex justify-between items-center mb-8 mt-2">
            <a href="<?php echo e($isFr ? '/fr' : '/'); ?>" class="flex items-center gap-2.5 shrink-0">
                <img src="<?php echo e(asset('images/brand-mark.png')); ?>" alt="Fluence Française" class="brand-mark-img w-auto shrink-0">
                <span class="brand-wordmark">Fluence <span class="accent">Française</span></span>
            </a>
            <button id="closeMobileMenu" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-[#002654] transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Mobile Nav Links -->
        <nav class="flex flex-col flex-1 divide-y divide-slate-100">
            <!-- Accordion: France -->
            <div class="py-3">
                <button type="button" class="mobile-acc-btn w-full flex items-center justify-between text-base font-bold text-[#002654] text-left">
                    <span>France</span>
                    <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200"></i>
                </button>
                <div class="mobile-acc-content hidden pl-3 pt-2 flex flex-col space-y-2 text-sm text-slate-600">
                    <a href="<?php echo e($isFr ? '/fr/test-francais-naturalisation' : '/en/french-citizenship-language-test'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span><?php echo e($isFr ? 'Naturalisation B2' : 'Citizenship B2'); ?></span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/titre-de-sejour-niveau-francais#carte-de-resident' : '/en/french-residence-permit-language#resident-card'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span><?php echo e($isFr ? 'Carte de résident B1' : 'Resident card B1'); ?></span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/titre-de-sejour-niveau-francais#carte-pluriannuelle' : '/en/french-residence-permit-language#multi-year-permit'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span><?php echo e($isFr ? 'Carte pluriannuelle A2' : 'Multi-year permit A2'); ?></span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/examen-civique' : '/en/french-civic-exam'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span><?php echo e($isFr ? 'Examen civique' : 'Civic exam'); ?></span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/naturalisation-francaise' : '/en/french-citizenship'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span><?php echo e($isFr ? 'Guide naturalisation' : 'Citizenship guide'); ?></span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                </div>
            </div>

            <!-- Accordion: Switzerland -->
            <div class="py-3">
                <button type="button" class="mobile-acc-btn w-full flex items-center justify-between text-base font-bold text-[#002654] text-left">
                    <span><?php echo e($isFr ? 'Suisse' : 'Switzerland'); ?></span>
                    <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200"></i>
                </button>
                <div class="mobile-acc-content hidden pl-3 pt-2 flex flex-col space-y-2 text-sm text-slate-600">
                    <a href="<?php echo e($isFr ? '/fr/permis-c-suisse' : '/en/c-permit-switzerland'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span><?php echo e($isFr ? 'Permis C (A2 / B1)' : 'C permit (A2 / B1)'); ?></span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/naturalisation-suisse' : '/en/swiss-citizenship'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span><?php echo e($isFr ? 'Naturalisation suisse (B1)' : 'Swiss citizenship (B1)'); ?></span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/test-fide' : '/en/fide-test'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span><?php echo e($isFr ? 'Test fide' : 'fide test'); ?></span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                </div>
            </div>

            <!-- Accordion: Exams -->
            <div class="py-3">
                <button type="button" class="mobile-acc-btn w-full flex items-center justify-between text-base font-bold text-[#002654] text-left">
                    <span><?php echo e($isFr ? 'Examens' : 'Exams'); ?></span>
                    <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200"></i>
                </button>
                <div class="mobile-acc-content hidden pl-3 pt-2 flex flex-col space-y-2 text-sm text-slate-600">
                    <a href="<?php echo e($isFr ? '/fr/tcf-irn' : '/en/tcf-irn'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span>TCF IRN</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/tef-irn' : '/en/tef-irn'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span>TEF IRN</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/test-fide' : '/en/fide-test'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span><?php echo e($isFr ? 'Test fide' : 'fide test'); ?></span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/delf-a2' : '/en/delf-a2'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span>DELF A2</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/delf-b1' : '/en/delf-b1'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span>DELF B1</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/delf-b2' : '/en/delf-b2'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span>DELF B2</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/delf' : '/en/delf'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span><?php echo e($isFr ? 'Tous les niveaux DELF' : 'All DELF levels'); ?></span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/tcf-tef-ou-delf' : '/en/tcf-vs-tef-vs-delf'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span><?php echo e($isFr ? 'TCF, TEF ou DELF ?' : 'TCF vs TEF vs DELF'); ?></span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                </div>
            </div>

            <!-- Courses -->
            <div class="py-3">
                <a href="<?php echo e($isFr ? '/fr/cours-de-francais-en-ligne' : '/our-courses'); ?>" class="block text-base font-bold text-[#002654] hover:text-[#0055A4]">
                    <?php echo e($isFr ? 'Cours' : 'Courses'); ?>

                </a>
            </div>

            <!-- Accordion: Guides -->
            <div class="py-3">
                <button type="button" class="mobile-acc-btn w-full flex items-center justify-between text-base font-bold text-[#002654] text-left">
                    <span>Guides</span>
                    <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200"></i>
                </button>
                <div class="mobile-acc-content hidden pl-3 pt-2 flex flex-col space-y-2 text-sm text-slate-600">
                    <a href="<?php echo e($isFr ? '/fr/naturalisation-francaise' : '/en/french-citizenship'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span><?php echo e($isFr ? 'Guide naturalisation' : 'Citizenship guide'); ?></span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/tcf-tef-ou-delf' : '/en/tcf-vs-tef-vs-delf'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span><?php echo e($isFr ? 'TCF, TEF ou DELF ?' : 'TCF vs TEF vs DELF'); ?></span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="<?php echo e($isFr ? '/fr/delf' : '/en/delf'); ?>" class="py-1 hover:text-[#0055A4] flex justify-between">
                        <span><?php echo e($isFr ? 'Guide DELF' : 'DELF guide'); ?></span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                </div>
            </div>

            <!-- About -->
            <div class="py-3">
                <a href="<?php echo e($isFr ? '/fr/a-propos' : '/about-us'); ?>" class="block text-base font-bold text-[#002654] hover:text-[#0055A4]">
                    <?php echo e($isFr ? 'À propos' : 'About'); ?>

                </a>
            </div>

            <!-- Contact -->
            <div class="py-3">
                <a href="<?php echo e($isFr ? '/fr/contact' : '/contact-us'); ?>" class="block text-base font-bold text-[#002654] hover:text-[#0055A4]">
                    Contact
                </a>
            </div>

            <!-- Student Portal -->
            <div class="py-3">
                <a href="/demo" class="block text-base font-bold text-[#002654] hover:text-[#0055A4]">
                    <?php echo e($isFr ? 'Espace élève' : 'Student portal'); ?>

                </a>
            </div>
        </nav>

        <!-- Bottom Actions (Language Switch & Enroll Button) -->
        <div class="mt-6 pt-6 border-t border-slate-100 flex flex-col gap-4">
            <div class="flex items-center justify-center gap-3 text-sm font-bold text-slate-700">
                <span>Lang:</span>
                <a href="<?php echo e($frUrl); ?>" class="px-3 py-1 rounded <?php echo e($isFr ? 'bg-[#002654] text-white' : 'bg-slate-100 text-slate-700'); ?>">FR</a>
                <a href="<?php echo e($enUrl); ?>" class="px-3 py-1 rounded <?php echo e(!$isFr ? 'bg-[#002654] text-white' : 'bg-slate-100 text-slate-700'); ?>">EN</a>
            </div>

            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'super_admin' ? '/admin/dashboard' :
                    (auth()->user()->user_type === 'tutor' ? '/tutor/dashboard' : '/student/dashboard')); ?>" class="w-full py-3.5 bg-[#002654] text-white rounded-full text-center font-bold text-sm tracking-wide shadow-md">
                    DASHBOARD
                </a>
            <?php else: ?>
                <a href="/register" class="w-full py-3.5 bg-[#002654] text-white rounded-full text-center font-bold text-sm tracking-wide shadow-md">
                    <?php echo e($isFr ? 'S\'inscrire' : 'Enroll now'); ?>

                </a>
                <a href="/login" class="w-full py-2 text-center text-slate-500 font-bold text-sm tracking-wide hover:text-[#0055A4] transition">
                    LOGIN
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuBackdrop = document.getElementById('mobileMenuBackdrop');

        // Move mobile menu to body for proper z-index stacking above Vue app
        if (mobileMenu && document.body) {
            document.body.appendChild(mobileMenu);
        }

        function openMenu() {
            if (mobileMenu) {
                mobileMenu.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeMenu() {
            if (mobileMenu) {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openMenu);
        if (closeMobileMenu) closeMobileMenu.addEventListener('click', closeMenu);
        if (mobileMenuBackdrop) mobileMenuBackdrop.addEventListener('click', closeMenu);

        // Mobile accordion toggles
        const accBtns = document.querySelectorAll('.mobile-acc-btn');
        accBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const icon = this.querySelector('i');
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    if (icon) icon.classList.add('rotate-180');
                } else {
                    content.classList.add('hidden');
                    if (icon) icon.classList.remove('rotate-180');
                }
            });
        });

        // Auth Sync Check: If PHP says logged in but localStorage says no token, force sync
        const phpAuthState = <?php echo e(auth()->check() ? 'true' : 'false'); ?>;
        const hasLocalToken = !!localStorage.getItem('token');

        if (phpAuthState && !hasLocalToken) {
            fetch('/api/auth/sync-logout', {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' }
            }).then(() => {
                window.location.reload();
            }).catch(() => {
                window.location.reload();
            });
        }
    });
</script>
<?php /**PATH C:\Sushil Hemrom\fluencefrancaise\new\fluencefrancaise.fr\resources\views/partials/header.blade.php ENDPATH**/ ?>