<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-800">My Courses</h1>
      <div class="flex gap-4">

        <select
          v-model="filterStatus"
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

    <div v-else-if="filteredCourses.length === 0" class="text-center py-12 bg-white rounded-lg shadow">
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
        v-for="course in filteredCourses"
        :key="course.id"
        class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden"
      >
        <!-- Image/Placeholder Area -->
        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
          <img
            v-if="course.image_url"
            :src="course.image_url"
            :alt="course.title"
            class="w-full h-full object-cover"
          />
          <div v-else class="text-gray-400 text-sm">No Image</div>
        </div>

        <!-- Content Area -->
        <div class="p-6">
          <!-- Title -->
          <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">
            {{ course.title }}
          </h2>

          <!-- Description -->
          <p class="text-gray-600 text-sm mb-4 line-clamp-2">
            {{ course.description || course.subtitle || 'No description available' }}
          </p>

          <!-- Progress Section -->
          <div v-if="(course.course_category || course.category) !== 'books' && (course.course_category || course.category) !== 'lingopie'" class="mb-4">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm text-gray-600 font-medium">Progress</span>
              <span class="text-sm text-gray-800 font-semibold">{{ course.progress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div
                :style="{ width: course.progress + '%' }"
                class="bg-[#0055A4] h-2 rounded-full transition-all"
              />
            </div>
          </div>

          <!-- Continue Learning Button -->
          <button
            @click="continueCourse(course)"
            :class="[
              'w-full bg-[#0055A4] hover:bg-[#003d7a] text-white font-medium py-3 px-4 rounded-lg transition-colors',
              (course.course_category || course.category) === 'books' || (course.course_category || course.category) === 'lingopie' ? 'mt-4' : ''
            ]"
          >
            Continue Learning
          </button>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="pagination && pagination.last_page > 1" class="mt-8 flex justify-center">
      <div class="flex gap-2">
        <button
          v-if="pagination.current_page > 1"
          @click="loadPage(pagination.current_page - 1)"
          class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
        >
          Previous
        </button>
        <span class="px-4 py-2 text-gray-600">
          Page {{ pagination.current_page }} of {{ pagination.last_page }}
        </span>
        <button
          v-if="pagination.current_page < pagination.last_page"
          @click="loadPage(pagination.current_page + 1)"
          class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useToast } from '../../composables/useToast'

const router = useRouter()
const toast = useToast()
const courses = ref([])
const loading = ref(false)
const searchQuery = ref('')
const filterStatus = ref('')
const pagination = ref(null)
const currentPage = ref(1)

const filteredCourses = computed(() => {
  return courses.value.filter(course => {
    const matchesSearch = course.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         course.description?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         course.subtitle?.toLowerCase().includes(searchQuery.value.toLowerCase())

    if (!filterStatus.value) return matchesSearch

    const status = course.status.toLowerCase().replace(' ', '-')
    return matchesSearch && status === filterStatus.value
  })
})

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
  router.push(`/student/courses/${course.id}/${courseSlug}`)
}

const loadPage = async (page) => {
  currentPage.value = page
  await loadCourses()
}

const loadCourses = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/student/courses', {
      params: {
        page: currentPage.value
      }
    })

    console.log('API Response:', response.data)

    if (response.data.success && response.data.data) {
      // Handle paginated response
      if (response.data.data.data && Array.isArray(response.data.data.data)) {
        courses.value = response.data.data.data
        pagination.value = {
          current_page: response.data.data.current_page,
          last_page: response.data.data.last_page,
          per_page: response.data.data.per_page,
          total: response.data.data.total
        }
      } else if (Array.isArray(response.data.data)) {
        courses.value = response.data.data
        pagination.value = null
      } else {
        courses.value = [response.data.data]
        pagination.value = null
      }

      if (courses.value.length === 0) {
        toast.info('No courses available. Please contact admin.')
      }
    } else {
      console.warn('Unexpected response format:', response.data)
      courses.value = []
      if (response.data.message) {
        toast.warning(response.data.message)
      }
    }
  } catch (error) {
    console.error('Failed to load courses:', error)
    const errorMessage = error.response?.data?.message || error.message || 'Failed to load your courses. Please try again.'
    toast.error(errorMessage)
    courses.value = []
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadCourses()
})
</script>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
