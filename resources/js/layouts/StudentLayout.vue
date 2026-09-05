<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Mobile Sidebar Backdrop -->
    <div 
      v-if="isSidebarOpen" 
      @click="isSidebarOpen = false" 
      class="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden"
    ></div>

    <!-- Sidebar -->
    <div 
      class="fixed inset-y-0 left-0 z-50 w-64 text-white shadow-lg transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-auto md:flex md:flex-col"
      :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
      style="background-color: #0055A4;"
    >
      <div class="p-6 border-b" style="border-color: #003d7a;">
        <h2 class="text-2xl font-bold">{{ settingsStore.siteName }}</h2>
        <p class="text-white/80 text-sm">Student Portal</p>
      </div>

      <nav class="mt-6 flex-1 space-y-1 px-2 overflow-y-auto">
        <template v-for="item in studentMenu" :key="item.path">
          <!-- Disabled state when payment not confirmed -->
          <div
            v-if="!auth.isPaymentConfirmed"
            :style="item.hidden ? 'display: none;' : ''"
            class="flex items-center justify-between px-4 py-3 rounded-lg text-white/50 cursor-not-allowed"
          >
            <div class="flex items-center">
              <component :is="item.icon" class="w-5 h-5 mr-3" />
              <span class="font-medium">{{ item.name }}</span>
            </div>
            <Lock class="w-4 h-4" />
          </div>
          <!-- Active state when payment confirmed -->
          <router-link
            v-else
            :to="item.path"
            class="flex items-center justify-between px-4 py-3 rounded-lg transition-colors duration-200"
            :class="isActive(item.path) ? 'text-white' : 'text-white/90 hover:opacity-80'"
            :style="[item.hidden ? 'display: none;' : '', isActive(item.path) ? 'background-color: #003d7a;' : '']"
            @click="isSidebarOpen = false"
          >
            <div class="flex items-center">
              <component :is="item.icon" class="w-5 h-5 mr-3" />
              <span class="font-medium">{{ item.name }}</span>
            </div>
            <span
              v-if="item.name === 'Homework' && homeworkCount > 0"
              class="bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"
            >
              {{ homeworkCount > 9 ? '9+' : homeworkCount }}
            </span>
          </router-link>
        </template>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">
      <!-- Top Bar -->
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
            <!-- Hamburger Menu -->
            <button
              @click="isSidebarOpen = !isSidebarOpen"
              class="mr-4 text-gray-600 hover:text-gray-900 focus:outline-none md:hidden"
            >
              <Menu class="w-6 h-6" />
            </button>
          <h1 class="text-xl font-bold text-gray-800 truncate">{{ pageTitle }}</h1>
        </div>

        <div class="flex items-center space-x-2 sm:space-x-4">
          <div class="flex items-center space-x-3">
            <div class="hidden sm:block text-right">
              <p class="text-sm font-medium text-gray-800">{{ auth.user?.first_name || 'Student' }}</p>
              <p class="text-xs text-gray-500">{{ auth.user?.user_type || 'Student' }}</p>
            </div>
            <div
              class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-700 font-bold text-sm sm:text-lg shadow-sm border border-amber-100 shrink-0"
            >
              {{ getUserInitial() }}
            </div>
          </div>
          <button
            @click="handleLogout"
            :disabled="isLoggingOut"
            class="ml-2 sm:ml-4 px-3 sm:px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
          >
            <Loader v-if="isLoggingOut" class="w-4 h-4 animate-spin" />
            <span class="hidden sm:inline">{{ isLoggingOut ? 'Logging out...' : 'Logout' }}</span>
            <span class="sm:hidden"><LogOut class="w-4 h-4" /></span>
          </button>
        </div>
      </div>

      <!-- Page Content -->
      <div class="flex-1 overflow-auto bg-gray-100 relative">
        <!-- Payment Pending Overlay -->
        <div
          v-if="!auth.isPaymentConfirmed"
          class="absolute inset-0 z-20 flex items-center justify-center"
        >
          <!-- Blurred Background -->
          <div class="absolute inset-0 bg-gray-100/80 backdrop-blur-sm"></div>

          <!-- Message Card -->
          <div class="relative bg-white rounded-2xl shadow-xl p-8 max-w-md mx-4 text-center border border-gray-200">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-amber-100 flex items-center justify-center">
              <Lock class="w-8 h-8 text-amber-600" />
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Almost Ready!</h3>
            <p class="text-gray-600 mb-4">
              We are currently setting up your profile, access will be granted shortly.
            </p>
            <p class="text-sm text-gray-500">
              If you need assistance, please contact administration.
            </p>
            <div class="mt-6 pt-4 border-t border-gray-100">
              <p class="text-xs text-gray-400">
                Your account is active. You can logout and return once access is granted.
              </p>
            </div>
          </div>
        </div>

        <!-- Actual Page Content (blurred when payment pending) -->
        <div :class="{ 'blur-sm pointer-events-none select-none': !auth.isPaymentConfirmed }">
          <router-view />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { BarChart3, BookOpen, User, Loader, FileText, Menu, LogOut, Lock, GraduationCap, ArrowLeft } from 'lucide-vue-next'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import { useToastStore } from '../stores/toast'
