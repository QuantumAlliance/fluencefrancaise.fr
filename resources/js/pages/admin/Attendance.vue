<template>
  <div class="attendance-tracker p-6">
    <!-- Page Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Google Meet Attendance Tracker</h1>
        <p class="text-gray-600 mt-1">Track and manage Google Meet attendance logs</p>
      </div>
    </div>

    <!-- Summary Box (Refined) -->
    <div v-if="selectedStaff" class="bg-white border-l-4 border-purple-600 rounded-xl border border-gray-200 p-6 mb-8 transition-all">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="p-3 bg-purple-50 rounded-2xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Instructor Hours</h3>
            <div class="flex items-baseline gap-2">
              <span class="text-3xl font-bold text-gray-900">{{ staffDuration.formatted_duration || '0m' }}</span>
              <span class="text-sm text-gray-500 font-medium">for {{ selectedStaff }}</span>
            </div>
          </div>
        </div>
        <div class="hidden md:block">
           <div class="text-[10px] font-bold text-purple-600 bg-purple-50 px-3 py-1 rounded-full uppercase">Active Filtered View</div>
        </div>
      </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-8">
      <div class="flex items-center gap-2 mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
        </svg>
        <h3 class="text-md font-bold text-gray-800">Filter Dataset</h3>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Staff Dropdown -->
        <div class="space-y-2">
          <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider">Instructor/Staff</label>
          <div class="relative">
            <select
              v-model="selectedStaff"
              @change="onStaffChange"
              class="w-full pl-4 pr-10 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 bg-gray-50/50 text-sm font-medium appearance-none cursor-pointer transition-all"
            >
              <option value="">All Registered Staff</option>
              <option v-for="email in staffEmails" :key="email" :value="email">
                {{ email }}
              </option>
            </select>
            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Start Date -->
        <div class="space-y-2">
          <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider">Start Date</label>
          <flat-pickr
            v-model="filters.start_date"
            :config="flatpickrConfig"
            @on-change="loadAttendanceData"
            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 bg-gray-50/50 text-sm font-medium cursor-pointer transition-all"
            placeholder="MM/DD/YYYY"
          ></flat-pickr>
        </div>

        <!-- End Date -->
        <div class="space-y-2">
          <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider">End Date</label>
          <flat-pickr
            v-model="filters.end_date"
            :config="flatpickrConfig"
            @on-change="loadAttendanceData"
            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 bg-gray-50/50 text-sm font-medium cursor-pointer transition-all"
            placeholder="MM/DD/YYYY"
          ></flat-pickr>
        </div>

        <!-- Search Bar -->
        <div class="space-y-2">
          <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider">Global Search</label>
          <div class="relative">
            <input
              type="text"
              v-model="filters.search"
              @input="debouncedSearch"
              placeholder="Attendee, code, name..."
              class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 bg-gray-50/50 text-sm font-medium transition-all"
            />
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-wrap items-center justify-between mt-8 pt-6 border-t border-gray-100 gap-4">
        <div class="flex flex-wrap items-center gap-3">
          <button
            @click="syncWithGoogle"
            :disabled="syncing"
            class="px-6 py-2.5 bg-purple-600 text-white rounded-xl font-bold text-sm hover:bg-purple-700 transition-all flex items-center gap-2 disabled:opacity-50"
          >
            <svg v-if="syncing" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            {{ syncing ? 'Synchronizing...' : 'Pull Google Logs' }}
          </button>

          <button
            @click="exportData"
            class="px-6 py-2.5 bg-white text-gray-700 border border-gray-200 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export CSV
          </button>
        </div>

        <button
          @click="clearFilters"
          class="px-5 py-2.5 text-gray-500 hover:text-red-500 font-bold text-sm transition-all flex items-center gap-1.5"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          Reset Filters
        </button>
      </div>
    </div>

    <!-- View Mode Switcher -->
    <div class="flex items-center gap-1 p-1 bg-gray-200/50 rounded-xl w-fit mb-6 border border-gray-200">
      <button
        @click="viewMode = 'sessions'"
        :class="viewMode === 'sessions' ? 'bg-white text-purple-600 ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700'"
        class="px-5 py-2 rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all duration-200"
      >
        Grouped Sessions
      </button>
      <button
        @click="viewMode = 'individual'"
        :class="viewMode === 'individual' ? 'bg-white text-purple-600 ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700'"
        class="px-5 py-2 rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all duration-200"
      >
        Individual Logs
      </button>
    </div>

    <!-- Data Presentation -->
    <div v-if="loading" class="bg-gray-50/50 border border-gray-100 rounded-3xl p-24 text-center">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600 mx-auto"></div>
      <p class="text-gray-500 mt-6 font-medium">Loading attendance data...</p>
    </div>

    <template v-else>
      <!-- AUTO LOGS (SESSIONS VIEW) -->
      <div v-if="viewMode === 'sessions'" class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div v-if="sessions.length === 0" class="p-20 text-center text-gray-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
          </svg>
          <p class="text-lg font-bold">No Records Found</p>
          <p class="text-sm font-medium">Verify instructors or expand the date range.</p>
        </div>

        <table v-else class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organizer</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participants</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Duration</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meeting Info</th>
            </tr>
          </thead>
          <tbody v-for="group in groupedSessions" :key="group.title" class="divide-y divide-gray-100">
            <!-- GROUP MONTH INDICATOR -->
            <tr class="bg-gray-100 cursor-pointer hover:bg-gray-200 transition-colors" @click="toggleGroup(group.title)">
              <td colspan="5" class="px-6 py-3 text-left text-sm font-bold text-gray-700 flex items-center gap-2 uppercase tracking-wider">
                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': expandedGroups[group.title] }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                {{ group.title }}
                <span class="text-xs font-normal text-gray-500 ml-2">({{ group.items.length }} sessions)</span>
              </td>
            </tr>

            <template v-if="expandedGroups[group.title]">
              <tr v-for="(session, index) in group.items" :key="index" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(session.event_time) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="isInternalEmail(session.organizer_email) ? 'text-purple-600 font-semibold' : 'text-gray-900'" class="text-sm">
                    {{ session.organizer_email }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="space-y-1">
                    <div v-for="(participant, pIndex) in session.participants" :key="pIndex" class="text-sm">
                      <span :class="isInternalEmail(participant.email) ? 'text-purple-600 font-semibold' : 'text-gray-700'">
                        {{ participant.email }}
                      </span>
                      <span class="text-gray-500 text-xs ml-2">({{ participant.formatted_duration }})</span>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="text-sm font-medium text-gray-900">{{ formatDuration(session.total_duration) }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ formatMeetingCode(session.meeting_code) || 'N/A' }}</div>
                  <div class="text-xs text-gray-500">{{ session.conference_id }}</div>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>

      <!-- AUTO LOGS (INDIVIDUAL VIEW) -->
      <div v-else-if="viewMode === 'individual'" class="bg-white rounded-lg shadow overflow-hidden">
        <div v-if="logs.length === 0" class="p-8 text-center text-gray-500">
          <p class="text-lg">No logs found</p>
          <p class="text-sm mt-2">Try adjusting your filters or sync with Google</p>
        </div>

        <table v-else class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Time</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organizer</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meeting Code</th>
            </tr>
          </thead>
          <tbody v-for="group in groupedLogs" :key="group.title" class="bg-white divide-y divide-gray-200 border-b border-gray-200">
            <!-- Group Header -->
            <tr class="bg-gray-100 cursor-pointer hover:bg-gray-200 transition-colors" @click="toggleGroup(group.title)">
              <td colspan="5" class="px-6 py-3 text-left text-sm font-bold text-gray-700 flex items-center gap-2 uppercase tracking-wider">
                <svg 
                  class="w-4 h-4 transition-transform duration-200" 
                  :class="{ 'rotate-90': expandedGroups[group.title] }"
                  fill="none" 
                  stroke="currentColor" 
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                {{ group.title }}
                <span class="text-xs font-normal text-gray-500 ml-2">({{ group.items.length }} logs)</span>
              </td>
            </tr>

            <!-- Group Items -->
            <template v-if="expandedGroups[group.title]">
              <tr v-for="log in group.items" :key="log.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(log.event_time) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="isInternalEmail(log.organizer_email) ? 'text-purple-600 font-semibold' : 'text-gray-900'"
                    class="text-sm"
                  >
                    {{ log.organizer_email }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="isInternalEmail(log.actor_email) ? 'text-purple-600 font-semibold' : 'text-gray-900'"
                    class="text-sm"
                  >
                    {{ log.actor_email }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ log.formatted_duration }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatMeetingCode(log.meeting_code) || 'N/A' }}
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useToast } from '../../composables/useToast'
import FlatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'

