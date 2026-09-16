<template>
  <div class="p-6 md:p-10 space-y-10 animate-in fade-in duration-700">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
      <div>
        <h1 class="text-4xl font-bold text-gray-900 tracking-tight">{{ workingStatus === 'full_time' ? 'Pay & Career' : 'Pay' }}</h1>
        <p class="text-gray-500 mt-2 font-medium">{{ workingStatus === 'full_time' ? 'Manage your compensation and career progression in one place.' : 'Manage your compensation details.' }}</p>
      </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
      
      <!-- Left Column: Compensation (Pay) -->
      <div :class="workingStatus === 'full_time' ? 'lg:col-span-4' : 'lg:col-span-12'" class="space-y-8">
        <div class="flex items-center gap-3 px-2">
          <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-[#0055A4] shadow-sm shadow-orange-100">
            <i class="fas fa-wallet text-sm"></i>
          </div>
          <h2 class="text-xl font-bold text-gray-900 tracking-tight">Compensation</h2>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all hover:shadow-md duration-500">
          <div class="px-8 py-6 border-b border-gray-50 bg-gradient-to-br from-gray-50/50 to-white">
            <h3 class="text-sm font-bold text-gray-900 tracking-tight">Hourly Rates</h3>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mt-1">Approved per headcount</p>
          </div>

          <div class="p-8 space-y-4">
            <div v-for="i in 5" :key="i" 
                 class="flex items-center justify-between p-5 rounded-xl border border-gray-50 bg-white hover:bg-orange-50/30 hover:border-orange-100 transition-all group">
              <div class="flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-sm font-black text-gray-400 group-hover:bg-[#0055A4] group-hover:text-white transition-all duration-500 group-hover:scale-110">
                  {{ i }}
                </div>
                <div>
                  <p class="font-bold text-gray-800 tracking-tight text-sm">{{ i }} Student{{ i > 1 ? 's' : '' }}</p>
                  <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">{{ i === 1 ? 'Solo Session' : 'Group Session' }}</p>
                </div>
              </div>
              <div class="flex items-baseline gap-1">
                <span class="text-xs font-bold text-gray-300">$</span>
                <span class="text-2xl font-black text-gray-900 tracking-tighter">{{ formatAmount(payRates[i]) }}</span>
              </div>
            </div>

            <div class="mt-8 p-6 bg-amber-50/50 rounded-2xl border border-amber-100 flex gap-4 items-start">
              <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                <i class="fas fa-info-circle text-amber-600 text-sm"></i>
              </div>
              <p class="text-xs text-amber-700 font-medium leading-relaxed">
                Rates are set by administration and are read-only. For adjustments, contact management.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Career Path (Full-time only) -->
      <div v-if="workingStatus === 'full_time'" class="lg:col-span-8 space-y-8">
        <div class="flex items-center gap-3 px-2">
          <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 shadow-sm shadow-purple-100">
            <i class="fas fa-rocket text-sm"></i>
          </div>
          <h2 class="text-xl font-bold text-gray-900 tracking-tight">Career Path</h2>
        </div>

        <!-- Content Area -->
        <div v-if="loadingStatus" class="flex flex-col items-center justify-center py-32 bg-white rounded-2xl border border-gray-100 shadow-sm">
          <div class="w-12 h-12 border-4 border-purple-100 border-t-purple-500 rounded-full animate-spin mb-6 shadow-sm"></div>
          <p class="text-xs font-black text-gray-300 uppercase tracking-widest">Sycing Status...</p>
        </div>

        <!-- Full Time: Vacation management -->
        <template v-else-if="workingStatus === 'full_time'">
          <!-- Vacation Quota Summary -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-7 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 transition-all hover:border-blue-100">
              <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm shadow-blue-50">
                <i class="fas fa-calendar-alt text-base"></i>
              </div>
              <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-0.5">Total Allowance</p>
                <p class="text-2xl font-black text-gray-900 tracking-tight">{{ vacation.max_days }} Days</p>
              </div>
            </div>
            <div class="bg-white p-7 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 transition-all hover:border-amber-100">
              <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-sm shadow-amber-50">
                <i class="fas fa-umbrella-beach text-base"></i>
              </div>
              <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-0.5">Days Used</p>
                <p class="text-2xl font-black text-gray-900 tracking-tight">{{ vacation.used_days }} Days</p>
              </div>
            </div>
            <div class="bg-white p-7 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 transition-all border-l-4 border-l-emerald-500 hover:border-emerald-100">
              <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm shadow-emerald-50">
                <i class="fas fa-hourglass-half text-base"></i>
              </div>
              <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-0.5">Remaining quota</p>
                <p class="text-2xl font-black text-emerald-600 tracking-tight">{{ vacation.remaining_days }} Days</p>
              </div>
            </div>
          </div>
          <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 items-start">
            <!-- Calendar Card -->
            <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm transition-all hover:shadow-md duration-500">
              <div class="flex items-center justify-between mb-10">
                <h3 class="font-bold text-gray-500 uppercase tracking-widest text-xs">Request Time Off</h3>
                <div class="flex items-center gap-3">
                  <button @click="prevMonth" class="p-2.5 hover:bg-gray-50 rounded-xl transition-all hover:scale-110 active:scale-95 shadow-sm bg-white border border-gray-100">
                    <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
                  </button>
                  <span class="text-xs font-black text-gray-700 uppercase tracking-widest w-40 text-center">{{ calendarMonthLabel }}</span>
                  <button @click="nextMonth" class="p-2.5 hover:bg-gray-50 rounded-xl transition-all hover:scale-110 active:scale-95 shadow-sm bg-white border border-gray-100">
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                  </button>
                </div>
              </div>

              <div class="grid grid-cols-7 gap-2.5 mb-4 px-2">
                <div v-for="day in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']" :key="day" class="text-center text-xs font-black text-gray-300 uppercase py-1">{{ day.charAt(0) }}</div>
              </div>
              
              <div class="grid grid-cols-7 gap-2.5 px-2">
                <button v-for="(day, idx) in calendarDays" :key="idx"
                     @click="handleCalendarClick(day)"
                     :disabled="!day.date || day.isPast || day.isBooked"
                     class="aspect-square flex items-center justify-center rounded-xl text-sm font-bold transition-all duration-300 hover:scale-110 shadow-sm border border-transparent"
                     :class="getDayClassPremium(day)">
                  <span :class="day.date ? 'opacity-100' : 'opacity-0'">{{ day.dayNum }}</span>
                </button>
              </div>

              <div class="flex items-center gap-10 mt-12 pt-10 border-t border-gray-50 justify-center">
                <div class="flex items-center gap-2.5">
                  <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.3)]"></div>
                  <span class="text-sm font-black text-gray-400 uppercase tracking-widest">Select</span>
                </div>
                <div class="flex items-center gap-2.5">
                  <div class="w-2.5 h-2.5 rounded-full bg-amber-200 shadow-sm"></div>
                  <span class="text-sm font-black text-gray-400 uppercase tracking-widest">Booked</span>
                </div>
                <div class="flex items-center gap-2.5">
                  <div class="w-2.5 h-2.5 rounded-full bg-blue-100 shadow-sm border border-blue-200"></div>
                  <span class="text-sm font-black text-gray-400 uppercase tracking-widest">Today</span>
                </div>
              </div>
            </div>

            <!-- Form & List -->
            <div class="space-y-8">
              <div v-if="form.start_date" class="bg-gray-900 rounded-2xl p-8 shadow-2xl shadow-purple-900/10 animate-in slide-in-from-bottom-8 duration-500 relative overflow-hidden group">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-1000"></div>
                
                <h4 class="font-bold text-white text-sm mb-6 uppercase tracking-widest flex items-center gap-2">
                  <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-ping"></span>
                  Confirm Request
                </h4>

                <div class="bg-white/5 backdrop-blur-md rounded-xl p-6 mb-6 border border-white/5 shadow-inner">
                  <div class="flex justify-between items-center mb-6">
                    <div>
                      <p class="text-xs font-black text-purple-300 uppercase tracking-[0.2em] mb-1">Time Period</p>
                      <p class="text-white font-bold text-xs tracking-tight">{{ formatVacDate(form.start_date) }} — {{ formatVacDate(form.end_date) }}</p>
                    </div>
                    <div class="text-right">
                      <p class="text-xs font-black text-emerald-300 uppercase tracking-[0.2em] mb-1">Total</p>
                      <p class="text-2xl font-black text-white leading-none tracking-tighter">{{ requestedDays }} Days</p>
                    </div>
                  </div>
                  
                  <textarea 
                    v-model="form.reason"
                    rows="2"
                    placeholder="Reason (Optional)"
                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-xl text-xs text-white placeholder-purple-300/50 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all"
                  ></textarea>
                </div>

                <button 
                  @click="submitRequest"
                  :disabled="savingVacation || requestedDays <= 0"
                  class="w-full py-5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-black uppercase tracking-[0.3em] text-xs transition-all hover:scale-[1.02] active:scale-95 shadow-xl shadow-emerald-900/20 disabled:opacity-30"
                >
                  {{ savingVacation ? 'Submitting...' : 'Send Request' }}
                </button>
              </div>

              <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col max-h-[400px]">
                <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-gradient-to-r from-gray-50/50 to-white">
                  <h4 class="font-bold text-gray-800 text-xs uppercase tracking-widest">History</h4>
                  <div class="px-3 py-1 bg-purple-50 rounded-full text-xs font-black text-purple-600 tracking-tighter shadow-sm border border-purple-100">
                    {{ vacation.vacations?.length || 0 }} RECORDS
                  </div>
                </div>
                <div class="flex-1 overflow-y-auto divide-y divide-gray-50">
                  <div v-for="v in vacation.vacations" :key="v.id" class="px-8 py-5 hover:bg-gray-50/50 transition-all group flex items-center justify-between">
                    <div>
                      <p class="font-bold text-gray-800 text-[13px] tracking-tight">{{ formatVacDate(v.start_date) }} — {{ formatVacDate(v.end_date) }}</p>
                      <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ v.total_days }} Days</span>
                        <div class="w-1 h-1 rounded-full bg-gray-200"></div>
                        <span class="text-xs font-bold text-gray-400 italic">"{{ v.reason || 'No note' }}"</span>
                      </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                       <span 
                        :class="{
                          'bg-amber-100 text-amber-700 border-amber-200': v.status === 'pending',
                          'bg-emerald-100 text-emerald-700 border-emerald-200': v.status === 'approved',
                          'bg-red-100 text-red-700 border-red-200': v.status === 'rejected'
                        }"
                        class="text-xs px-3 py-1 rounded-full font-black uppercase tracking-widest border shadow-sm"
                      >
                        {{ v.status }}
                      </span>
                    </div>
                  </div>
                  <div v-if="!vacation.vacations?.length" class="p-20 text-center flex flex-col items-center opacity-20">
                    <i class="fas fa-calendar-times text-4xl mb-4"></i>
                    <p class="text-sm font-black uppercase tracking-widest">No previous requests</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>

        <!-- Part Time: Requirements -->
        <template v-else-if="workingStatus === 'part_time'">
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden p-10 md:p-14 transition-all hover:shadow-md duration-500">
            <div class="max-w-3xl mb-14">
              <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-purple-50 text-purple-600 rounded-full text-xs font-black uppercase tracking-widest mb-6 border border-purple-100">
                Performance Track
              </div>
              <h3 class="text-4xl font-bold text-gray-900 tracking-tight mb-6">Full-Time Advancement</h3>
              <p class="text-lg text-gray-500 font-medium leading-relaxed">
                Take the next professional leap at Focus Frame French. Achieving full-time status unlocks comprehensive benefits and priority scheduling.
              </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
              <div v-for="(req, i) in requirements" :key="i" 
                   class="bg-gray-50/50 p-6 rounded-xl flex items-center gap-5 border border-gray-100 hover:bg-white hover:border-purple-200 hover:shadow-sm transition-all duration-500 group">
                <div class="w-12 h-12 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-purple-600 font-black text-xs shadow-sm group-hover:bg-purple-600 group-hover:text-white group-hover:border-transparent transition-all duration-500">
                  {{ (i+1).toString().padStart(2, '0') }}
                </div>
                <p class="text-sm font-bold text-gray-700 leading-snug tracking-tight">{{ req }}</p>
              </div>
            </div>

            <div class="bg-gradient-to-br from-purple-900 via-gray-900 to-black rounded-2xl p-10 md:p-14 text-white relative overflow-hidden group shadow-2xl">
              <div class="absolute -top-32 -right-32 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl group-hover:bg-purple-500/30 transition-all duration-1000"></div>
              <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all duration-1000"></div>
              
              <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-12">
                <div class="max-w-lg">
                  <h4 class="text-xs font-black uppercase tracking-[0.4em] text-purple-300 mb-6">Inquiry Pathway</h4>
                  <p class="text-base font-medium leading-relaxed opacity-80 mb-8 border-l border-white/20 pl-6">
                    Full-time opportunities are reviewed monthly. Candidates must maintain availability during peak Vancouver slots (3 PM onwards) and exhibit top-tier student feedback.
                  </p>
                </div>
                <button class="px-10 py-5 bg-white text-gray-900 rounded-xl font-black uppercase tracking-[0.2em] text-xs hover:bg-emerald-500 hover:text-white transition-all shadow-xl hover:-translate-y-1.5 active:scale-95 shrink-0">
                  Open Inquiry
                </button>
              </div>
            </div>
          </div>
        </template>

        <div v-else class="text-center py-32 bg-white rounded-2xl border border-dashed border-gray-100">
          <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200">
            <i class="fas fa-lock text-3xl"></i>
          </div>
          <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Checking Career Entitlements</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, reactive } from 'vue'