import { useSettingsStore } from '../stores/settings'

const router = useRouter()
const route = useRoute()
const auth = useAuthStore()
const toast = useToastStore()
const settingsStore = useSettingsStore()
const isLoggingOut = ref(false)
const homeworkCount = ref(0)
const isSidebarOpen = ref(false)

// Fetch pending homework count
const fetchHomeworkCount = async () => {
  try {
    const response = await axios.get('/api/student/homework/pending-count')
    if (response.data.success) {
      homeworkCount.value = response.data.data.count || 0
    }
  } catch (error) {
    console.error('Failed to fetch homework count:', error)
  }
}

// Check for maintenance mode
const checkMaintenanceMode = async () => {
  try {
    const response = await axios.get('/api/student-portal/maintenance-status')
    if (response.data.success && response.data.data.maintenance_mode) {
      // Redirect to maintenance page
      router.push('/student/maintenance')
    }
  } catch (error) {
    console.error('Failed to check maintenance status:', error)
  }
}

onMounted(() => {
  checkMaintenanceMode()
  settingsStore.fetchSettings()
  fetchHomeworkCount()
})

const studentMenu = ref([
  { name: 'Dashboard', path: '/student/dashboard', icon: BarChart3 },
  { name: 'My Courses', path: '/student/courses', icon: BookOpen },
  { name: 'Exam Prep', path: '/student/exam-prep', icon: GraduationCap },
  { name: 'Homework', path: '/student/homework', icon: FileText },
  { name: 'Account', path: '/student/account', icon: User }
])

const pageTitle = computed(() => {
  const menuItem = studentMenu.value.find(item => item.path === route.path)
  return menuItem ? menuItem.name : 'Student Portal'
})

const isActive = (path) => {
  return route.path === path
}

const getUserInitial = () => {
  const firstName = auth.user?.first_name || 'S'
  return firstName.charAt(0).toUpperCase()
}

const handleLogout = async () => {
  isLoggingOut.value = true
  try {
    // 1. Clear API Auth state
    await auth.logout()
    
    // 2. Clear Web Session (Blade) via Axios to stay on the same port
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    await axios.post('/logout', { _token: csrfToken })
    
    // 3. Hard redirect to Homepage staying on the same origin (port)
    window.location.href = '/'
  } catch (error) {
    console.error('Logout error:', error)
    // Fallback redirect even if logout fails
    window.location.href = '/'
  }
}
</script>

<style scoped>
/* Smooth transitions for active states */
.router-link-active {
  background-color: #003d7a !important;
  color: white !important;
}
</style>
