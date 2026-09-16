<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#0055A4] to-[#003d7a] px-4 py-8">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl">
      <!-- Header -->
      <div class="border-b border-gray-200 p-6">
        <h1 class="text-3xl font-bold text-[#0055A4] text-center mb-2">Complete Payment</h1>
        <p class="text-gray-600 text-center">Secure payment powered by Stripe</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="p-8 text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4] mb-4"></div>
        <p class="text-gray-600">{{ loadingMessage }}</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="p-6">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
          {{ error }}
        </div>
        <button
          @click="$router.back()"
          class="w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
        >
          Go Back
        </button>
      </div>

      <!-- Payment Form -->
      <div v-else class="p-6 space-y-6">
        <!-- Order Summary -->
        <div class="bg-gray-50 rounded-lg p-4">
          <h3 class="font-semibold text-gray-800 mb-3">Order Summary</h3>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between text-gray-700">
              <span>Enrollment ID:</span>
              <span class="font-medium">#{{ enrollmentId }}</span>
            </div>
            <div v-if="couponCode" class="flex justify-between text-green-600">
              <span>Coupon:</span>
              <span>{{ couponCode }}</span>
            </div>
            <div v-if="discountAmount > 0" class="flex justify-between text-green-600">
              <span>Discount:</span>
              <span>-{{ formatPrice(discountAmount) }}</span>
            </div>
            <div class="flex justify-between font-bold text-lg text-gray-800 border-t pt-2">
              <span>Total:</span>
              <span>{{ formatPrice(amount) }}</span>
            </div>
          </div>
        </div>

        <!-- Stripe Card Element -->
        <div>
          <label class="block text-gray-700 text-sm font-semibold mb-2">Card Details</label>
          <div id="card-element" class="p-4 border border-gray-300 rounded-lg"></div>
          <div id="card-errors" class="text-red-500 text-sm mt-2" role="alert"></div>
        </div>

        <!-- Submit Button -->
        <button
          @click="handlePayment"
          :disabled="processing || !stripeLoaded"
          class="w-full bg-[#0055A4] hover:bg-[#003d7a] text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
          <svg v-if="processing" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
          <span>{{ processing ? 'Processing...' : `Pay ${formatPrice(amount)}` }}</span>
        </button>

        <!-- Security Badge -->
        <div class="text-center text-sm text-gray-500 flex items-center justify-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
          <span>Secure payment powered by Stripe</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useToast } from '../composables/useToast'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()
const toast = useToast()

// Stripe variables
let stripe = null
let elements = null
let cardElement = null

// Component state
const loading = ref(true)
const loadingMessage = ref('Loading payment form...')
const error = ref('')
const processing = ref(false)
const stripeLoaded = ref(false)

// Payment data from query params
const enrollmentId = ref(route.query.enrollment_id || '')
const amount = ref(parseFloat(route.query.amount) || 0)
const couponCode = ref(route.query.coupon_code || '')
const discountAmount = ref(parseFloat(route.query.discount_amount) || 0)

// Format price
const formatPrice = (price) => {
  if (!price) return '$0.00'
  return new Intl.NumberFormat('en-CA', {
    style: 'currency',
    currency: 'CAD'
  }).format(price)
}

// Load Stripe.js
const loadStripe = () => {
  return new Promise((resolve, reject) => {
    // Check if Stripe is already loaded
    if (window.Stripe) {
      resolve(window.Stripe)
      return
    }

    // Load Stripe.js script
    const script = document.createElement('script')
    script.src = 'https://js.stripe.com/v3/'
    script.onload = () => resolve(window.Stripe)
    script.onerror = () => reject(new Error('Failed to load Stripe.js'))
    document.head.appendChild(script)
  })
}

// Initialize Stripe
const initializeStripe = async () => {
  try {
    loadingMessage.value = 'Loading Stripe...'
    
    // Get Stripe publishable key from API (public endpoint)
    const settingsResponse = await axios.get('/api/stripe/publishable-key')
    if (!settingsResponse.data.success) {
      throw new Error('Failed to load Stripe settings')
    }

    const publishableKey = settingsResponse.data.data.publishable_key
    if (!publishableKey) {
      throw new Error('Stripe publishable key not configured. Please configure it in admin settings.')
    }

    // Load Stripe.js library
    const Stripe = await loadStripe()
    
    // Initialize Stripe
    stripe = Stripe(publishableKey)
    elements = stripe.elements()

    // Create card element
    cardElement = elements.create('card', {
      style: {
        base: {
          fontSize: '16px',
          color: '#2B2D42',
          '::placeholder': {
            color: '#8D99AE',
          },
        },
      },
    })

    // Mount card element
    cardElement.mount('#card-element')

    // Handle card errors
    cardElement.on('change', (event) => {
      const displayError = document.getElementById('card-errors')
      if (event.error) {
        displayError.textContent = event.error.message
      } else {
        displayError.textContent = ''
      }
    })

    stripeLoaded.value = true
    loading.value = false
  } catch (err) {
    console.error('Stripe initialization error:', err)
    error.value = err.message || 'Failed to initialize payment form'
    loading.value = false
  }
}

// Handle payment submission
const handlePayment = async () => {
  if (!stripe || !cardElement) {
    toast.error('Payment form not ready. Please wait...')
    return
  }

  processing.value = true
  const cardErrors = document.getElementById('card-errors')
  cardErrors.textContent = ''

  try {
    // Create payment intent
    const intentResponse = await axios.post('/api/payment/create-intent', {
      enrollment_id: enrollmentId.value,
      amount: amount.value,
      currency: 'cad',
      coupon_code: couponCode.value || null,
      discount_amount: discountAmount.value || 0,
    })

    if (!intentResponse.data.success) {
      throw new Error(intentResponse.data.message || 'Failed to create payment intent')
    }

    const clientSecret = intentResponse.data.data.client_secret
    if (!clientSecret) {
      throw new Error('No payment intent returned from server')
    }

    // Confirm payment with Stripe
    const { error: stripeError, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
      payment_method: {
        card: cardElement,
        billing_details: {
          name: `${auth.user?.first_name || ''} ${auth.user?.last_name || ''}`.trim(),
          email: auth.user?.email || '',
        },
      },
    })

    if (stripeError) {
      throw new Error(stripeError.message)
    }

    if (paymentIntent.status === 'succeeded') {
      // Update enrollment status to active
      try {
        await axios.put(`/api/enrollments/${enrollmentId.value}`, {
          status: 'active',
        })
      } catch (err) {
        console.error('Failed to update enrollment status:', err)
        // Don't fail the payment if enrollment update fails
      }

      toast.success('Payment successful!')
      
      // Redirect to success page or dashboard
      setTimeout(() => {
        router.push('/student/dashboard')
      }, 1500)
    } else {
      throw new Error('Payment was not successful')
    }
  } catch (err) {
    console.error('Payment error:', err)
    const errorMessage = err.response?.data?.message || err.message || 'Payment failed. Please try again.'
    cardErrors.textContent = errorMessage
    toast.error(errorMessage)
  } finally {
    processing.value = false
  }
}

// Cleanup on unmount
onUnmounted(() => {
  if (cardElement) {
    cardElement.unmount()
  }
})

// Initialize on mount
onMounted(() => {
  if (!enrollmentId.value || !amount.value) {
    error.value = 'Invalid payment details. Please try again.'
    loading.value = false
    return
  }

  if (!auth.isAuthenticated) {
    error.value = 'Please login to continue with payment.'
    loading.value = false
    return
  }

  initializeStripe()
})
</script>

<style scoped>
#card-element {
  min-height: 40px;
}
</style>

