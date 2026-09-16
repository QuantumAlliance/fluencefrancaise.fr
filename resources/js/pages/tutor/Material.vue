<template>
  <div class="p-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8">Material</h1>

    <!-- Breadcrumb -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
      <div class="flex items-center gap-2 text-sm">
        <button
          @click="navigateTo('')"
          class="text-[#0055A4] hover:text-[#003d7a] font-medium"
        >
          Material
        </button>
        <template v-for="(part, index) in pathParts" :key="index">
          <span class="text-gray-400">/</span>
          <button
            @click="navigateTo(pathParts.slice(0, index + 1).join('/'))"
            class="text-[#0055A4] hover:text-[#003d7a] font-medium"
          >
            {{ part }}
          </button>
        </template>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
      <p class="ml-4 text-gray-600">Loading materials...</p>
    </div>

    <!-- Material List -->
    <div v-else class="bg-white rounded-lg shadow">
      <div class="border-b border-gray-200 p-4">
        <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-600">
          <div class="col-span-6">Name</div>
          <div class="col-span-3">Date Modified</div>
          <div class="col-span-2">Size</div>
          <div class="col-span-1"></div>
        </div>
      </div>

      <!-- Back Button (if not at root) -->
      <div v-if="currentPath" class="border-b border-gray-100">
        <button
          @click="goBack"
          class="w-full p-4 flex items-center gap-3 hover:bg-gray-50 transition-colors text-left"
        >
          <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
          </svg>
          <span class="text-gray-600">..</span>
        </button>
      </div>

      <!-- Empty State -->
      <div v-if="materials.length === 0 && !loading" class="p-8 text-center text-gray-500">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
        </svg>
        <p>This folder is empty</p>
      </div>

      <!-- Items List -->
      <div v-for="item in materials" :key="item.path" class="border-b border-gray-100 last:border-b-0">
        <!-- Folder -->
        <button
          v-if="item.type === 'folder'"
          @click="navigateTo(item.path)"
          class="w-full p-4 grid grid-cols-12 gap-4 items-center hover:bg-gray-50 transition-colors text-left"
        >
          <div class="col-span-6 flex items-center gap-3">
            <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
              <path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
            </svg>
            <span class="font-medium text-gray-800">{{ item.name }}</span>
          </div>
          <div class="col-span-3 text-sm text-gray-500">{{ item.modified }}</div>
          <div class="col-span-2 text-sm text-gray-500">-</div>
          <div class="col-span-1 text-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </div>
        </button>

        <!-- File -->
        <div
          v-else
          class="p-4 grid grid-cols-12 gap-4 items-center hover:bg-gray-50 transition-colors"
        >
          <div class="col-span-6 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
              <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
            </svg>
            <span class="text-gray-800">{{ item.name }}</span>
          </div>
          <div class="col-span-3 text-sm text-gray-500">{{ item.modified }}</div>
          <div class="col-span-2 text-sm text-gray-500">{{ item.size }}</div>
          <div class="col-span-1">
            <button
              @click="downloadFile(item.path)"
              :disabled="downloadingFile === item.path"
              class="p-2 text-[#0055A4] hover:text-[#003d7a] hover:bg-[#0055A4]/10 rounded-lg transition-colors disabled:opacity-50"
              title="Download"
            >
              <svg v-if="downloadingFile !== item.path" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              <svg v-else class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useToast } from '../../composables/useToast'

const toast = useToast()

// State
const loading = ref(false)
const currentPath = ref('')
const materials = ref([])
const downloadingFile = ref(null)

// Computed path parts for breadcrumb
const pathParts = computed(() => {
  if (!currentPath.value) return []
  return currentPath.value.split('/').filter(p => p)
})

const loadMaterials = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/tutor/materials', {
      params: { path: currentPath.value }
    })
    if (response.data.success) {
      materials.value = response.data.data.items || []
    }
  } catch (error) {
    console.error('Failed to load materials:', error)
    toast.error(error.response?.data?.message || 'Failed to load materials')
    materials.value = []
  } finally {
    loading.value = false
  }
}

const navigateTo = (path) => {
  currentPath.value = path
  loadMaterials()
}

const goBack = () => {
  const parts = currentPath.value.split('/').filter(p => p)
  parts.pop()
  currentPath.value = parts.join('/')
  loadMaterials()
}

const downloadFile = async (filePath) => {
  downloadingFile.value = filePath
  try {
    const response = await axios.get('/api/tutor/materials/download', {
      params: { path: filePath },
      responseType: 'blob'
    })

    // Create download link
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', filePath.split('/').pop())
    document.body.appendChild(link)
    link.click()
    link.remove()
    window.URL.revokeObjectURL(url)

    toast.success('File downloaded successfully')
  } catch (error) {
    console.error('Download failed:', error)
    toast.error('Failed to download file')
  } finally {
    downloadingFile.value = null
  }
}

onMounted(() => {
  loadMaterials()
})
</script>

<style scoped>
/* Additional styling if needed */
</style>
