<template>
  <div class="">
    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
      <p class="ml-4 text-gray-600">Loading dashboard...</p>
    </div>

    <!-- KPI Cards -->
    <div v-else :class="shouldHideRevenue ? 'grid grid-cols-1 md:grid-cols-3 gap-6 mb-8' : 'grid grid-cols-1 md:grid-cols-4 gap-6 mb-8'">
      <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
        <div class="flex flex-col h-full">
          <div class="flex items-center justify-between mb-2">
            <p class="text-gray-600 text-sm font-medium">Active Students</p>

            <!-- Custom Premium Dropdown for Tutor Filter -->
            <div class="relative custom-dropdown" v-click-outside="() => showActiveStudentsDropdown = false">
              <button
                @click="showActiveStudentsDropdown = !showActiveStudentsDropdown"
                class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-gray-50 border border-gray-100 text-xs font-semibold text-blue-600 hover:bg-white hover:shadow-sm transition-all duration-200 max-w-[140px]"
              >
                <span class="truncate">{{ activeStudentsTutorLabel }}</span>
                <svg class="w-3 h-3 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': showActiveStudentsDropdown }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </button>

              <transition name="dropdown">
                <div v-if="showActiveStudentsDropdown" class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                  <button
                    v-for="opt in activeStudentsOptions"
                    :key="opt.value"
                    @click="selectActiveStudentsTutor(opt.value)"
                    class="w-full text-left px-4 py-2.5 text-xs font-semibold transition-all duration-200"
                    :class="activeStudentsTutorFilter === opt.value ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:bg-gray-50'"
                  >
                    {{ opt.label }}
                  </button>
                </div>
              </transition>
            </div>
          </div>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-4xl font-bold text-blue-600 mt-2">{{ displayActiveStudents }}</p>
              <p class="text-gray-500 text-xs mt-2">{{ activeStudentsSubLabel }}</p>
            </div>
            <span class="text-5xl opacity-20">👥</span>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Total Courses</p>
            <p class="text-4xl font-bold text-green-600 mt-2">{{ stats.total_courses || 0 }}</p>
            <p class="text-gray-500 text-xs mt-2">{{ stats.active_courses || 0 }} active</p>
          </div>
          <span class="text-5xl opacity-20">📚</span>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Total Enrollments</p>
            <p class="text-4xl font-bold text-[#0055A4] mt-2">{{ stats.total_enrollments || 0 }}</p>
            <p class="text-gray-500 text-xs mt-2">{{ enrollmentRate }}% conversion</p>
          </div>
          <span class="text-5xl opacity-20">📋</span>
        </div>
      </div>

      <div v-if="!shouldHideRevenue" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between mb-2">
              <p class="text-gray-600 text-sm font-medium">Revenue</p>
              
              <!-- Custom Premium Dropdown for Revenue -->
              <div class="relative custom-dropdown" v-click-outside="() => showRevenueDropdown = false">
                <button 
                  @click="showRevenueDropdown = !showRevenueDropdown"
                  class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-gray-50 border border-gray-100 text-xs font-semibold text-[#0055A4] hover:bg-white hover:shadow-sm transition-all duration-200"
                >
                  {{ revenueLabel }}
                  <svg class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': showRevenueDropdown }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                
                <transition name="dropdown">
                  <div v-if="showRevenueDropdown" class="absolute right-0 mt-2 w-40 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                    <button 
                      v-for="opt in revenueOptions" 
                      :key="opt.value"
                      @click="selectRevenueFilter(opt.value)"
                      class="w-full text-left px-4 py-2.5 text-xs font-semibold transition-all duration-200"
                      :class="revenueFilter === opt.value ? 'text-[#0055A4] bg-[#0055A4]/5' : 'text-gray-600 hover:bg-gray-50'"
                    >
                      {{ opt.label }}
                    </button>
                  </div>
                </transition>
              </div>
            </div>
          <div class="flex items-center justify-between">
            <div class="relative flex-1">
              <!-- Loading Overlay for Revenue -->
              <div v-if="isRevenueRefreshing" class="absolute inset-0 bg-white/60 flex items-center justify-start z-10">
                <div class="animate-spin rounded-full h-6 w-6 border-2 border-purple-500 border-t-transparent"></div>
              </div>
              
              <p :class="{ 'opacity-20': isRevenueRefreshing }" class="text-4xl font-bold text-purple-600 mt-2 transition-opacity">
                {{ formatCurrency(displayRevenue) }}
              </p>
              <p :class="{ 'opacity-20': isRevenueRefreshing }" class="text-gray-500 text-xs mt-2 text-nowrap transition-opacity">
                {{ revenueLabel }} revenue
              </p>
            </div>
            <span class="text-5xl opacity-20 ml-2">💰</span>
          </div>
        </div>
      </div>

    </div>

    <!-- Charts Section -->
    <div v-if="!loading" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <!-- Enrollment Trends -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-bold text-gray-800">Enrollment Trends</h3>
          <div class="flex gap-2">
            <!-- Custom Premium Dropdown for Trends -->
            <div class="relative custom-dropdown" v-click-outside="() => showTrendDropdown = false">
              <button 
                @click="showTrendDropdown = !showTrendDropdown"
                class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-gray-50 border border-gray-100 text-xs font-semibold text-[#0055A4] hover:bg-white hover:shadow-sm transition-all duration-200"
              >
                {{ trendLabel }}
                <svg class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': showTrendDropdown }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </button>
              
              <transition name="dropdown">
                <div v-if="showTrendDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                  <button 
                    v-for="opt in trendOptions" 
                    :key="opt.value"
                    @click="selectTrendFilter(opt.value)"
                    class="w-full text-left px-4 py-2.5 text-xs font-semibold transition-all duration-200"
                    :class="trendFilter === opt.value ? 'text-[#0055A4] bg-[#0055A4]/5' : 'text-gray-600 hover:bg-gray-50'"
                  >
                    {{ opt.label }}
                  </button>
                </div>
              </transition>
            </div>
          </div>
        </div>
        <div v-if="filteredTrends.length === 0" class="h-[300px] flex items-center justify-center text-gray-500">
          No data available
        </div>
        <div v-else class="relative h-[300px]">
          <!-- Trends Loading Overlay -->
          <div v-if="isTrendsRefreshing" class="absolute inset-0 bg-white/40 flex items-center justify-center z-10 backdrop-blur-[1px]">
            <div class="flex flex-col items-center">
              <div class="animate-spin rounded-full h-10 w-10 border-4 border-[#0055A4] border-t-transparent"></div>
              <p class="text-[#0055A4] text-xs font-bold mt-2">Updating...</p>
            </div>
          </div>

          <apexchart
            type="area"
            height="100%"
            :options="trendChartOptions"
            :series="trendChartSeries"
          ></apexchart>
        </div>
      </div>

      <!-- User Distribution -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">User Distribution</h3>
        <div v-if="userTypes.length === 0" class="text-gray-500 text-center py-8">
          No data available
        </div>
        <div v-else class="h-[300px]">
          <apexchart
            type="donut"
            height="100%"
            :options="userDistributionOptions"
            :series="userDistributionSeries"
          ></apexchart>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div v-if="!loading" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <!-- New Users -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-bold text-gray-800">Recent Sign-ups</h3>
          <router-link
            to="/admin/enrollments"
            class="text-sm text-[#0055A4] hover:text-[#003d7a] font-medium"
          >
            View All →
          </router-link>
        </div>
        <div v-if="recentUsers.length === 0" class="text-gray-500 text-center py-4">
          No recent sign-ups
        </div>
        <div v-else class="space-y-3">
          <div v-for="user in recentUsers.slice(0, 5)" :key="user.id" class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
            <div>
              <p class="text-gray-800 font-medium text-sm">{{ user.name }}</p>
              <p class="text-gray-600 text-xs">{{ formatDate(user.created_at) }}</p>
            </div>
            <span class="text-xs font-medium text-[#0055A4]">{{ user.user_type }}</span>
          </div>
        </div>
      </div>

      <!-- Popular Courses -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-bold text-gray-800">Top Courses</h3>
          <router-link
            to="/admin/courses"
            class="text-sm text-[#0055A4] hover:text-[#003d7a] font-medium"
          >
            View All →
          </router-link>
        </div>
        <div v-if="topCourses.length === 0" class="text-gray-500 text-center py-4">
          No courses available
        </div>
        <div v-else class="space-y-3">
          <div v-for="course in topCourses.slice(0, 5)" :key="course.id" class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
            <div class="flex-1">
              <p class="text-gray-800 font-medium text-sm">{{ course.title }}</p>
            </div>
            <span v-if="course.growth" class="text-xs font-medium text-green-600">↑ {{ course.growth }}%</span>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useToast } from '../../composables/useToast'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import VueApexCharts from 'vue3-apexcharts'

const apexchart = VueApexCharts

const toast = useToast()
const router = useRouter()
const auth = useAuthStore()
const loading = ref(true)
const stats = ref({})
const enrollmentTrends = ref([])
const allEnrollments = ref([])
const userTypes = ref([])
const recentUsers = ref([])
const topCourses = ref([])
const trendFilter = ref('6months')
const hoveredPoint = ref(null)
const revenueFilter = ref('total')
const isRevenueRefreshing = ref(false)
const isTrendsRefreshing = ref(false)
const activeStudentsTutorFilter = ref('all')

const activeStudentsOptions = [
  { label: 'All Active Students', short: 'All', value: 'all' },
  { label: 'Assigned to Teachers', short: 'With Teacher', value: 'assigned' }
]

// UI State for Custom Dropdowns
const showRevenueDropdown = ref(false)
const showTrendDropdown = ref(false)
const showActiveStudentsDropdown = ref(false)

const revenueOptions = [
  { label: 'All Time', value: 'total' },
  { label: 'This Month', value: 'month' },
  { label: 'This Week', value: 'week' }
]

const trendOptions = [
  { label: 'This Week', value: 'week' },
  { label: 'This Month', value: 'month' },
  { label: 'Last 6 Months', value: '6months' }
]

const selectRevenueFilter = (val) => {
  revenueFilter.value = val
  showRevenueDropdown.value = false
  saveRevenueFilter()
}

const selectTrendFilter = (val) => {
  trendFilter.value = val
  showTrendDropdown.value = false
  saveTrendFilter()
}

const selectActiveStudentsTutor = (val) => {
  activeStudentsTutorFilter.value = val
  showActiveStudentsDropdown.value = false
  saveActiveStudentsTutorFilter()
}

const saveActiveStudentsTutorFilter = async () => {
  try {
    await axios.put(`/api/preferences/admin_active_students_tutor`, { value: activeStudentsTutorFilter.value })
  } catch (error) {
    console.error('Failed to save active students tutor preference:', error)
  }
}

const activeStudentsTutorLabel = computed(() => {
  return activeStudentsOptions.find(o => o.value === activeStudentsTutorFilter.value)?.short || 'All'
})

const displayActiveStudents = computed(() => {
  if (activeStudentsTutorFilter.value === 'assigned') return stats.value.active_users_with_tutor || 0
  return stats.value.active_users || 0
})

const activeStudentsSubLabel = computed(() => {
  if (activeStudentsTutorFilter.value === 'assigned') {
    return `Assigned to Teachers · of ${stats.value.active_users || 0} active`
  }
  return `${stats.value.total_users || 0} total registered`
})

const trendLabel = computed(() => {
  return trendOptions.find(o => o.value === trendFilter.value)?.label || 'Filter'
})

const saveTrendFilter = async () => {
  isTrendsRefreshing.value = true
  try {
    // Artificial small delay for UX so it doesn't blink too fast
    await Promise.all([
      axios.put(`/api/preferences/admin_trend_filter`, { value: trendFilter.value }),
      new Promise(resolve => setTimeout(resolve, 600))
    ])
  } catch (error) {
    console.error('Failed to save trend preference:', error)
  } finally {
    isTrendsRefreshing.value = false
  }
}

const saveRevenueFilter = async () => {
  isRevenueRefreshing.value = true
  try {
    await Promise.all([
      axios.put(`/api/preferences/admin_revenue_filter`, { value: revenueFilter.value }),
      new Promise(resolve => setTimeout(resolve, 600))
    ])
  } catch (error) {
    console.error('Failed to save revenue preference:', error)
  } finally {
    isRevenueRefreshing.value = false
  }
}

const loadPreferences = async () => {
  try {
    const [trendRes, revenueRes] = await Promise.all([
      axios.get('/api/preferences/admin_trend_filter'),
      axios.get('/api/preferences/admin_revenue_filter')
    ])
    
    if (trendRes.data.data.value) trendFilter.value = trendRes.data.data.value
    if (revenueRes.data.data.value) revenueFilter.value = revenueRes.data.data.value
  } catch (error) {
    console.error('Failed to load preferences:', error)
  }
}

const displayRevenue = computed(() => {
  if (revenueFilter.value === 'week') return stats.value.revenue_this_week || 0
  if (revenueFilter.value === 'month') return stats.value.revenue_this_month || 0
  return stats.value.total_revenue || 0
})

const revenueLabel = computed(() => {
  return revenueOptions.find(o => o.value === revenueFilter.value)?.label || 'Filter'
})

// Custom Directive for clicking outside
const vClickOutside = {
  mounted(el, binding) {
    el.clickOutsideEvent = (event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value(event)
      }
    }
    document.body.addEventListener('click', el.clickOutsideEvent)
  },
  unmounted(el) {
    document.body.removeEventListener('click', el.clickOutsideEvent)
  }
}

