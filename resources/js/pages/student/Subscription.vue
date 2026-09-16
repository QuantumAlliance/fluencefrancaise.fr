<template>
  <div class="p-6">
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4] mb-4"></div>
      <p class="text-gray-600">Loading subscription details...</p>
    </div>

    <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
      {{ error }}
    </div>

    <div v-else>
      <!-- Active Subscription Card -->
      <div v-if="subscription" class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-[#0055A4] px-6 py-4 flex justify-between items-center">
          <h2 class="text-xl font-bold text-white">Current Subscription</h2>
          <span 
            class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide"
            :class="subscription.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
          >
            {{ subscription.status }}
          </span>
        </div>
        
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Plan Details</h3>
              <div class="space-y-3">
                <div class="flex justify-between border-b pb-2">
                  <span class="text-gray-600">Plan Name</span>
                  <span class="font-medium text-gray-900">{{ subscription.class_type?.name || subscription.class_type?.class_name || 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                  <span class="text-gray-600">Duration</span>
                  <span class="font-medium text-gray-900 capitalize">{{ subscription.class_type?.duration || 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                  <span class="text-gray-600">Price Paid</span>
                  <span class="font-medium text-gray-900">{{ formatPrice(subscription.final_amount, subscription.currency) }}</span>
                </div>
              </div>
            </div>

            <div>
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Dates</h3>
              <div class="space-y-3">
                <div class="flex justify-between border-b pb-2">
                  <span class="text-gray-600">Enrolled On</span>
                  <span class="font-medium text-gray-900">{{ formatDate(subscription.created_at) }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                  <span class="text-gray-600">Ends On</span>
                  <span class="font-medium text-gray-900">{{ calculateEndDate(subscription) }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-8 pt-6 border-t">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Features</h3>
            <p class="text-gray-600 text-sm" v-if="subscription.class_type?.description">
              {{ subscription.class_type.description }}
            </p>
             <p class="text-gray-500 italic text-sm" v-else>No description available.</p>
          </div>
        </div>
      </div>

      <!-- No Active Subscription -->
      <div v-else class="bg-white rounded-lg shadow-md p-8 text-center">
        <div class="mb-4">
          <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">No Active Subscription</h3>
        <p class="text-gray-600 mb-6">You don't have any active subscriptions at the moment.</p>
        <router-link 
          to="/student/browse-courses" 
          class="inline-block px-6 py-3 bg-[#0055A4] text-white font-semibold rounded-lg hover:bg-[#003d7a] transition-colors"
        >
          Browse Courses
        </router-link>
      </div>

      <!-- Payment History (Optional, if needed later) -->
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useToast } from '../../composables/useToast'

const toast = useToast()
const loading = ref(true)
const error = ref('')
const subscription = ref(null)

const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('en-CA', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatPrice = (price, currency = 'CAD') => {
  return new Intl.NumberFormat('en-CA', {
    style: 'currency',
    currency: currency || 'CAD'
  }).format(price)
}

const calculateEndDate = (sub) => {
  if (sub.completion_date) {
    return formatDate(sub.completion_date)
  }
  
  if (!sub.enrollment_date || !sub.class_type?.duration) {
    return '--'
  }
  
  const startDate = new Date(sub.enrollment_date)
  const duration = sub.class_type.duration.toLowerCase()
  
  // Calculate end date based on duration
  let endDate = new Date(startDate)
  
  if (duration === 'weekly') {
    endDate.setDate(startDate.getDate() + 7)
  } else if (duration === 'monthly') {
    endDate.setMonth(startDate.getMonth() + 1)
  } else if (duration === 'quarterly') {
    endDate.setMonth(startDate.getMonth() + 3)
  } else {
    return '--'
  }
  
  return formatDate(endDate)
}

const fetchSubscription = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await axios.get('/api/student/subscription')
    if (response.data.success) {
      subscription.value = response.data.data
    } else {
      // If success is false but no error, it might mean no subscription found which is fine
      subscription.value = null
    }
  } catch (err) {
    console.error('Failed to fetch subscription:', err)
    // If 404, it just means no subscription
    if (err.response && err.response.status === 404) {
        subscription.value = null
    } else {
        error.value = 'Failed to load subscription details.'
        toast.error('Failed to load subscription details.')
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchSubscription()
})
</script>
