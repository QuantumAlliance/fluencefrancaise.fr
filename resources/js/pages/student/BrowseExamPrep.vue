<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Browse Exam Prep</h1>
      <div class="flex gap-4">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search exam preps..."
          class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
          @input="searchExamPreps"
        />
        <select
          v-model="selectedCategory"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
          @change="filterExamPreps"
        >
          <option value="">All Categories</option>
          <option value="written">Written</option>
          <option value="orale">Orale</option>
          <option value="expression">Oral Expression</option>
          <option value="written_expression">Written Expression</option>
        </select>
      </div>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
      <p class="mt-2 text-gray-500">Loading exam preps...</p>
    </div>

    <div v-else-if="examPreps.length === 0" class="text-center py-12 bg-white rounded-lg">
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
        v-for="examPrep in examPreps"
        :key="examPrep.id"
        class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden cursor-pointer"
        @click="viewExamPrep(examPrep.id)"
      >
        <img
          :src="examPrep.image_url || 'https://via.placeholder.com/400x200?text=Exam+Prep'"
          :alt="examPrep.title"
          class="w-full h-48 object-cover"
        />
        <div class="p-6">
          <div class="flex justify-between items-start mb-2">
            <h3 class="text-xl font-bold text-gray-800 line-clamp-2">{{ examPrep.title }}</h3>
            <span
              v-if="examPrep.category"
              class="px-2 py-1 bg-[#0055A4]/10 text-[#003d7a] text-xs rounded-full ml-2 flex-shrink-0"
            >
              {{ examPrep.category }}
            </span>
          </div>
          <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ examPrep.description }}</p>
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm text-gray-600">
              <strong>Level:</strong> {{ examPrep.level || 'Beginner' }}
            </span>
            <span class="text-sm text-gray-600">
              <strong>Language:</strong> {{ examPrep.language || 'French' }}
            </span>
          </div>
          <div class="flex gap-2">
            <button
              @click.stop="viewExamPrep(examPrep.id)"
              class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors"
            >
              View Details
            </button>
            <button
              v-if="!isEnrolled(examPrep.id)"
              @click.stop="enrollExamPrep(examPrep.id)"
              :disabled="enrollingId === examPrep.id"
              class="flex-1 px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] disabled:opacity-50 text-white rounded-lg transition-colors"
            >
              {{ enrollingId === examPrep.id ? 'Enrolling...' : 'Enroll' }}
            </button>
            <button
              v-else
              disabled
              class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg"
            >
              ✓ Enrolled
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useExamPrepStore } from '../../stores/examPrep'
import { useToast } from '../../composables/useToast'

const router = useRouter()
const examPrepStore = useExamPrepStore()
const toast = useToast()
const examPreps = ref([])
const enrolledIds = ref([])
const loading = ref(false)
const searchQuery = ref('')
const selectedCategory = ref('')
const enrollingId = ref(null)

const isEnrolled = (id) => {
  return enrolledIds.value.includes(id)
}

const viewExamPrep = (id) => {
  const examPrep = examPreps.value.find(c => c.id === id)
  const category = examPrep?.exam_prep_category || examPrep?.category
  if (examPrep && (category === 'books' || category === 'lingopie') && examPrep.custom_url) {
    const target = examPrep.custom_url_target || '_blank'
    if (target === '_blank') {
      window.open(examPrep.custom_url, '_blank')
    } else {
      window.location.href = examPrep.custom_url
    }
    return
  }
  router.push(`/student/exam-preps/${id}`)
}

const enrollExamPrep = async (id) => {
  enrollingId.value = id
  try {
    const response = await examPrepStore.enrollExamPrep(id)
    if (response.success) {
      enrolledIds.value.push(id)
      toast.success('Successfully enrolled in exam prep!')
      await loadExamPreps()
    } else {
      toast.error(response.message || 'Failed to enroll in exam prep')
    }
  } catch (error) {
    if (error.response?.data?.message) {
      toast.error(error.response.data.message)
    } else {
      toast.error('Failed to enroll in exam prep. Please try again.')
    }
  } finally {
    enrollingId.value = null
  }
}

const loadExamPreps = async () => {
  loading.value = true
  try {
    await examPrepStore.fetchExamPreps()
    examPreps.value = examPrepStore.examPreps || []

    try {
      await examPrepStore.fetchEnrolledExamPreps()
      enrolledIds.value = examPrepStore.examPreps.map(c => c.id)
    } catch (error) {
      console.error('Failed to load enrolled exam preps:', error)
    }
  } catch (error) {
    toast.error('Failed to load exam preps. Please try again.')
  } finally {
    loading.value = false
  }
}

const searchExamPreps = async () => {
  if (searchQuery.value.length >= 3) {
    loading.value = true
    try {
      await examPrepStore.searchExamPreps(searchQuery.value, selectedCategory.value || null)
      examPreps.value = examPrepStore.examPreps || []
    } catch (error) {
      toast.error('Failed to search exam preps')
    } finally {
      loading.value = false
    }
  } else if (searchQuery.value.length === 0) {
    await loadExamPreps()
  }
}

const filterExamPreps = async () => {
  if (searchQuery.value.length >= 3) {
    await searchExamPreps()
  } else {
    await loadExamPreps()
  }
}

onMounted(async () => {
  await loadExamPreps()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
