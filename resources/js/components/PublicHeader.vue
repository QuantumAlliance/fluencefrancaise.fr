<template>
  <div :class="['header-container global-header w-full z-50 px-4 sm:px-6 lg:px-8', isScrolled ? 'is-scrolled' : '']">
    <header class="header-pill max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between">
      <!-- Left: Logo & Wordmark -->
      <div class="flex items-center shrink-0">
        <a href="/" class="flex items-center gap-2 sm:gap-2.5">
          <img src="/images/brand-mark.png" alt="Fluence Française" class="brand-mark-img w-auto shrink-0">
          <span class="brand-wordmark">Fluence <span class="accent">Française</span></span>
        </a>
        <!-- Divider after Logo -->
        <div class="h-5 w-[1px] bg-slate-200 ml-4 mr-1 hidden xl:block"></div>
      </div>

      <!-- Middle: Nav Links with Dropdowns (Desktop) -->
      <nav class="navbar-desktop-nav items-center gap-5 xl:gap-6 mx-auto h-full hidden custom-desktop:flex">
        <!-- 1. France Dropdown -->
        <div 
          class="nav-dropdown-group relative group h-full flex items-center"
          :class="{ 'is-clicked-open': openDropdown === 'france' }"
        >
          <button 
            type="button" 
            @click.stop="toggleDropdown('france')"
            :class="['nav-item-btn py-4', isFranceActive ? 'active-nav' : '']"
          >
            <span class="nav-item-inner gap-1">
              <span>France</span>
              <svg class="chevron-icon w-3 h-3 text-slate-600 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
            </span>
          </button>
          <!-- Dropdown Card -->
          <div class="dropdown-menu-wrapper absolute top-full left-0 pt-2 z-50 opacity-0 invisible translate-y-1.5 transition-all duration-200 ease-out">
            <div class="dropdown-card" @click.stop>
              <!-- Card Header -->
              <div class="flex items-center justify-between pb-2.5 mb-1.5 border-b border-slate-100 text-slate-900 font-bold text-[14px]">
                <span>France</span>
                <svg class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
              <!-- Card Items -->
              <div class="flex flex-col space-y-0.5">
                <a href="/en/french-citizenship-language-test" :class="['dropdown-item-link group/item', isSubActive('french-citizenship-language-test') ? 'active-submenu' : '']">
                  <span>Citizenship B2</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/french-residence-permit-language#resident-card" :class="['dropdown-item-link group/item', isSubActive('resident-card') ? 'active-submenu' : '']">
                  <span>Resident card B1</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/french-residence-permit-language#multi-year-permit" :class="['dropdown-item-link group/item', isSubActive('multi-year-permit') ? 'active-submenu' : '']">
                  <span>Multi-year permit A2</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/french-civic-exam" :class="['dropdown-item-link group/item', isSubActive('french-civic-exam') ? 'active-submenu' : '']">
                  <span>Civic exam</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/french-citizenship" :class="['dropdown-item-link group/item', isSubActive('french-citizenship') ? 'active-submenu' : '']">
                  <span>French Citizenship guide</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- 2. Switzerland Dropdown -->
        <div 
          class="nav-dropdown-group relative group h-full flex items-center"
          :class="{ 'is-clicked-open': openDropdown === 'switzerland' }"
        >
          <button 
            type="button" 
            @click.stop="toggleDropdown('switzerland')"
            :class="['nav-item-btn py-4', isSwissActive ? 'active-nav' : '']"
          >
            <span class="nav-item-inner gap-1">
              <span>Switzerland</span>
              <svg class="chevron-icon w-3 h-3 text-slate-600 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
            </span>
          </button>
          <!-- Dropdown Card -->
          <div class="dropdown-menu-wrapper absolute top-full left-0 pt-2 z-50 opacity-0 invisible translate-y-1.5 transition-all duration-200 ease-out">
            <div class="dropdown-card" @click.stop>
              <!-- Card Header -->
              <div class="flex items-center justify-between pb-2.5 mb-1.5 border-b border-slate-100 text-slate-900 font-bold text-[14px]">
                <span>Switzerland</span>
                <svg class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
              <!-- Card Items -->
              <div class="flex flex-col space-y-0.5">
                <a href="/en/c-permit-switzerland" :class="['dropdown-item-link group/item', isSubActive('c-permit-switzerland') ? 'active-submenu' : '']">
                  <span>C permit (A2 / B1)</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/swiss-citizenship" :class="['dropdown-item-link group/item', isSubActive('swiss-citizenship') ? 'active-submenu' : '']">
                  <span>Swiss citizenship (B1)</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/fide-test" :class="['dropdown-item-link group/item', isSubActive('fide-test') ? 'active-submenu' : '']">
                  <span>fide test</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- 3. Exams Dropdown -->
        <div 
          class="nav-dropdown-group relative group h-full flex items-center"
          :class="{ 'is-clicked-open': openDropdown === 'exams' }"
        >
          <button 
            type="button" 
            @click.stop="toggleDropdown('exams')"
            :class="['nav-item-btn py-4', isExamsActive ? 'active-nav' : '']"
          >
            <span class="nav-item-inner gap-1">
              <span>Exams</span>
              <svg class="chevron-icon w-3 h-3 text-slate-600 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
            </span>
          </button>
          <!-- Dropdown Card -->
          <div class="dropdown-menu-wrapper absolute top-full left-0 pt-2 z-50 opacity-0 invisible translate-y-1.5 transition-all duration-200 ease-out">
            <div class="dropdown-card" @click.stop>
              <!-- Card Header -->
              <div class="flex items-center justify-between pb-2.5 mb-1.5 border-b border-slate-100 text-slate-900 font-bold text-[14px]">
                <span>Exams</span>
                <svg class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
              <!-- Card Items -->
              <div class="flex flex-col space-y-0.5">
                <a href="/en/tcf-irn" :class="['dropdown-item-link group/item', isSubActive('tcf-irn') ? 'active-submenu' : '']">
                  <span>TCF IRN</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/tef-irn" :class="['dropdown-item-link group/item', isSubActive('tef-irn') ? 'active-submenu' : '']">
                  <span>TEF IRN</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/fide-test" :class="['dropdown-item-link group/item', isSubActive('fide-test') ? 'active-submenu' : '']">
                  <span>fide test</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/delf-a2" :class="['dropdown-item-link group/item', isSubActive('delf-a2') ? 'active-submenu' : '']">
                  <span>DELF A2</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/delf-b1" :class="['dropdown-item-link group/item', isSubActive('delf-b1') ? 'active-submenu' : '']">
                  <span>DELF B1</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/delf-b2" :class="['dropdown-item-link group/item', isSubActive('delf-b2') ? 'active-submenu' : '']">
                  <span>DELF B2</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/delf" :class="['dropdown-item-link group/item', (isSubActive('/en/delf') || isSubActive('/delf')) ? 'active-submenu' : '']">
                  <span>All DELF levels</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/tcf-vs-tef-vs-delf" :class="['dropdown-item-link group/item', isSubActive('tcf-vs-tef-vs-delf') ? 'active-submenu' : '']">
                  <span>TCF vs TEF vs DELF</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- 4. Courses (Direct Link) -->
        <a href="/our-courses" :class="['nav-pill-link', isCoursesActive ? 'active-nav' : '']">
          <span class="nav-label">Courses</span>
        </a>

        <!-- 5. Guides Dropdown -->
        <div 
          class="nav-dropdown-group relative group h-full flex items-center"
          :class="{ 'is-clicked-open': openDropdown === 'guides' }"
        >
          <button 
            type="button" 
            @click.stop="toggleDropdown('guides')"
            :class="['nav-item-btn py-4', isGuidesActive ? 'active-nav' : '']"
          >
            <span class="nav-item-inner gap-1">
              <span>Guides</span>
              <svg class="chevron-icon w-3 h-3 text-slate-600 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
            </span>
          </button>
          <!-- Dropdown Card -->
          <div class="dropdown-menu-wrapper absolute top-full left-0 pt-2 z-50 opacity-0 invisible translate-y-1.5 transition-all duration-200 ease-out">
            <div class="dropdown-card" @click.stop>
              <!-- Card Header -->
              <div class="flex items-center justify-between pb-2.5 mb-1.5 border-b border-slate-100 text-slate-900 font-bold text-[14px]">
                <span>Guides</span>
                <svg class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
              <!-- Card Items -->
              <div class="flex flex-col space-y-0.5">
                <a href="/en/french-citizenship" :class="['dropdown-item-link group/item', (isSubActive('french-citizenship') && !isSubActive('language-test')) ? 'active-submenu' : '']">
                  <span>French Citizenship guide</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/tcf-vs-tef-vs-delf" :class="['dropdown-item-link group/item', isSubActive('tcf-vs-tef-vs-delf') ? 'active-submenu' : '']">
                  <span>TCF vs TEF vs DELF</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/delf" :class="['dropdown-item-link group/item', (isSubActive('delf') && !isSubActive('delf-')) ? 'active-submenu' : '']">
                  <span>DELF guide</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- 6. About (Direct Link) -->
        <a href="/about-us" :class="['nav-pill-link', isAboutActive ? 'active-nav' : '']">
          <span class="nav-label">About</span>
        </a>
      </nav>

      <!-- Right: Desktop Navigation Items -->
      <div class="navbar-desktop-nav items-center gap-4 xl:gap-5 shrink-0 hidden custom-desktop:flex">
        <!-- Vertical Divider -->
        <div class="h-5 w-[1px] bg-slate-200 mr-1 hidden xl:block"></div>

        <!-- Contact Link -->
        <a href="/contact-us" :class="['nav-pill-link', isContactActive ? 'active-nav' : '']">
          <span class="nav-label">Contact</span>
        </a>

        <!-- FR | EN Language Switcher (Desktop) -->
        <div class="text-[13px] font-bold text-[#002654] flex items-center gap-1 select-none">
          <a href="/fr" class="text-slate-400 hover:text-[#0055A4]">FR</a>
          <span class="text-slate-300 font-normal">|</span>
          <a href="/en" class="text-[#002654]">EN</a>
        </div>

        <!-- Student Portal Link -->
        <router-link to="/demo" :class="['nav-pill-link whitespace-nowrap', isPortalActive ? 'active-nav' : '']">
          <span class="nav-label">Student portal</span>
        </router-link>

        <!-- CTA Button -->
        <template v-if="auth.user">
          <router-link :to="dashboardLink" class="btn-enroll-pill">
            DASHBOARD
          </router-link>
        </template>
        <template v-else>
          <router-link to="/register" class="btn-enroll-pill">
            Enroll now
          </router-link>
        </template>
      </div>

      <!-- Right: Mobile View (FR | EN and 3-line Hamburger Button matching Image 3) -->
      <div class="navbar-mobile-toggle flex items-center gap-3.5 sm:gap-4 shrink-0 custom-desktop:hidden">
        <!-- FR | EN on Mobile Header Bar -->
        <div class="text-[14px] sm:text-[15px] font-bold text-[#002654] flex items-center gap-1.5 select-none tracking-tight">
          <a href="/fr" class="text-slate-400 hover:text-[#0055A4]">FR</a>
          <span class="text-slate-300 font-normal">|</span>
          <a href="/en" class="text-[#002654]">EN</a>
        </div>

        <!-- Sleek 3-line Hamburger Button -->
        <button 
          @click="openMobileMenu" 
          class="flex flex-col justify-center items-center gap-[4.5px] w-8 h-8 p-1 text-[#002654] hover:text-[#0055A4] transition cursor-pointer" 
          aria-label="Toggle menu"
        >
          <span class="w-5 h-[2px] bg-[#002654] rounded-full transition-transform"></span>
          <span class="w-5 h-[2px] bg-[#002654] rounded-full transition-opacity"></span>
          <span class="w-5 h-[2px] bg-[#002654] rounded-full transition-transform"></span>
        </button>
      </div>
    </header>
  </div>

  <!-- Mobile Menu Overlay -->
  <Teleport to="body">
    <div v-if="isMobileMenuOpen" class="fixed inset-0 z-[9999]">
      <!-- Backdrop -->
      <div @click="closeMobileMenu" class="absolute inset-0 backdrop-blur-md bg-[#001632]/60"></div>

      <!-- Menu Content -->
      <div class="relative w-full h-full flex flex-col p-6 sm:p-8 bg-white overflow-y-auto">
        <!-- Top French Tricolor Ribbon -->
        <div class="h-1.5 absolute top-0 left-0 right-0" style="background:linear-gradient(90deg,#0055A4 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>

        <!-- Header: Logo & Close -->
        <div class="flex justify-between items-center mb-8 mt-2">
          <a href="/" class="flex items-center gap-2.5 shrink-0">
            <img src="/images/brand-mark.png" alt="Fluence Française" class="brand-mark-img w-auto shrink-0">
            <span class="brand-wordmark">Fluence <span class="accent">Française</span></span>
          </a>
          <button @click="closeMobileMenu" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-[#002654] transition">
            <i class="fas fa-times text-2xl"></i>
          </button>
        </div>

        <!-- Mobile Navigation List -->
        <nav class="flex flex-col flex-1 divide-y divide-slate-100">
          <!-- Accordion: France -->
          <div class="py-3">
            <button 
              type="button" 
              @click="toggleAccordion('france')" 
              :class="['w-full flex items-center justify-between text-base font-bold text-left', isFranceActive ? 'text-[#0055A4]' : 'text-[#002654]']"
            >
              <span>France</span>
              <i :class="['fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200', activeAccordion === 'france' ? 'rotate-180' : '']"></i>
            </button>
            <div v-show="activeAccordion === 'france'" class="pl-3 pt-2 flex flex-col space-y-1 text-sm text-slate-600">
              <a href="/en/french-citizenship-language-test" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('french-citizenship-language-test') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>Citizenship B2</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/french-residence-permit-language#resident-card" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('resident-card') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>Resident card B1</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/french-residence-permit-language#multi-year-permit" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('multi-year-permit') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>Multi-year permit A2</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/french-civic-exam" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('french-civic-exam') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>Civic exam</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/french-citizenship" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('french-citizenship') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>French Citizenship guide</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
            </div>
          </div>

          <!-- Accordion: Switzerland -->
          <div class="py-3">
            <button 
              type="button" 
              @click="toggleAccordion('switzerland')" 
              :class="['w-full flex items-center justify-between text-base font-bold text-left', isSwissActive ? 'text-[#0055A4]' : 'text-[#002654]']"
            >
              <span>Switzerland</span>
              <i :class="['fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200', activeAccordion === 'switzerland' ? 'rotate-180' : '']"></i>
            </button>
            <div v-show="activeAccordion === 'switzerland'" class="pl-3 pt-2 flex flex-col space-y-1 text-sm text-slate-600">
              <a href="/en/c-permit-switzerland" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('c-permit-switzerland') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>C permit (A2 / B1)</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/swiss-citizenship" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('swiss-citizenship') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>Swiss citizenship (B1)</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/fide-test" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('fide-test') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>fide test</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
            </div>
          </div>

          <!-- Accordion: Exams -->
          <div class="py-3">
            <button 
              type="button" 
              @click="toggleAccordion('exams')" 
              :class="['w-full flex items-center justify-between text-base font-bold text-left', isExamsActive ? 'text-[#0055A4]' : 'text-[#002654]']"
            >
              <span>Exams</span>
              <i :class="['fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200', activeAccordion === 'exams' ? 'rotate-180' : '']"></i>
            </button>
            <div v-show="activeAccordion === 'exams'" class="pl-3 pt-2 flex flex-col space-y-1 text-sm text-slate-600">
              <a href="/en/tcf-irn" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('tcf-irn') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>TCF IRN</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/tef-irn" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('tef-irn') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>TEF IRN</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/fide-test" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('fide-test') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>fide test</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/delf-a2" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('delf-a2') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>DELF A2</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/delf-b1" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('delf-b1') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>DELF B1</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/delf-b2" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('delf-b2') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>DELF B2</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/delf" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', (isSubActive('/en/delf') || isSubActive('/delf')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>All DELF levels</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/tcf-vs-tef-vs-delf" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('tcf-vs-tef-vs-delf') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>TCF vs TEF vs DELF</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
            </div>
          </div>

          <!-- Courses -->
          <div class="py-3">
            <a href="/our-courses" :class="['block text-base font-bold hover:text-[#0055A4]', isCoursesActive ? 'text-[#0055A4]' : 'text-[#002654]']">
              Courses
            </a>
          </div>

          <!-- Accordion: Guides -->
          <div class="py-3">
            <button 
              type="button" 
              @click="toggleAccordion('guides')" 
              :class="['w-full flex items-center justify-between text-base font-bold text-left', isGuidesActive ? 'text-[#0055A4]' : 'text-[#002654]']"
            >
              <span>Guides</span>
              <i :class="['fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200', activeAccordion === 'guides' ? 'rotate-180' : '']"></i>
            </button>
            <div v-show="activeAccordion === 'guides'" class="pl-3 pt-2 flex flex-col space-y-1 text-sm text-slate-600">
              <a href="/en/french-citizenship" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', (isSubActive('french-citizenship') && !isSubActive('language-test')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>French Citizenship guide</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/tcf-vs-tef-vs-delf" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', isSubActive('tcf-vs-tef-vs-delf') ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>TCF vs TEF vs DELF</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/delf" :class="['py-1.5 px-2 rounded-md hover:text-[#0055A4] flex justify-between items-center', (isSubActive('delf') && !isSubActive('delf-')) ? 'bg-blue-50 text-[#0055A4] font-semibold' : '']">
                <span>DELF guide</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
            </div>
          </div>

          <!-- About -->
          <div class="py-3">
            <a href="/about-us" :class="['block text-base font-bold hover:text-[#0055A4]', isAboutActive ? 'text-[#0055A4]' : 'text-[#002654]']">
              About
            </a>
          </div>

          <!-- Contact -->
          <div class="py-3">
            <a href="/contact-us" :class="['block text-base font-bold hover:text-[#0055A4]', isContactActive ? 'text-[#0055A4]' : 'text-[#002654]']">
              Contact
            </a>
          </div>

          <!-- Student Portal -->
          <div class="py-3">
            <router-link to="/demo" @click="closeMobileMenu" :class="['block text-base font-bold hover:text-[#0055A4]', isPortalActive ? 'text-[#0055A4]' : 'text-[#002654]']">
              Student portal
            </router-link>
          </div>
        </nav>

        <!-- Bottom Actions -->
        <div class="mt-6 pt-6 border-t border-slate-100 flex flex-col gap-3">
          <template v-if="auth.user">
            <router-link :to="dashboardLink" @click="closeMobileMenu" class="w-full py-3.5 bg-[#002654] text-white rounded-full text-center font-bold text-sm tracking-wide shadow-md">
              DASHBOARD
            </router-link>
          </template>
          <template v-else>
            <router-link to="/register" @click="closeMobileMenu" class="w-full py-3.5 bg-[#002654] text-white rounded-full text-center font-bold text-sm tracking-wide shadow-md">
              Enroll now
            </router-link>
            <router-link to="/login" @click="closeMobileMenu" class="w-full py-2 text-center text-slate-500 font-bold text-sm tracking-wide hover:text-[#0055A4] transition">
              LOGIN
            </router-link>
          </template>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useSettingsStore } from '../stores/settings'

