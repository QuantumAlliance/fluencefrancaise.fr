<template>
  <div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Cache Management</h1>
        <p class="text-gray-500 text-sm mt-1">Control what data is cached and for how long</p>
      </div>
      <button
        @click="clearAllCache"
        :disabled="clearingAll"
        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors disabled:opacity-50 flex items-center gap-2"
      >
        <Loader v-if="clearingAll" class="w-4 h-4 animate-spin" />
        <Trash2 v-else class="w-4 h-4" />
        Clear All Cache
      </button>
    </div>

    <!-- Redis Status -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div :class="redisConnected ? 'bg-green-100' : 'bg-red-100'" class="p-3 rounded-full">
            <Database :class="redisConnected ? 'text-green-600' : 'text-red-600'" class="w-6 h-6" />
          </div>
          <div>
            <h3 class="font-semibold text-gray-800">Redis Status</h3>
            <p :class="redisConnected ? 'text-green-600' : 'text-red-600'" class="text-sm">
              {{ redisConnected ? 'Connected' : 'Not Connected' }}
            </p>
          </div>
        </div>
        <button
          @click="checkRedisStatus"
          :disabled="checkingRedis"
          class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors disabled:opacity-50"
        >
          <Loader v-if="checkingRedis" class="w-4 h-4 animate-spin" />
          <span v-else>Refresh Status</span>
        </button>
      </div>
      <p v-if="!redisConnected" class="mt-3 text-sm text-gray-500">
        Redis is not connected. Caching will use file-based storage which is slower.
        <a href="https://redis.io/docs/getting-started/" target="_blank" class="text-[#0055A4] hover:underline">Learn how to install Redis</a>
      </p>
    </div>

    <!-- Cache Settings -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Cache Configuration</h2>
        <p class="text-sm text-gray-500">Enable/disable caching and set duration for each data type</p>
      </div>

      <div v-if="loading" class="p-8 text-center">
        <Loader class="w-8 h-8 animate-spin text-[#0055A4] mx-auto" />
        <p class="mt-2 text-gray-500">Loading cache settings...</p>
      </div>

      <div v-else class="divide-y divide-gray-200">
        <div v-for="item in cacheItems" :key="item.key" class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
          <div class="flex-1">
            <div class="flex items-center gap-3">
              <component :is="item.icon" class="w-5 h-5 text-gray-400" />
              <div>
                <h3 class="font-medium text-gray-800">{{ item.name }}</h3>
                <p class="text-sm text-gray-500">{{ item.description }}</p>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-4">
            <!-- Duration Input -->
            <div class="flex items-center gap-2">
              <input
                v-model.number="item.duration"
                type="number"
                min="1"
                :disabled="!item.enabled"
                class="w-20 px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] disabled:bg-gray-100 disabled:text-gray-400"
              />
              <select
                v-model="item.unit"
                :disabled="!item.enabled"
                class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] disabled:bg-gray-100 disabled:text-gray-400"
              >
                <option value="minutes">Minutes</option>
                <option value="hours">Hours</option>
                <option value="days">Days</option>
              </select>
            </div>

            <!-- Clear Cache Button -->
            <button
              @click="clearCache(item.key)"
              :disabled="item.clearing || !item.enabled"
              class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              title="Clear this cache"
            >
              <Loader v-if="item.clearing" class="w-4 h-4 animate-spin" />
              <Trash2 v-else class="w-4 h-4" />
            </button>

            <!-- Enable/Disable Toggle -->
            <button
              @click="item.enabled = !item.enabled"
              :class="item.enabled ? 'bg-green-600' : 'bg-gray-300'"
              class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
              role="switch"
              :aria-checked="item.enabled"
            >
              <span
                :class="item.enabled ? 'translate-x-5' : 'translate-x-0'"
                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out mt-0.5 ml-0.5"
              ></span>
            </button>
          </div>
        </div>
      </div>

      <!-- Save Button -->
      <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
        <button
          @click="saveSettings"
          :disabled="saving"
          class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 flex items-center gap-2"
        >
          <Loader v-if="saving" class="w-4 h-4 animate-spin" />
          <Save v-else class="w-4 h-4" />
          Save Settings
        </button>
      </div>
    </div>

    <!-- Cache Info -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
      <div class="flex gap-3">
        <Info class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" />
        <div class="text-sm text-blue-800">
          <p class="font-medium mb-1">How caching works:</p>
          <ul class="list-disc list-inside space-y-1 text-blue-700">
            <li>Cached data loads instantly without database queries</li>
            <li>Cache automatically clears when you update/add/delete data</li>
            <li>You can manually clear cache anytime using the buttons above</li>
            <li>Student progress and form submissions are never cached</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Database, Trash2, Loader, Save, Info, BookOpen, Users, GraduationCap, Settings, Layout, FileText, ShoppingCart } from 'lucide-vue-next'
import axios from 'axios'
import { useToast } from '../../composables/useToast'

