@php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';

    // French homepage is /, English homepage is /en. Other French pages use /fr.
    $isFr = request()->is('fr*') || request()->is('/');
    $currentPath = request()->path();

    // Clean URLs for language toggle using Developer Guide pairs
    $routePairs = [
        'en' => '/',
        '' => '/en',
        'fr' => '/en',
        'en/tcf-irn' => '/fr/tcf-irn',
        'fr/tcf-irn' => '/en/tcf-irn',
        'en/french-citizenship-language-test' => '/fr/test-francais-naturalisation',
        'fr/test-francais-naturalisation' => '/en/french-citizenship-language-test',
        'en/french-civic-exam' => '/fr/examen-civique',
        'fr/examen-civique' => '/en/french-civic-exam',
        'en/fide-test' => '/fr/test-fide',
        'fr/test-fide' => '/en/fide-test',
        'en/tef-irn' => '/fr/tef-irn',
        'fr/tef-irn' => '/en/tef-irn',
        'en/delf-b2' => '/fr/delf-b2',
        'fr/delf-b2' => '/en/delf-b2',
        'en/delf-b1' => '/fr/delf-b1',
        'fr/delf-b1' => '/en/delf-b1',
        'en/delf-a2' => '/fr/delf-a2',
        'fr/delf-a2' => '/en/delf-a2',
        'en/french-residence-permit-language' => '/fr/titre-de-sejour-niveau-francais',
        'fr/titre-de-sejour-niveau-francais' => '/en/french-residence-permit-language',
        'en/c-permit-switzerland' => '/fr/permis-c-suisse',
        'fr/permis-c-suisse' => '/en/c-permit-switzerland',
        'en/swiss-citizenship' => '/fr/naturalisation-suisse',
        'fr/naturalisation-suisse' => '/en/swiss-citizenship',
        'en/online-french-classes' => '/fr/cours-de-francais-en-ligne',
        'fr/cours-de-francais-en-ligne' => '/en/online-french-classes',
        'en/delf' => '/fr/delf',
        'fr/delf' => '/en/delf',
        'en/tcf-vs-tef-vs-delf' => '/fr/tcf-tef-ou-delf',
        'fr/tcf-tef-ou-delf' => '/en/tcf-vs-tef-vs-delf',
        'en/french-citizenship' => '/fr/naturalisation-francaise',
        'fr/naturalisation-francaise' => '/en/french-citizenship',
        'en/about' => '/fr/a-propos',
        'fr/a-propos' => '/en/about',
        'en/contact' => '/fr/contact',
        'fr/contact' => '/en/contact',
    ];

    $trimmedCurrent = trim($currentPath, '/');
    if (isset($routePairs[$trimmedCurrent])) {
        $target = $routePairs[$trimmedCurrent];
        $frUrl = $isFr ? '/' . $trimmedCurrent : $target;
        $enUrl = $isFr ? $target : '/' . $trimmedCurrent;
    } elseif ($isFr) {
        $cleanPath = preg_replace('#^fr/?#', '', $currentPath);
        $frUrl = '/' . ltrim($currentPath, '/');
        $enUrl = $cleanPath ? '/en/' . ltrim($cleanPath, '/') : '/en';
    } elseif (request()->is('en*')) {
        $cleanPath = preg_replace('#^en/?#', '', $currentPath);
        $enUrl = '/' . ltrim($currentPath, '/');
        $frUrl = $cleanPath ? '/fr/' . ltrim($cleanPath, '/') : '/';
    } else {
        $enUrl = '/en';
        $frUrl = '/';
    }

    // Determine active menu sections based on current path
    $isFranceActive = request()->is('why-french*') ||
        request()->is('*french-citizenship-language-test*') ||
        request()->is('*test-francais-naturalisation*') ||
        request()->is('*french-residence-permit-language*') ||
        request()->is('*titre-de-sejour-niveau-francais*') ||
        request()->is('*french-civic-exam*') ||
        request()->is('*examen-civique*') ||
        (request()->is('*french-citizenship*') && !request()->is('*french-citizenship-language-test*')) ||
        request()->is('*naturalisation-francaise*');

    $isSwissActive = request()->is('*c-permit-switzerland*') ||
        request()->is('*permis-c-suisse*') ||
        request()->is('*swiss-citizenship*') ||
        request()->is('*naturalisation-suisse*') ||
        request()->is('*fide-test*') ||
        request()->is('*test-fide*');

    $isExamsActive = request()->is('tef-tcf*') ||
        request()->is('*tcf-irn*') ||
        request()->is('*tef-irn*') ||
        request()->is('*delf-a2*') ||
        request()->is('*delf-b1*') ||
        request()->is('*delf-b2*') ||
        (request()->is('*delf*') && !request()->is('*guide*') && !request()->is('*tcf-vs-tef-vs-delf*') && !request()->is('*tcf-tef-ou-delf*'));

    $isCoursesActive = request()->is('*our-courses*') ||
        request()->is('*online-french-classes*') ||
        request()->is('*cours-de-francais-en-ligne*') ||
        request()->is('beginner*') ||
        request()->is('elementary*') ||
        request()->is('intermediate*') ||
        request()->is('upper-intermediate*');

    $isGuidesActive = request()->is('*citizenship-guide*') ||
        (request()->is('*french-citizenship*') && !request()->is('*language-test*')) ||
        request()->is('*naturalisation-francaise*') ||
        request()->is('*tcf-vs-tef-vs-delf*') ||
        request()->is('*tcf-tef-ou-delf*') ||
        request()->is('*/delf') ||
        request()->is('delf');

    $isAboutActive = request()->is('about-us') ||
        request()->is('*about*') ||
        request()->is('*a-propos*');

    $isContactActive = request()->is('contact-us') ||
        request()->is('*contact*');

    $isPortalActive = request()->is('demo*');