// Check if current user has permission to hide total revenue
const shouldHideRevenue = computed(() => {
  if (!auth.user || !auth.user.permissions) return false
  return auth.user.permissions.hide_total_revenue === true
})

const enrollmentRate = computed(() => {
  if (!stats.value.total_users || stats.value.total_users === 0) return 0
  return Math.round((stats.value.total_enrollments / stats.value.total_users) * 100)
})

const filteredTrends = computed(() => {
  if (allEnrollments.value.length === 0) return []
  
  const now = new Date()
  let filtered = []
  
  if (trendFilter.value === 'week') {
    // Last 7 days
    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
    for (let i = 6; i >= 0; i--) {
      const dayDate = new Date(now)
      dayDate.setDate(now.getDate() - i)
      dayDate.setHours(0, 0, 0, 0)
      const dayEnd = new Date(dayDate)
      dayEnd.setHours(23, 59, 59, 999)
      const dayName = days[dayDate.getDay()]
      const dayEnrollments = allEnrollments.value.filter(e => {
        const eDate = new Date(e.enrollment_date || e.created_at)
        return eDate >= dayDate && eDate <= dayEnd
      }).length
      filtered.push({ name: dayName, value: dayEnrollments, date: dayDate })
    }
  } else if (trendFilter.value === 'month') {
    // Last 30 days grouped by week
    for (let i = 3; i >= 0; i--) {
      const weekStart = new Date(now)
      weekStart.setDate(now.getDate() - (i * 7))
      weekStart.setHours(0, 0, 0, 0)
      const weekEnd = new Date(weekStart)
      weekEnd.setDate(weekStart.getDate() + 6)
      weekEnd.setHours(23, 59, 59, 999)
      const weekEnrollments = allEnrollments.value.filter(e => {
        const eDate = new Date(e.enrollment_date || e.created_at)
        return eDate >= weekStart && eDate <= weekEnd
      }).length
      filtered.push({ 
        name: `Week ${4 - i}`, 
        value: weekEnrollments,
        date: weekStart
      })
    }
  } else {
    // Last 6 months (default)
    for (let i = 5; i >= 0; i--) {
      const monthDate = new Date(now.getFullYear(), now.getMonth() - i, 1)
      const monthEnd = new Date(monthDate.getFullYear(), monthDate.getMonth() + 1, 0, 23, 59, 59, 999)
      const monthName = monthDate.toLocaleDateString('en-US', { month: 'short' })
      const monthEnrollments = allEnrollments.value.filter(e => {
        const enrollDate = new Date(e.enrollment_date || e.created_at)
        return enrollDate >= monthDate && enrollDate <= monthEnd
      }).length
      filtered.push({ name: monthName, value: monthEnrollments, date: monthDate })
    }
  }
  
  return filtered
})