import axios from 'axios'
import { useToast } from '../../composables/useToast'

const toast = useToast()

// Pay Data Logic
const payRates = ref({})
const formatAmount = (val) => {
  const num = parseFloat(val || 0)
  return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

// Career / Vacation Logic
const loadingStatus = ref(true)
const workingStatus = ref('')
const vacation = ref({ vacations: [], used_days: 0, max_days: 0, remaining_days: 0 })
const savingVacation = ref(false)

const requirements = [
  'Hold a past TEF/TCF certification (valid or expired).',
  'Maintain excellent attendance (95%+).',
  'Consistently receive positive student feedback.',
  'Take on at least 8+ classes per week, including minimum 2 group classes.',
  'Demonstrate reliability and quality over time at Focus Frame French.'
]

const form = reactive({
  start_date: '',
  end_date: '',
  reason: ''
})

const requestedDays = computed(() => {
  if (!form.start_date || !form.end_date) return 0
  const start = new Date(form.start_date + 'T00:00:00')
  const end = new Date(form.end_date + 'T00:00:00')
  if (end < start) return 0
  const diffTime = Math.abs(end - start)
  return Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1
})

const calendarMonth = ref(new Date().getMonth())
const calendarYear = ref(new Date().getFullYear())

const today = new Date()
const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`

const loadAllData = async () => {
  loadingStatus.value = true
  try {
    const response = await axios.get('/api/tutor/account')
    if (response.data.success) {
      payRates.value = response.data.data.pay_rates || {}
      workingStatus.value = response.data.data.working_status
    }

    if (workingStatus.value === 'full_time') {
      const vResponse = await axios.get('/api/tutor/vacations')
      if (vResponse.data.success) {
        vacation.value = vResponse.data.data
      }
    }
  } catch (error) {
    console.error('Portal sync error:', error)
  } finally {
    loadingStatus.value = false
  }
}

const getLocalDateStr = (date) => {
  return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`
}

