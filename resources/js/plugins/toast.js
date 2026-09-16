/**
 * Global Toast Plugin
 * Makes toast available everywhere without imports
 * Usage: $toast.success('Message') or this.$toast.success('Message')
 */
import { useToastStore } from '../stores/toast'

export default {
  install(app) {
    // Make toast available as global property
    app.config.globalProperties.$toast = {
      success: (message, duration) => {
        const toastStore = useToastStore()
        return toastStore.success(message, duration)
      },
      error: (message, duration) => {
        const toastStore = useToastStore()
        return toastStore.error(message, duration)
      },
      warning: (message, duration) => {
        const toastStore = useToastStore()
        return toastStore.warning(message, duration)
      },
      info: (message, duration) => {
        const toastStore = useToastStore()
        return toastStore.info(message, duration)
      },
      clearAll: () => {
        const toastStore = useToastStore()
        return toastStore.clearAll()
      }
    }

    // Also add to window for non-Vue contexts
    if (typeof window !== 'undefined') {
      window.$toast = app.config.globalProperties.$toast
    }
  }
}

