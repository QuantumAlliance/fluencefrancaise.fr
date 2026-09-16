<template>
  <div class="p-8">
    <router-link to="/demo/exam-prep" class="text-[#0055A4] hover:text-[#003d7a] mb-4 inline-block">
      ← Back to Exam Prep
    </router-link>

    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
      <p class="mt-2 text-gray-500">Loading exam prep...</p>
    </div>

    <div v-else-if="error" class="text-center py-12 bg-white rounded-lg mt-4">
      <p class="text-gray-700 mb-4">{{ error }}</p>
      <router-link
        to="/demo/exam-prep"
        class="inline-block px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg transition-colors"
      >
        Back to Exam Prep
      </router-link>
    </div>

    <div v-else class="bg-white rounded-lg shadow-lg p-8 mt-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Exam Prep Image -->
        <div class="md:col-span-1">
          <img
            :src="examPrep.image_url || EXAM_PREP_PLACEHOLDER"
            :alt="examPrep.title"
            class="w-full rounded-lg shadow-lg mb-4 bg-gray-100"
            @error="onImageError"
          />
          <button
            class="block w-full px-6 py-3 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-bold text-center transition-colors"
          >
            Continue Learning
          </button>
        </div>

        <!-- Exam Prep Details -->
        <div class="md:col-span-2">
          <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ examPrep.title }}</h1>
          <p v-if="examPrep.subtitle" class="text-gray-600 text-lg mb-4">{{ examPrep.subtitle }}</p>

          <!-- Each cell renders only if the record actually carries that value. -->
          <div v-if="facts.length" class="grid gap-4 mb-8 p-4 bg-gray-50 rounded-lg"
               :class="facts.length === 1 ? 'grid-cols-1' : facts.length === 2 ? 'grid-cols-2' : 'grid-cols-3'">
            <div v-for="fact in facts" :key="fact.label">
              <p class="text-gray-600 text-sm">{{ fact.label }}</p>
              <p class="text-lg font-bold text-[#0055A4]">{{ fact.value }}</p>
            </div>
          </div>

          <template v-if="examPrep.description">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">About This Exam Prep</h2>
            <p class="text-gray-700 mb-6 whitespace-pre-line">{{ examPrep.description }}</p>
          </template>

          <!-- Syllabus: the real section titles, with the material itself still locked. -->
          <template v-if="examPrep.outline?.length">
            <div class="flex items-baseline justify-between mb-4">
              <h2 class="text-2xl font-bold text-gray-800">What's Included</h2>
              <span class="text-sm text-gray-500">
                {{ examPrep.outline.length }} {{ examPrep.outline.length === 1 ? 'section' : 'sections' }}
              </span>
            </div>
            <ol class="border border-gray-200 rounded-lg divide-y divide-gray-200 mb-2">
              <li
                v-for="(section, index) in examPrep.outline"
                :key="index"
                class="flex items-center gap-4 px-4 py-3"
              >
                <span class="w-7 h-7 shrink-0 rounded-full bg-[#0055A4]/10 text-[#0055A4] text-xs font-bold flex items-center justify-center">
                  {{ index + 1 }}
                </span>
                <span class="text-gray-800 flex-1">{{ section }}</span>
                <Lock class="w-4 h-4 text-gray-300 shrink-0" />
              </li>
            </ol>
          </template>

          <!-- No syllabus stored: fall back to the record's own published count. -->
          <template v-else-if="examPrep.total_texts">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">What's Included</h2>
            <div class="border border-gray-200 rounded-lg p-4 mb-8">
              <h3 class="font-bold text-gray-800 mb-1">
                {{ examPrep.total_texts }} {{ examPrep.total_texts === 1 ? 'practice text' : 'practice texts' }}
              </h3>
              <p class="text-gray-600">Available to enrolled students.</p>
            </div>
          </template>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { Lock } from 'lucide-vue-next'
import axios from 'axios'
import { EXAM_PREP_PLACEHOLDER, makeImageErrorHandler } from '../../utils/imagePlaceholder'

const onImageError = makeImageErrorHandler(EXAM_PREP_PLACEHOLDER)
const route = useRoute()
const examPrep = ref({})
const loading = ref(false)
const error = ref('')

const facts = computed(() => [
  { label: 'Level', value: examPrep.value.level },
  { label: 'Language', value: examPrep.value.language },
  { label: 'Exam', value: examPrep.value.category },
].filter(fact => fact.value))

const loadExamPrep = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await axios.get(`/api/demo/exam-preps/${route.params.id}`)
    examPrep.value = response.data?.data ?? {}
  } catch (err) {
    console.error('Failed to load exam prep:', err)
    error.value = 'This exam prep is not available.'
    examPrep.value = {}
  } finally {
    loading.value = false
  }
}

onMounted(loadExamPrep)
</script>