const toast = useToast()

const loading = ref(true)
const saving = ref(false)
const clearingAll = ref(false)
const checkingRedis = ref(false)
const redisConnected = ref(false)

const cacheItems = ref([
  {
    key: 'courses',
    name: 'Courses',
    description: 'Course list and course details',
    icon: BookOpen,
    enabled: true,
    duration: 30,
    unit: 'days',
    clearing: false,
    clearOnUpdate: true
  },
  {
    key: 'exam_preps',
    name: 'Exam Prep',
    description: 'Exam Prep list and content (large JSON payload)',
    icon: GraduationCap,
    enabled: true,
    duration: 30,
    unit: 'days',
    clearing: false,
    clearOnUpdate: true
  },
  {
    key: 'class_types',
    name: 'Class Types',
    description: 'Class type options for enrollment',
    icon: Layout,
    enabled: true,
    duration: 30,
    unit: 'days',
    clearing: false,
    clearOnUpdate: true
  },
  {
    key: 'tutors',
    name: 'Tutors',
    description: 'Tutor list and profiles',
    icon: GraduationCap,
    enabled: true,
    duration: 30,
    unit: 'days',
    clearing: false,
    clearOnUpdate: true
  },
  {
    key: 'students',
    name: 'Students List',
    description: 'Student list for admin panel',
    icon: Users,
    enabled: true,
    duration: 5,
    unit: 'minutes',
    clearing: false,
    clearOnUpdate: false
  },
  {
    key: 'dashboard',
    name: 'Dashboard Stats',
    description: 'Admin dashboard statistics',
    icon: Layout,
    enabled: true,
    duration: 10,
    unit: 'minutes',
    clearing: false,
    clearOnUpdate: false
  },
  {
    key: 'settings',
    name: 'Site Settings',
    description: 'General site settings and configurations',
    icon: Settings,
    enabled: true,
    duration: 30,
    unit: 'days',
    clearing: false,
    clearOnUpdate: true
  },
  {
    key: 'pages',
    name: 'Static Pages',
    description: 'About, Contact, and other pages',
    icon: FileText,
    enabled: true,
    duration: 30,
    unit: 'days',
    clearing: false,
    clearOnUpdate: true
  },
  {
    key: 'enrollments',
    name: 'Enrollments',
    description: 'Enrollment data for student portal',
    icon: ShoppingCart,
    enabled: true,
    duration: 5,
    unit: 'minutes',
    clearing: false,
    clearOnUpdate: true
  }
])

const checkRedisStatus = async () => {
  checkingRedis.value = true
  try {
    const response = await axios.get('/api/admin/cache/status')
    redisConnected.value = response.data.data.redis_connected
  } catch (error) {
    console.error('Failed to check Redis status:', error)
    redisConnected.value = false
  } finally {
    checkingRedis.value = false
  }
}

const loadSettings = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/admin/cache/settings')
    if (response.data.success && response.data.data) {
      const savedSettings = response.data.data
      cacheItems.value.forEach(item => {
        if (savedSettings[item.key]) {
          item.enabled = savedSettings[item.key].enabled
          item.duration = savedSettings[item.key].duration
          item.unit = savedSettings[item.key].unit
        }
      })
    }
    redisConnected.value = response.data.data?.redis_connected || false
  } catch (error) {
    console.error('Failed to load cache settings:', error)
  } finally {
    loading.value = false
  }
}

const saveSettings = async () => {
  saving.value = true
  try {
    const settings = {}
    cacheItems.value.forEach(item => {
      settings[item.key] = {
        enabled: item.enabled,
        duration: item.duration,
        unit: item.unit
      }
    })

    const response = await axios.post('/api/admin/cache/settings', { settings })
    if (response.data.success) {
      toast.success('Cache settings saved successfully')
    }
  } catch (error) {
    console.error('Failed to save cache settings:', error)
    toast.error('Failed to save cache settings')
  } finally {
    saving.value = false
  }
}

const clearCache = async (key) => {
  const item = cacheItems.value.find(i => i.key === key)
  if (!item) return

  item.clearing = true
  try {
    const response = await axios.post(`/api/admin/cache/clear/${key}`)
    if (response.data.success) {
      toast.success(`${item.name} cache cleared`)
    }
  } catch (error) {
    console.error('Failed to clear cache:', error)
    toast.error('Failed to clear cache')
  } finally {
    item.clearing = false
  }
}

const clearAllCache = async () => {
  if (!confirm('Are you sure you want to clear all cache? This may temporarily slow down the site.')) {
    return
  }

  clearingAll.value = true
  try {
    const response = await axios.post('/api/admin/cache/clear-all')
    if (response.data.success) {
      toast.success('All cache cleared successfully')
    }
  } catch (error) {
    console.error('Failed to clear all cache:', error)
    toast.error('Failed to clear cache')
  } finally {
    clearingAll.value = false
  }
}

onMounted(() => {
  loadSettings()
})
</script>