const toast = useToast()

// State
const loading = ref(true)
const syncing = ref(false)
const logs = ref([])
const sessions = ref([])
const staffEmails = ref([])
const selectedStaff = ref('')
const staffDuration = ref({})
const viewMode = ref('sessions')

// Filters
const filters = ref({
  start_date: '',
  end_date: '',
  search: '',
  organizer_email: '',
  actor_email: '',
})

const flatpickrConfig = {
  altInput: true,
  altFormat: "F j, Y",
  dateFormat: "Y-m-d",
}

// Internal email domain (highlight these)
const internalDomain = '@fluencefrancaise.fr'

const isInternalEmail = (email) => {
  return email && email.endsWith(internalDomain)
}

// Debounced search
let searchTimeout = null
const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    loadAttendanceData()
  }, 500)
}

// Load attendance data from database
const loadAttendanceData = async () => {
  loading.value = true
  try {
    const params = {
      start_date: filters.value.start_date || undefined,
      end_date: filters.value.end_date || undefined,
      search: filters.value.search || undefined,
      organizer_email: filters.value.organizer_email || undefined,
      actor_email: selectedStaff.value || undefined,
    }

    const response = await axios.get('/api/admin/meet-logs', { params })
    
    if (response.data.success) {
      logs.value = response.data.data.logs || []
      sessions.value = response.data.data.sessions || []
      
      // Auto-expand all groups by default when data loads
      const allGroups = [...new Set([
        ...sessions.value.map(s => getGroupKey(s.event_time)),
        ...logs.value.map(l => getGroupKey(l.event_time))
      ])]
      
      allGroups.forEach(key => {
        expandedGroups.value[key] = true
      })

      // If staff is selected, calculate their total duration based on current filters
      if (selectedStaff.value) {
        await fetchStaffDuration()
      }
    }
  } catch (error) {
    console.error('Error loading attendance data:', error)
    toast.error(error.response?.data?.message || 'Failed to load attendance data')
  } finally {
    loading.value = false
  }
}