@endphp

<style>
    .header-pill {
        height: 60px !important;
        background-color: #ffffff !important;
        box-shadow: 0 4px 20px -2px rgba(0, 38, 84, 0.08) !important;
        border: 1px solid #f1f5f9 !important;
        border-radius: 9999px !important;
        pointer-events: auto;
        transition: background-color 0.25s ease, backdrop-filter 0.25s ease, -webkit-backdrop-filter 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
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
        position: relative;
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
        position: relative;
    }

    /* Inner Container for Submenu Section Buttons (Text + Chevron Arrow) */
    .nav-item-inner {
        position: relative;
        display: inline-flex;
        align-items: center;
        transition: color 0.2s ease;
    }

    /* Text Label Container for Direct Links */
    .nav-label {
        position: relative;
        display: inline-block;
        transition: color 0.2s ease;
    }

    /* Active, Hover & Dropdown-Open States: Blue text */
    .nav-pill-link.active-nav,
    .nav-item-btn.active-nav,
    .nav-pill-link.active-nav .nav-label,
    .nav-item-btn.active-nav .nav-item-inner,
    .nav-item-btn.active-nav .nav-label,
    .nav-pill-link:hover,
    .nav-item-btn:hover,
    .nav-dropdown-group:hover .nav-item-btn,
    .nav-dropdown-group.is-clicked-open .nav-item-btn,
    .nav-pill-link:hover .nav-label,
    .nav-item-btn:hover .nav-item-inner,
    .nav-item-btn:hover .nav-label,
    .nav-dropdown-group:hover .nav-item-inner,
    .nav-dropdown-group.is-clicked-open .nav-item-inner {
        color: #0055A4 !important;
    }

    .nav-item-btn.active-nav .chevron-icon,
    .nav-item-btn:hover .chevron-icon,
    .nav-dropdown-group:hover .chevron-icon,
    .nav-dropdown-group.is-clicked-open .chevron-icon {
        color: #0055A4 !important;
        transform: rotate(180deg);
    }

    /* INCREASED ACTIVE BORDER WIDTH: Spans full width of submenu item (text + chevron) + direct links */
    .nav-pill-link.active-nav .nav-label::after,
    .nav-item-btn.active-nav .nav-item-inner::after,
    .nav-item-btn.active-nav .nav-label::after,
    .nav-pill-link:hover .nav-label::after,
    .nav-item-btn:hover .nav-item-inner::after,
    .nav-item-btn:hover .nav-label::after,
    .nav-dropdown-group:hover .nav-item-inner::after,
    .nav-dropdown-group.is-clicked-open .nav-item-inner::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: -4px;
        height: 2px;
        background-color: #EF4135;
        border-radius: 2px;
    }

    .dropdown-card {
        background-color: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 16px 36px -4px rgba(0, 38, 84, 0.12), 0 4px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid #f1f5f9;
        padding: 0.875rem 0.875rem;
        min-width: 275px;
    }

    .dropdown-item-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.55rem 0.65rem;
        border-radius: 0.5rem;
        font-size: 13.5px;
        font-weight: 500;
        color: #334155;
        text-decoration: none;
        transition: all 0.15s ease-in-out;
    }

    .dropdown-item-link:hover {
        color: #0055A4 !important;
        background-color: #f8fafc !important;
    }

    .dropdown-item-link:hover .arrow-icon {
        color: #0055A4 !important;
        transform: translate(2px, -2px);
    }

    /* Active Submenu Item */
    .dropdown-item-link.active-submenu {
        color: #0055A4 !important;
        background-color: #eff6ff !important;
        font-weight: 600 !important;
    }

    .dropdown-item-link.active-submenu .arrow-icon {
        color: #0055A4 !important;
        transform: translate(1px, -1px);
    }

    /* Dropdown hover and click-open triggers */
    .nav-dropdown-group:hover .dropdown-menu-wrapper,
    .nav-dropdown-group.is-clicked-open .dropdown-menu-wrapper {
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(0) !important;
    }

    .nav-dropdown-group:hover .chevron-icon,
    .nav-dropdown-group.is-clicked-open .chevron-icon {
        transform: rotate(180deg) !important;
        color: #0055A4 !important;
    }

    .btn-enroll-pill {
        font-family: 'DM Sans', system-ui, sans-serif;
        font-size: 14px !important;
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
        position: fixed !important;
        top: 24px !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        z-index: 50 !important;
        pointer-events: none;
        transition: top 0.25s ease;
    }

    /* Sticky on scroll with blur background */
    .header-container.is-scrolled {
        top: 12px !important;
    }

    .header-container.is-scrolled .header-pill {
        background-color: rgba(255, 255, 255, 0.82) !important;
        backdrop-filter: blur(16px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(16px) saturate(180%) !important;
        box-shadow: 0 10px 30px -4px rgba(0, 38, 84, 0.12), 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        border-color: rgba(241, 245, 249, 0.75) !important;
    }

    .dropdown-menu-wrapper {
        pointer-events: auto;
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
            display: flex !important;
        }
    }

    @media (max-width: 768px) {
        .header-container {
            top: 16px !important;
        }

        .header-container.is-scrolled {
            top: 10px !important;
        }

        .header-pill {
            height: 56px !important;
        }

        .brand-wordmark {
            font-size: 0.9375rem;
        }

        .brand-mark-img {
            height: 30px;
        }
    }
