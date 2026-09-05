import { ref } from 'vue'

// Module-scope state: every component that imports this shares one gate.
const isOpen = ref(false)
const reason = ref('')

export function useDemoGate() {
  const open = (nextReason = 'to continue') => {
    reason.value = nextReason
    isOpen.value = true
  }

  const close = () => {
    isOpen.value = false
    reason.value = ''
  }

  return { isOpen, reason, open, close }
}