const trendChartSeries = computed(() => [{
  name: 'Enrollments',
  data: filteredTrends.value.map(t => t.value)
}])

const trendChartOptions = computed(() => ({
  chart: {
    type: 'area',
    toolbar: { show: false },
    zoom: { enabled: false }
  },
  colors: ['#0055A4'],
  dataLabels: { enabled: false },
  stroke: { curve: 'smooth', width: 2 },
  fill: {
    type: 'gradient',
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.45,
      opacityTo: 0.05,
      stops: [20, 100]
    }
  },
  xaxis: {
    categories: filteredTrends.value.map(t => t.name),
    axisBorder: { show: false },
    axisTicks: { show: false }
  },
  yaxis: {
    labels: {
      formatter: (val) => Math.floor(val)
    }
  },
  grid: {
    borderColor: '#f3f4f6',
    strokeDashArray: 4
  },
  tooltip: {
    enabled: true,
    theme: 'light',
    followCursor: true,
    intersect: false,
    fixed: {
      enabled: false
    },
    x: {
      show: true
    },
    y: {
      formatter: (val) => `${val} Enrollments`,
      title: {
        formatter: () => ''
      }
    },
    marker: {
      show: true
    }
  }
}))

const userDistributionSeries = computed(() => userTypes.value.map(type => type.count))