const handleCalendarClick = (day) => {
  if (!day.date || day.isBooked) return
  if (day.isPast && !day.isToday) return
  
  const remaining = vacation.value.remaining_days || 0

  if (!form.start_date || (form.start_date && form.end_date !== form.start_date && form.end_date)) {
    if (remaining < 1) {
      toast.error("Low quota: You have 0 days remaining.")
      return
    }
    form.start_date = day.date
    form.end_date = day.date
  } else {
    const start = new Date(form.start_date + 'T00:00:00')
    const clicked = new Date(day.date + 'T00:00:00')
    
    if (clicked < start) {
      form.start_date = day.date
      form.end_date = day.date
    } else {
      const diffTime = Math.abs(clicked - start)
      const requested = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1
      
      if (requested > remaining) {
        toast.error(`You can only take ${remaining} day${remaining > 1 ? 's' : ''} leave currently.`)
        return
      }
      form.end_date = day.date
    }
  }
}

const submitRequest = async () => {
  if (requestedDays.value <= 0) return
  savingVacation.value = true
  try {
    const response = await axios.post('/api/tutor/vacations', form)
    if (response.data.success) {
      toast.success('Vacation request sent successfully')
      form.start_date = ''
      form.end_date = ''
      form.reason = ''
      await loadAllData()
    }
  } catch (error) {
    toast.error(error.response?.data?.message || 'Error submitting request')
  } finally {
    savingVacation.value = false
  }
}