</style>

<div class="header-container global-header w-full z-50 px-4 sm:px-6 lg:px-8">
    <header class="header-pill max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between">
        <!-- Left: Logo & Wordmark -->
        <div class="flex items-center shrink-0">
            <a href="{{ $isFr ? '/' : '/en' }}" class="flex items-center gap-2 sm:gap-2.5">
                <img src="{{ asset('images/brand-mark.png') }}" alt="Fluence Française"
                    class="brand-mark-img w-auto shrink-0">
                <span class="brand-wordmark">Fluence <span class="accent">Française</span></span>
            </a>
            <!-- Divider after Logo (Desktop only) -->
            <div class="h-5 w-[1px] bg-slate-200 ml-4 mr-1 hidden xl:block"></div>
        </div>

        <!-- Middle: Nav Links with Dropdowns (Desktop) -->
        <nav class="navbar-desktop-nav items-center gap-5 xl:gap-6 mx-auto h-full hidden">
            <!-- 1. France Dropdown -->
            <div class="nav-dropdown-group relative group h-full flex items-center">
                <button type="button" class="nav-item-btn py-4 {{ $isFranceActive ? 'active-nav' : '' }}">
                    <span class="nav-item-inner gap-1">
                        <span>France</span>
                        <svg class="chevron-icon w-3 h-3 text-slate-600 transition-transform duration-200" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </button>
                <!-- Dropdown Card -->
                <div
                    class="dropdown-menu-wrapper absolute top-full left-0 pt-2 z-50 opacity-0 invisible translate-y-1.5 transition-all duration-200 ease-out">
                    <div class="dropdown-card">
                        <!-- Card Header -->
                        <div
                            class="flex items-center justify-between pb-2.5 mb-1.5 border-b border-slate-100 text-slate-900 font-bold text-[14px]">
                            <span>France</span>
                            <svg class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <!-- Card Items -->
                        <div class="flex flex-col space-y-0.5">
                            <a href="{{ $isFr ? '/fr/test-francais-naturalisation' : '/en/french-citizenship-language-test' }}"
                                class="dropdown-item-link group/item {{ (request()->is('*french-citizenship-language-test*') || request()->is('*test-francais-naturalisation*')) ? 'active-submenu' : '' }}">
                                <span>{{ $isFr ? 'Naturalisation — niveau B2' : 'Citizenship — B2 level' }}</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/titre-de-sejour-niveau-francais#carte-de-resident' : '/en/french-residence-permit-language#resident-card' }}"
                                class="dropdown-item-link group/item {{ request()->is('*resident-card*') ? 'active-submenu' : '' }}">
                                <span>{{ $isFr ? 'Carte de résident — B1' : 'Resident card — B1' }}</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/titre-de-sejour-niveau-francais#carte-pluriannuelle' : '/en/french-residence-permit-language#multi-year-permit' }}"
                                class="dropdown-item-link group/item {{ request()->is('*multi-year-permit*') ? 'active-submenu' : '' }}">
                                <span>{{ $isFr ? 'Carte pluriannuelle — A2' : 'Multi-year permit — A2' }}</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/examen-civique' : '/en/french-civic-exam' }}"
                                class="dropdown-item-link group/item {{ (request()->is('*french-civic-exam*') || request()->is('*examen-civique*')) ? 'active-submenu' : '' }}">
                                <span>{{ $isFr ? 'Examen civique' : 'Civic exam' }}</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/naturalisation-francaise' : '/en/french-citizenship' }}"
                                class="dropdown-item-link group/item {{ ((request()->is('*french-citizenship*') || request()->is('*naturalisation-francaise*')) && !request()->is('*language-test*')) ? 'active-submenu' : '' }}">
                                <span>{{ $isFr ? 'Guide de la naturalisation' : 'Citizenship guide' }}</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Switzerland Dropdown -->
            <div class="nav-dropdown-group relative group h-full flex items-center">
                <button type="button" class="nav-item-btn py-4 {{ $isSwissActive ? 'active-nav' : '' }}">
                    <span class="nav-item-inner gap-1">
                        <span>{{ $isFr ? 'Suisse' : 'Switzerland' }}</span>
                        <svg class="chevron-icon w-3 h-3 text-slate-600 transition-transform duration-200" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </button>
                <!-- Dropdown Card -->
                <div
                    class="dropdown-menu-wrapper absolute top-full left-0 pt-2 z-50 opacity-0 invisible translate-y-1.5 transition-all duration-200 ease-out">
                    <div class="dropdown-card">
                        <!-- Card Header -->
                        <div
                            class="flex items-center justify-between pb-2.5 mb-1.5 border-b border-slate-100 text-slate-900 font-bold text-[14px]">
                            <span>{{ $isFr ? 'Suisse' : 'Switzerland' }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <!-- Card Items -->
                        <div class="flex flex-col space-y-0.5">
                            <a href="{{ $isFr ? '/fr/permis-c-suisse' : '/en/c-permit-switzerland' }}"
                                class="dropdown-item-link group/item {{ (request()->is('*c-permit-switzerland*') || request()->is('*permis-c-suisse*')) ? 'active-submenu' : '' }}">
                                <span>{{ $isFr ? 'Permis C — A2 / B1' : 'C permit — A2 / B1' }}</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/naturalisation-suisse' : '/en/swiss-citizenship' }}"
                                class="dropdown-item-link group/item {{ (request()->is('*swiss-citizenship*') || request()->is('*naturalisation-suisse*')) ? 'active-submenu' : '' }}">
                                <span>{{ $isFr ? 'Naturalisation suisse — B1' : 'Swiss citizenship — B1' }}</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/test-fide' : '/en/fide-test' }}"
                                class="dropdown-item-link group/item {{ (request()->is('*fide-test*') || request()->is('*test-fide*')) ? 'active-submenu' : '' }}">
                                <span>{{ $isFr ? 'Test fide' : 'fide test' }}</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Exams Dropdown -->
            <div class="nav-dropdown-group relative group h-full flex items-center">
                <button type="button" class="nav-item-btn py-4 {{ $isExamsActive ? 'active-nav' : '' }}">
                    <span class="nav-item-inner gap-1">
                        <span>{{ $isFr ? 'Examens' : 'Exams' }}</span>
                        <svg class="chevron-icon w-3 h-3 text-slate-600 transition-transform duration-200" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </button>
                <!-- Dropdown Card -->
                <div
                    class="dropdown-menu-wrapper absolute top-full left-0 pt-2 z-50 opacity-0 invisible translate-y-1.5 transition-all duration-200 ease-out">
                    <div class="dropdown-card">
                        <!-- Card Header -->
                        <div
                            class="flex items-center justify-between pb-2.5 mb-1.5 border-b border-slate-100 text-slate-900 font-bold text-[14px]">
                            <span>{{ $isFr ? 'Examens' : 'Exams' }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <!-- Card Items -->
                        <div class="flex flex-col space-y-0.5">
                            <a href="{{ $isFr ? '/fr/tcf-irn' : '/en/tcf-irn' }}"
                                class="dropdown-item-link group/item {{ request()->is('*tcf-irn*') ? 'active-submenu' : '' }}">
                                <span>TCF IRN</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/tef-irn' : '/en/tef-irn' }}"
                                class="dropdown-item-link group/item {{ request()->is('*tef-irn*') ? 'active-submenu' : '' }}">
                                <span>TEF IRN</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/test-fide' : '/en/fide-test' }}"
                                class="dropdown-item-link group/item {{ (request()->is('*fide-test*') || request()->is('*test-fide*')) ? 'active-submenu' : '' }}">
                                <span>{{ $isFr ? 'Test fide' : 'fide test' }}</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/delf-a2' : '/en/delf-a2' }}"
                                class="dropdown-item-link group/item {{ request()->is('*delf-a2*') ? 'active-submenu' : '' }}">
                                <span>DELF A2</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/delf-b1' : '/en/delf-b1' }}"
                                class="dropdown-item-link group/item {{ request()->is('*delf-b1*') ? 'active-submenu' : '' }}">
                                <span>DELF B1</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/delf-b2' : '/en/delf-b2' }}"
                                class="dropdown-item-link group/item {{ request()->is('*delf-b2*') ? 'active-submenu' : '' }}">
                                <span>DELF B2</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/delf' : '/en/delf' }}"
                                class="dropdown-item-link group/item {{ (request()->is('*delf') || request()->is('*/delf/*')) ? 'active-submenu' : '' }}">
                                <span>{{ $isFr ? 'Tous les niveaux du DELF' : 'All DELF levels' }}</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/tcf-tef-ou-delf' : '/en/tcf-vs-tef-vs-delf' }}"
                                class="dropdown-item-link group/item {{ (request()->is('*tcf-vs-tef-vs-delf*') || request()->is('*tcf-tef-ou-delf*')) ? 'active-submenu' : '' }}">
                                <span>{{ $isFr ? 'Comparer : TCF, TEF ou DELF ?' : 'Compare: TCF, TEF or DELF?' }}</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Courses (Direct Link) -->
            <a href="{{ $isFr ? '/fr/cours-de-francais-en-ligne' : '/en/online-french-classes' }}"
                class="nav-pill-link {{ $isCoursesActive ? 'active-nav' : '' }}">
                <span class="nav-label">{{ $isFr ? 'Cours' : 'Courses' }}</span>
            </a>

            <!-- 5. Guides Dropdown -->
            <div class="nav-dropdown-group relative group h-full flex items-center">
                <button type="button" class="nav-item-btn py-4 {{ $isGuidesActive ? 'active-nav' : '' }}">
                    <span class="nav-item-inner gap-1">
                        <span>Guides</span>
                        <svg class="chevron-icon w-3 h-3 text-slate-600 transition-transform duration-200" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </button>
                <!-- Dropdown Card -->
                <div
                    class="dropdown-menu-wrapper absolute top-full left-0 pt-2 z-50 opacity-0 invisible translate-y-1.5 transition-all duration-200 ease-out">
                    <div class="dropdown-card">
                        <!-- Card Header -->
                        <div
                            class="flex items-center justify-between pb-2.5 mb-1.5 border-b border-slate-100 text-slate-900 font-bold text-[14px]">
                            <span>Guides</span>
                            <svg class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <!-- Card Items -->
                        <div class="flex flex-col space-y-0.5">
                            <a href="{{ $isFr ? '/fr/naturalisation-francaise' : '/en/french-citizenship' }}"
                                class="dropdown-item-link group/item {{ ((request()->is('*french-citizenship*') || request()->is('*naturalisation-francaise*')) && !request()->is('*language-test*')) ? 'active-submenu' : '' }}">
                                <span>{{ $isFr ? 'Naturalisation française : le guide' : 'French citizenship guide' }}</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/tcf-tef-ou-delf' : '/en/tcf-vs-tef-vs-delf' }}"
                                class="dropdown-item-link group/item {{ (request()->is('*tcf-vs-tef-vs-delf*') || request()->is('*tcf-tef-ou-delf*')) ? 'active-submenu' : '' }}">
                                <span>{{ $isFr ? 'TCF, TEF ou DELF ?' : 'TCF, TEF or DELF?' }}</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                            <a href="{{ $isFr ? '/fr/delf' : '/en/delf' }}"
                                class="dropdown-item-link group/item {{ (request()->is('*delf*') && !request()->is('*tcf*') && !request()->is('*delf-a2*') && !request()->is('*delf-b1*') && !request()->is('*delf-b2*')) ? 'active-submenu' : '' }}">
                                <span>{{ $isFr ? 'DELF : le guide' : 'DELF guide' }}</span>
                                <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 6. About (Direct Link) -->
            <a href="{{ $isFr ? '/fr/a-propos' : '/en/about' }}"
                class="nav-pill-link {{ $isAboutActive ? 'active-nav' : '' }}">
                <span class="nav-label">{{ $isFr ? 'À propos' : 'About' }}</span>
            </a>
        </nav>

        <!-- Right: Desktop Navigation Items -->
        <div class="navbar-desktop-nav items-center gap-4 xl:gap-5 shrink-0 hidden">
            <!-- Vertical Divider -->
            <div class="h-5 w-[1px] bg-slate-200 mr-1 hidden xl:block"></div>

            <!-- Contact Link -->
            <a href="{{ $isFr ? '/fr/contact' : '/en/contact' }}"
                class="nav-pill-link {{ $isContactActive ? 'active-nav' : '' }}">
                <span class="nav-label">Contact</span>
            </a>

            <!-- FR | EN Language Switcher (Desktop) -->
            <div class="text-[13px] font-bold text-[#002654] flex items-center gap-1 select-none">
                <a href="{{ $frUrl }}"
                    class="{{ $isFr ? 'text-[#002654]' : 'text-slate-400 hover:text-[#0055A4]' }}">FR</a>
                <span class="text-slate-300 font-normal">|</span>
                <a href="{{ $enUrl }}"
                    class="{{ !$isFr ? 'text-[#002654]' : 'text-slate-400 hover:text-[#0055A4]' }}">EN</a>
            </div>

            <!-- Student Portal Link -->
            <a href="/demo" class="nav-pill-link whitespace-nowrap {{ $isPortalActive ? 'active-nav' : '' }}">
                <span class="nav-label">{{ $isFr ? 'Espace élève' : 'Student portal' }}</span>
            </a>

            <!-- Enroll Now / Dashboard CTA Button -->
            @auth
                    <a href="{{
                auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'super_admin' ? '/admin/dashboard' :
                (auth()->user()->user_type === 'tutor' ? '/tutor/dashboard' : '/student/dashboard')
                                                                                                                                                                                                                                                                                                                                                                }}"
                        class="btn-enroll-pill">
                        DASHBOARD
                    </a>
            @else
                <a href="/register" class="btn-enroll-pill">
                    {{ $isFr ? 'S\'inscrire' : 'Enroll now' }}
                </a>
            @endauth
        </div>

        <!-- Right: Mobile View (FR | EN and 3-line Hamburger Button matching Image 3) -->
        <div class="navbar-mobile-toggle flex items-center gap-3.5 sm:gap-4 shrink-0">
            <!-- FR | EN on Mobile Header Bar -->
            <div
                class="text-[14px] sm:text-[15px] font-bold text-[#002654] flex items-center gap-1.5 select-none tracking-tight">
                <a href="{{ $frUrl }}"
                    class="{{ $isFr ? 'text-[#002654]' : 'text-slate-400 hover:text-[#0055A4]' }}">FR</a>
                <span class="text-slate-300 font-normal">|</span>
                <a href="{{ $enUrl }}"
                    class="{{ !$isFr ? 'text-[#002654]' : 'text-slate-400 hover:text-[#0055A4]' }}">EN</a>
            </div>

            <!-- Sleek 3-line Hamburger Button -->
            <button id="mobileMenuBtn"
                class="flex flex-col justify-center items-center gap-[4.5px] w-8 h-8 p-1 text-[#002654] hover:text-[#0055A4] transition cursor-pointer"
                aria-label="Toggle menu">
                <span class="w-5 h-[2px] bg-[#002654] rounded-full transition-transform"></span>
                <span class="w-5 h-[2px] bg-[#002654] rounded-full transition-opacity"></span>
                <span class="w-5 h-[2px] bg-[#002654] rounded-full transition-transform"></span>
            </button>
        </div>
    </header>
