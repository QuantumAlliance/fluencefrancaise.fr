<template>
  <div class="p-8">
    <router-link to="/demo/courses" class="text-[#0055A4] hover:text-[#003d7a] mb-4 inline-block">
      ← Back to Courses
    </router-link>

    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
      <p class="mt-2 text-gray-500">Loading course...</p>
    </div>

    <div v-else-if="error" class="text-center py-12 bg-white rounded-lg mt-4">
      <p class="text-gray-700 mb-4">{{ error }}</p>
      <router-link
        to="/demo/courses"
        class="inline-block px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg transition-colors"
      >
        Back to Courses
      </router-link>
    </div>

    <div v-else class="bg-white rounded-lg shadow-lg p-8 mt-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Course Image -->
        <div class="md:col-span-1">
          <img
            :src="course.image_url || COURSE_PLACEHOLDER"
            :alt="course.title"
            class="w-full rounded-lg shadow-lg mb-4 bg-gray-100"
            @error="onImageError"
          />
          <button
            class="block w-full px-6 py-3 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-bold text-center transition-colors"
          >
            Continue Learning
          </button>
        </div>

        <!-- Course Details -->
        <div class="md:col-span-2">
          <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ course.title }}</h1>
          <p v-if="course.subtitle" class="text-gray-600 text-lg mb-4">{{ course.subtitle }}</p>

          <!-- Each cell renders only if the course actually carries that value. -->
          <div v-if="facts.length" class="grid gap-4 mb-8 p-4 bg-gray-50 rounded-lg"
               :class="facts.length === 1 ? 'grid-cols-1' : facts.length === 2 ? 'grid-cols-2' : 'grid-cols-3'">
            <div v-for="fact in facts" :key="fact.label">
              <p class="text-gray-600 text-sm">{{ fact.label }}</p>
              <p class="text-lg font-bold text-[#0055A4]">{{ fact.value }}</p>
            </div>
          </div>

          <template v-if="course.description">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">About This Course</h2>
            <p class="text-gray-700 mb-6 whitespace-pre-line">{{ course.description }}</p>
          </template>

          <!-- Syllabus: the real lesson titles. Names are visible so a visitor can judge
               the course; the lessons themselves stay locked. -->
          <template v-if="course.outline?.length">
            <div class="flex items-baseline justify-between mb-4">
              <h2 class="text-2xl font-bold text-gray-800">Course Outline</h2>
              <span class="text-sm text-gray-500">
                {{ course.outline.length }} {{ course.outline.length === 1 ? 'lesson' : 'lessons' }}
              </span>
            </div>
            <ol class="border border-gray-200 rounded-lg divide-y divide-gray-200 mb-2">
              <li
                v-for="(lesson, index) in course.outline"
                :key="index"
                class="flex items-center gap-4 px-4 py-3"
              >
                <span class="w-7 h-7 shrink-0 rounded-full bg-[#0055A4]/10 text-[#0055A4] text-xs font-bold flex items-center justify-center">
                  {{ index + 1 }}
                </span>
                <span class="text-gray-800 flex-1">{{ lesson }}</span>
                <Lock class="w-4 h-4 text-gray-300 shrink-0" />
              </li>
            </ol>
          </template>

          <!-- No syllabus stored: fall back to the course's own published count. -->
          <template v-else-if="course.total_texts">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Course Structure</h2>
            <div class="border border-gray-200 rounded-lg p-4 mb-8">
              <h3 class="font-bold text-gray-800 mb-1">
                {{ course.total_texts }} {{ course.total_texts === 1 ? 'lesson' : 'lessons' }}
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
import { COURSE_PLACEHOLDER, makeImageErrorHandler } from '../../utils/imagePlaceholder'

const onImageError = makeImageErrorHandler(COURSE_PLACEHOLDER)
const route = useRoute()
const course = ref({})
const loading = ref(false)
const error = ref('')

const facts = computed(() => [
  { label: 'Level', value: course.value.level },
  { label: 'Language', value: course.value.language },
  { label: 'Category', value: course.value.category },
].filter(fact => fact.value))

const loadCourse = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await axios.get(`/api/demo/courses/${route.params.id}`)
    course.value = response.data?.data ?? {}
  } catch (err) {
    console.error('Failed to load course:', err)
    error.value = 'This course is not available.'
    course.value = {}
  } finally {
    loading.value = false
  }
}

onMounted(loadCourse)
</script>
