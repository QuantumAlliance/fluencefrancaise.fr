/**
 * Toast utility - Easy way to show toast notifications
 * Usage: import { toast } from '@/utils/toast'
 *        toast.success('Message')
 *        toast.error('Error message')
 */

import { useToastStore } from '../stores/toast'

let toastStore = null

const getToastStore = () => {
  if (!toastStore) {
    toastStore = useToastStore()
  }
  return toastStore
}

export const toast = {
  success: (message, duration = 3000) => {
    return getToastStore().success(message, duration)
  },
  error: (message, duration = 4000) => {
    return getToastStore().error(message, duration)
  },
  warning: (message, duration = 3000) => {
    return getToastStore().warning(message, duration)
  },
  info: (message, duration = 3000) => {
    return getToastStore().info(message, duration)
  }
}

// Export default for convenience
export default toast