const route = useRoute()
const auth = useAuthStore()
const settingsStore = useSettingsStore()

const isMobileMenuOpen = ref(false)
const activeAccordion = ref(null)
const openDropdown = ref(null)
const isScrolled = ref(false)

const handleScroll = () => {
  isScrolled.value = window.scrollY > 20
}

onMounted(() => {
  settingsStore.fetchSettings()
  document.addEventListener('click', closeAllDropdowns)
  window.addEventListener('scroll', handleScroll, { passive: true })
  handleScroll()
})

onUnmounted(() => {
  document.removeEventListener('click', closeAllDropdowns)
  window.removeEventListener('scroll', handleScroll)
})

const currentPath = computed(() => route?.path || window.location.pathname)

// Active states for parent categories
const isFranceActive = computed(() => {
  const p = currentPath.value
  return p.includes('why-french') || p.includes('citizenship') || p.includes('naturalisation') || p.includes('residence-permit') || p.includes('civic-exam')
})

const isSwissActive = computed(() => {
  const p = currentPath.value
  return p.includes('c-permit') || p.includes('permis-c') || p.includes('swiss') || p.includes('fide')
})

const isExamsActive = computed(() => {
  const p = currentPath.value
  return p.includes('tef-tcf') || p.includes('tcf') || p.includes('tef') || p.includes('delf') || p.includes('exam')
})

