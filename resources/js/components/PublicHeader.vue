<template>
  <div class="header-container global-header absolute w-full z-50 px-4 sm:px-6 lg:px-8">
    <header class="main-header max-w-7xl mx-auto bg-white rounded-full shadow-lg border border-gray-100 px-6">
      <div class="header-inner flex justify-between items-center w-full">
        <!-- Logo -->
        <div class="flex items-center w-auto md:w-[200px] shrink-0">
          <a href="/" class="flex items-center gap-2.5">
            <img src="/images/brand-mark.png" alt="" class="brand-mark-img w-auto shrink-0">
            <span class="brand-wordmark">Fluence <span class="accent">Française</span></span>
          </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="mobile-hidden items-center gap-6 mx-auto h-full hidden custom-desktop:flex">
          <a href="/" class="nav-link">HOME</a>
          <a href="/our-courses" class="nav-link">COURSE STRUCTURE</a>
          <a href="/why-french" class="nav-link">WHY FRENCH</a>
          <a href="/tef-tcf" class="nav-link">TEF/TCF</a>
          <a href="/contact-us" class="nav-link">CONTACT US</a>
          <a href="/about-us" class="nav-link">ABOUT US</a>
          <!-- Guests land in the demo portal; signed-in users are redirected to their own
               dashboard by the router guard, so one link serves both. -->
          <router-link to="/demo" class="nav-link">STUDENT PORTAL</router-link>
        </nav>

        <!-- Header Actions -->
        <div class="mobile-hidden items-center shrink-0 gap-4 w-auto md:w-[200px] justify-end hidden custom-desktop:flex">
          <template v-if="auth.user">
             <router-link :to="dashboardLink" class="px-7 py-3 bg-[#0055A4] text-white rounded-full transition font-bold text-[11px] uppercase tracking-widest shadow-md hover:scale-105 transform">
               DASHBOARD
             </router-link>
          </template>
          <template v-else>
            <!-- One CTA only: the navbar serves new visitors. Returning users reach
                 login from the sign-up page ("Already have an account?") or /login.
                 The demo is reached from the STUDENT PORTAL nav link, not a button. -->
            <router-link to="/register" class="action-btn-signup whitespace-nowrap">SIGN UP</router-link>
          </template>
        </div>

        <!-- Mobile Menu Button -->
        <button @click="openMobileMenu" class="custom-desktop:hidden p-2">
          <i class="fas fa-bars text-xl text-gray-900"></i>
        </button>
      </div>
    </header>
  </div>

  <!-- Mobile Menu Overlay -->
  <Teleport to="body">
    <div v-if="isMobileMenuOpen" class="fixed inset-0 z-[9999]">
      <!-- Backdrop -->
      <div 
        @click="closeMobileMenu"
        class="absolute inset-0 backdrop-blur-md bg-black/60"
      ></div>
      
      <!-- Menu Content -->
      <div class="relative w-full h-full flex flex-col p-8 bg-white">
        <div class="flex justify-between items-center mb-12">
          <a href="/" class="flex items-center gap-2.5 shrink-0">
            <img src="/images/brand-mark.png" alt="" class="brand-mark-img w-auto shrink-0">
            <span class="brand-wordmark">Fluence <span class="accent">Française</span></span>
          </a>
          <button @click="closeMobileMenu" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-900 transition">
            <i class="fas fa-times text-2xl"></i>
          </button>
        </div>

        <nav class="flex flex-col flex-1">
          <div class="flex flex-col gap-6">
            <a href="/" class="text-xl font-semibold tracking-tight text-gray-900 hover:text-[#0055A4] transition">Home</a>
            <a href="/our-courses" class="text-xl font-semibold tracking-tight text-gray-900 hover:text-[#0055A4] transition">Course Structure</a>
            <a href="/why-french" class="text-xl font-semibold tracking-tight text-gray-900 hover:text-[#0055A4] transition">Why French</a>
            <a href="/tef-tcf" class="text-xl font-semibold tracking-tight text-gray-900 hover:text-[#0055A4] transition">TEF/TCF</a>
            <a href="/contact-us" class="text-xl font-semibold tracking-tight text-gray-900 hover:text-[#0055A4] transition">Contact Us</a>
            <a href="/about-us" class="text-xl font-semibold tracking-tight text-gray-900 hover:text-[#0055A4] transition">About Us</a>
            <router-link to="/demo" class="text-xl font-semibold tracking-tight text-gray-900 hover:text-[#0055A4] transition">Student Portal</router-link>
          </div>

          <div class="mt-8 flex flex-col gap-6">
            <template v-if="auth.user">
               <router-link :to="dashboardLink" class="w-full py-4 bg-[#0055A4] text-white rounded-full text-center font-bold text-sm uppercase tracking-widest shadow-lg shadow-amber-900/10 block">
                 DASHBOARD
               </router-link>
            </template>
            <template v-else>
               <router-link to="/register" class="w-full py-4 bg-[#0055A4] text-white rounded-full text-center font-bold text-sm uppercase tracking-widest shadow-lg shadow-amber-900/10 block">
                 SIGN UP
               </router-link>
               <router-link to="/login" class="w-full py-2 text-center text-gray-500 font-bold text-sm uppercase tracking-widest hover:text-[#0055A4] transition block">
                 LOGIN
               </router-link>
               <router-link to="/demo" class="w-full py-2 text-center text-gray-500 font-bold text-sm uppercase tracking-widest hover:text-[#0055A4] transition block">
                 See Demo
               </router-link>
            </template>
          </div>
        </nav>
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
</script>

<style scoped>
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
.brand-mark-img { height: 40px; }
@media (max-width: 768px) {
    .brand-wordmark { font-size: 0.9375rem; }
    .brand-mark-img { height: 32px; }
}
.main-header {
    height: 65px;
    display: flex;
    align-items: center;
    box-sizing: border-box;
    background-color: white;
}
.header-inner {
    height: 100%;
    display: flex;
    align-items: center;
    width: 100%;
}
.nav-link {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #0055A4;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    height: 100%;
    text-decoration: none;
}
.nav-link:hover {
    color: #111827;
}

.action-btn-login {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #0055A4;
    transition: all 0.2s ease;
    text-decoration: none;
}
.action-btn-login:hover {
    color: #111827;
}
.action-btn-signup {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    background-color: #0055A4;
    color: white;
    padding: 10px 32px;
    border-radius: 9999px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}
.action-btn-signup:hover {
    transform: scale(1.05);
    background-color: #003d7a;
}

.header-container {
    position: absolute;
    top: 24px;
    width: 100%;
}
@media (max-width: 768px) {
    .header-container {
        top: 16px;
    }
}

/* Custom Desktop Breakpoint to match 1350px logic */
@media (min-width: 1351px) {
    .custom-desktop\:flex {
        display: flex !important;
    }
    .custom-desktop\:hidden {
        display: none !important;
    }
}
@media (max-width: 1350px) {
    .custom-desktop\:flex {
        display: none !important;
    }
    .custom-desktop\:hidden {
        display: block !important;
    }
}
</style>
