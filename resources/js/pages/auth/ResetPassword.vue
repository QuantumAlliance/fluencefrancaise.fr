<template>
  <div class="flex flex-col min-h-screen">
    <PublicHeader />
    <div class="flex-grow flex items-center justify-center bg-[#f4f7fb] px-4 pt-24 pb-12">
      <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md">

        <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">Reset Password</h2>
        <p class="text-gray-600 text-center mb-6">Enter your new password below</p>

      <form @submit.prevent="handleResetPassword" class="space-y-5">
        <div>
          <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
          <input
            v-model="form.email"
            type="email"
            required
            readonly
            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20"
            placeholder="your@email.com"
          />
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-semibold mb-2">New Password</label>
          <input
            v-model="form.password"
            type="password"
            required
            minlength="6"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20"
            placeholder="Enter new password"
          />
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
          <input
            v-model="form.password_confirmation"
            type="password"
            required
            minlength="6"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20"
            placeholder="Confirm new password"
          />
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 disabled:opacity-50 flex items-center justify-center gap-2"
        >
          <i v-if="loading" class="fas fa-circle-notch fa-spin"></i>
          {{ loading ? 'Resetting...' : 'Reset Password' }}
        </button>
      </form>

      <p v-if="error" class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
        {{ error }}
      </p>

      <p v-if="success" class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
        {{ success }}
      </p>

      <div class="mt-6 text-center border-t pt-6 space-y-2">
        <p class="text-gray-600 text-sm">
          <router-link to="/login" class="text-brand-600 hover:text-brand-700 font-semibold">
            Back to Sign In
          </router-link>
        </p>
      </div>
      </div>
    </div>

    <PublicFooter />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useSettingsStore } from '../../stores/settings'
import axios from 'axios'
import PublicFooter from '../../components/PublicFooter.vue'
import PublicHeader from '../../components/PublicHeader.vue'

const route = useRoute()
const router = useRouter()
const settingsStore = useSettingsStore()

const form = ref({
  email: '',
  password: '',
  password_confirmation: '',
  token: ''
})

const loading = ref(false)
const error = ref('')
const success = ref('')

// Fetch settings on mount
settingsStore.fetchSettings()

onMounted(async () => {
  // Get token and email from URL query parameters
  form.value.token = route.query.token || ''
  form.value.email = route.query.email ? decodeURIComponent(route.query.email) : ''
  
  if (!form.value.token || !form.value.email) {
    error.value = 'Invalid reset link. Please request a new password reset.'
    return
  }
  
  // Validate token on mount to show error immediately if expired/invalid
  try {
    // We'll validate when form is submitted, but show a message if token format is invalid
  } catch (err) {
    error.value = 'Invalid or expired reset link. Please request a new password reset.'
  }
})

const handleResetPassword = async () => {
  error.value = ''
  success.value = ''
  
  if (form.value.password !== form.value.password_confirmation) {
    error.value = 'Passwords do not match.'
    return
  }
  
  if (form.value.password.length < 6) {
    error.value = 'Password must be at least 6 characters.'
    return
  }
  
  loading.value = true

  try {
    await axios.post('/api/auth/reset-password', {
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation,
      token: form.value.token
    })
    
    success.value = 'Password reset successfully! Redirecting to login...'
    
    // Redirect to login after 2 seconds
    setTimeout(() => {
      router.push('/login')
    }, 2000)
  } catch (err) {
    const errorMessage = err.response?.data?.message || 'Failed to reset password. Please try again.'
    error.value = errorMessage
    
    // If token is expired or invalid, show a more helpful message
    if (errorMessage.includes('expired') || errorMessage.includes('Invalid') || errorMessage.includes('invalid')) {
      error.value = 'This reset link has expired or has already been used. Please request a new password reset link.'
    }
  } finally {
    loading.value = false
  }
}
</script>