</div>

<!-- Mobile Menu Overlay -->
<div id="mobileMenu" class="fixed inset-0 hidden" style="z-index: 9999;">
    <!-- Backdrop -->
    <div class="absolute inset-0 backdrop-blur-md bg-[#001632]/60" id="mobileMenuBackdrop"></div>

    <!-- Drawer Content -->
    <div class="relative w-full h-full flex flex-col p-6 sm:p-8 bg-white overflow-y-auto" id="mobileMenuContent">
        <!-- Top French Tricolor Ribbon -->
        <div class="h-1.5 absolute top-0 left-0 right-0"
            style="background:linear-gradient(90deg,#0055A4 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>

        <!-- Header: Logo & Close Button -->
        <div class="flex justify-between items-center mb-8 mt-2">
            <a href="{{ $isFr ? '/' : '/en' }}" class="flex items-center gap-2.5 shrink-0">
                <img src="{{ asset('images/brand-mark.png') }}" alt="Fluence Française"
                    class="brand-mark-img w-auto shrink-0">
                <span class="brand-wordmark">Fluence <span class="accent">Française</span></span>
            </a>
            <button id="closeMobileMenu"
                class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-[#002654] transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Mobile Nav Links -->
        <nav class="flex flex-col flex-1 divide-y divide-slate-100">
            <!-- Accordion: France -->
            <div class="py-3">
                <button type="button"
                    class="mobile-acc-btn w-full flex items-center justify-between text-base font-bold {{ $isFranceActive ? 'text-[#0055A4]' : 'text-[#002654]' }} text-left">
                    <span>France</span>
                    <i
                        class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200 {{ $isFranceActive ? 'rotate-180' : '' }}"></i>
                </button>
                <div
                    class="mobile-acc-content {{ $isFranceActive ? '' : 'hidden' }} pl-3 pt-2 flex flex-col space-y-1 text-sm text-slate-600">
                    <a href="{{ $isFr ? '/fr/test-francais-naturalisation' : '/en/french-citizenship-language-test' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ (request()->is('*french-citizenship-language-test*') || request()->is('*test-francais-naturalisation*')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>{{ $isFr ? 'Naturalisation — niveau B2' : 'Citizenship — B2 level' }}</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/titre-de-sejour-niveau-francais#carte-de-resident' : '/en/french-residence-permit-language#resident-card' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ request()->is('*resident-card*') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>{{ $isFr ? 'Carte de résident — B1' : 'Resident card — B1' }}</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/titre-de-sejour-niveau-francais#carte-pluriannuelle' : '/en/french-residence-permit-language#multi-year-permit' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ request()->is('*multi-year-permit*') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>{{ $isFr ? 'Carte pluriannuelle — A2' : 'Multi-year permit — A2' }}</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/examen-civique' : '/en/french-civic-exam' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ (request()->is('*french-civic-exam*') || request()->is('*examen-civique*')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>{{ $isFr ? 'Examen civique' : 'Civic exam' }}</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/naturalisation-francaise' : '/en/french-citizenship' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ ((request()->is('*french-citizenship*') || request()->is('*naturalisation-francaise*')) && !request()->is('*language-test*')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>{{ $isFr ? 'Guide de la naturalisation' : 'Citizenship guide' }}</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                </div>
            </div>

            <!-- Accordion: Switzerland -->
            <div class="py-3">
                <button type="button"
                    class="mobile-acc-btn w-full flex items-center justify-between text-base font-bold {{ $isSwissActive ? 'text-[#0055A4]' : 'text-[#002654]' }} text-left">
                    <span>{{ $isFr ? 'Suisse' : 'Switzerland' }}</span>
                    <i
                        class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200 {{ $isSwissActive ? 'rotate-180' : '' }}"></i>
                </button>
                <div
                    class="mobile-acc-content {{ $isSwissActive ? '' : 'hidden' }} pl-3 pt-2 flex flex-col space-y-1 text-sm text-slate-600">
                    <a href="{{ $isFr ? '/fr/permis-c-suisse' : '/en/c-permit-switzerland' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ (request()->is('*c-permit-switzerland*') || request()->is('*permis-c-suisse*')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>{{ $isFr ? 'Permis C — A2 / B1' : 'C permit — A2 / B1' }}</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/naturalisation-suisse' : '/en/swiss-citizenship' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ (request()->is('*swiss-citizenship*') || request()->is('*naturalisation-suisse*')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>{{ $isFr ? 'Naturalisation suisse — B1' : 'Swiss citizenship — B1' }}</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/test-fide' : '/en/fide-test' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ (request()->is('*fide-test*') || request()->is('*test-fide*')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>{{ $isFr ? 'Test fide' : 'fide test' }}</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                </div>
            </div>

            <!-- Accordion: Exams -->
            <div class="py-3">
                <button type="button"
                    class="mobile-acc-btn w-full flex items-center justify-between text-base font-bold {{ $isExamsActive ? 'text-[#0055A4]' : 'text-[#002654]' }} text-left">
                    <span>{{ $isFr ? 'Examens' : 'Exams' }}</span>
                    <i
                        class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200 {{ $isExamsActive ? 'rotate-180' : '' }}"></i>
                </button>
                <div
                    class="mobile-acc-content {{ $isExamsActive ? '' : 'hidden' }} pl-3 pt-2 flex flex-col space-y-1 text-sm text-slate-600">
                    <a href="{{ $isFr ? '/fr/tcf-irn' : '/en/tcf-irn' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ request()->is('*tcf-irn*') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>TCF IRN</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/tef-irn' : '/en/tef-irn' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ request()->is('*tef-irn*') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>TEF IRN</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/test-fide' : '/en/fide-test' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ (request()->is('*fide-test*') || request()->is('*test-fide*')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>{{ $isFr ? 'Test fide' : 'fide test' }}</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/delf-a2' : '/en/delf-a2' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ request()->is('*delf-a2*') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>DELF A2</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/delf-b1' : '/en/delf-b1' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ request()->is('*delf-b1*') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>DELF B1</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/delf-b2' : '/en/delf-b2' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ request()->is('*delf-b2*') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>DELF B2</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/delf' : '/en/delf' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ (request()->is('*delf') || request()->is('*/delf/*')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>{{ $isFr ? 'Tous les niveaux du DELF' : 'All DELF levels' }}</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/tcf-tef-ou-delf' : '/en/tcf-vs-tef-vs-delf' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ (request()->is('*tcf-vs-tef-vs-delf*') || request()->is('*tcf-tef-ou-delf*')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>{{ $isFr ? 'Comparer : TCF, TEF ou DELF ?' : 'Compare: TCF, TEF or DELF?' }}</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                </div>
            </div>

            <!-- Courses -->
            <div class="py-3">
                <a href="{{ $isFr ? '/fr/cours-de-francais-en-ligne' : '/en/online-french-classes' }}"
                    class="block text-base font-bold {{ $isCoursesActive ? 'text-[#0055A4]' : 'text-[#002654]' }} hover:text-[#0055A4]">
                    {{ $isFr ? 'Cours' : 'Courses' }}
                </a>
            </div>

            <!-- Accordion: Guides -->
            <div class="py-3">
                <button type="button"
                    class="mobile-acc-btn w-full flex items-center justify-between text-base font-bold {{ $isGuidesActive ? 'text-[#0055A4]' : 'text-[#002654]' }} text-left">
                    <span>Guides</span>
                    <i
                        class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200 {{ $isGuidesActive ? 'rotate-180' : '' }}"></i>
                </button>
                <div
                    class="mobile-acc-content {{ $isGuidesActive ? '' : 'hidden' }} pl-3 pt-2 flex flex-col space-y-1 text-sm text-slate-600">
                    <a href="{{ $isFr ? '/fr/naturalisation-francaise' : '/en/french-citizenship' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ ((request()->is('*french-citizenship*') || request()->is('*naturalisation-francaise*')) && !request()->is('*language-test*')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>{{ $isFr ? 'Naturalisation française : le guide' : 'French citizenship guide' }}</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/tcf-tef-ou-delf' : '/en/tcf-vs-tef-vs-delf' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ (request()->is('*tcf-vs-tef-vs-delf*') || request()->is('*tcf-tef-ou-delf*')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>{{ $isFr ? 'TCF, TEF ou DELF ?' : 'TCF, TEF or DELF?' }}</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                    <a href="{{ $isFr ? '/fr/delf' : '/en/delf' }}"
                        class="py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center {{ (request()->is('*delf*') && !request()->is('*tcf*') && !request()->is('*delf-a2*') && !request()->is('*delf-b1*') && !request()->is('*delf-b2*')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '' }}">
                        <span>{{ $isFr ? 'DELF : le guide' : 'DELF guide' }}</span>
                        <span class="text-xs text-slate-400">↗</span>
                    </a>
                </div>
            </div>

            <!-- About -->
            <div class="py-3">
                <a href="{{ $isFr ? '/fr/a-propos' : '/en/about' }}"
                    class="block text-base font-bold {{ $isAboutActive ? 'text-[#0055A4]' : 'text-[#002654]' }} hover:text-[#0055A4]">
                    {{ $isFr ? 'À propos' : 'About' }}
                </a>
            </div>

            <!-- Contact -->
            <div class="py-3">
                <a href="{{ $isFr ? '/fr/contact' : '/en/contact' }}"
                    class="block text-base font-bold {{ $isContactActive ? 'text-[#0055A4]' : 'text-[#002654]' }} hover:text-[#0055A4]">
                    Contact
                </a>
            </div>

            <!-- Student Portal -->
            <div class="py-3">
                <a href="/demo"
                    class="block text-base font-bold {{ $isPortalActive ? 'text-[#0055A4]' : 'text-[#002654]' }} hover:text-[#0055A4]">
                    {{ $isFr ? 'Espace élève' : 'Student portal' }}
                </a>
            </div>
        </nav>

        <!-- Bottom Actions (Enroll Button & Login) -->
        <div class="mt-6 pt-6 border-t border-slate-100 flex flex-col gap-3">
            @auth
                    <a href="{{
                auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'super_admin' ? '/admin/dashboard' :
                (auth()->user()->user_type === 'tutor' ? '/tutor/dashboard' : '/student/dashboard')
                                                                                                                                                                                                                                                                                                                                                                }}"
                        class="w-full py-3.5 bg-[#002654] text-white rounded-full text-center font-bold text-sm tracking-wide shadow-md">
                        DASHBOARD
                    </a>
            @else
                <a href="/register"
                    class="w-full py-3.5 bg-[#002654] text-white rounded-full text-center font-bold text-sm tracking-wide shadow-md">
                    {{ $isFr ? 'S\'inscrire' : 'Enroll now' }}
                </a>
                <a href="/login"
                    class="w-full py-2 text-center text-slate-500 font-bold text-sm tracking-wide hover:text-[#0055A4] transition">
                    LOGIN
                </a>
            @endauth
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

        // Mobile accordion toggles (mutually exclusive: opening one closes all other open items)
        const accBtns = document.querySelectorAll('.mobile-acc-btn');
        accBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const content = this.nextElementSibling;
                const icon = this.querySelector('i');
                const isCurrentlyOpen = content && !content.classList.contains('hidden');

                // Close all other accordions first
                accBtns.forEach(otherBtn => {
                    const otherContent = otherBtn.nextElementSibling;
                    const otherIcon = otherBtn.querySelector('i');
                    if (otherContent) otherContent.classList.add('hidden');
                    if (otherIcon) otherIcon.classList.remove('rotate-180');
                });

                // If this accordion was not open, open it now (if it was open, it remains closed)
                if (!isCurrentlyOpen && content) {
                    content.classList.remove('hidden');
                    if (icon) icon.classList.add('rotate-180');
                }
            });
        });

        // Desktop Click-to-toggle for Dropdowns
        const dropdownGroups = document.querySelectorAll('.nav-dropdown-group');
        dropdownGroups.forEach(group => {
            const btn = group.querySelector('.nav-item-btn');
            if (btn) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isOpen = group.classList.contains('is-clicked-open');
                    dropdownGroups.forEach(g => g.classList.remove('is-clicked-open'));
                    if (!isOpen) {
                        group.classList.add('is-clicked-open');
                    }
                });
            }
        });

        // Close dropdowns on outside click
        document.addEventListener('click', function () {
            dropdownGroups.forEach(g => g.classList.remove('is-clicked-open'));
        });

        // Prevent click inside dropdown card from closing it
        document.querySelectorAll('.dropdown-card').forEach(card => {
            card.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        });

        // Sticky navbar with blur on scroll
        const headerEl = document.querySelector('.header-container');
        const updateHeaderScroll = () => {
            if (!headerEl) return;
            if (window.scrollY > 20) {
                headerEl.classList.add('is-scrolled');
            } else {
                headerEl.classList.remove('is-scrolled');
            }
        };
        window.addEventListener('scroll', updateHeaderScroll, { passive: true });
        updateHeaderScroll();

        // Auth Sync Check: If PHP says logged in but localStorage says no token, force sync
        const phpAuthState = {{ auth()->check() ? 'true' : 'false' }};
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