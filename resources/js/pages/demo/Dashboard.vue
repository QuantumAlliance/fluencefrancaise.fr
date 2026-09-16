<template>
  <div class="p-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8">Dashboard</h1>

    <!-- Catalogue figures are real. Personal figures belong to an account, so they are
         shown as unset rather than invented. -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-2">
          <p class="text-gray-600 text-sm">Courses available</p>
          <BookOpen class="w-5 h-5 text-[#0055A4]" />
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ loading ? '—' : courseCount }}</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-2">
          <p class="text-gray-600 text-sm">Exam preps available</p>
          <GraduationCap class="w-5 h-5 text-[#0055A4]" />
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ loading ? '—' : examPrepCount }}</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-2">
          <p class="text-gray-600 text-sm">Lessons completed</p>
          <CheckCircle2 class="w-5 h-5 text-gray-300" />
        </div>
        <p class="text-3xl font-bold text-gray-300">—</p>
        <p class="text-xs text-gray-500 mt-1">Starts once you enrol</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-2">
          <p class="text-gray-600 text-sm">Homework due</p>
          <FileText class="w-5 h-5 text-gray-300" />
        </div>
        <p class="text-3xl font-bold text-gray-300">—</p>
        <p class="text-xs text-gray-500 mt-1">Assigned by your tutor</p>
      </div>
    </div>

    <h3 class="text-xl font-bold text-gray-800 mb-4">Courses you can join</h3>

    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-[#0055A4]"></div>
    </div>

    <div v-else-if="error" class="bg-white rounded-lg p-6 text-gray-700">{{ error }}</div>

    <div v-else-if="!courses.length" class="bg-white rounded-lg p-6 text-gray-600">
      No courses are published yet.
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
      <div
        v-for="course in courses"
        :key="course.id"
        class="bg-white rounded-lg shadow overflow-hidden"
      >
        <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
          <img
            v-if="course.image_url"
            :src="course.image_url"
            :alt="course.title"
            class="w-full h-full object-cover"
          />
          <div v-else class="text-gray-400 text-sm">No Image</div>
        </div>
        <div class="p-5">
          <h4 class="font-bold text-gray-800 mb-1">{{ course.title }}</h4>
          <p v-if="course.subtitle" class="text-sm text-gray-600 mb-3">{{ course.subtitle }}</p>
          <div class="flex flex-wrap gap-3 text-sm text-gray-600">
            <span v-if="course.level"><strong class="text-gray-700">Level:</strong> {{ course.level }}</span>
            <span v-if="course.total_texts">
              <strong class="text-gray-700">Lessons:</strong> {{ course.total_texts }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { BookOpen, GraduationCap, FileText, CheckCircle2 } from 'lucide-vue-next'
import axios from 'axios'

const courses = ref([])
const courseCount = ref(0)
const examPrepCount = ref(0)
const loading = ref(false)
const error = ref('')

const load = async () => {
  loading.value = true
  error.value = ''
  try {
    const [courseRes, examRes] = await Promise.all([
      axios.get('/api/demo/courses?per_page=100'),
      axios.get('/api/demo/exam-preps?per_page=100'),
    ])

    const courseData = courseRes.data?.data ?? {}
    courses.value = courseData.data ?? []
    courseCount.value = courseData.total ?? courses.value.length
    examPrepCount.value = examRes.data?.data?.total ?? 0
  } catch (err) {
    console.error('Failed to load the dashboard:', err)
    error.value = 'Unable to load your courses right now.'
  } finally {
    loading.value = false
  }
}

onMounted(load)
</script>
