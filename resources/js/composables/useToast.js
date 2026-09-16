/**
 * Composable for toast notifications
 * Works with global plugin - no need to import store
 * Usage: const toast = useToast()
 *        toast.success('Message')
 */
import { getCurrentInstance } from 'vue'

export function useToast() {
  const instance = getCurrentInstance()
  
  if (instance) {
    // Use global property from plugin
    return instance.appContext.config.globalProperties.$toast
  }
  
  // Fallback: use window.$toast if available
  if (typeof window !== 'undefined' && window.$toast) {
    return window.$toast
  }
  
  // Last resort: import store directly
  const { useToastStore } = require('../stores/toast')
  const toastStore = useToastStore()
  return {
    success: (message, duration) => toastStore.success(message, duration),
    error: (message, duration) => toastStore.error(message, duration),
    warning: (message, duration) => toastStore.warning(message, duration),
    info: (message, duration) => toastStore.info(message, duration),
    clearAll: () => toastStore.clearAll()
  }
}
