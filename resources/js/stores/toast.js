import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useToastStore = defineStore('toast', () => {
  const toasts = ref([])

  const showToast = (message, type = 'info', duration = 3000) => {
    const id = Date.now() + Math.random()
    const toast = {
      id,
      message,
      type, // 'success', 'error', 'warning', 'info'
      duration
    }

    toasts.value.push(toast)

    // Auto remove after duration
    if (duration > 0) {
      setTimeout(() => {
        removeToast(id)
      }, duration)
    }

    return id
  }

  const removeToast = (id) => {
    const index = toasts.value.findIndex(t => t.id === id)
    if (index > -1) {
      toasts.value.splice(index, 1)
    }
  }

  const success = (message, duration = 3000) => {
    return showToast(message, 'success', duration)
  }

  const error = (message, duration = 4000) => {
    return showToast(message, 'error', duration)
  }

  const warning = (message, duration = 3000) => {
    return showToast(message, 'warning', duration)
  }

  const info = (message, duration = 3000) => {
    return showToast(message, 'info', duration)
  }

  const clearAll = () => {
    toasts.value = []
  }

  return {
    toasts,
    showToast,
    removeToast,
    success,
    error,
    warning,
    info,
    clearAll
  }
})