// Grouping Logic
const expandedGroups = ref({})

const getGroupKey = (dateString) => {
  if (!dateString) return 'Unknown Date'
  const date = new Date(dateString)
  return date.toLocaleString('default', { month: 'long', year: 'numeric' })
}

const groupByMonth = (items) => {
  if (!items || !items.length) return []
  
  const groups = []
  let currentKey = ''
  let currentGroup = null
  
  items.forEach(item => {
    const key = getGroupKey(item.event_time)
    
    if (key !== currentKey) {
      currentKey = key
      currentGroup = { title: key, items: [] }
      groups.push(currentGroup)
    }
    currentGroup.items.push(item)
  })
  
  return groups
}

const groupedSessions = computed(() => groupByMonth(sessions.value))
const groupedLogs = computed(() => groupByMonth(logs.value))

const toggleGroup = (groupTitle) => {
  expandedGroups.value[groupTitle] = !expandedGroups.value[groupTitle]
}

// Load unique staff emails
const loadStaffEmails = async () => {
  try {
    const response = await axios.get('/api/admin/meet-logs/staff-emails')
    if (response.data.success) {
      staffEmails.value = response.data.data || []
    }
  } catch (error) {
    console.error('Error loading staff emails:', error)
  }
}

// Sync with Google API
const syncWithGoogle = async () => {
  syncing.value = true
  try {
    const params = {
      start_date: filters.value.start_date || undefined,
      end_date: filters.value.end_date || undefined,
    }

    const response = await axios.post('/api/admin/meet-logs/sync', params)
    
    if (response.data.success) {
      toast.success(response.data.message)
      // Reload data after sync
      await loadAttendanceData()
      await loadStaffEmails()
    }
  } catch (error) {
    console.error('Error syncing with Google:', error)
    toast.error(error.response?.data?.message || 'Failed to sync with Google. Please ensure you have authorized Google OAuth.')
  } finally {
    syncing.value = false
  }
}

// Fetch total duration for selected staff
const fetchStaffDuration = async () => {
  if (!selectedStaff.value) return

  try {
    const response = await axios.post('/api/admin/meet-logs/staff-duration', {
      email: selectedStaff.value,
      start_date: filters.value.start_date || undefined,
      end_date: filters.value.end_date || undefined,
    })

    if (response.data.success) {
      staffDuration.value = response.data.data
    }
  } catch (error) {
    console.error('Error calculating staff duration:', error)
  }
}

// Handle staff change
const onStaffChange = async () => {
  if (!selectedStaff.value) {
    staffDuration.value = {}
  }
  // Reload data which will also trigger duration fetch if staff is selected
  await loadAttendanceData()
}

// Export data to CSV
const exportData = () => {
  const params = new URLSearchParams({
    start_date: filters.value.start_date || '',
    end_date: filters.value.end_date || '',
    search: filters.value.search || '',
    organizer_email: filters.value.organizer_email || '',
    actor_email: selectedStaff.value || '',
  })

  window.open(`/api/admin/meet-logs/export?${params.toString()}`, '_blank')
  toast.success('Exporting data...')
}

// Clear all filters
const clearFilters = () => {
  filters.value = {
    start_date: '',
    end_date: '',
    search: '',
    organizer_email: '',
    actor_email: '',
  }
  selectedStaff.value = ''
  staffDuration.value = {}
  loadAttendanceData()
}

// Format duration from seconds
const formatDuration = (seconds) => {
  const hours = Math.floor(seconds / 3600)
  const minutes = Math.floor((seconds % 3600) / 60)
  const secs = seconds % 60

  if (hours > 0) {
    return `${hours}h ${minutes}m`
  } else if (minutes > 0) {
    return `${minutes}m ${secs}s`
  } else {
    return `${secs}s`
  }
}

// Format date
const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

// Format meeting code (abc-defg-hij)
const formatMeetingCode = (code) => {
  if (!code) return ''
  // If already has dashes, return as is
  if (code.includes('-')) return code
  
  // Format 10-char code: 3-4-3
  if (code.length === 10) {
    return `${code.slice(0, 3)}-${code.slice(3, 7)}-${code.slice(7)}`
  }
  return code
}

// Initialize
onMounted(async () => {
  await Promise.all([
    loadAttendanceData(),
    loadStaffEmails()
  ])
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

.attendance-tracker {
  font-family: 'Inter', sans-serif !important;
}

/* Smooth transitions */
table tbody tr {
  transition: background-color 0.2s ease;
}

/* Custom scrollbar for tables */
.overflow-x-auto::-webkit-scrollbar {
  height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
