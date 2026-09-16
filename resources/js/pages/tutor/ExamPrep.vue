<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-800">All Exam Preps</h1>
      <div class="flex gap-4">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search exam preps..."
          class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
        />
        <select
          v-model="filterStatus"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
        >
          <option value="">All Status</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
      <p class="mt-2 text-gray-500">Loading exam preps...</p>
    </div>

    <div v-else-if="filteredExamPreps.length === 0" class="text-center py-12 bg-white rounded-lg shadow">
      <p class="text-gray-500 mb-4">No exam preps found</p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="examPrep in filteredExamPreps"
        :key="examPrep.id"
        class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden"
      >
        <div class="w-full h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
          <img
            v-if="examPrep.exam_prep_banner"
            :src="getBannerImageUrl(examPrep.exam_prep_banner)"
            :alt="examPrep.exam_prep_title"
            class="w-full h-full object-cover"
          />
          <div v-else class="w-full h-full bg-gradient-to-r from-[#0055A4] to-[#003d7a]"></div>
        </div>

        <div class="p-6">
          <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">
            {{ examPrep.exam_prep_title }}
          </h2>

          <p class="text-gray-600 text-sm mb-4 line-clamp-2">
            {{ examPrep.exam_prep_subtitle || examPrep.exam_prep_description || 'No description available' }}
          </p>

          <button
            @click="previewExamPrep(examPrep)"
            class="w-full bg-[#0055A4] hover:bg-[#003d7a] text-white font-medium py-3 px-4 rounded-lg transition-colors"
          >
            Preview
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const loading = ref(false)
const examPreps = ref([])
const searchQuery = ref('')
const filterStatus = ref('')

const filteredExamPreps = computed(() => {
  return examPreps.value.filter(examPrep => {
    const matchesSearch = (examPrep.exam_prep_title || '').toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         (examPrep.exam_prep_subtitle && examPrep.exam_prep_subtitle.toLowerCase().includes(searchQuery.value.toLowerCase()))

    if (filterStatus.value === 'active') {
      return matchesSearch && examPrep.exam_prep_is_active
    }
    if (filterStatus.value === 'inactive') {
      return matchesSearch && !examPrep.exam_prep_is_active
    }

    return matchesSearch
  })
})

const getBannerImageUrl = (imagePath) => {
  if (!imagePath) return ''
  if (imagePath.startsWith('http')) return imagePath
  const apiURL = import.meta.env.VITE_API_URL
  if (!imagePath.startsWith('/')) {
    return apiURL + '/storage/' + imagePath
  }
  return apiURL + imagePath
}

const loadExamPreps = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/tutor/exam-preps')

    if (response.data.success && response.data.data) {
      const data = response.data.data.data || response.data.data || []
      examPreps.value = Array.isArray(data) ? data : []
    } else {
      examPreps.value = []
    }
  } catch (error) {
    examPreps.value = []
  } finally {
    loading.value = false
  }
}

const previewExamPrep = (examPrep) => {
  if ((examPrep.exam_prep_category === 'books' || examPrep.exam_prep_category === 'lingopie') && examPrep.custom_url) {
    const target = examPrep.custom_url_target || '_blank'
    if (target === '_blank') {
      window.open(examPrep.custom_url, '_blank')
    } else {
      window.location.href = examPrep.custom_url
    }
    return
  }

  const slug = (examPrep.exam_prep_title || '').toLowerCase().replace(/\s+/g, '-')
  // Use the shared preview view so tutor sees the same UI as admin/student
  router.push(`/exam-preps/preview/${examPrep.id}/${slug}`)
}

onMounted(async () => {
  await loadExamPreps()
})
</script>
