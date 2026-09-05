@php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
@endphp

<style>
    .main-header {
        height: 66px !important;
        display: flex !important;
        align-items: center !important;
        box-sizing: border-box !important;
        background-color: #ffffff !important;
    }
    .header-inner {
        height: 100% !important;
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
    }
    .brand-wordmark {
        font-family: 'Urbanist', system-ui, sans-serif;
        font-weight: 400;
        font-size: 18px;
        letter-spacing: -0.01em;
        color: #002654;
        line-height: 1;
        white-space: nowrap;
    }
    .brand-wordmark .accent { color: #EF4135; }
    .brand-flag { display:inline-flex; height:25px; width:8px; border-radius:2px; overflow:hidden; box-shadow:0 1px 2px rgba(0,0,0,.12); }
    .brand-mark-img { height: 40px; }
    /* Slightly smaller on phones so the wordmark still clears the hamburger. */
    @media (max-width: 768px) {
        .brand-wordmark { font-size: 0.9375rem; }
        .brand-mark-img { height: 32px; }
        .brand-flag { height:23px; width:7px; }
    }
    .nav-link {
        font-size: 13px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        color: #334155 !important;
        transition: all 0.2s ease;
        display: flex !important;
        align-items: center !important;
        height: 100% !important;
        text-decoration: none !important;
        position: relative;
    }
    .nav-link:hover { color: #0055A4 !important; }
    .active-link { color: #0055A4 !important; }
    .active-link::after {
        content: ""; position: absolute; left: 0; right: 0; bottom: 14px; height: 2px;
        background: #EF4135; border-radius: 2px;
    }

    .action-btn-login {
        font-size: 12px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        color: #0055A4 !important;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }
    .action-btn-login:hover { color: #002654 !important; }
    .action-btn-signup {
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
        background-color: #0055A4 !important;
        color: white !important;
        padding: 10px 30px !important;
        border-radius: 9999px !important;
        transition: all 0.25s ease !important;
        box-shadow: 0 8px 20px -8px rgba(0,85,164,0.7) !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
    }
    .action-btn-signup:hover {
        transform: translateY(-2px);
        background-color: #003d7a !important;
    }

    @media (min-width: 1351px) {
        .mobile-hidden { display: flex !important; }
        .desktop-hidden { display: none !important; }
    }
    @media (max-width: 1350px) {
        .mobile-hidden { display: none !important; }
        .desktop-hidden { display: block !important; }
    }
    .header-container {
        position: absolute !important;
        top: 24px !important;
        width: 100% !important;
    }
    @media (max-width: 768px) {
        .header-container { top: 16px !important; }
    }
</style>

<div class="header-container global-header absolute w-full z-50 px-4 sm:px-6 lg:px-8">
    <header class="main-header max-w-7xl mx-auto bg-white rounded-full shadow-lg border border-slate-100 px-6">
        <div class="header-inner flex justify-between items-center w-full">
            <!-- Logo -->
            <div class="flex items-center w-auto md:w-[230px] shrink-0">
                <a href="/" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/brand-mark.png') }}" alt="" class="brand-mark-img w-auto shrink-0">
                    <span class="brand-wordmark">Fluence <span class="accent">Française</span></span>
                </a>
            </div>

            <!-- Navigation Menu -->
            <nav class="mobile-hidden flex items-center gap-6 mx-auto h-full">
                <a href="/" class="nav-link {{ Request::is('/') ? 'active-link' : '' }}">HOME</a>
                <a href="/our-courses" class="nav-link {{ Request::is('our-courses') ? 'active-link' : '' }}">COURSES</a>
                <a href="/why-french" class="nav-link {{ Request::is('why-french') ? 'active-link' : '' }}">WHY FRENCH</a>
                <a href="/tef-tcf" class="nav-link {{ Request::is('tef-tcf') ? 'active-link' : '' }}">TEF/TCF</a>
                <a href="/about-us" class="nav-link {{ Request::is('about-us') ? 'active-link' : '' }}">ABOUT</a>
                <a href="/contact-us" class="nav-link {{ Request::is('contact-us') ? 'active-link' : '' }}">CONTACT</a>
                {{-- Guests land in the demo portal; signed-in users are redirected to their own
                     dashboard by the router guard, so one link serves both. --}}
                <a href="/demo" class="nav-link {{ Request::is('demo*') ? 'active-link' : '' }}">STUDENT PORTAL</a>
            </nav>

            <!-- Header Actions (Secure: Server-side auth check) -->
            <div class="mobile-hidden items-center shrink-0 flex gap-4 w-auto md:w-[230px] justify-end">
                @auth
                    <a href="{{
                        auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'super_admin' ? '/admin/dashboard' :
                        (auth()->user()->user_type === 'tutor' ? '/tutor/dashboard' : '/student/dashboard')
                    }}" class="px-7 py-3 bg-[#0055A4] text-white rounded-full transition font-bold text-[11px] uppercase tracking-widest shadow-md hover:-translate-y-0.5 transform">
                        DASHBOARD
                    </a>
                @else
                    <a href="/login" class="action-btn-login whitespace-nowrap">LOGIN</a>
                    <a href="/register" class="action-btn-signup whitespace-nowrap">ENROLL NOW</a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="desktop-hidden p-2">
                <i class="fas fa-bars text-xl text-[#002654]"></i>
            </button>
        </div>
    </header>
</div>

<!-- Mobile Menu Overlay -->
<div id="mobileMenu" class="fixed inset-0 hidden" style="z-index: 9999; position: fixed; top: 0; left: 0; width: 100%; height: 100%;">
    <!-- Backdrop -->
    <div class="absolute inset-0 backdrop-blur-md" id="mobileMenuBackdrop" style="background-color: rgba(0, 22, 50, 0.6); position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>

    <!-- Menu Content -->
    <div class="relative w-full h-full flex flex-col p-8" id="mobileMenuContent" style="background-color: #ffffff; position: relative; width: 100%; height: 100%;">
        <div class="h-1.5 absolute top-0 left-0 right-0" style="background:linear-gradient(90deg,#0055A4 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>
        <div class="flex justify-between items-center mb-12 mt-4">
            <a href="/" class="flex items-center gap-2.5 shrink-0">
                <img src="{{ asset('images/brand-mark.png') }}" alt="" class="brand-mark-img w-auto shrink-0">
                <span class="brand-wordmark">Fluence <span class="accent">Française</span></span>
            </a>
            <button id="closeMobileMenu" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-[#002654] transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <nav class="flex flex-col flex-1">
            <div class="flex flex-col gap-6">
                <a href="/" class="text-xl font-semibold tracking-tight transition {{ Request::is('/') ? 'text-[#0055A4]' : 'text-[#16213E] hover:text-[#0055A4]' }}">Home</a>
                <a href="/our-courses" class="text-xl font-semibold tracking-tight text-[#16213E] hover:text-[#0055A4] transition">Courses</a>
                <a href="/why-french" class="text-xl font-semibold tracking-tight text-[#16213E] hover:text-[#0055A4] transition">Why French</a>
                <a href="/tef-tcf" class="text-xl font-semibold tracking-tight text-[#16213E] hover:text-[#0055A4] transition">TEF/TCF</a>
                <a href="/about-us" class="text-xl font-semibold tracking-tight text-[#16213E] hover:text-[#0055A4] transition">About Us</a>
                <a href="/contact-us" class="text-xl font-semibold tracking-tight text-[#16213E] hover:text-[#0055A4] transition">Contact Us</a>
                <a href="/demo" class="text-xl font-semibold tracking-tight {{ Request::is('demo*') ? 'text-[#0055A4]' : 'text-[#16213E] hover:text-[#0055A4]' }} transition">Student Portal</a>
            </div>

            <div class="mt-8 flex flex-col gap-6">
                @auth
                    <a href="{{
                        auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'super_admin' ? '/admin/dashboard' :
                        (auth()->user()->user_type === 'tutor' ? '/tutor/dashboard' : '/student/dashboard')
                    }}" class="w-full py-4 bg-[#0055A4] text-white rounded-full text-center font-bold text-sm uppercase tracking-widest shadow-lg block">DASHBOARD</a>
                @else
                    <a href="/register" class="w-full py-4 bg-[#0055A4] text-white rounded-full text-center font-bold text-sm uppercase tracking-widest shadow-lg block">ENROLL NOW</a>
                    <a href="/login" class="w-full py-2 text-center text-slate-500 font-bold text-sm uppercase tracking-widest hover:text-[#0055A4] transition block">LOGIN</a>
                @endauth
            </div>
        </nav>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Auth Sync Check: If PHP says logged in but localStorage says no token, force sync
        const phpAuthState = {{ auth()->check() ? 'true' : 'false' }};
        const hasLocalToken = !!localStorage.getItem('token');

        if (phpAuthState && !hasLocalToken) {
            // PHP session exists but no frontend token - clear the stale session
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
