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
        <p class="text-white/80 text-sm">Tutor Portal</p>
      </div>

      <nav class="mt-6 flex-1 space-y-1 px-2 overflow-y-auto">
        <div
          v-for="item in tutorMenu"
          :key="item.path"
          @click="handleNavClick(item.path)"
          class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 cursor-pointer"
          :class="isActive(item.path) ? 'text-white' : 'text-white/90 hover:opacity-80'"
          :style="[item.hidden ? 'display: none;' : '', isActive(item.path) ? 'background-color: #003d7a;' : '']"
        >
          <component :is="item.icon" class="w-5 h-5 mr-3" />
          <span class="font-medium">{{ item.name }}</span>
        </div>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">
      <!-- Top Bar -->
      <div class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-8 z-30">
        <div class="flex items-center">
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
              <p class="text-sm font-medium text-gray-800">{{ auth.user?.first_name || 'Tutor' }}</p>
              <p class="text-xs text-gray-500">Tutor</p>
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
      <div class="flex-1 overflow-auto bg-gray-100">
        <router-view />
      </div>
    </div>

    <!-- Global Sticky Timer — visible on all pages except /tutor/students (Students.vue has its own) -->
    <transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 translate-y-4 scale-95"
      enter-to-class="opacity-100 translate-y-0 scale-100"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0 scale-100"
      leave-to-class="opacity-0 translate-y-4 scale-95"
    >
      <div
        v-if="timerStore.isTimerRunning && (!route.path.startsWith('/tutor/students') || timerStore.timerOwner === 'student-record')"
        class="fixed bottom-6 right-6 z-[70] select-none"
      >
        <div class="bg-gray-900 text-white rounded-2xl shadow-2xl border border-white/10 overflow-hidden min-w-[260px]">
          <!-- Top bar -->
          <div class="flex items-center gap-2 px-4 pt-3 pb-1">
            <span class="relative flex h-2.5 w-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
            </span>
            <span class="text-xs font-semibold text-green-400 uppercase tracking-widest">Session Running</span>
          </div>
          <div class="px-4 pb-1">
            <p class="text-xs text-gray-400 truncate max-w-[200px]">
              <i :class="timerStore.timerOwner === 'student-record' ? 'fas fa-user' : 'fas fa-users'" class="mr-1"></i>{{ timerStore.activeGroupName }}
            </p>
          </div>
          <!-- Timer display -->
          <div class="px-4 py-2 flex items-center justify-center">
            <span class="text-4xl font-mono font-bold tracking-tight text-white">
              {{ timerStore.formattedTimer }}
            </span>
          </div>
          <!-- Action button -->
          <div class="flex border-t border-white/10">
            <button
              @click="goToSession"
              class="flex-1 flex items-center justify-center gap-1.5 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-colors"
              title="Go back to the session"
            >
              <i class="fas fa-arrow-left text-xs"></i>
              Go to Session
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { BarChart3, Loader, Users, User, BookOpen, FileText, FolderOpen, Menu, LogOut, Rocket, Wallet, GraduationCap } from 'lucide-vue-next'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import { useToastStore } from '../stores/toast'
import { useSettingsStore } from '../stores/settings'
import { useTimerStore } from '../stores/timer'

const router = useRouter()
const route = useRoute()
const auth = useAuthStore()
const toast = useToastStore()
const settingsStore = useSettingsStore()
const timerStore = useTimerStore()
const isLoggingOut = ref(false)
const isSidebarOpen = ref(false)

const handleBeforeUnload = (e) => {
  if (timerStore.isTimerRunning) {
    const message = 'Hey, the timer is still running! You may lose the data. Are you sure you want to leave?'
    e.returnValue = message
    return message
  }
}

onMounted(() => {
  settingsStore.fetchSettings()
  window.addEventListener('beforeunload', handleBeforeUnload)
})

onUnmounted(() => {
  window.removeEventListener('beforeunload', handleBeforeUnload)
})

const tutorMenu = ref([
  { name: 'Dashboard', path: '/tutor/dashboard', icon: BarChart3 },
  { name: 'Courses', path: '/tutor/courses', icon: BookOpen },
  { name: 'Exam Prep', path: '/tutor/exam-prep', icon: GraduationCap },
  { name: 'Students', path: '/tutor/students', icon: Users },
  { name: 'Homework', path: '/tutor/homework', icon: FileText },
  { name: 'Material', path: '/tutor/material', icon: FolderOpen },
  { name: 'Pay & Career', path: '/tutor/pay', icon: Wallet },
  { name: 'Account', path: '/tutor/account', icon: User }
])

const pageTitle = computed(() => {
  const menuItem = tutorMenu.value.find(item => item.path === route.path)
  return menuItem ? menuItem.name : 'Tutor Portal'
})

const isActive = (path) => {
  return route.path === path
}

const getUserInitial = () => {
  const firstName = auth.user?.first_name || 'T'
  return firstName.charAt(0).toUpperCase()
}

const handleLogout = async () => {
  if (timerStore.isTimerRunning) {
    toast.error('Please stop and save the timer before logging out.')
    return
  }
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

const handleNavClick = (path) => {
  isSidebarOpen.value = false
  router.push(path)
}

const goToSession = () => {
  // For student-record timers, signal Students.vue to reopen the specific modal
  if (timerStore.timerOwner === 'student-record') {
    timerStore.pendingOpenStudentModal = true
  }
  router.push('/tutor/students/').catch(() => {})
}
</script>

<style scoped>
/* Smooth transitions for active states */
.router-link-active {
  background-color: #003d7a !important;
  color: white !important;
}
</style>