const isCoursesActive = computed(() => {
  const p = currentPath.value
  return p.includes('courses') || p.includes('online-french-classes') || p.includes('cours') || p.includes('beginner') || p.includes('elementary') || p.includes('intermediate')
})

const isGuidesActive = computed(() => {
  const p = currentPath.value
  return p.includes('guide') || p.includes('vs')
})

const isAboutActive = computed(() => {
  const p = currentPath.value
  return p.includes('about') || p.includes('a-propos')
})

const isContactActive = computed(() => {
  const p = currentPath.value
  return p.includes('contact')
})

const isPortalActive = computed(() => {
  const p = currentPath.value
  return p.includes('demo')
})

const isSubActive = (slug) => {
  return currentPath.value.includes(slug)
}

const dashboardLink = computed(() => {
  if (auth.user?.user_type === 'admin' || auth.user?.user_type === 'super_admin') {
    return '/admin/dashboard'
  }
  if (auth.user?.user_type === 'tutor') {
    return '/tutor/dashboard'
  }
  return '/student/dashboard'
})

const toggleDropdown = (name) => {
  openDropdown.value = openDropdown.value === name ? null : name
}

const closeAllDropdowns = () => {
  openDropdown.value = null
}

const openMobileMenu = () => {
  if (isFranceActive.value) activeAccordion.value = 'france'
  else if (isSwissActive.value) activeAccordion.value = 'switzerland'
  else if (isExamsActive.value) activeAccordion.value = 'exams'
  else if (isGuidesActive.value) activeAccordion.value = 'guides'
  
  isMobileMenuOpen.value = true
  document.body.style.overflow = 'hidden'
}

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
  document.body.style.overflow = 'auto'
}

const toggleAccordion = (name) => {
  activeAccordion.value = activeAccordion.value === name ? null : name
}
</script>

<style scoped>
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
    transform: rotate(180deg) !important;
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
    .custom-desktop\:flex {
        display: flex !important;
    }
    .custom-desktop\:hidden {
        display: none !important;
    }
}

@media (max-width: 1199px) {
    .custom-desktop\:flex {
        display: none !important;
    }
    .custom-desktop\:hidden {
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
