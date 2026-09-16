<template>
  <div class="p-4 md:p-8 space-y-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Account Settings</h1>
        <p class="text-gray-500 mt-1">Manage your profile, security, and view your tracking analytics.</p>
      </div>
    </div>

    <!-- Time Logs Section - TOP -->
    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
      <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-[#cb8e4f] rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-pie text-white text-sm"></i>
            </div>
            <div>
              <h3 class="text-xl font-bold text-gray-900 tracking-tight">Time Logs & Earnings</h3>
              <p class="text-[10px] font-semibold text-[#cb8e4f] uppercase tracking-widest">{{ periodLabel }} Sessions</p>
            </div>
        </div>
        <!-- Filters: custom date-range calendar picker + period toggle -->
        <div
          :class="loaded ? '' : 'opacity-50 pointer-events-none'"
          class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 self-start sm:self-auto transition-opacity"
        >
          <!-- Custom date-range calendar picker -->
          <div class="relative">
            <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-xs pointer-events-none z-10"
               :class="period === 'custom' ? 'text-[#cb8e4f]' : 'text-gray-400'"></i>
            <flat-pickr
              v-model="customRangeStr"
              :config="flatpickrConfig"
              @on-change="onCustomRangeChange"
              placeholder="Custom range"
              :class="period === 'custom' ? 'border-[#cb8e4f] text-[#cb8e4f] ring-2 ring-[#cb8e4f]/10' : 'border-gray-200 text-gray-500 hover:border-[#cb8e4f]/40'"
              class="w-full sm:w-48 pl-9 pr-8 py-2 bg-white border rounded-2xl text-xs font-bold tracking-tight cursor-pointer outline-none transition-all"
            ></flat-pickr>
            <button
              v-if="period === 'custom'"
              type="button"
              @click="setPeriod('all')"
              title="Clear custom range"
              class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500 transition-colors z-10"
            >
              <i class="fas fa-times-circle text-xs"></i>
            </button>
          </div>

          <!-- Period Toggle: This Week / This Month / All Time -->
          <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-2xl">
            <button
              v-for="opt in periodOptions"
              :key="opt.value"
              type="button"
              :disabled="!loaded"
              @click="setPeriod(opt.value)"
              :class="loaded && period === opt.value ? 'bg-white text-[#cb8e4f] shadow-sm' : 'text-gray-500 hover:text-gray-700'"
              class="px-4 py-2 rounded-xl text-xs font-bold tracking-tight transition-all whitespace-nowrap"
            >
              {{ opt.label }}
            </button>
          </div>
        </div>
      </div>

      <!-- Loading placeholder: shown until the saved period + records arrive, to avoid a flash of the wrong period -->
      <div v-if="!loaded" class="p-8">
        <div class="flex items-center justify-center py-16 text-gray-300">
          <i class="fas fa-spinner fa-spin text-2xl"></i>
        </div>
      </div>

      <div v-else class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
          <div v-for="i in 5" :key="i" 
                class="group p-5 rounded-2xl border border-gray-100 bg-white hover:border-[#cb8e4f]/20 hover:bg-[#cb8e4f]/5 transition-all duration-300">
            <div class="flex items-center gap-4 mb-3">
              <div :class="tieredRecords[i] > 0 ? 'bg-[#cb8e4f] text-white' : 'bg-gray-100 text-gray-400'" 
                    class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm transition-all duration-500 group-hover:scale-110">
                {{ i }}
              </div>
              <div>
                <h4 class="font-bold text-gray-800 text-xs tracking-tight group-hover:text-[#cb8e4f] transition-colors">{{ i }}-1 Session</h4>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ i === 1 ? 'Solo' : i + ' Students' }}</p>
              </div>
            </div>
            <div class="flex flex-col gap-1">
              <span :class="tieredRecords[i] > 0 ? 'text-gray-900 font-bold' : 'text-gray-200'" class="text-2xl tracking-tighter">
                {{ formatDurationShort(tieredRecords[i] || 0) }}
              </span>
              <div v-if="tieredRecords[i] > 0" class="mt-2 space-y-1">
                <div class="flex items-center justify-between text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                  <span>({{ tieredRecords[i] }}s ÷ 3600) × {{ formatCurrency(payRates[i] || 0) }}/hr</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-xl font-bold text-green-600 tracking-tight">{{ formatCurrency(tieredEarnings[i]) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Earnings Summary Bar (reflects selected period) -->
        <div class="mt-6 flex flex-col sm:flex-row items-stretch gap-4">
          <div class="flex-1 flex items-center justify-between p-6 rounded-2xl bg-gradient-to-r from-[#cb8e4f]/10 to-[#cb8e4f]/5 border border-[#cb8e4f]/20">
            <div>
              <p class="text-[10px] font-bold text-[#cb8e4f] uppercase tracking-widest">{{ periodLabel }} Earnings</p>
              <p class="text-3xl font-black text-gray-900 tracking-tighter mt-1">{{ formatCurrency(totalEarnings) }}</p>
            </div>
            <div class="text-right">
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Time</p>
              <p class="text-lg font-bold text-gray-700 mt-1">{{ formatDurationShort(totalSeconds) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Left Column: Profile & Info -->
      <div class="lg:col-span-8 space-y-8">
        <!-- Personal Information Card -->
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
          <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex items-center gap-4">
            <div class="w-10 h-10 bg-[#cb8e4f] rounded-xl flex items-center justify-center">
                <i class="fas fa-user-circle text-white text-lg"></i>
            </div>
            <div>
              <h3 class="text-xl font-bold text-gray-900 tracking-tight">Personal Information</h3>
              <p class="text-[10px] font-semibold text-[#cb8e4f] uppercase tracking-widest">Profile Details</p>
            </div>
          </div>

          <div class="p-8">
            <form @submit.prevent="updateProfile" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">First Name</label>
                  <input
                    v-model="editForm.firstName"
                    type="text"
                    placeholder="John"
                    class="w-full px-5 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-[#cb8e4f]/10 focus:border-[#cb8e4f] outline-none transition-all text-gray-700 font-medium"
                  />
                </div>
                <div class="space-y-2">
                  <label class="text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">Last Name</label>
                  <input
                    v-model="editForm.lastName"
                    type="text"
                    placeholder="Doe"
                    class="w-full px-5 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-[#cb8e4f]/10 focus:border-[#cb8e4f] outline-none transition-all text-gray-700 font-medium"
                  />
                </div>
              </div>

              <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">Email Address</label>
                <div class="relative">
                  <input
                    v-model="editForm.email"
                    type="email"
                    placeholder="john@example.com"
                    class="w-full pl-12 pr-5 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-[#cb8e4f]/10 focus:border-[#cb8e4f] outline-none transition-all text-gray-700 font-medium"
                  />
                  <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">Phone Number</label>
                  <div class="relative">
                    <input
                      v-model="editForm.phone"
                      type="tel"
                      placeholder="+1 (555) 000-0000"
                      class="w-full pl-12 pr-5 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-[#cb8e4f]/10 focus:border-[#cb8e4f] outline-none transition-all text-gray-700 font-medium"
                    />
                    <i class="fas fa-phone absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                  </div>
                </div>
                <div class="space-y-2">
                  <label class="text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">Timezone</label>
                  <div class="relative">
                    <select
                      v-model="editForm.timezone"
                      class="w-full pl-12 pr-5 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-[#cb8e4f]/10 focus:border-[#cb8e4f] outline-none transition-all text-gray-700 font-medium appearance-none"
                    >
                      <option value="UTC">UTC</option>
                      <option value="EST">EST</option>
                      <option value="CST">CST</option>
                      <option value="PST">PST</option>
                      <option value="CET">CET</option>
                    </select>
                    <i class="fas fa-globe absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 text-xs pointer-events-none"></i>
                  </div>
                </div>
              </div>

              <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">Biography</label>
                <textarea
                  v-model="editForm.biography"
                  rows="4"
                  placeholder="Tell us about yourself..."
                  class="w-full px-5 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-[#cb8e4f]/10 focus:border-[#cb8e4f] outline-none transition-all text-gray-700 font-medium resize-none"
                />
              </div>

              <div class="flex items-center justify-between pt-4">
                <div v-if="updateMessage" :class="updateSuccess ? 'text-green-600' : 'text-red-600'" class="text-sm font-bold flex items-center gap-2">
                  <i :class="updateSuccess ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
                  {{ updateMessage }}
                </div>
                <div v-else></div>
                <button
                  type="submit"
                  class="px-10 py-3.5 bg-[#cb8e4f] hover:bg-[#b87b3c] text-white font-bold rounded-2xl transition-all hover:-translate-y-0.5 active:translate-y-0"
                >
                  Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Right Column: Profile Card & Security -->
      <div class="lg:col-span-4 space-y-8">
        <!-- Profile Card -->
        <div class="relative group">
            <div class="absolute inset-0 bg-gradient-to-br from-[#cb8e4f] to-[#b87b3c] rounded-[2rem] blur-2xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
            <div class="relative bg-white rounded-3xl border border-gray-100 p-8 text-center flex flex-col items-center">
                <div class="relative mb-6">
                    <div class="w-28 h-28 rounded-full bg-gradient-to-br from-[#cb8e4f]/20 to-[#cb8e4f]/5 flex items-center justify-center border-4 border-white">
                        <span class="text-4xl font-black text-[#cb8e4f]">
                        {{ getInitials(profile) }}
                        </span>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-green-500 border-4 border-white rounded-full"></div>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">{{ profile.firstName }} {{ profile.lastName }}</h2>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-50 rounded-full mt-2 border border-gray-100">
                    <span class="w-1 h-1 rounded-full bg-[#cb8e4f]"></span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em]">{{ profile.email }}</span>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-50 w-full">
                    <p class="text-[10px] font-bold text-[#cb8e4f] uppercase tracking-[0.2em] mb-4">Account Status</p>
                    <div class="flex items-center justify-center gap-2 px-6 py-3 bg-green-50 rounded-2xl border border-green-100">
                        <i class="fas fa-shield-alt text-green-500"></i>
                        <span class="text-xs font-bold text-green-700">Verified Tutor Account</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Change Card -->
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
          <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex items-center gap-4">
              <div class="w-10 h-10 bg-gray-900 rounded-xl flex items-center justify-center">
                  <i class="fas fa-lock text-white text-sm"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900 tracking-tight">Security</h3>
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Update Password</p>
              </div>
          </div>

          <div class="p-8">
            <form @submit.prevent="changePassword" class="space-y-5">
              <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Current Password</label>
                <div class="relative">
                    <input
                        v-model="passwordForm.currentPassword"
                        type="password"
                        placeholder="••••••••"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-gray-100 focus:border-gray-900 outline-none transition-all text-sm font-medium"
                    />
                    <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                </div>
              </div>

              <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">New Password</label>
                <div class="relative">
                    <input
                        v-model="passwordForm.newPassword"
                        type="password"
                        placeholder="••••••••"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-gray-100 focus:border-gray-900 outline-none transition-all text-sm font-medium"
                    />
                    <i class="fas fa-plus absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                </div>
              </div>

              <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Confirm New Password</label>
                <div class="relative">
                    <input
                        v-model="passwordForm.confirmPassword"
                        type="password"
                        placeholder="••••••••"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-gray-100 focus:border-gray-900 outline-none transition-all text-sm font-medium"
                    />
                    <i class="fas fa-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                </div>
              </div>

              <button
                type="submit"
                class="w-full mt-4 py-3 bg-gray-900 hover:bg-black text-white text-sm font-bold rounded-xl transition-all"
              >
                Update Password
              </button>

              <div v-if="passwordMessage" :class="passwordSuccess ? 'text-green-600' : 'text-red-600'" class="text-[10px] font-bold text-center uppercase tracking-widest pt-2">
                {{ passwordMessage }}
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import axios from 'axios'
import FlatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'

const router = useRouter()
const auth = useAuthStore()
const updateMessage = ref('')
const updateSuccess = ref(false)
const passwordMessage = ref('')
const passwordSuccess = ref(false)
const records = ref([])
const groups = ref([])
const payRates = ref({})
const loaded = ref(false)

// Earnings period filter: 'all' | 'week' | 'month' | 'custom'
const period = ref('all')
const periodOptions = [
  { value: 'week', label: 'This Week' },
  { value: 'month', label: 'This Month' },
  { value: 'all', label: 'All Time' },
]

// Custom date-range calendar picker state
const customRangeStr = ref('')   // model string bound to flatpickr (e.g. "Jul 1, 2026 to Jul 10, 2026")
const customRange = ref([])       // [startDate, endDate] as Date objects
const flatpickrConfig = {
  mode: 'range',
  altInput: true,
  altFormat: 'M j',
  dateFormat: 'Y-m-d',
  locale: { rangeSeparator: ' – ' },
  // Tag this instance's calendar so our brand theme only targets it
  onReady: (selectedDates, dateStr, instance) => {
    instance.calendarContainer.classList.add('ff-flatpickr')
  },
}

const formatRangeLabel = (dates) => {
  if (!dates || dates.length < 2) return 'Custom Range'
  const fmt = { month: 'short', day: 'numeric' }
  return `${dates[0].toLocaleDateString('en-US', fmt)} – ${dates[1].toLocaleDateString('en-US', fmt)}`
}

const periodLabel = computed(() => {
  if (period.value === 'custom') return formatRangeLabel(customRange.value)
  return periodOptions.find(o => o.value === period.value)?.label || 'All Time'
})

// Fired by the calendar picker; only activate the custom filter once a full range is chosen
const onCustomRangeChange = (selectedDates) => {
  if (selectedDates.length === 2) {
    customRange.value = selectedDates
    period.value = 'custom'
  }
}

// Switch period. Only the three standard options are persisted (backend accepts week/month/all).
const setPeriod = async (value) => {
  if (period.value === value) return
  period.value = value
  // Leaving custom mode: clear the calendar selection
  customRange.value = []
  customRangeStr.value = ''
  try {
    await axios.put('/api/tutor/earnings-period', { period: value })
  } catch (error) {
    console.error('Failed to save earnings period preference:', error)
  }
}

const profile = reactive({
  firstName: '',
  lastName: '',
  email: '',
  profilePicture: '',
  phone: '',
  timezone: '',
  biography: ''
})

const editForm = reactive({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  timezone: '',
  biography: ''
})

const passwordForm = reactive({
  currentPassword: '',
  newPassword: '',
  confirmPassword: ''
})

const loadProfile = async () => {
  try {
    const response = await axios.get('/api/tutor/account')

    if (response.data.success) {
      const data = response.data.data
      profile.firstName = data.first_name || ''
      profile.lastName = data.last_name || ''
      profile.email = data.email || ''
      profile.profilePicture = data.profile_picture || ''
      profile.phone = data.phone || ''
      profile.timezone = data.timezone || 'UTC'
      profile.biography = data.biography || ''

      records.value = data.records || []
      groups.value = data.groups || []
      payRates.value = data.pay_rates || {}

      // Restore the tutor's saved earnings period filter
      if (['week', 'month', 'all'].includes(data.earnings_period)) {
        period.value = data.earnings_period
      }

      Object.assign(editForm, profile)
    }
  } catch (error) {
    console.error('Failed to load profile:', error)
  } finally {
    loaded.value = true
  }
}

const getInitials = (profile) => {
  if (!profile) return 'U'
  const name = `${profile.firstName || ''} ${profile.lastName || ''}`.trim() || profile.email || 'U'
  const parts = name.trim().split(/\s+/).filter(p => p.length > 0)
  if (parts.length === 0) return 'U'
  if (parts.length === 1) return parts[0].charAt(0).toUpperCase()
  return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase()
}

const updateProfile = async () => {
  try {
    const response = await axios.put('/api/tutor/account', {
      first_name: editForm.firstName,
      last_name: editForm.lastName,
      email: editForm.email,
      phone: editForm.phone,
      timezone: editForm.timezone,
      biography: editForm.biography
    })

    if (response.data.success) {
      Object.assign(profile, editForm)
      updateMessage.value = 'Profile updated successfully'
      updateSuccess.value = true
      setTimeout(() => {
        updateMessage.value = ''
      }, 3000)
    }
  } catch (error) {
    updateMessage.value = error.response?.data?.message || 'Failed to update profile'
    updateSuccess.value = false
  }
}

const changePassword = async () => {
  if (passwordForm.newPassword !== passwordForm.confirmPassword) {
    passwordMessage.value = 'New passwords do not match'
    passwordSuccess.value = false
    return
  }

  try {
    const response = await axios.put('/api/tutor/password', {
      current_password: passwordForm.currentPassword,
      new_password: passwordForm.newPassword
    })

    if (response.data.success) {
      passwordMessage.value = 'Password changed successfully'
      passwordSuccess.value = true
      passwordForm.currentPassword = ''
      passwordForm.newPassword = ''
      passwordForm.confirmPassword = ''
      setTimeout(() => {
        passwordMessage.value = ''
      }, 3000)
    }
  } catch (error) {
    passwordMessage.value = error.response?.data?.message || 'Failed to change password'
    passwordSuccess.value = false
  }
}



const parseTimerToSeconds = (timerStr) => {
  if (!timerStr || typeof timerStr !== 'string') return 0
  let totalSeconds = 0
  const hours = timerStr.match(/(\d+)h/)
  const minutes = timerStr.match(/(\d+)m/)
  const seconds = timerStr.match(/(\d+)s/)
  
  if (hours) totalSeconds += parseInt(hours[1]) * 3600
  if (minutes) totalSeconds += parseInt(minutes[1]) * 60
  if (seconds) totalSeconds += parseInt(seconds[1])
  
  return totalSeconds
}

const formatDurationShort = (totalSeconds) => {
  const h = Math.floor(totalSeconds / 3600)
  const m = Math.floor((totalSeconds % 3600) / 60)
  const s = totalSeconds % 60
  
  const parts = []
  if (h > 0) parts.push(`${h}hrs`)
  if (m > 0) parts.push(`${m}min`)
  if (s > 0 || (parts.length === 0 && s > 0)) parts.push(`${s}s`)
  if (parts.length === 0) return '0s'
  
  return parts.join(' ')
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount || 0)
}

const tieredEarnings = computed(() => {
  const earnings = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 }
  if (!payRates.value || !tieredRecords.value) return earnings

  for (let i = 1; i <= 5; i++) {
    const rate = parseFloat(payRates.value[i] || 0)
    const seconds = tieredRecords.value[i] || 0
    earnings[i] = (seconds / 3600) * rate
  }

  return earnings
})

// Returns true if a record date (YYYY-MM-DD) falls within the selected period
const isInPeriod = (dateStr, selected) => {
  if (selected === 'all') return true
  if (!dateStr) return false
  const d = new Date(dateStr)
  if (isNaN(d.getTime())) return false
  const now = new Date()

  if (selected === 'custom') {
    if (!customRange.value || customRange.value.length < 2) return true
    const start = new Date(customRange.value[0]); start.setHours(0, 0, 0, 0)
    const end = new Date(customRange.value[1]); end.setHours(23, 59, 59, 999)
    return d >= start && d <= end
  }

  if (selected === 'month') {
    return d.getFullYear() === now.getFullYear() && d.getMonth() === now.getMonth()
  }

  if (selected === 'week') {
    // Week starts on Monday
    const startOfWeek = new Date(now)
    const dayOffset = (now.getDay() + 6) % 7 // Monday = 0
    startOfWeek.setDate(now.getDate() - dayOffset)
    startOfWeek.setHours(0, 0, 0, 0)
    const endOfWeek = new Date(startOfWeek)
    endOfWeek.setDate(startOfWeek.getDate() + 7)
    return d >= startOfWeek && d < endOfWeek
  }

  return true
}

// Deduplicate raw records into unique sessions, preserving the session date
const allSessions = computed(() => {
  if (!records.value) return []

  const sessions = {}
  records.value.forEach(studentGroup => {
    if (studentGroup.records) {
      studentGroup.records.forEach(record => {
        if (record.timer && record.timer.trim() !== '' && record.timer !== 'No timer') {
          // Identify a single session by date + timer string
          // Sessions with exact same date/time/notes are likely the same session
          const sessionKey = `${record.date}_${record.timer}_${record.notes || ''}`
          if (!sessions[sessionKey]) {
            sessions[sessionKey] = {
              date: record.date,
              studentCount: 0,
              seconds: parseTimerToSeconds(record.timer)
            }
          }
          sessions[sessionKey].studentCount++
        }
      })
    }
  })

  return Object.values(sessions)
})

const tieredRecords = computed(() => {
  const aggregates = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 }

  allSessions.value.forEach(session => {
    if (!isInPeriod(session.date, period.value)) return
    const tier = Math.min(session.studentCount, 5)
    if (tier > 0) {
      aggregates[tier] += session.seconds
    }
  })

  return aggregates
})

