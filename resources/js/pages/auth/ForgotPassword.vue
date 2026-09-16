<template>
  <div class="flex flex-col min-h-screen">
    <PublicHeader />
    <div class="flex-grow flex items-center justify-center bg-[#f4f7fb] px-4 pt-40 pb-12">
      <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md">
        
        <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">Reset Password</h2>
        <p class="text-gray-600 text-center mb-6">Enter your email to receive password reset instructions</p>

        <form @submit.prevent="handleForgotPassword" class="space-y-5">
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
            <input
              v-model="form.email"
              type="email"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20"
              placeholder="your@email.com"
            />
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 disabled:opacity-50 flex items-center justify-center gap-2"
          >
            <i v-if="loading" class="fas fa-circle-notch fa-spin"></i>
            {{ loading ? 'Sending...' : 'Send Reset Link' }}
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
          <p class="text-gray-600 text-sm">
            Don't have an account?
            <router-link to="/register" class="text-brand-600 hover:text-brand-700 font-semibold">
              Register here
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
import axios from 'axios'
import { useSettingsStore } from '../../stores/settings'
import PublicFooter from '../../components/PublicFooter.vue'
import PublicHeader from '../../components/PublicHeader.vue'

const settingsStore = useSettingsStore()

onMounted(() => {
  settingsStore.fetchSettings()
})

const form = ref({
  email: ''
})

const loading = ref(false)
const error = ref('')
const success = ref('')

const handleForgotPassword = async () => {
  error.value = ''
  success.value = ''
  loading.value = true

  try {
    await axios.post('/api/auth/forgot-password', {
      email: form.value.email
    })
    
    success.value = 'Password reset link has been sent to your email. Please check your inbox.'
    form.value.email = ''
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to send reset link. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>