const userDistributionOptions = computed(() => ({
  labels: userTypes.value.map(type => type.name),
  colors: ['#3b82f6', '#22c55e', '#0055A4'],
  chart: {
    type: 'donut'
  },
  legend: {
    position: 'bottom',
    fontFamily: 'inherit',
    fontWeight: 500
  },
  dataLabels: {
    enabled: true,
    formatter: function (val) {
      return Math.round(val) + "%"
    }
  },
  plotOptions: {
    pie: {
      donut: {
        size: '70%',
        labels: {
          show: true,
          total: {
            show: true,
            label: 'Users',
            formatter: () => userTypes.value.reduce((acc, t) => acc + t.count, 0)
          }
        }
      }
    }
  }
}))

const showTooltip = (item, index, event) => {
  const svg = event.target.closest('svg')
  const svgRect = svg.getBoundingClientRect()
  const containerRect = svg.closest('.relative').getBoundingClientRect()
  
  const xPercent = index / (filteredTrends.value.length - 1 || 1)
  const x = xPercent * svgRect.width
  
  const yPercent = item.value / maxTrendValue.value
  const y = svgRect.height - (yPercent * svgRect.height)
  
  hoveredPoint.value = {
    name: item.name,
    value: item.value,
    x: x,
    y: y
  }
}