const formatVacDate = (dateStr) => {
  if (!dateStr) return ''
  const date = new Date(dateStr + 'T00:00:00')
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
}

const calendarMonthLabel = computed(() => {
  const date = new Date(calendarYear.value, calendarMonth.value, 1)
  return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
})

const prevMonth = () => { calendarMonth.value === 0 ? (calendarMonth.value = 11, calendarYear.value--) : calendarMonth.value-- }
const nextMonth = () => { calendarMonth.value === 11 ? (calendarMonth.value = 0, calendarYear.value++) : calendarMonth.value++ }

const calendarDays = computed(() => {
  const firstDay = new Date(calendarYear.value, calendarMonth.value, 1)
  const lastDay = new Date(calendarYear.value, calendarMonth.value + 1, 0)
  const startPadding = firstDay.getDay()
  const days = []
  for (let i = 0; i < startPadding; i++) days.push({ date: null })
  
  const bookedSet = (vacation.value.vacations || []).reduce((acc, v) => {
    let s = new Date(v.start_date + 'T00:00:00'), e = new Date(v.end_date + 'T00:00:00')
    for (let d = new Date(s); d <= e; d.setDate(d.getDate() + 1)) acc.add(getLocalDateStr(d))
    return acc
  }, new Set())

  const previewSet = new Set()
  if (form.start_date && form.end_date) {
    let s = new Date(form.start_date + 'T00:00:00'), e = new Date(form.end_date + 'T00:00:00')
    for (let d = new Date(s); d <= e; d.setDate(d.getDate() + 1)) previewSet.add(getLocalDateStr(d))
  }

  const todayNorm = new Date(); todayNorm.setHours(0,0,0,0)
  for (let d = 1; d <= lastDay.getDate(); d++) {
    const dObj = new Date(calendarYear.value, calendarMonth.value, d)
    const dStr = getLocalDateStr(dObj)
    days.push({
      date: dStr,
      dayNum: d,
      isBooked: bookedSet.has(dStr),
      isPreview: previewSet.has(dStr),
      isToday: dStr === todayStr,
      isPast: dObj < todayNorm && dStr !== todayStr
    })
  }
  return days
})

const getDayClassPremium = (day) => {
  if (!day.date) return 'bg-transparent text-transparent cursor-default shadow-none border-transparent'
  if (day.isPast && !day.isToday) return 'bg-gray-50 text-gray-200 cursor-not-allowed opacity-40 shadow-none'
  if (day.isBooked) return 'bg-amber-100 text-amber-700 shadow-sm shadow-amber-900/5 cursor-not-allowed'
  if (day.isPreview) return 'bg-emerald-500 text-white shadow-none'
  if (day.isToday) return 'bg-blue-50 text-blue-600 border border-blue-100 shadow-sm shadow-blue-500/5'
  return 'bg-white text-gray-600 hover:bg-gray-50 hover:border-gray-200 shadow-sm'
}

onMounted(() => loadAllData())
</script>
