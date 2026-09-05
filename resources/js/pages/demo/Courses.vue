<template>
  <!-- Mirrors student/Courses.vue so a visitor sees the portal exactly as a student does. -->
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-800">My Courses</h1>
      <div class="flex gap-4">
        <select
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
      <p class="mt-2 text-gray-500">Loading courses...</p>
    </div>

    <div v-else-if="error" class="text-center py-12 bg-white rounded-lg shadow">
      <p class="text-gray-700">{{ error }}</p>
    </div>

    <div v-else-if="!courses.length" class="text-center py-12 bg-white rounded-lg shadow">
      <p class="text-gray-500">No courses found</p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="course in courses"
        :key="course.id"
        class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden"
      >
        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
          <img
            v-if="course.image_url"
            :src="course.image_url"
            :alt="course.title"
            class="w-full h-full object-cover"
          />
          <div v-else class="text-gray-400 text-sm">No Image</div>
        </div>

        <div class="p-6">
          <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">{{ course.title }}</h2>

          <p class="text-gray-600 text-sm mb-4 line-clamp-2">
            {{ course.description || course.subtitle || 'No description available' }}
          </p>

          <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm text-gray-600 font-medium">Progress</span>
              <span class="text-sm text-gray-800 font-semibold">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div style="width: 0%" class="bg-[#0055A4] h-2 rounded-full transition-all" />
            </div>
          </div>

          <button
            class="w-full bg-[#0055A4] hover:bg-[#003d7a] text-white font-medium py-3 px-4 rounded-lg transition-colors"
          >
            Continue Learning
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const courses = ref([])
const loading = ref(false)
const error = ref('')

const loadCourses = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await axios.get('/api/demo/courses?per_page=100')
    courses.value = response.data?.data?.data ?? []
  } catch (err) {
    console.error('Failed to load courses:', err)
    error.value = 'Unable to load your courses right now.'
  } finally {
    loading.value = false
  }
}

onMounted(loadCourses)
</script>
