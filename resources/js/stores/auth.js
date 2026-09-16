import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token') || null)
  const isAuthenticated = computed(() => !!token.value)
  const isPaymentConfirmed = computed(() => {
    // Only applies to students - admins and tutors always have access
    if (!user.value) return false
    if (user.value.user_type !== 'student') return true
    return user.value.payment_confirmed === true
  })

  if (token.value) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }

  const login = async (email, password) => {
    try {
      const response = await axios.post('/api/auth/login', {
        email,
        password
      })

      token.value = response.data.data.token
      user.value = response.data.data.user

      localStorage.setItem('token', token.value)
      axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`

      return response.data
    } catch (error) {
      throw error.response?.data || error.message
    }
  }

  const register = async (firstName, lastName, email, password, passwordConfirmation) => {
    try {
      const response = await axios.post('/api/auth/register', {
        first_name: firstName,
        last_name: lastName,
        email,
        password,
        password_confirmation: passwordConfirmation
      })

      token.value = response.data.data.token
      user.value = response.data.data.user

      localStorage.setItem('token', token.value)
      axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`

      return response.data
    } catch (error) {
      throw error.response?.data || error.message
    }
  }

  const logout = async () => {
    try {
      await axios.post('/api/auth/logout')
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      user.value = null
      token.value = null
      localStorage.removeItem('token')
      delete axios.defaults.headers.common['Authorization']
    }
  }

  const getCurrentUser = async () => {
    if (!token.value) {
      throw new Error('No token available')
    }

    try {
      const response = await axios.get('/api/auth/me')
      user.value = response.data.data
      return response.data
    } catch (error) {
      // If token is invalid (401 Unauthorized), clear it
      if (error.response?.status === 401 || error.response?.status === 403) {
        user.value = null
        token.value = null
        localStorage.removeItem('token')
        delete axios.defaults.headers.common['Authorization']
      }

      throw error.response?.data || error.message
    }
  }

  // Initialize: Validate token and load user data on app start
  const initializeAuth = async () => {
    if (token.value && !user.value) {
      try {
        await getCurrentUser()
      } catch (error) {
        // Token is invalid, already cleared by getCurrentUser
      }
    }
  }

  return {
    user,
    token,
    isAuthenticated,
    isPaymentConfirmed,
    login,
    register,
    logout,
    getCurrentUser,
    initializeAuth
  }
})
