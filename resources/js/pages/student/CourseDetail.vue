<template>
  <div class="p-8">
    <router-link to="/student/browse-courses" class="text-[#0055A4] hover:text-[#003d7a] mb-4 inline-block">
      ← Back to Browse Courses
    </router-link>

    <div class="bg-white rounded-lg shadow-lg p-8 mt-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Course Image -->
        <div class="md:col-span-1">
          <img
            :src="course.image_url || 'https://via.placeholder.com/300?text=Course'"
            :alt="course.title"
            class="w-full rounded-lg shadow-lg mb-4"
          />
          <button
            v-if="!enrolled"
            @click="enrollCourse"
            :disabled="enrolling"
            class="w-full px-6 py-3 bg-[#0055A4] hover:bg-[#003d7a] disabled:opacity-50 text-white rounded-lg font-bold transition-colors"
          >
            {{ enrolling ? 'Enrolling...' : 'Enroll Now' }}
          </button>
          <div v-else class="w-full px-6 py-3 bg-green-600 text-white rounded-lg font-bold text-center">
            ✓ Already Enrolled
          </div>
        </div>

        <!-- Course Details -->
        <div class="md:col-span-2">
          <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ course.title }}</h1>
          <p class="text-gray-600 text-lg mb-4">By {{ course.instructor_name || 'TBD' }}</p>

          <div class="grid grid-cols-3 gap-4 mb-8 p-4 bg-gray-50 rounded-lg">
            <div>
              <p class="text-gray-600 text-sm">Level</p>
              <p class="text-lg font-bold text-[#0055A4]">{{ course.level || 'Beginner' }}</p>
            </div>
            <div>
              <p class="text-gray-600 text-sm">Duration</p>
              <p class="text-lg font-bold text-[#0055A4]">4-6 weeks</p>
            </div>
            <div>
              <p class="text-gray-600 text-sm">Students</p>
              <p class="text-lg font-bold text-[#0055A4]">234 enrolled</p>
            </div>
          </div>

          <h2 class="text-2xl font-bold text-gray-800 mb-4">About This Course</h2>
          <p class="text-gray-700 mb-6">{{ course.description }}</p>

          <h2 class="text-2xl font-bold text-gray-800 mb-4">What You'll Learn</h2>
          <ul class="space-y-3 mb-8">
            <li class="flex items-center text-gray-700">
              <span class="text-green-600 font-bold mr-3">✓</span>
              Master French grammar fundamentals
            </li>
            <li class="flex items-center text-gray-700">
              <span class="text-green-600 font-bold mr-3">✓</span>
              Build conversational skills
            </li>
            <li class="flex items-center text-gray-700">
              <span class="text-green-600 font-bold mr-3">✓</span>
              Improve listening comprehension
            </li>
            <li class="flex items-center text-gray-700">
              <span class="text-green-600 font-bold mr-3">✓</span>
              Expand vocabulary
            </li>
            <li class="flex items-center text-gray-700">
              <span class="text-green-600 font-bold mr-3">✓</span>
              Practice with native speakers
            </li>
          </ul>

          <h2 class="text-2xl font-bold text-gray-800 mb-4">Course Structure</h2>
          <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg p-4">
              <h3 class="font-bold text-gray-800 mb-2">20 Lessons</h3>
              <p class="text-gray-600">Comprehensive lessons covering grammar, vocabulary, and conversation</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
              <h3 class="font-bold text-gray-800 mb-2">60+ Activities</h3>
              <p class="text-gray-600">Grammar exercises, reading passages, listening exercises, vocabulary drills</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
              <h3 class="font-bold text-gray-800 mb-2">Quizzes & Assessments</h3>
              <p class="text-gray-600">Test your knowledge and track your progress</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Related Courses -->
    <div class="mt-12">
      <h2 class="text-3xl font-bold text-gray-800 mb-6">Related Courses</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="related in relatedCourses" :key="related.id" class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
          <img
            :src="related.image || 'https://via.placeholder.com/200'"
            :alt="related.title"
            class="w-full h-40 object-cover rounded-t-lg"
          />
          <div class="p-4">
            <h3 class="font-bold text-gray-800 mb-2">{{ related.title }}</h3>
            <p class="text-gray-600 text-sm mb-3">{{ related.level }}</p>
            <router-link
              :to="`/student/courses/${related.id}`"
              class="text-[#0055A4] hover:text-[#003d7a] font-medium"
            >
              View Course →
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useCourseStore } from '../../stores/course'
import { useToast } from '../../composables/useToast'

const route = useRoute()
const courseStore = useCourseStore()
const toast = useToast()
const courseId = parseInt(route.params.id)
const enrolled = ref(false)
const enrolling = ref(false)
const loading = ref(false)

const course = ref({
  id: courseId,
  title: '',
  description: '',
  instructor_name: 'TBD',
  level: 'Beginner',
  image_url: null,
  category: '',
  language: ''
})

const relatedCourses = ref([])

const enrollCourse = async () => {
  enrolling.value = true
  try {
    const response = await courseStore.enrollCourse(courseId)
    if (response.success) {
    enrolled.value = true
      toast.success('Successfully enrolled in course!')
    } else {
      toast.error(response.message || 'Failed to enroll in course')
    }
  } catch (error) {
    console.error('Enrollment error:', error)
    if (error.response?.data?.message) {
      toast.error(error.response.data.message)
    } else {
      toast.error('Failed to enroll in course. Please try again.')
    }
  } finally {
    enrolling.value = false
  }
}

const loadCourseDetail = async () => {
  loading.value = true
  try {
    const response = await courseStore.fetchCourseDetail(courseId)
    if (response.success && response.data) {
      const courseData = response.data
      course.value = {
        id: courseData.id,
        title: courseData.course_title || courseData.title || 'Untitled Course',
        description: courseData.course_description || courseData.description || '',
        instructor_name: courseData.instructor_name || 'TBD',
        level: courseData.course_level || courseData.level || 'Beginner',
        image_url: courseData.course_image ? `/storage/${courseData.course_image.replace(/\/+$/, '')}` : courseData.image_url || null,
        category: courseData.course_category || courseData.category || '',
        language: courseData.course_language || courseData.language || 'French'
      }
      
      // Check if already enrolled
      try {
        await courseStore.fetchEnrolledCourses()
        enrolled.value = courseStore.courses.some(c => c.id === courseId)
      } catch (error) {
        console.error('Failed to check enrollment:', error)
      }
    }
  } catch (error) {
    console.error('Failed to load course detail:', error)
    toast.error('Failed to load course details')
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await loadCourseDetail()
})
</script>

<style scoped>
/* Additional styling if needed */
</style>
