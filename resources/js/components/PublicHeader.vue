<template>
  <div class="header-container global-header absolute w-full z-50 px-4 sm:px-6 lg:px-8">
    <header class="header-pill max-w-7xl mx-auto px-5 sm:px-6 flex items-center justify-between">
      <!-- Left: Logo & Wordmark -->
      <div class="flex items-center shrink-0">
        <a href="/" class="flex items-center gap-2.5">
          <img src="/images/brand-mark.png" alt="Fluence Française" class="brand-mark-img w-auto shrink-0">
          <span class="brand-wordmark">Fluence <span class="accent">Française</span></span>
        </a>
        <!-- Divider after Logo -->
        <div class="h-5 w-[1px] bg-slate-200 ml-4 mr-1 hidden xl:block"></div>
      </div>

      <!-- Middle: Nav Links with Dropdowns (Desktop) -->
      <nav class="navbar-desktop-nav items-center gap-5 xl:gap-6 mx-auto h-full hidden custom-desktop:flex">
        <!-- 1. France Dropdown -->
        <div class="relative group h-full flex items-center">
          <button type="button" class="nav-item-btn gap-1 py-4">
            <span>France</span>
            <svg class="w-3 h-3 text-slate-600 group-hover:text-[#0055A4] transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <!-- Dropdown Card (Image 2 style) -->
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
                <a href="/en/french-citizenship-language-test" class="dropdown-item-link group/item">
                  <span>Citizenship B2</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/french-residence-permit-language#resident-card" class="dropdown-item-link group/item">
                  <span>Resident card B1</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/french-residence-permit-language#multi-year-permit" class="dropdown-item-link group/item">
                  <span>Multi-year permit A2</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/french-civic-exam" class="dropdown-item-link group/item">
                  <span>Civic exam</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/french-citizenship" class="dropdown-item-link group/item">
                  <span>Citizenship guide</span>
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
            <span>Switzerland</span>
            <svg class="w-3 h-3 text-slate-600 group-hover:text-[#0055A4] transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <!-- Dropdown Card -->
          <div class="absolute top-full left-0 pt-2 z-50 opacity-0 invisible translate-y-1.5 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-200 ease-out">
            <div class="dropdown-card">
              <!-- Card Header -->
              <div class="flex items-center justify-between pb-3 border-b border-slate-100 text-slate-900 font-bold text-[14px]">
                <span>Switzerland</span>
                <svg class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
              <!-- Card Items -->
              <div class="mt-2.5 flex flex-col">
                <a href="/en/c-permit-switzerland" class="dropdown-item-link group/item">
                  <span>C permit (A2 / B1)</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/swiss-citizenship" class="dropdown-item-link group/item">
                  <span>Swiss citizenship (B1)</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/fide-test" class="dropdown-item-link group/item">
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
        <div class="relative group h-full flex items-center">
          <button type="button" class="nav-item-btn gap-1 py-4">
            <span>Exams</span>
            <svg class="w-3 h-3 text-slate-600 group-hover:text-[#0055A4] transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <!-- Dropdown Card -->
          <div class="absolute top-full left-0 pt-2 z-50 opacity-0 invisible translate-y-1.5 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-200 ease-out">
            <div class="dropdown-card">
              <!-- Card Header -->
              <div class="flex items-center justify-between pb-3 border-b border-slate-100 text-slate-900 font-bold text-[14px]">
                <span>Exams</span>
                <svg class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
              <!-- Card Items -->
              <div class="mt-2.5 flex flex-col">
                <a href="/en/tcf-irn" class="dropdown-item-link group/item">
                  <span>TCF IRN</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/tef-irn" class="dropdown-item-link group/item">
                  <span>TEF IRN</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/fide-test" class="dropdown-item-link group/item">
                  <span>fide test</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/delf-a2" class="dropdown-item-link group/item">
                  <span>DELF A2</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/delf-b1" class="dropdown-item-link group/item">
                  <span>DELF B1</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/delf-b2" class="dropdown-item-link group/item">
                  <span>DELF B2</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/delf" class="dropdown-item-link group/item">
                  <span>All DELF levels</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/tcf-vs-tef-vs-delf" class="dropdown-item-link group/item">
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
        <a href="/our-courses" class="nav-pill-link">Courses</a>

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
                <a href="/en/french-citizenship" class="dropdown-item-link group/item">
                  <span>Citizenship guide</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/tcf-vs-tef-vs-delf" class="dropdown-item-link group/item">
                  <span>TCF vs TEF vs DELF</span>
                  <svg class="arrow-icon w-3.5 h-3.5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
                <a href="/en/delf" class="dropdown-item-link group/item">
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
        <a href="/about-us" class="nav-pill-link">About</a>
      </nav>

      <!-- Right: Divider | Contact | FR | EN | Student Portal | CTA Button -->
      <div class="navbar-desktop-nav items-center gap-4 xl:gap-5 shrink-0 hidden custom-desktop:flex">
        <!-- Vertical Divider -->
        <div class="h-5 w-[1px] bg-slate-200 mr-1 hidden xl:block"></div>

        <!-- Contact Link -->
        <a href="/contact-us" class="nav-pill-link">Contact</a>

        <!-- FR | EN Language Switcher -->
        <div class="text-[13px] font-semibold text-slate-700 flex items-center gap-1 select-none">
          <a href="/fr" class="text-slate-400 hover:text-[#0055A4]">FR</a>
          <span class="text-slate-300 font-normal">|</span>
          <a href="/en" class="font-bold text-[#002654]">EN</a>
        </div>

        <!-- Student Portal Link -->
        <router-link to="/demo" class="nav-pill-link whitespace-nowrap">Student portal</router-link>

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

      <!-- Mobile Hamburger Button -->
      <button @click="openMobileMenu" class="custom-desktop:hidden p-2 text-[#002654] hover:text-[#0055A4] transition">
        <i class="fas fa-bars text-xl"></i>
      </button>
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
            <button type="button" @click="toggleAccordion('france')" class="w-full flex items-center justify-between text-base font-bold text-[#002654] text-left">
              <span>France</span>
              <i :class="['fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200', activeAccordion === 'france' ? 'rotate-180' : '']"></i>
            </button>
            <div v-show="activeAccordion === 'france'" class="pl-3 pt-2 flex flex-col space-y-2 text-sm text-slate-600">
              <a href="/en/french-citizenship-language-test" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>Citizenship B2</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/french-residence-permit-language#resident-card" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>Resident card B1</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/french-residence-permit-language#multi-year-permit" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>Multi-year permit A2</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/french-civic-exam" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>Civic exam</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/french-citizenship" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>Citizenship guide</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
            </div>
          </div>

          <!-- Accordion: Switzerland -->
          <div class="py-3">
            <button type="button" @click="toggleAccordion('switzerland')" class="w-full flex items-center justify-between text-base font-bold text-[#002654] text-left">
              <span>Switzerland</span>
              <i :class="['fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200', activeAccordion === 'switzerland' ? 'rotate-180' : '']"></i>
            </button>
            <div v-show="activeAccordion === 'switzerland'" class="pl-3 pt-2 flex flex-col space-y-2 text-sm text-slate-600">
              <a href="/en/c-permit-switzerland" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>C permit (A2 / B1)</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/swiss-citizenship" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>Swiss citizenship (B1)</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/fide-test" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>fide test</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
            </div>
          </div>

          <!-- Accordion: Exams -->
          <div class="py-3">
            <button type="button" @click="toggleAccordion('exams')" class="w-full flex items-center justify-between text-base font-bold text-[#002654] text-left">
              <span>Exams</span>
              <i :class="['fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200', activeAccordion === 'exams' ? 'rotate-180' : '']"></i>
            </button>
            <div v-show="activeAccordion === 'exams'" class="pl-3 pt-2 flex flex-col space-y-2 text-sm text-slate-600">
              <a href="/en/tcf-irn" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>TCF IRN</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/tef-irn" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>TEF IRN</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/fide-test" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>fide test</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/delf-a2" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>DELF A2</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/delf-b1" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>DELF B1</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/delf-b2" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>DELF B2</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/delf" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>All DELF levels</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/tcf-vs-tef-vs-delf" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>TCF vs TEF vs DELF</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
            </div>
          </div>

          <!-- Courses -->
          <div class="py-3">
            <a href="/our-courses" class="block text-base font-bold text-[#002654] hover:text-[#0055A4]">
              Courses
            </a>
          </div>

          <!-- Accordion: Guides -->
          <div class="py-3">
            <button type="button" @click="toggleAccordion('guides')" class="w-full flex items-center justify-between text-base font-bold text-[#002654] text-left">
              <span>Guides</span>
              <i :class="['fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200', activeAccordion === 'guides' ? 'rotate-180' : '']"></i>
            </button>
            <div v-show="activeAccordion === 'guides'" class="pl-3 pt-2 flex flex-col space-y-2 text-sm text-slate-600">
              <a href="/en/french-citizenship" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>Citizenship guide</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/tcf-vs-tef-vs-delf" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>TCF vs TEF vs DELF</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
              <a href="/en/delf" class="py-1 hover:text-[#0055A4] flex justify-between">
                <span>DELF guide</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
            </div>
          </div>

          <!-- About -->
          <div class="py-3">
            <a href="/about-us" class="block text-base font-bold text-[#002654] hover:text-[#0055A4]">
              About
            </a>
          </div>

          <!-- Contact -->
          <div class="py-3">
            <a href="/contact-us" class="block text-base font-bold text-[#002654] hover:text-[#0055A4]">
              Contact
            </a>
          </div>

          <!-- Student Portal -->
          <div class="py-3">
            <router-link to="/demo" @click="closeMobileMenu" class="block text-base font-bold text-[#002654] hover:text-[#0055A4]">
              Student portal
            </router-link>
          </div>
        </nav>

        <!-- Bottom Actions -->
        <div class="mt-6 pt-6 border-t border-slate-100 flex flex-col gap-4">
          <div class="flex items-center justify-center gap-3 text-sm font-bold text-slate-700">
            <span>Lang:</span>
            <a href="/fr" class="px-3 py-1 rounded bg-slate-100 text-slate-700">FR</a>
            <a href="/en" class="px-3 py-1 rounded bg-[#002654] text-white">EN</a>
          </div>

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
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useSettingsStore } from '../stores/settings'

const auth = useAuthStore()
const settingsStore = useSettingsStore()
const isMobileMenuOpen = ref(false)
const activeAccordion = ref(null)

onMounted(() => {
  settingsStore.fetchSettings()
})

const dashboardLink = computed(() => {
  if (auth.user?.user_type === 'admin' || auth.user?.user_type === 'super_admin') {
    return '/admin/dashboard'
  }
  if (auth.user?.user_type === 'tutor') {
    return '/tutor/dashboard'
  }
  return '/student/dashboard'
})

const openMobileMenu = () => {
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
