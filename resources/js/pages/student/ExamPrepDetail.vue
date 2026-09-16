<template>
  <div class="p-8">
    <router-link to="/student/browse-exam-preps" class="text-[#0055A4] hover:text-[#003d7a] mb-4 inline-block">
      ← Back to Browse Exam Prep
    </router-link>

    <div class="bg-white rounded-lg shadow-lg p-8 mt-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-1">
          <img
            :src="examPrep.image_url || 'https://via.placeholder.com/300?text=Exam+Prep'"
            :alt="examPrep.title"
            class="w-full rounded-lg shadow-lg mb-4"
          />
          <button
            v-if="!enrolled"
            @click="enrollExamPrep"
            :disabled="enrolling"
            class="w-full px-6 py-3 bg-[#0055A4] hover:bg-[#003d7a] disabled:opacity-50 text-white rounded-lg font-bold transition-colors"
          >
            {{ enrolling ? 'Enrolling...' : 'Enroll Now' }}
          </button>
          <div v-else class="w-full px-6 py-3 bg-green-600 text-white rounded-lg font-bold text-center">
            ✓ Already Enrolled
          </div>
        </div>

        <div class="md:col-span-2">
          <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ examPrep.title }}</h1>
          <p class="text-gray-600 text-lg mb-4">By {{ examPrep.instructor_name || 'TBD' }}</p>

          <div class="grid grid-cols-3 gap-4 mb-8 p-4 bg-gray-50 rounded-lg">
            <div>
              <p class="text-gray-600 text-sm">Level</p>
              <p class="text-lg font-bold text-[#0055A4]">{{ examPrep.level || 'Beginner' }}</p>
            </div>
            <div>
              <p class="text-gray-600 text-sm">Category</p>
              <p class="text-lg font-bold text-[#0055A4]">{{ examPrep.category || '-' }}</p>
            </div>
            <div>
              <p class="text-gray-600 text-sm">Language</p>
              <p class="text-lg font-bold text-[#0055A4]">{{ examPrep.language || 'French' }}</p>
            </div>
          </div>

          <h2 class="text-2xl font-bold text-gray-800 mb-4">About This Exam Prep</h2>
          <p class="text-gray-700 mb-6">{{ examPrep.description }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useExamPrepStore } from '../../stores/examPrep'
import { useToast } from '../../composables/useToast'

const route = useRoute()
const examPrepStore = useExamPrepStore()
const toast = useToast()
const examPrepId = parseInt(route.params.id)
const enrolled = ref(false)
const enrolling = ref(false)
const loading = ref(false)

const examPrep = ref({
  id: examPrepId,
  title: '',
  description: '',
  instructor_name: 'TBD',
  level: 'Beginner',
  image_url: null,
  category: '',
  language: ''
})

const enrollExamPrep = async () => {
  enrolling.value = true
  try {
    const response = await examPrepStore.enrollExamPrep(examPrepId)
    if (response.success) {
      enrolled.value = true
      toast.success('Successfully enrolled in exam prep!')
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
    enrolling.value = false
  }
}

const loadExamPrepDetail = async () => {
  loading.value = true
  try {
    const response = await examPrepStore.fetchExamPrepDetail(examPrepId)
    if (response.success && response.data) {
      const raw = response.data.examPrep || response.data
      examPrep.value = {
        id: raw.id,
        title: raw.exam_prep_title || raw.title || 'Untitled Exam Prep',
        description: raw.exam_prep_description || raw.description || '',
        instructor_name: raw.instructor_name || 'TBD',
        level: raw.exam_prep_level || raw.level || 'Beginner',
        image_url: raw.exam_prep_image ? `/storage/${raw.exam_prep_image.replace(/\/+$/, '')}` : raw.image_url || null,
        category: raw.exam_prep_category || raw.category || '',
        language: raw.exam_prep_language || raw.language || 'French'
      }

      enrolled.value = response.data.enrolled === true
    }
  } catch (error) {
    toast.error('Failed to load exam prep details')
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await loadExamPrepDetail()
})
</script>
