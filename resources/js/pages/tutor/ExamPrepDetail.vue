<template>
  <div class="min-h-screen bg-gray-50">
    <div v-if="loading" class="flex items-center justify-center h-screen">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
    </div>

    <div v-else-if="examPrep">
      <div class="relative h-80 bg-gray-900 overflow-hidden">
        <img
          v-if="examPrep.exam_prep_banner"
          :src="getBannerImageUrl(examPrep.exam_prep_banner)"
          :alt="examPrep.exam_prep_title"
          class="w-full h-full object-cover"
        />
        <div v-else class="w-full h-full bg-gradient-to-r from-[#0055A4] to-[#003d7a]"></div>

        <div class="absolute inset-0 bg-black bg-opacity-40"></div>

        <div class="absolute top-6 left-6 z-10">
          <button
            @click="goBack"
            class="flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-100 text-gray-800 rounded-lg font-medium transition-colors shadow-lg"
          >
            ← Back to Exam Preps
          </button>
        </div>

        <div class="absolute inset-0 flex flex-col justify-end p-8">
          <h1 class="text-4xl font-semibold text-white mb-3">{{ examPrep.exam_prep_title }}</h1>
          <p class="text-xl text-gray-200">{{ examPrep.exam_prep_subtitle }}</p>
        </div>
      </div>

      <div class="max-w-7xl mx-auto p-8">
        <div class="bg-white rounded-lg shadow p-6 mb-8">
          <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ examPrep.exam_prep_title }}</h2>
          <p class="text-gray-600 mb-4">{{ examPrep.exam_prep_subtitle }}</p>
          <p class="text-gray-700 leading-relaxed">{{ examPrep.exam_prep_description }}</p>
        </div>

        <div v-if="sections.length > 0" class="space-y-4">
          <h2 class="text-2xl font-semibold text-gray-800 mb-4">Sections</h2>
          <div
            v-for="(section, index) in sections"
            :key="index"
            class="bg-white rounded-lg shadow p-6 border border-gray-100"
          >
            <h3 class="text-lg font-semibold text-gray-800 mb-2">
              {{ section.sectionTitle || section.title || section.section || `Section ${index + 1}` }}
            </h3>
            <p v-if="section.text || section.content || section.description" class="text-gray-700 whitespace-pre-wrap">
              {{ section.text || section.content || section.description }}
            </p>
            <span v-if="section.category" class="inline-block mt-2 text-xs px-2 py-1 bg-[#0055A4]/10 text-[#003d7a] rounded">
              {{ section.category }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="flex items-center justify-center h-screen">
      <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Exam Prep Not Found</h1>
        <p class="text-gray-600 mb-6">The exam prep you're looking for doesn't exist.</p>
        <button
          @click="goBack"
          class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
        >
          Back to Exam Preps
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const loading = ref(true)
const examPrep = ref(null)
const sections = ref([])

const examPrepId = parseInt(route.params.id)

const getBannerImageUrl = (imagePath) => {
  if (!imagePath) return ''
  if (imagePath.startsWith('http')) return imagePath
  const apiURL = import.meta.env.VITE_API_URL
  if (!imagePath.startsWith('/')) {
    return apiURL + '/storage/' + imagePath
  }
  return apiURL + imagePath
}

const fetchExamPrep = async () => {
  loading.value = true
  try {
    const response = await axios.get(`/api/admin/exam-preps/${examPrepId}`)

    if (response.data.success && response.data.data) {
      const data = response.data.data
      examPrep.value = {
        id: data.id,
        exam_prep_title: data.exam_prep_title || 'Untitled Exam Prep',
        exam_prep_subtitle: data.exam_prep_subtitle || '',
        exam_prep_description: data.exam_prep_description || '',
        exam_prep_category: data.exam_prep_category || '',
        exam_prep_language: data.exam_prep_language || '',
        exam_prep_level: data.exam_prep_level || '',
        exam_prep_total_texts: data.exam_prep_total_texts || 0,
        exam_prep_banner: data.exam_prep_banner || null
      }

      try {
        let jsonContent = data.exam_prep_json_content
        if (typeof jsonContent === 'string') {
          jsonContent = JSON.parse(jsonContent)
        }

        if (Array.isArray(jsonContent)) {
          const processed = []
          jsonContent.forEach((section, idx) => {
            if (section.activities && Array.isArray(section.activities)) {
              section.activities.forEach((activity, aIdx) => {
                processed.push({
                  ...activity,
                  sectionIndex: idx,
                  activityIndex: aIdx,
                  category: section.category,
                  difficulty: section.difficulty,
                  sectionTitle: activity.title || `Section ${processed.length + 1}`,
                })
              })
            } else {
              processed.push({
                ...section,
                sectionIndex: idx,
                sectionTitle: section.section || section.title || `Section ${processed.length + 1}`,
              })
            }
          })
          sections.value = processed
        } else {
          sections.value = []
        }
      } catch (e) {
        sections.value = []
      }
    } else {
      examPrep.value = null
    }
  } catch (error) {
    examPrep.value = null
  } finally {
    loading.value = false
  }
}

const goBack = () => {
  router.push('/tutor/exam-preps')
}

onMounted(async () => {
  await fetchExamPrep()
})
</script>
