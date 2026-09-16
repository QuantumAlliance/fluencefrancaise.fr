<template>
  <div class="p-4 md:p-8 space-y-8">
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight">
          {{ greeting }}, {{ auth.user?.first_name || 'Tutor' }}
        </h1>
        <p class="text-gray-500 mt-2 font-medium">Here's what's happening with your students today.</p>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
      <p class="ml-4 text-gray-600">Loading dashboard...</p>
    </div>

    <!-- Quick Stats -->
    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Assigned Students</p>
            <p class="text-4xl font-bold text-[#0055A4] mt-2">{{ stats.assigned_students || 0 }}</p>
          </div>
          <span class="text-5xl opacity-20">👥</span>
        </div>
      </div>

      <div v-if="stats.active_this_month > 0" class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Active This Month</p>
            <p class="text-4xl font-bold text-green-600 mt-2">{{ stats.active_this_month }}</p>
          </div>
          <span class="text-5xl opacity-20">📈</span>
        </div>
      </div>

      <div v-if="stats.avg_progress > 0" class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Avg. Progress</p>
            <p class="text-4xl font-bold text-blue-600 mt-2">{{ Math.round(stats.avg_progress) }}%</p>
          </div>
          <span class="text-5xl opacity-20">📊</span>
        </div>
      </div>
    </div>

    <!-- Assigned Students -->
    <div v-if="!loading" class="bg-white rounded-lg shadow mb-8">
      <div class="border-b border-gray-200 p-6 flex justify-between items-center">
        <h2 class="text-lg md:text-2xl font-semibold md:font-bold text-gray-800">Assigned Students</h2>
        <router-link
          to="/tutor/students"
          class="text-[#0055A4] hover:text-[#003d7a] font-medium text-sm"
        >
          View All →
        </router-link>
      </div>
      <div v-if="students.length === 0" class="p-8 text-center text-gray-500">
        <p class="mb-4">No students assigned yet</p>
        <router-link
          to="/tutor/students"
          class="inline-block px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg transition-colors"
        >
          Add Students
        </router-link>
      </div>
      <div v-else class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div
            v-for="student in students.slice(0, 6)"
            :key="student.id"
            class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
        >
            <div class="flex items-center gap-3 mb-3">
              <div class="w-12 h-12 rounded-full bg-[#0055A4]/20 flex items-center justify-center">
                <span class="text-xl font-bold text-[#0055A4]">
                  {{ (student.name || student.first_name || student.email || 'U').charAt(0).toUpperCase() }}
                </span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 truncate">
                  {{ student.name || `${student.first_name || ''} ${student.last_name || ''}`.trim() || 'Student' }}
                </p>
                <p class="text-sm text-gray-500 truncate">{{ student.email }}</p>
              </div>
            </div>
            <div class="flex items-center justify-between text-sm">
              <span class="text-gray-600">Progress: {{ Math.round(student.progress || 0) }}%</span>
              <span class="text-gray-500">{{ formatDate(student.last_activity) }}</span>
            </div>
            <router-link
              :to="`/tutor/students`"
              class="mt-3 block text-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors text-sm"
            >
              View Details
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Vacation Section -->
    <div v-if="!loading && vacation.max_days > 0" class="bg-white rounded-lg shadow mb-8 overflow-hidden">
      <div class="border-b border-gray-200 p-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-sm">
              <span class="text-white text-lg">🏖️</span>
            </div>
            <div>
              <h2 class="text-lg md:text-2xl font-semibold md:font-bold text-gray-800">My Vacation Days</h2>
              <p class="text-xs text-gray-500">Select and manage your vacation dates</p>
            </div>
          </div>
          <div class="flex items-center gap-4">
            <div class="text-right">
              <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Remaining</p>
              <p class="text-2xl font-bold" :class="vacation.remaining_days <= 2 ? 'text-red-500' : 'text-emerald-600'">
                {{ vacation.remaining_days }} <span class="text-sm font-normal text-gray-400">/ {{ vacation.max_days }}</span>
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Calendar -->
          <div>
            <div class="flex items-center justify-between mb-4">
              <button @click="prevMonth" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
              </button>
              <span class="text-base font-bold text-gray-800">{{ calendarMonthLabel }}</span>
              <button @click="nextMonth" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              </button>
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-1 mb-1">
              <div v-for="day in ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']" :key="day"
                   class="text-center text-xs font-bold text-gray-400 uppercase tracking-wider py-2">
                {{ day }}
              </div>
            </div>
            <div class="grid grid-cols-7 gap-1">
              <button v-for="(day, idx) in calendarDays" :key="idx"
                   @click="toggleVacationDay(day)"
                   :disabled="!day.date || day.isPast"
                   class="aspect-square flex items-center justify-center rounded-lg text-sm relative transition-all duration-200"
                   :class="getDayClass(day)">
                <span v-if="day.date" class="relative z-10">{{ day.dayNum }}</span>
              </button>
            </div>

            <!-- Legend -->
            <div class="flex items-center gap-4 mt-4 pt-4 border-t border-gray-100">
              <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded bg-emerald-100 border border-emerald-300"></div>
                <span class="text-xs text-gray-500">Vacation</span>
              </div>
              <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded bg-blue-100 border border-blue-300"></div>
                <span class="text-xs text-gray-500">Today</span>
              </div>
              <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded bg-gray-100 border border-gray-200"></div>
                <span class="text-xs text-gray-500">Past</span>
              </div>
            </div>
          </div>

          <!-- Vacation Dates List -->
          <div>
            <!-- Add Vacation Form -->
            <div v-if="selectedDate" class="mb-4 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
              <p class="text-sm font-bold text-emerald-800 mb-2">Add vacation: {{ formatVacDate(selectedDate) }}</p>
              <input
                v-model="vacationReason"
                type="text"
                placeholder="Reason (optional)"
                class="w-full px-3 py-2 border border-emerald-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none mb-3 bg-white"
              />
              <div class="flex gap-2">
                <button
                  @click="confirmVacation"
                  :disabled="savingVacation"
                  class="flex-1 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-sm font-medium transition-colors disabled:opacity-50"
                >
                  {{ savingVacation ? 'Saving...' : 'Confirm' }}
                </button>
                <button
                  @click="selectedDate = null; vacationReason = ''"
                  class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-sm font-medium transition-colors"
                >
                  Cancel
                </button>
              </div>
            </div>

            <h3 class="font-bold text-gray-800 text-sm mb-3">Booked Dates <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-bold">{{ vacation.vacations?.length || 0 }}</span></h3>

            <div v-if="vacation.vacations && vacation.vacations.length > 0" class="space-y-2 max-h-[320px] overflow-y-auto pr-1">
              <div v-for="v in vacation.vacations" :key="v.id"
                   class="flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-emerald-50/50 transition-colors group">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <span class="text-emerald-600 text-sm">📅</span>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-800 text-sm">{{ formatVacDate(v.date) }}</p>
                    <p v-if="v.reason" class="text-xs text-gray-500">{{ v.reason }}</p>
                  </div>
                </div>
                <button
                  v-if="!isDatePast(v.date)"
                  @click="removeVacation(v.id)"
                  class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all opacity-0 group-hover:opacity-100"
                  title="Remove vacation date"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                <span v-else class="text-xs text-gray-300 italic">Past</span>
              </div>
            </div>
            <div v-else class="text-center py-8">
              <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                <span class="text-2xl opacity-30">🏖️</span>
              </div>
              <p class="text-sm text-gray-400 font-medium">No vacation days booked yet</p>
              <p class="text-xs text-gray-300 mt-1">Click a date on the calendar to add one</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div v-if="!loading" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-base md:text-lg font-semibold md:font-bold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
          <router-link
            to="/tutor/students"
            class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors"
          >
            <span class="text-2xl">👥</span>
            <div class="flex-1">
              <p class="font-medium text-gray-800">Manage Students</p>
              <p class="text-sm text-gray-500">View and manage assigned students</p>
            </div>
            <span class="text-gray-400">→</span>
          </router-link>
          <router-link
            to="/tutor/courses"
            class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors"
          >
            <span class="text-2xl">📚</span>
            <div class="flex-1">
              <p class="font-medium text-gray-800">Browse Courses</p>
              <p class="text-sm text-gray-500">Preview all available courses</p>
            </div>
            <span class="text-gray-400">→</span>
          </router-link>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-base md:text-lg font-semibold md:font-bold text-gray-800 mb-4">Overview</h3>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-gray-600">Total Students</span>
            <span class="font-bold text-gray-800">{{ stats.assigned_students || 0 }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-gray-600">Active This Month</span>
            <span class="font-bold text-green-600">{{ stats.active_this_month || 0 }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-gray-600">Average Progress</span>
            <span class="font-bold text-blue-600">{{ Math.round(stats.avg_progress || 0) }}%</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import axios from 'axios'
import { useToast } from '../../composables/useToast'

const router = useRouter()
const auth = useAuthStore()
const toast = useToast()
const loading = ref(true)
const stats = ref({})
const courses = ref([])
const students = ref([])

// Vacation state
const vacation = ref({ vacations: [], used_days: 0, max_days: 0, remaining_days: 0 })
const selectedDate = ref(null)
const vacationReason = ref('')
const savingVacation = ref(false)
const calendarMonth = ref(new Date().getMonth())
const calendarYear = ref(new Date().getFullYear())

const greeting = computed(() => {
  const hour = new Date().getHours()
  if (hour < 12) return 'Good morning'
  if (hour < 18) return 'Good afternoon'
  return 'Good evening'
})

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  const now = new Date()
  const diffTime = Math.abs(now - date)
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

  if (diffDays === 0) return 'Today'
  if (diffDays === 1) return 'Yesterday'
  if (diffDays < 7) return `${diffDays} days ago`

  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
}

const loadDashboard = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/tutor/dashboard')
    const data = response.data.data || {}

    stats.value = {
      assigned_students: data.assigned_students || 0,
      my_courses: data.my_courses || 0,
      avg_progress: data.avg_progress || 0,
      active_this_month: data.active_this_month || 0
    }

    courses.value = data.courses || []
    students.value = data.students || []
  } catch (error) {
    console.error('Failed to load dashboard:', error)
    toast.error(error.response?.data?.message || 'Failed to load dashboard data')
  } finally {
    loading.value = false
  }
}

