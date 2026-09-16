<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-800">All Courses</h1>
      <div class="flex gap-4">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search courses..."
          class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
        />
        <select
          v-model="filterStatus"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
        >
          <option value="">All Status</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
      <p class="mt-2 text-gray-500">Loading courses...</p>
    </div>

    <div v-else-if="filteredCourses.length === 0" class="text-center py-12 bg-white rounded-lg shadow">
      <p class="text-gray-500 mb-4">No courses found</p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="course in filteredCourses"
        :key="course.id"
        class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden"
      >
        <!-- Banner / image area — prefer banner, fall back to course image -->
        <div class="w-full h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
          <img
            v-if="course.course_banner || course.course_image"
            :src="getBannerImageUrl(course.course_banner || course.course_image)"
            :alt="course.course_title"
            class="w-full h-full object-cover"
          />
          <div v-else class="w-full h-full bg-gradient-to-r from-[#0055A4] to-[#003d7a] flex items-center justify-center">
            <svg class="w-12 h-12 text-white/50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
          </div>
        </div>

        <!-- Content Area -->
        <div class="p-6">
          <!-- Title -->
          <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">
            {{ course.course_title }}
          </h2>

          <!-- Description -->
          <p class="text-gray-600 text-sm mb-4 line-clamp-2">
            {{ course.course_subtitle || course.course_description || 'No description available' }}
          </p>

          <!-- Preview Button -->
          <button
            @click="continueCourse(course)"
            class="w-full bg-[#0055A4] hover:bg-[#003d7a] text-white font-medium py-3 px-4 rounded-lg transition-colors"
          >
            Preview
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const loading = ref(false)
const courses = ref([])
const searchQuery = ref('')
const filterStatus = ref('')

const filteredCourses = computed(() => {
  return courses.value.filter(course => {
    const matchesSearch = course.course_title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         (course.course_subtitle && course.course_subtitle.toLowerCase().includes(searchQuery.value.toLowerCase()))

    if (filterStatus.value === 'active') {
      return matchesSearch && course.course_is_active
    }
    if (filterStatus.value === 'inactive') {
      return matchesSearch && !course.course_is_active
    }

    return matchesSearch
  })
})

const getBannerImageUrl = (imagePath) => {
  if (!imagePath) return ''
  if (imagePath.startsWith('http')) return imagePath
  const apiURL = import.meta.env.VITE_API_URL
  if (!imagePath.startsWith('/')) {
    return apiURL + '/storage/' + imagePath
  }
  return apiURL + imagePath
}

const loadCourses = async () => {
  loading.value = true
  try {
    // Get all courses created by admin
    const response = await axios.get('/api/admin/courses')

    if (response.data.success && response.data.data) {
      // Handle paginated response
      const coursesData = response.data.data.data || response.data.data || []
      courses.value = Array.isArray(coursesData) ? coursesData : []
    } else {
      courses.value = []
    }
  } catch (error) {
    console.error('Failed to load courses:', error)
    courses.value = []
  } finally {
    loading.value = false
  }
}

const continueCourse = (course) => {
  // Check if this is a books or lingopie course - redirect to custom_url instead
  if ((course.course_category === 'books' || course.course_category === 'lingopie') && course.custom_url) {
    const target = course.custom_url_target || '_blank'
    if (target === '_blank') {
      window.open(course.custom_url, '_blank')
    } else {
      window.location.href = course.custom_url
    }
    return
  }
  
  // Use the shared preview view so tutor sees the same UI as admin/student
  const courseSlug = course.course_title.toLowerCase().replace(/\s+/g, '-')
  router.push(`/courses/preview/${course.id}/${courseSlug}`)
}

onMounted(async () => {
  await loadCourses()
})
</script>
