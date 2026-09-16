<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="max-w-lg w-full bg-white rounded-lg shadow-lg p-8 text-center">
      <!-- Maintenance Icon -->
      <div class="mb-6">
        <div class="w-24 h-24 mx-auto bg-yellow-100 rounded-full flex items-center justify-center">
          <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
          </svg>
        </div>
      </div>

      <!-- Title -->
      <h1 class="text-2xl font-bold text-gray-800 mb-4">Under Maintenance</h1>

      <!-- Message -->
      <div v-if="loading" class="mb-6">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#0055A4]"></div>
      </div>
      <p v-else class="text-gray-600 mb-6 whitespace-pre-wrap">{{ maintenanceMessage }}</p>

      <!-- Actions -->
      <div class="space-y-3">
        <button
          @click="checkStatus"
          :disabled="checking"
          class="w-full px-6 py-3 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
          <svg v-if="checking" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>{{ checking ? 'Checking...' : 'Check Again' }}</span>
        </button>

        <a
          href="/"
          class="block w-full px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors"
        >
          Go to Homepage
        </a>
      </div>

      <!-- Footer Note -->
      <p class="text-sm text-gray-500 mt-6">
        We apologize for any inconvenience. Please check back later.
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const loading = ref(true)
const checking = ref(false)
const maintenanceMessage = ref('The student portal is currently under maintenance. Please try again later.')

const loadMaintenanceStatus = async () => {
  try {
    const response = await axios.get('/api/student-portal/maintenance-status')
    if (response.data.success) {
      if (!response.data.data.maintenance_mode) {
        // Maintenance mode is off, redirect to dashboard
        router.push('/student/dashboard')
        return
      }
      maintenanceMessage.value = response.data.data.message || maintenanceMessage.value
    }
  } catch (error) {
    console.error('Failed to load maintenance status:', error)
  } finally {
    loading.value = false
  }
}

const checkStatus = async () => {
  checking.value = true
  try {
    const response = await axios.get('/api/student-portal/maintenance-status')
    if (response.data.success) {
      if (!response.data.data.maintenance_mode) {
        // Maintenance mode is off, redirect to dashboard
        router.push('/student/dashboard')
      } else {
        maintenanceMessage.value = response.data.data.message || maintenanceMessage.value
      }
    }
  } catch (error) {
    console.error('Failed to check maintenance status:', error)
  } finally {
    checking.value = false
  }
}

onMounted(() => {
  loadMaintenanceStatus()
})
</script>
