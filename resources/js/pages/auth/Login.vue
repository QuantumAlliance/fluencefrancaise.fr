<template>
  <div class="flex flex-col min-h-screen">
    <PublicHeader />
    <div class="flex-grow flex items-center justify-center bg-[#f4f7fb] px-4 pt-40 pb-12">
      <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md">

        <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">Sign In</h2>
        <p class="text-gray-600 text-center mb-6">Welcome back! Please enter your details.</p>

        <form @submit.prevent="handleLogin" class="space-y-5">
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
            <input
              v-model="form.email"
              type="email"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 transition"
              placeholder="your@email.com"
            />
          </div>

          <div class="relative">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 transition"
                placeholder="••••••••"
              />
              <button 
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition"
              >
                <i :class="['fas', showPassword ? 'fa-eye-slash' : 'fa-eye']"></i>
              </button>
            </div>
          </div>

          <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-500 mt-0.5 shrink-0"></i>
            <p class="text-sm text-blue-800 leading-relaxed">
              If you are unable to login please try resetting the password.
            </p>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
          >
            <i v-if="loading" class="fas fa-circle-notch fa-spin"></i>
            {{ loading ? 'Signing in...' : 'Sign In' }}
          </button>
        </form>

        <p v-if="error" class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
          {{ error }}
        </p>

        <div class="mt-6 space-y-3 text-center text-sm border-t pt-6">
          <p class="text-gray-600">
            Don't have an account?
            <router-link to="/register" class="text-brand-600 hover:text-brand-700 font-semibold">
              Register here
            </router-link>
          </p>
          <p>
            <router-link to="/forgot-password" class="text-brand-600 hover:text-brand-700 font-semibold">
              Forgot your password?
            </router-link>
          </p>
          <p class="pt-3 border-t border-gray-100">
            <router-link to="/demo" class="text-gray-600 hover:text-[#0055A4] font-medium">
              Curious? Preview the student dashboard →
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
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import { useSettingsStore } from '../../stores/settings'
import { useSEO } from '../../composables/useSEO'
import PublicFooter from '../../components/PublicFooter.vue'
import PublicHeader from '../../components/PublicHeader.vue'

const router = useRouter()
const auth = useAuthStore()
const settingsStore = useSettingsStore()

// SEO: noindex for login page
useSEO({
  title: 'Sign In',
  description: 'Sign in to your account',
  noindex: true
})

onMounted(() => {
  settingsStore.fetchSettings()
})

const form = ref({
  email: '',
  password: ''
})

const loading = ref(false)
const error = ref('')
const showPassword = ref(false)

const handleLogin = async () => {
  error.value = ''
  loading.value = true

  try {
    await auth.login(form.value.email, form.value.password)
    
    const dashboardUrl = auth.user.user_type === 'admin' || auth.user.user_type === 'super_admin' 
      ? '/admin/dashboard' 
      : (auth.user.user_type === 'tutor' ? '/tutor/dashboard' : '/student/dashboard')
      
    router.push(dashboardUrl)
  } catch (err) {
    error.value = err.message || err.email?.[0] || 'Login failed. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>
