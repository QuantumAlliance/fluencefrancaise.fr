<template>
  <div class="p-4 md:p-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Enrolled Courses</p>
            <p class="text-3xl font-bold text-[#0055A4] mt-2">{{ enrolledCourses.length }}</p>
          </div>
          <BookOpen class="w-12 h-12 text-[#0055A4] opacity-20" />
        </div>
      </div>

      <div
        @click="showSyllabusModal = true"
        class="bg-white rounded-lg shadow p-6 cursor-pointer hover:shadow-lg transition-shadow"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Overall Progress</p>
            <p class="text-3xl font-bold text-[#0055A4] mt-2">{{ overallProgress }}%</p>
          </div>
          <TrendingUp class="w-12 h-12 text-[#0055A4] opacity-20" />
        </div>
      </div>
    </div>

    <!-- Syllabus Progress Modal -->
    <div
      v-if="showSyllabusModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      @click.self="showSyllabusModal = false"
    >
      <div class="bg-white rounded-lg w-full max-w-4xl mx-4 max-h-[90vh] overflow-hidden flex flex-col">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-lg md:text-2xl font-semibold md:font-bold text-gray-800">Syllabus Progress</h2>
        </div>

        <div class="flex-1 overflow-auto p-6">
          <div v-if="syllabusData.length === 0" class="text-center py-12">
            <p class="text-gray-500">No syllabus data available</p>
          </div>

          <table v-else class="w-full">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">French Levels</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Topics</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="(item, index) in syllabusData" :key="index" class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-800 font-medium">
                  {{ shouldShowLevel(index) ? item.level : '' }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ item.topic }}</td>
                <td class="px-4 py-3">
                  <span
                    :class="[
                      'px-3 py-1 rounded-full text-xs font-medium',
                      item.status === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                    ]"
                  >
                    {{ item.status }}
                  </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{{ formatDate(item.date) }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="p-4 border-t border-gray-200 flex justify-end">
          <button
            @click="showSyllabusModal = false"
            class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors"
          >
            Close
          </button>
        </div>
      </div>
    </div>

    <!-- Currently Learning Section -->
    <div class="bg-white rounded-lg shadow mb-8">
      <div class="border-b border-gray-200 p-6">
        <h2 class="text-lg md:text-2xl font-semibold md:font-bold text-gray-800">Your Courses</h2>
      </div>

      <div v-if="loading" class="p-12 text-center">
        <p class="text-gray-500">Loading courses...</p>
      </div>

      <div v-else-if="enrolledCourses.length === 0" class="p-12 text-center">
        <p class="text-gray-500 mb-4">No enrolled courses yet</p>
        <router-link
          to="/student/browse-courses"
          class="inline-block px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg transition-colors"
        >
          Browse Courses
        </router-link>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
        <div
          v-for="course in enrolledCourses.slice(0, 6)"
          :key="course.id"
          class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow"
        >
          <img
            :src="course.image_url || 'https://via.placeholder.com/200?text=Course'"
            :alt="course.title"
            class="w-full h-40 object-cover rounded-lg mb-4"
          />
          <h3 class="font-bold text-gray-800 mb-2">{{ course.title }}</h3>
          <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ course.description }}</p>

          <div v-if="(course.course_category || course.category) !== 'books' && (course.course_category || course.category) !== 'lingopie'" class="mb-4">
            <div class="flex justify-between text-sm text-gray-600 mb-2">
              <span>Progress</span>
              <span>{{ courseProgress(course.id) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div
                :style="{ width: courseProgress(course.id) + '%' }"
                class="bg-[#0055A4] h-2 rounded-full transition-all"
              />
            </div>
          </div>

          <button
            @click="continueCourse(course)"
            :class="[
              'w-full px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg transition-colors',
              (course.course_category || course.category) === 'books' || (course.course_category || course.category) === 'lingopie' ? 'mt-4' : ''
            ]"
          >
            Continue Learning
          </button>
        </div>
      </div>

      <div v-if="enrolledCourses.length > 6" class="p-6 border-t border-gray-200 text-center">
        <router-link
          to="/student/courses"
          class="text-[#0055A4] hover:text-[#003d7a] font-medium"
        >
          View All My Courses →
        </router-link>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { BookOpen, TrendingUp } from 'lucide-vue-next'
import axios from 'axios'

const enrolledCourses = ref([])
const loading = ref(false)

// Syllabus-based overall progress
const overallProgress = ref(0)
const syllabusData = ref([])
const showSyllabusModal = ref(false)

// Get progress directly from course data (already calculated by API)
const courseProgress = (courseId) => {
  const course = enrolledCourses.value.find(c => c.id === courseId)
  return course?.progress || 0
}

// Format date for display
const formatDate = (date) => {
  if (!date) return 'dd-mm-yyyy'
  try {
    const d = new Date(date)
    if (isNaN(d.getTime())) return 'dd-mm-yyyy'
    return d.toLocaleDateString('en-GB', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    })
  } catch {
    return 'dd-mm-yyyy'
  }
}

// Only show level on first row of each level group
const shouldShowLevel = (index) => {
  if (index === 0) return true
  const currentLevel = syllabusData.value[index]?.level
  const previousLevel = syllabusData.value[index - 1]?.level
  return currentLevel !== previousLevel
}

const continueCourse = (course) => {
  // Check if this is a books or lingopie course - redirect to custom_url instead
  // Handle both 'category' (from API mapping) and 'course_category' (from full course object)
  const category = course.course_category || course.category
  if ((category === 'books' || category === 'lingopie') && course.custom_url) {
    const target = course.custom_url_target || '_blank'
    if (target === '_blank') {
      window.open(course.custom_url, '_blank')
    } else {
      window.location.href = course.custom_url
    }
    return
  }
  
  // Redirect to student course learning page
  const courseSlug = (course.title || 'course').toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '')
  window.location.href = `/student/courses/${course.id}/${courseSlug}`
}

onMounted(async () => {
  loading.value = true
  try {
    // Fetch courses and syllabus progress in parallel
    const [coursesResult, syllabusResult] = await Promise.all([
      axios.get('/api/student/courses'),
      axios.get('/api/student/syllabus-progress').catch(err => {
        console.error('Failed to load syllabus progress:', err)
        return { data: { success: false } }
      })
    ])

    // Get courses from API (progress is already calculated)
    if (coursesResult.data?.success && coursesResult.data?.data) {
      const data = coursesResult.data.data
      enrolledCourses.value = data.data || data || []
    }

    // Set overall progress and syllabus data
    if (syllabusResult.data?.success && syllabusResult.data?.data) {
      overallProgress.value = syllabusResult.data.data.progress_percentage || 0
      syllabusData.value = syllabusResult.data.data.syllabus || []
    }
  } catch (error) {
    console.error('Failed to load courses:', error)
  } finally {
    loading.value = false
  }
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
