import { ref } from 'vue'

/**
 * Composable for managing loading states
 * Usage:
 * const { isLoading, setLoading, loadingIds, setLoadingId, clearLoadingId } = useLoading()
 */
export function useLoading() {
  const isLoading = ref(false)
  const loadingIds = ref(new Set())

  const setLoading = (value) => {
    isLoading.value = value
  }

  const setLoadingId = (id) => {
    if (id !== null && id !== undefined) {
      loadingIds.value.add(id)
    }
  }

  const clearLoadingId = (id) => {
    if (id !== null && id !== undefined) {
      loadingIds.value.delete(id)
    }
  }

  const isIdLoading = (id) => {
    return loadingIds.value.has(id)
  }

  const clearAll = () => {
    isLoading.value = false
    loadingIds.value.clear()
  }

  return {
    isLoading,
    loadingIds,
    setLoading,
    setLoadingId,
    clearLoadingId,
    isIdLoading,
    clearAll
  }
}