// Vacation methods
const loadVacation = async () => {
  try {
    const response = await axios.get('/api/tutor/vacations')
    if (response.data.success) {
      vacation.value = response.data.data
    }
  } catch (error) {
    console.error('Failed to load vacation:', error)
  }
}

const toggleVacationDay = (day) => {
  if (!day.date || day.isPast) return
  if (day.isVacation) {
    // Find and remove this vacation
    const v = vacation.value.vacations.find(v => v.date === day.date)
    if (v) removeVacation(v.id)
    return
  }
  // Check remaining days
  if (vacation.value.remaining_days <= 0) {
    toast.error('No vacation days remaining')
    return
  }
  selectedDate.value = day.date
  vacationReason.value = ''
}

const confirmVacation = async () => {
  if (!selectedDate.value) return
  savingVacation.value = true
  try {
    const response = await axios.post('/api/tutor/vacations', {
      date: selectedDate.value,
      reason: vacationReason.value || null,
    })
    if (response.data.success) {
      toast.success('Vacation date saved')
      selectedDate.value = null
      vacationReason.value = ''
      await loadVacation()
    }
  } catch (error) {
    console.error('Failed to save vacation:', error)
    toast.error(error.response?.data?.message || 'Failed to save vacation date')
  } finally {
    savingVacation.value = false
  }
}