const hideTooltip = () => {
  hoveredPoint.value = null
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount)
}

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
    // Ensure current user data is loaded (needed for permission checks)
    if (!auth.user || !auth.user.permissions) {
      try {
        await auth.getCurrentUser()
      } catch (error) {
        console.error('Failed to load current user:', error)
      }
    }

    // Single API call for all dashboard data
    const response = await axios.get('/api/admin/dashboard-all')
    const data = response.data.data

    // Set stats
    stats.value = data.stats || {}

    // Set preferences (filters)
    if (data.preferences) {
      trendFilter.value = data.preferences.trend_filter || '6months'
      revenueFilter.value = data.preferences.revenue_filter || 'total'
      activeStudentsTutorFilter.value = data.preferences.active_students_tutor || 'all'
    }

    // Calculate user distribution from insights
    const insights = data.insights || {}
    const totalUsers = (insights.students || 0) + (insights.tutors || 0) + (insights.admins || 0)
    if (totalUsers > 0) {
      userTypes.value = [
        {
          name: 'Students',
          count: insights.students || 0,
          percentage: Math.round((insights.students / totalUsers) * 100),
          color: 'bg-blue-600'
        },
        {
          name: 'Tutors',
          count: insights.tutors || 0,
          percentage: Math.round((insights.tutors / totalUsers) * 100),
          color: 'bg-green-600'
        },
        {
          name: 'Admins',
          count: insights.admins || 0,
          percentage: Math.round((insights.admins / totalUsers) * 100),
          color: 'bg-[#0055A4]'
        }
      ]
    }

    // Set recent users
    if (data.recent_users && data.recent_users.length > 0) {
      recentUsers.value = data.recent_users.map(user => ({
        id: user.id,
        name: `${user.first_name || ''} ${user.last_name || ''}`.trim() || user.name,
        created_at: user.created_at,
        user_type: user.user_type
      }))
    }

    // Set enrollments for trends
    if (data.enrollments && data.enrollments.length > 0) {
      allEnrollments.value = data.enrollments

      // Generate initial 6-month trends
      const now = new Date()
      const trends = []
      for (let i = 5; i >= 0; i--) {
        const monthDate = new Date(now.getFullYear(), now.getMonth() - i, 1)
        const monthEnd = new Date(monthDate.getFullYear(), monthDate.getMonth() + 1, 0, 23, 59, 59, 999)
        const monthName = monthDate.toLocaleDateString('en-US', { month: 'short' })
        const monthEnrollments = allEnrollments.value.filter(e => {
          const enrollDate = new Date(e.enrollment_date || e.created_at)
          return enrollDate >= monthDate && enrollDate <= monthEnd
        }).length
        trends.push({ name: monthName, value: monthEnrollments, date: monthDate })
      }
      enrollmentTrends.value = trends
    }

    // Set top courses
    if (data.top_courses && data.top_courses.length > 0) {
      topCourses.value = data.top_courses.map(course => ({
        id: course.id,
        title: course.course_title || course.title || 'Untitled Course',
        enrollments: 0,
        growth: null
      }))
    }

    // Update stats with additional calculated values
    stats.value.student_count = insights.students || 0
    stats.value.active_courses = stats.value.total_courses || 0

  } catch (error) {
    console.error('Failed to load dashboard:', error)
    toast.error(error.response?.data?.message || 'Failed to load dashboard data')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadDashboard()
})
</script>

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-8px) scale(0.95);
}

.custom-dropdown button {
  outline: none !important;
}
</style>
