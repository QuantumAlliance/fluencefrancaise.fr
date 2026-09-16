<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Browse Courses</h1>
      <div class="flex gap-4">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search courses..."
          class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
          @input="searchCourses"
        />
        <select
          v-model="selectedCategory"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
          @change="filterCourses"
        >
          <option value="">All Categories</option>
          <option value="reading">Reading</option>
          <option value="grammar">Grammar</option>
          <option value="listening">Listening</option>
          <option value="vocabulary">Vocabulary</option>
          <option value="books">Books</option>
          <option value="lingopie">Lingopie</option>
        </select>
      </div>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
      <p class="mt-2 text-gray-500">Loading courses...</p>
    </div>

    <div v-else-if="courses.length === 0" class="text-center py-12 bg-white rounded-lg">
      <p class="text-gray-500 mb-4">No courses found</p>
      <router-link
        to="/student/dashboard"
        class="text-[#0055A4] hover:text-[#003d7a] font-medium"
      >
        Back to Dashboard
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="course in courses"
        :key="course.id"
        class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden cursor-pointer"
        @click="viewCourse(course.id)"
      >
        <img
          :src="course.image_url || 'https://via.placeholder.com/400x200?text=Course'"
          :alt="course.title"
          class="w-full h-48 object-cover"
        />
        <div class="p-6">
          <div class="flex justify-between items-start mb-2">
            <h3 class="text-xl font-bold text-gray-800 line-clamp-2">{{ course.title }}</h3>
            <span
              v-if="course.category"
              class="px-2 py-1 bg-[#0055A4]/10 text-[#003d7a] text-xs rounded-full ml-2 flex-shrink-0"
            >
              {{ course.category }}
            </span>
          </div>
          <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ course.description }}</p>
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm text-gray-600">
              <strong>Level:</strong> {{ course.level || 'Beginner' }}
            </span>
            <span class="text-sm text-gray-600">
              <strong>Language:</strong> {{ course.language || 'French' }}
            </span>
          </div>
          <div class="flex gap-2">
            <button
              @click.stop="viewCourse(course.id)"
              class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors"
            >
              View Details
            </button>
            <button
              v-if="!isEnrolled(course.id)"
              @click.stop="enrollCourse(course.id)"
              :disabled="enrollingCourseId === course.id"
              class="flex-1 px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] disabled:opacity-50 text-white rounded-lg transition-colors"
            >
              {{ enrollingCourseId === course.id ? 'Enrolling...' : 'Enroll' }}
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
import { useCourseStore } from '../../stores/course'
import { useToast } from '../../composables/useToast'

const router = useRouter()
const courseStore = useCourseStore()
const toast = useToast()
const courses = ref([])
const enrolledCourseIds = ref([])
const loading = ref(false)
const searchQuery = ref('')
const selectedCategory = ref('')
const enrollingCourseId = ref(null)

const isEnrolled = (courseId) => {
  return enrolledCourseIds.value.includes(courseId)
}

const viewCourse = (courseId) => {
  // Find the course in the courses array to check its category
  const course = courses.value.find(c => c.id === courseId)
  
  // Check if this is a books or lingopie course - redirect to custom_url instead
  // Handle both 'category' (from API mapping) and 'course_category' (from full course object)
  const category = course?.course_category || course?.category
  if (course && (category === 'books' || category === 'lingopie') && course.custom_url) {
    const target = course.custom_url_target || '_blank'
    if (target === '_blank') {
      window.open(course.custom_url, '_blank')
    } else {
      window.location.href = course.custom_url
    }
    return
  }
  
  router.push(`/student/courses/${courseId}`)
}

const enrollCourse = async (courseId) => {
  enrollingCourseId.value = courseId
  try {
    const response = await courseStore.enrollCourse(courseId)
    if (response.success) {
      enrolledCourseIds.value.push(courseId)
      toast.success('Successfully enrolled in course!')
      // Refresh courses to update enrollment status
      await loadCourses()
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
    enrollingCourseId.value = null
  }
}

const loadCourses = async () => {
  loading.value = true
  try {
    await courseStore.fetchCourses()
    courses.value = courseStore.courses || []
    
    // Load enrolled courses to check enrollment status
    try {
      await courseStore.fetchEnrolledCourses()
      enrolledCourseIds.value = courseStore.courses.map(c => c.id)
    } catch (error) {
      console.error('Failed to load enrolled courses:', error)
    }
  } catch (error) {
    console.error('Failed to load courses:', error)
    toast.error('Failed to load courses. Please try again.')
  } finally {
    loading.value = false
  }
}

const searchCourses = async () => {
  if (searchQuery.value.length >= 3) {
    loading.value = true
    try {
      await courseStore.searchCourses(searchQuery.value, selectedCategory.value || null)
      courses.value = courseStore.courses || []
    } catch (error) {
      console.error('Search error:', error)
      toast.error('Failed to search courses')
    } finally {
      loading.value = false
    }
  } else if (searchQuery.value.length === 0) {
    await loadCourses()
  }
}

const filterCourses = async () => {
  if (searchQuery.value.length >= 3) {
    await searchCourses()
  } else {
    await loadCourses()
  }
}

onMounted(async () => {
  await loadCourses()
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

