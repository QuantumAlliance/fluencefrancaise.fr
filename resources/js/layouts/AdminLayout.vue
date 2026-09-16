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
      class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white shadow-lg transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-auto md:flex md:flex-col"
      :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <div class="p-6 border-b border-gray-700">
        <h2 class="text-2xl font-bold">{{ settingsStore.siteName }}</h2>
        <p class="text-gray-400 text-sm">Admin Dashboard</p>
      </div>

      <nav class="mt-6 flex-1 space-y-1 px-2 overflow-y-auto">
        <router-link
          v-for="item in adminMenu"
          :key="item.path"
          :to="item.path"
          class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200"
          :class="isActive(item.path) ? 'text-white' : 'text-gray-300 hover:bg-gray-800'"
          :style="isActive(item.path) ? 'background-color: #0055A4;' : ''"
          @click="isSidebarOpen = false"
        >
          <component :is="item.icon" class="w-5 h-5 mr-3" />
          <span class="font-medium">{{ item.name }}</span>
        </router-link>
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
              <p class="text-sm font-medium text-gray-800">{{ auth.user?.first_name || 'Admin' }}</p>
              <p class="text-xs text-gray-500">
                {{ auth.user?.user_type === 'super_admin' ? 'Super Administrator' : 'Administrator' }}
              </p>
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
      <div class="flex-1 overflow-auto bg-gray-100 p-4 sm:p-6 lg:p-8">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { BarChart3, BookOpen, Users, GraduationCap, ClipboardList, FileText, User, Settings, Loader, Library, Menu, LogOut, Database, CalendarCheck, UserCog } from 'lucide-vue-next'
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
const isSidebarOpen = ref(false)

const adminMenu = computed(() => {
  const menu = [
    { name: 'Dashboard', path: '/admin/dashboard', icon: BarChart3 },
    { name: 'Courses', path: '/admin/courses', icon: BookOpen },
    { name: 'Exam Prep', path: '/admin/exam-prep', icon: GraduationCap },
    { name: 'Books', path: '/admin/books', icon: Library },
    { name: 'Students', path: '/admin/students', icon: Users },
    { name: 'Tutors', path: '/admin/tutors', icon: UserCog },
    { name: 'Enrollments', path: '/admin/enrollments', icon: ClipboardList },
    { name: 'Attendance', path: '/admin/attendance', icon: CalendarCheck },
    { name: 'Pages', path: '/admin/pages', icon: FileText },
    { name: 'Manage Users', path: '/admin/manage-users', icon: User },
    { name: 'Settings', path: '/admin/settings', icon: Settings },
    { name: 'Cache', path: '/admin/cache', icon: Database }
  ]

  let filteredMenu = [...menu]

  // Filter based on hide permissions
  if (auth.user?.permissions?.hide_manage_users) {
    filteredMenu = filteredMenu.filter(item => item.path !== '/admin/manage-users')
  }
  
  if (auth.user?.permissions?.hide_attendance) {
    filteredMenu = filteredMenu.filter(item => item.path !== '/admin/attendance')
  }
  
  if (auth.user?.permissions?.hide_tutors) {
    filteredMenu = filteredMenu.filter(item => item.path !== '/admin/tutors')
  }

  return filteredMenu
})

const pageTitle = computed(() => {
  const menuItem = adminMenu.value.find(item => item.path === route.path)
  return menuItem ? menuItem.name : 'Admin Dashboard'
})

const isActive = (path) => {
  return route.path === path
}

const getUserInitial = () => {
  const firstName = auth.user?.first_name || 'A'
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

// Redirect if accessing restricted route
onMounted(() => {
  const restrictedManageUsers = route.path === '/admin/manage-users' && auth.user?.permissions?.hide_manage_users
  const restrictedAttendance = route.path === '/admin/attendance' && auth.user?.permissions?.hide_attendance
  const restrictedTutors = route.path === '/admin/tutors' && auth.user?.permissions?.hide_tutors

  if (restrictedManageUsers || restrictedAttendance || restrictedTutors) {
    router.push('/admin/dashboard')
    toast.error('You do not have permission to access this page.')
  }
})

// Watch route changes to prevent navigation
watch(() => route.path, (newPath) => {
  const restrictedManageUsers = newPath === '/admin/manage-users' && auth.user?.permissions?.hide_manage_users
  const restrictedAttendance = newPath === '/admin/attendance' && auth.user?.permissions?.hide_attendance
  const restrictedTutors = newPath === '/admin/tutors' && auth.user?.permissions?.hide_tutors

  if (restrictedManageUsers || restrictedAttendance || restrictedTutors) {
    router.push('/admin/dashboard')
    toast.error('You do not have permission to access this page.')
  }
})
</script>

<style scoped>
/* Smooth transitions for active states */
.router-link-active {
  background-color: #0055A4 !important;
  color: white !important;
}
</style>