const removeVacation = async (id) => {
  if (!confirm('Remove this vacation date?')) return
  try {
    const response = await axios.delete(`/api/tutor/vacations/${id}`)
    if (response.data.success) {
      toast.success('Vacation date removed')
      await loadVacation()
    }
  } catch (error) {
    console.error('Failed to remove vacation:', error)
    toast.error('Failed to remove vacation date')
  }
}

const isDatePast = (dateStr) => {
  const today = new Date()
  today.setHours(0, 0, 0, 0)
  return new Date(dateStr + 'T00:00:00') < today
}

const formatVacDate = (dateStr) => {
  const date = new Date(dateStr + 'T00:00:00')
  return date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' })
}

const calendarMonthLabel = computed(() => {
  const date = new Date(calendarYear.value, calendarMonth.value, 1)
  return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
})

const prevMonth = () => {
  if (calendarMonth.value === 0) {
    calendarMonth.value = 11
    calendarYear.value--
  } else {
    calendarMonth.value--
  }
}

const nextMonth = () => {
  if (calendarMonth.value === 11) {
    calendarMonth.value = 0
    calendarYear.value++
  } else {
    calendarMonth.value++
  }
}

const calendarDays = computed(() => {
  const firstDay = new Date(calendarYear.value, calendarMonth.value, 1)
  const lastDay = new Date(calendarYear.value, calendarMonth.value + 1, 0)
  const startPadding = firstDay.getDay()
  const days = []

  for (let i = 0; i < startPadding; i++) {
    days.push({ date: null })
  }

  const vacationDates = new Set(
    (vacation.value.vacations || []).map(v => v.date)
  )
  const today = new Date()
  today.setHours(0, 0, 0, 0)
  const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`

  for (let d = 1; d <= lastDay.getDate(); d++) {
    const dateStr = `${calendarYear.value}-${String(calendarMonth.value + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`
    const dateObj = new Date(calendarYear.value, calendarMonth.value, d)
    days.push({
      date: dateStr,
      dayNum: d,
      isVacation: vacationDates.has(dateStr),
      isToday: dateStr === todayStr,
      isPast: dateObj < today && dateStr !== todayStr,
    })
  }

  return days
})

const getDayClass = (day) => {
  if (!day.date) return 'text-transparent cursor-default'
  if (day.isPast && !day.isVacation) return 'text-gray-300 cursor-not-allowed bg-gray-50/50'
  if (day.isVacation) return 'bg-emerald-100 text-emerald-800 font-bold border border-emerald-300 cursor-pointer hover:bg-emerald-200'
  if (day.isToday) return 'bg-blue-100 text-blue-800 font-bold border border-blue-300 cursor-pointer hover:bg-blue-200'
  if (day.isPast) return 'text-gray-300 cursor-not-allowed'
  return 'text-gray-600 hover:bg-gray-100 cursor-pointer'
}


onMounted(() => {
  loadDashboard()
  loadVacation()
})
</script>

<style scoped>
/* Additional styling if needed */
</style>
