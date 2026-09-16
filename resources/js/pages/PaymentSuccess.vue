<template>
  <div class="min-h-screen bg-gradient-to-br from-brand-600 to-brand-700 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-8 text-center">
      <!-- Loading State -->
      <div v-if="loading" class="py-8">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-brand-600 mb-4"></div>
        <p class="text-gray-600">Verifying your payment...</p>
      </div>

      <!-- Success State -->
      <div v-else-if="success" class="py-4">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-green-100 flex items-center justify-center">
          <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Payment Successful!</h1>
        <p class="text-gray-600 mb-6">Thank you for your enrollment. Your account is now active.</p>

        <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
          <p class="text-sm text-gray-500 mb-1">Enrollment</p>
          <p class="font-medium text-gray-800">{{ enrollmentDetails?.class_type || 'Course' }}</p>
        </div>

        <p class="text-sm text-gray-500 mb-6">A confirmation email has been sent to your email address.</p>

        <button
          @click="goToDashboard"
          class="w-full px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white rounded-lg font-medium transition-colors"
        >
          Go to Dashboard
        </button>
      </div>

      <!-- Error State -->
      <div v-else class="py-4">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-red-100 flex items-center justify-center">
          <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Payment Verification Failed</h1>
        <p class="text-gray-600 mb-6">{{ errorMessage }}</p>

        <div class="space-y-3">
          <button
            @click="retryVerification"
            class="w-full px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white rounded-lg font-medium transition-colors"
          >
            Try Again
          </button>
          <a
            href="/register"
            class="block w-full px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors"
          >
            Back to Registration
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const route = useRoute()
const auth = useAuthStore()

const loading = ref(true)
const success = ref(false)
const errorMessage = ref('Something went wrong. Please contact support.')
const enrollmentDetails = ref(null)

const verifyPayment = async () => {
  loading.value = true

  const sessionId = route.query.session_id
  const enrollmentId = route.query.enrollment_id

  if (!sessionId || !enrollmentId) {
    errorMessage.value = 'Invalid payment session. Please try again.'
    loading.value = false
    return
  }

  try {
    const response = await axios.get(`/api/payment/checkout-success`, {
      params: {
        session_id: sessionId,
        enrollment_id: enrollmentId
      }
    })

    if (response.data.success) {
      success.value = true
      enrollmentDetails.value = {
        class_type: response.data.data.enrollment?.classType?.class_name || 'Course Enrollment'
      }

      // Update auth store to reflect payment confirmed status
      if (auth.user) {
        auth.user.payment_confirmed = true
      }
    } else {
      errorMessage.value = response.data.message || 'Payment verification failed.'
    }
  } catch (error) {
    console.error('Payment verification error:', error)
    errorMessage.value = error.response?.data?.message || 'Failed to verify payment. Please contact support.'
  } finally {
    loading.value = false
  }
}

const retryVerification = () => {
  verifyPayment()
}

const goToDashboard = () => {
  router.push('/student/dashboard')
}

onMounted(() => {
  verifyPayment()
})
</script>