// Totals for the selected period (sum across all tiers)
const totalSeconds = computed(() => {
  return Object.values(tieredRecords.value).reduce((sum, s) => sum + s, 0)
})

const totalEarnings = computed(() => {
  return Object.values(tieredEarnings.value).reduce((sum, e) => sum + e, 0)
})

onMounted(async () => {
  await loadProfile()
})
</script>

<!-- Global (unscoped) brand theme for the earnings date-range calendar.
     flatpickr renders into document.body, so scoped styles can't reach it;
     the .ff-flatpickr tag (added in flatpickrConfig.onReady) keeps these
     overrides from touching other calendars in the app. -->
<style>
.ff-flatpickr.flatpickr-calendar {
  border: 1px solid #f3f4f6;
  border-radius: 1rem;
  box-shadow: 0 18px 45px -15px rgba(17, 24, 39, 0.28);
  padding: 6px;
  margin-top: 8px;
}
.ff-flatpickr.flatpickr-calendar.arrowTop:before { border-bottom-color: #f3f4f6; }
.ff-flatpickr.flatpickr-calendar.arrowTop:after { border-bottom-color: #fff; }

/* Make the grid fill the padded container instead of flatpickr's fixed
   ~308px width — otherwise the last (Sat) column overflows and gets clipped. */
.ff-flatpickr .flatpickr-rContainer,
.ff-flatpickr .flatpickr-weekdays,
.ff-flatpickr .flatpickr-weekdaycontainer,
.ff-flatpickr .flatpickr-days,
.ff-flatpickr .dayContainer {
  width: 100%;
  min-width: 0;
  max-width: 100%;
}
.ff-flatpickr .flatpickr-day {
  max-width: 100%;
  flex-basis: 14.2857%;
}

/* Header: month + year — fixed-height row so title and both arrows share
   one vertical center */
.ff-flatpickr .flatpickr-months { height: 42px; padding: 2px 0 0; }
.ff-flatpickr .flatpickr-months .flatpickr-month { height: 42px; }
.ff-flatpickr .flatpickr-current-month {
  height: 42px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
}
.ff-flatpickr .flatpickr-current-month .flatpickr-monthDropdown-months,
.ff-flatpickr .flatpickr-current-month input.cur-year {
  font-weight: 700;
  color: #111827;
}
.ff-flatpickr .flatpickr-current-month .flatpickr-monthDropdown-months {
  border-radius: 8px;
  padding: 2px 6px;
  transition: background 0.15s ease;
}
.ff-flatpickr .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
  background: rgba(203, 142, 79, 0.1);
}

/* Nav arrows — square, inset from the rounded corners, centered */
.ff-flatpickr .flatpickr-months .flatpickr-prev-month,
.ff-flatpickr .flatpickr-months .flatpickr-next-month {
  top: 0;
  height: 42px;
  width: 34px;
  margin-top: 5px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 9px;
  transition: background 0.15s ease;
}
.ff-flatpickr .flatpickr-months .flatpickr-prev-month { left: 6px; }
.ff-flatpickr .flatpickr-months .flatpickr-next-month { right: 6px; }
.ff-flatpickr .flatpickr-months .flatpickr-prev-month svg,
.ff-flatpickr .flatpickr-months .flatpickr-next-month svg {
  width: 14px;
  height: 14px;
  fill: #9ca3af;
  transition: fill 0.15s ease;
}
.ff-flatpickr .flatpickr-months .flatpickr-prev-month:hover,
.ff-flatpickr .flatpickr-months .flatpickr-next-month:hover {
  background: rgba(203, 142, 79, 0.12);
}
.ff-flatpickr .flatpickr-months .flatpickr-prev-month:hover svg,
.ff-flatpickr .flatpickr-months .flatpickr-next-month:hover svg { fill: #cb8e4f; }

/* Weekday labels */
.ff-flatpickr span.flatpickr-weekday {
  color: #9ca3af;
  font-weight: 700;
  font-size: 10px;
  text-transform: uppercase;
  letter-spacing: 0.06em;
}

/* Day cells */
.ff-flatpickr .flatpickr-day {
  border-radius: 10px;
  color: #374151;
  font-weight: 500;
}
.ff-flatpickr .flatpickr-day:hover,
.ff-flatpickr .flatpickr-day:focus {
  background: rgba(203, 142, 79, 0.12);
  border-color: transparent;
}
.ff-flatpickr .flatpickr-day.prevMonthDay,
.ff-flatpickr .flatpickr-day.nextMonthDay { color: #d1d5db; }
.ff-flatpickr .flatpickr-day.flatpickr-disabled { color: #e5e7eb; }

/* Today */
.ff-flatpickr .flatpickr-day.today { border-color: rgba(203, 142, 79, 0.5); }
.ff-flatpickr .flatpickr-day.today:hover {
  background: #cb8e4f;
  border-color: #cb8e4f;
  color: #fff;
}

/* Selected + range endpoints */
.ff-flatpickr .flatpickr-day.selected,
.ff-flatpickr .flatpickr-day.startRange,
.ff-flatpickr .flatpickr-day.endRange,
.ff-flatpickr .flatpickr-day.selected:hover,
.ff-flatpickr .flatpickr-day.startRange:hover,
.ff-flatpickr .flatpickr-day.endRange:hover {
  background: #cb8e4f;
  border-color: #cb8e4f;
  color: #fff;
  box-shadow: none;
}
.ff-flatpickr .flatpickr-day.startRange { border-radius: 10px 0 0 10px; }
.ff-flatpickr .flatpickr-day.endRange { border-radius: 0 10px 10px 0; }
.ff-flatpickr .flatpickr-day.startRange.endRange { border-radius: 10px; }

/* Days between the two endpoints — one continuous band, not separate tiles */
.ff-flatpickr .flatpickr-day.inRange {
  background: rgba(203, 142, 79, 0.14);
  border-color: transparent;
  border-radius: 0;
  box-shadow: none;
}
/* Round the band where it meets the start/end of each week row */
.ff-flatpickr .dayContainer .flatpickr-day.inRange:nth-child(7n+1) { border-radius: 10px 0 0 10px; }
.ff-flatpickr .dayContainer .flatpickr-day.inRange:nth-child(7n)   { border-radius: 0 10px 10px 0; }
</style>
