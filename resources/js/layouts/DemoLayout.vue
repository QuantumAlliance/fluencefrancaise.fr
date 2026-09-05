<template>
  <div class="flex h-screen bg-gray-100">
    <div
      v-if="isSidebarOpen"
      class="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden"
      @click="isSidebarOpen = false"
    ></div>

    <!-- Sidebar -->
    <div
      class="fixed inset-y-0 left-0 z-50 w-64 text-white shadow-lg transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-auto md:flex md:flex-col"
      :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
      style="background-color: #0055A4;"
    >
      <!-- Plain anchor, not router-link: the homepage is a Blade view (web.php:91),
           not an SPA route, so it needs a real page load. -->
      <a href="/" class="block p-6 border-b hover:bg-white/5 transition-colors" style="border-color: #003d7a;">
        <h2 class="text-2xl font-bold">{{ settingsStore.siteName }}</h2>
        <p class="text-white/80 text-sm">Student Portal</p>
      </a>

      <nav class="mt-6 flex-1 space-y-1 px-2 overflow-y-auto">
        <!-- Every page is browsable. The sidebar stays live so a visitor can move
             around freely; it is the content that is read-only. -->
        <router-link
          v-for="item in demoMenu"
          :key="item.name"
          :to="item.path"
          class="flex items-center justify-between px-4 py-3 rounded-lg transition-colors duration-200"
          :class="isActive(item.path) ? 'text-white' : 'text-white/90 hover:opacity-80'"
          :style="isActive(item.path) ? 'background-color: #003d7a;' : ''"
          @click="isSidebarOpen = false"
        >
          <div class="flex items-center">
            <component :is="item.icon" class="w-5 h-5 mr-3" />
            <span class="font-medium">{{ item.name }}</span>
          </div>
          <Lock v-if="item.path === '/demo/account'" class="w-4 h-4 text-white/50" />
        </router-link>

      </nav>
    </div>

    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">
      <div class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-8 z-30">
        <div class="flex items-center">
          <!-- Back to the public site. Plain anchor, not router-link: the homepage is a
               Blade view (web.php:91), not an SPA route, so it needs a real page load. -->
          <a
            href="/"
            aria-label="Back to website"
            class="mr-3 -ml-1 p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
          >
            <ArrowLeft class="w-5 h-5" />
          </a>
          <button
            class="mr-4 text-gray-600 hover:text-gray-900 focus:outline-none md:hidden"
            @click="isSidebarOpen = !isSidebarOpen"
          >
            <Menu class="w-6 h-6" />
          </button>
          <h1 class="text-xl font-bold text-gray-800 truncate">{{ pageTitle }}</h1>
        </div>

        <div class="flex items-center space-x-2 sm:space-x-4">
          <!-- The topbar carries a single action: log in. Registration is still reachable
               from the login page and from the gate modal. -->
          <router-link
            to="/login"
            class="px-4 sm:px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg text-sm font-medium transition-colors"
          >
            Login
          </router-link>
        </div>
      </div>

      <!-- Content area. The lock overlay is a sibling of the scroll container and is
           positioned against this box, so it stays centred over the content while the
           page scrolls underneath, and never covers the sidebar. -->
      <div class="flex-1 relative overflow-hidden">
        <div ref="scroller" class="absolute inset-0 overflow-auto bg-gray-100">
          <!-- pointer-events-none kills every click inside the page, but wheel and
               touch scrolling still reach this scroll container, so reading works.
               Once the login prompt appears the page gets a light blur — enough to
               signal "locked" while the content stays teasingly readable. -->
          <div
            class="pointer-events-none select-none transition-[filter] duration-500"
            :class="isAccount ? 'blur-sm' : showLoginPrompt ? 'blur-[2px]' : ''"
          >
            <router-view />
          </div>
        </div>

        <!-- Account is fully locked: blurred, no way in. Every other page invites login. -->
        <div
          v-if="isAccount"
          class="absolute inset-0 flex items-center justify-center bg-white/40 px-4"
        >
          <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8 max-w-sm text-center">
            <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
              <Lock class="w-7 h-7 text-gray-400" />
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Account is locked</h3>
            <p class="text-gray-600 text-sm">
              Your profile, billing and subscription live here once you are a student.
            </p>
          </div>
        </div>

        <!-- pointer-events-none on the wrapper lets scrolling pass through to the page
             beneath; only the card re-enables pointer events, so Login is the one
             clickable thing on screen. -->
        <div
          v-else-if="showLoginPrompt"
          class="absolute inset-0 flex items-center justify-center pointer-events-none px-4 z-40"
        >
          <div class="pointer-events-auto bg-white rounded-2xl shadow-2xl border border-gray-200 p-8 max-w-sm text-center">
            <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-[#0055A4]/10 flex items-center justify-center">
              <Lock class="w-7 h-7 text-[#0055A4]" />
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Log in to use the portal</h3>
            <p class="text-gray-600 text-sm mb-6">
              Keep scrolling to look around. To open a course or use any control, log in and
              choose a plan to become a student.
            </p>
            <router-link
              to="/login"
              class="block w-full px-6 py-3 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-bold transition-colors"
            >
              Login
            </router-link>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue'
import { useRoute } from 'vue-router'
import { BarChart3, BookOpen, User, FileText, Menu, Lock, GraduationCap, ArrowLeft } from 'lucide-vue-next'
import { useSettingsStore } from '../stores/settings'

// How long a visitor gets to look at a page before the login prompt appears.
const PROMPT_DELAY_MS = 2000

const route = useRoute()
const settingsStore = useSettingsStore()
const isSidebarOpen = ref(false)
const scroller = ref(null)
const showLoginPrompt = ref(false)
let promptTimer = null

// Every page is reachable — the visitor browses the whole portal, read-only.
const demoMenu = ref([
  { name: 'Dashboard', path: '/demo/dashboard', icon: BarChart3 },
  { name: 'My Courses', path: '/demo/courses', icon: BookOpen },
  { name: 'Exam Prep', path: '/demo/exam-prep', icon: GraduationCap },
  { name: 'Homework', path: '/demo/homework', icon: FileText },
  { name: 'Account', path: '/demo/account', icon: User },
])

const isAccount = computed(() => route.path.startsWith('/demo/account'))

const pageTitle = computed(() => {
  const match = demoMenu.value
    .filter(item => item.path)
    .find(item => route.path.startsWith(item.path))
  return match ? match.name : 'Student Portal'
})

const isActive = (path) => route.path.startsWith(path)

const restartPromptTimer = () => {
  clearTimeout(promptTimer)
  showLoginPrompt.value = false

  if (isAccount.value) return

  promptTimer = setTimeout(() => { showLoginPrompt.value = true }, PROMPT_DELAY_MS)
}

// Each page gets its own look-around window, and the scroll position resets with it.
watch(() => route.path, () => {
  if (scroller.value) scroller.value.scrollTop = 0
  restartPromptTimer()
})

onBeforeUnmount(() => clearTimeout(promptTimer))

onMounted(() => {
  restartPromptTimer()
  settingsStore.fetchSettings()
})
</script>
