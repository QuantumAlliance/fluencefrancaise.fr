<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-800">My Exam Prep</h1>
      <div class="flex gap-4">
        <select
          v-model="filterStatus"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
        >
          <option value="">All Status</option>
          <option value="in-progress">In Progress</option>
          <option value="completed">Completed</option>
          <option value="not-started">Not Started</option>
        </select>
      </div>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
      <p class="mt-2 text-gray-500">Loading exam preps...</p>
    </div>

    <div v-else-if="filteredExamPreps.length === 0" class="text-center py-12 bg-white rounded-lg shadow">
      <p class="text-gray-500 mb-4">No exam preps found</p>
      <router-link
        to="/student/dashboard"
        class="text-[#0055A4] hover:text-[#003d7a] font-medium"
      >
        Back to Dashboard
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="examPrep in filteredExamPreps"
        :key="examPrep.id"
        class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden relative"
      >
        <div class="w-full h-48 flex items-center justify-center relative overflow-hidden"
             :class="{ 'bg-gradient-to-br from-[#0055A4]/30 via-[#003d7a]/20 to-gray-200': examPrep.is_locked && !examPrep.image_url, 'bg-gray-200': !examPrep.is_locked && !examPrep.image_url }">
          <img
            v-if="examPrep.image_url"
            :src="examPrep.image_url"
            :alt="examPrep.title"
            class="w-full h-full object-cover transition-all duration-300"
            :style="examPrep.is_locked ? { filter: 'blur(12px) saturate(0.7)', transform: 'scale(1.1)' } : {}"
          />
          <div v-else-if="!examPrep.is_locked" class="text-gray-400 text-sm">No Image</div>

          <div v-if="examPrep.is_locked" class="group absolute inset-0 backdrop-blur-md bg-white/30 flex flex-col items-center justify-center px-4 py-3 text-center overflow-hidden">
            <!-- Default state: icon + short heading only -->
            <div class="flex flex-col items-center transition-opacity duration-300 group-hover:opacity-0">
              <div class="bg-white/70 backdrop-blur-sm rounded-full p-3 shadow-lg ring-1 ring-white/60 mb-2">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
              </div>
              <p class="text-sm md:text-base font-bold text-gray-900 leading-tight drop-shadow-sm">
                Please Request Access From Tutor
              </p>
            </div>

            <!-- Hover state: explanation revealed -->
            <div class="absolute inset-0 flex items-center justify-center px-4 py-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white/85 backdrop-blur-sm pointer-events-none">
              <p class="text-xs md:text-sm text-gray-800 leading-snug max-w-xs">
                To ensure the best use of our exam preparation materials, certain content is only unlocked when students are approaching their exam-writing level. This helps keep the practice tests effective and representative of the real exam experience.
              </p>
            </div>
          </div>
        </div>

        <div class="p-6">
          <h2 class="text-xl font-bold text-gray-800 mb-2 break-words">
            {{ examPrep.title }}
          </h2>

          <p
            v-if="examPrep.subtitle"
            class="text-gray-600 text-sm mb-4 line-clamp-2"
          >
            {{ examPrep.subtitle }}
          </p>

          <div v-if="(examPrep.exam_prep_category || examPrep.category) !== 'books' && (examPrep.exam_prep_category || examPrep.category) !== 'lingopie'" class="mb-4">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm text-gray-600 font-medium">Progress</span>
              <span class="text-sm text-gray-800 font-semibold">{{ examPrep.progress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div
                :style="{ width: examPrep.progress + '%' }"
                class="bg-[#0055A4] h-2 rounded-full transition-all"
              />
            </div>
          </div>

          <button
            v-if="examPrep.is_locked"
            disabled
            class="w-full bg-gray-300 text-gray-600 font-medium py-3 px-4 rounded-lg cursor-not-allowed flex items-center justify-center gap-2"
            :class="(examPrep.exam_prep_category || examPrep.category) === 'books' || (examPrep.exam_prep_category || examPrep.category) === 'lingopie' ? 'mt-4' : ''"
            :title="'Ask your tutor for access'"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Locked
          </button>
          <button
            v-else
            @click="continueExamPrep(examPrep)"
            :class="[
              'w-full bg-[#0055A4] hover:bg-[#003d7a] text-white font-medium py-3 px-4 rounded-lg transition-colors',
              (examPrep.exam_prep_category || examPrep.category) === 'books' || (examPrep.exam_prep_category || examPrep.category) === 'lingopie' ? 'mt-4' : ''
            ]"
          >
            Continue Learning
          </button>
        </div>
      </div>
    </div>

    <div v-if="pagination && pagination.last_page > 1" class="mt-8 flex justify-center">
      <div class="flex gap-2">
        <button
          v-if="pagination.current_page > 1"
          @click="loadPage(pagination.current_page - 1)"
          class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
        >
          Previous
        </button>
        <span class="px-4 py-2 text-gray-600">
          Page {{ pagination.current_page }} of {{ pagination.last_page }}
        </span>
        <button
          v-if="pagination.current_page < pagination.last_page"
          @click="loadPage(pagination.current_page + 1)"
          class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useToast } from '../../composables/useToast'

const router = useRouter()
const toast = useToast()
const examPreps = ref([])
const loading = ref(false)
const searchQuery = ref('')
const filterStatus = ref('')
const pagination = ref(null)
const currentPage = ref(1)

const filteredExamPreps = computed(() => {
  return examPreps.value.filter(examPrep => {
    const matchesSearch = examPrep.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         examPrep.description?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         examPrep.subtitle?.toLowerCase().includes(searchQuery.value.toLowerCase())

    if (!filterStatus.value) return matchesSearch

    const status = examPrep.status.toLowerCase().replace(' ', '-')
    return matchesSearch && status === filterStatus.value
  })
})

const continueExamPrep = (examPrep) => {
  const category = examPrep.exam_prep_category || examPrep.category
  if ((category === 'books' || category === 'lingopie') && examPrep.custom_url) {
    const target = examPrep.custom_url_target || '_blank'
    if (target === '_blank') {
      window.open(examPrep.custom_url, '_blank')
    } else {
      window.location.href = examPrep.custom_url
    }
    return
  }

  const slug = (examPrep.title || 'exam-prep').toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '')
  router.push(`/student/exam-preps/${examPrep.id}/${slug}`)
}

const loadPage = async (page) => {
  currentPage.value = page
  await loadExamPreps()
}

const loadExamPreps = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/student/exam-preps', {
      params: { page: currentPage.value }
    })

    if (response.data.success && response.data.data) {
      if (response.data.data.data && Array.isArray(response.data.data.data)) {
        examPreps.value = response.data.data.data
        pagination.value = {
          current_page: response.data.data.current_page,
          last_page: response.data.data.last_page,
          per_page: response.data.data.per_page,
          total: response.data.data.total
        }
      } else if (Array.isArray(response.data.data)) {
        examPreps.value = response.data.data
        pagination.value = null
      } else {
        examPreps.value = [response.data.data]
        pagination.value = null
      }

      if (examPreps.value.length === 0) {
        toast.info('No exam preps available. Please contact admin.')
      }
    } else {
      examPreps.value = []
      if (response.data.message) {
        toast.warning(response.data.message)
      }
    }
  } catch (error) {
    const errorMessage = error.response?.data?.message || error.message || 'Failed to load your exam preps. Please try again.'
    toast.error(errorMessage)
    examPreps.value = []
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadExamPreps()
})
</script>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
