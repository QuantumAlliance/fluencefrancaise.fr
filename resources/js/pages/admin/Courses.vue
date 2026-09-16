<template>
  <div class="">
    <div class="flex justify-end items-center mb-6">
      <button
        @click="showCreateForm = true"
        class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
      >
        + Add New Course
      </button>
    </div>

    <!-- Saving Order Indicator -->
    <div v-if="savingOrder" class="mb-4 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg flex items-center gap-2">
      <div class="animate-spin rounded-full h-4 w-4 border-2 border-blue-700 border-t-transparent"></div>
      <span>Saving order...</span>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6 flex gap-4">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search courses..."
        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
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

    <!-- Courses Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
      <div v-if="loadingCourses" class="p-8 text-center">
        <svg class="animate-spin h-8 w-8 text-[#0055A4] mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-500 mt-4">Loading courses...</p>
      </div>
      <table v-else class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-3 py-3 text-left text-sm font-semibold text-gray-700 w-10"></th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Course Name</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Category</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Language</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Level</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(course, index) in filteredCourses"
            :key="course.id"
            class="border-b border-gray-200 hover:bg-gray-50 transition-colors"
            :class="{
              'bg-blue-50 border-blue-300': dragOverIndex === index,
              'opacity-50': draggingIndex === index
            }"
            draggable="true"
            @dragstart="handleDragStart($event, index)"
            @dragend="handleDragEnd"
            @dragover.prevent="handleDragOver($event, index)"
            @dragleave="handleDragLeave"
            @drop.prevent="handleDrop($event, index)"
          >
            <!-- Drag Handle -->
            <td class="px-3 py-4 cursor-move">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
              </svg>
            </td>
            <td class="px-6 py-4">
              <div>
                <p class="font-medium text-gray-800">{{ course.course_title }}</p>
                <p class="text-gray-600 text-sm">{{ course.course_description?.substring(0, 50) }}...</p>
              </div>
            </td>
            <td class="px-6 py-4 text-gray-700">{{ course.course_category }}</td>
            <td class="px-6 py-4 text-gray-700">{{ course.course_language }}</td>
            <td class="px-6 py-4 text-gray-700">{{ course.course_level || 'Beginner' }}</td>
            <td class="px-6 py-4">
              <span
                :class="course.course_is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                class="px-3 py-1 rounded-full text-sm font-medium"
              >
                {{ course.course_is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <LoadingButton
                  v-if="course.course_category !== 'books' && course.course_category !== 'lingopie'"
                  @click="previewCourse(course)"
                  variant="success"
                  size="sm"
                  text="Preview"
                />
                <LoadingButton
                @click="editCourse(course)"
                  :loading="isIdLoading(`edit-${course.id}`)"
                  :disabled="isLoading || isIdLoading(`delete-${course.id}`) || isIdLoading(`toggle-${course.id}`)"
                  variant="info"
                  size="sm"
                  text="Edit"
                  loading-text="Loading..."
                />
                <LoadingButton
                @click="toggleStatus(course)"
                  :loading="isIdLoading(`toggle-${course.id}`)"
                  :disabled="isLoading || isIdLoading(`delete-${course.id}`) || isIdLoading(`edit-${course.id}`)"
                  variant="warning"
                  size="sm"
                  :text="course.course_is_active ? 'Deactivate' : 'Activate'"
                  loading-text="Updating..."
                />
                <LoadingButton
                @click="deleteCourse(course.id)"
                  :loading="isIdLoading(`delete-${course.id}`)"
                  :disabled="isLoading || isIdLoading(`toggle-${course.id}`) || isIdLoading(`edit-${course.id}`)"
                  variant="danger"
                  size="sm"
                  text="Delete"
                  loading-text="Deleting..."
                />
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="!loadingCourses && filteredCourses.length === 0" class="p-8 text-center">
        <p class="text-gray-500">No courses found</p>
      </div>
    </div>

    <!-- Create/Edit Form Modal -->
    <div
      v-if="showCreateForm"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto py-8"
    >
      <div class="bg-white rounded-lg p-8 max-w-3xl w-full mx-4 my-auto max-h-[90vh] overflow-y-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
          {{ editingCourse ? 'Edit Course' : 'Create New Course' }}
        </h2>

        <form @submit.prevent="saveCourse" class="space-y-6">
          <!-- Course Title and Subtitle -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Course Title <span class="text-red-500">*</span></label>
              <input
                v-model="formData.course_title"
                type="text"
                required
                placeholder="e.g., French Grammar Basics"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>
          </div>

          <!-- Category and Language -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Category <span class="text-red-500">*</span></label>
              <select
                v-model="formData.course_category"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              >
                <option value="">Select Category</option>
                <option value="reading">Reading</option>
                <option value="grammar">Grammar</option>
                <option value="listening">Listening</option>
                <option value="vocabulary">Vocabulary</option>
                <option value="books">Books</option>
                <option value="lingopie">Lingopie</option>
              </select>
            </div>
            <div v-if="!isExternalLinkCategory">
              <label class="block text-gray-700 text-sm font-semibold mb-2">Language <span class="text-red-500">*</span></label>
              <input
                v-model="formData.course_language"
                type="text"
                :required="!isExternalLinkCategory"
                placeholder="e.g., French"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>
          </div>

          <!-- Custom URL (for Books/Lingopie) -->
          <div v-if="isExternalLinkCategory" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Custom URL <span class="text-red-500">*</span>
            </label>
            <input
              v-model="formData.custom_url"
              type="url"
              required
              placeholder="https://example.com/your-resource"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
            
            <!-- Link Target Option -->
            <div class="mt-3">
              <label class="block text-gray-700 text-sm font-semibold mb-2">Open Link In:</label>
              <div class="flex gap-4">
                <label class="flex items-center cursor-pointer">
                  <input
                    v-model="formData.custom_url_target"
                    type="radio"
                    value="_blank"
                    class="w-4 h-4 text-[#0055A4] focus:ring-[#0055A4]"
                  />
                  <span class="ml-2 text-sm text-gray-700">New Tab (External)</span>
                </label>
                <label class="flex items-center cursor-pointer">
                  <input
                    v-model="formData.custom_url_target"
                    type="radio"
                    value="_self"
                    class="w-4 h-4 text-[#0055A4] focus:ring-[#0055A4]"
                  />
                  <span class="ml-2 text-sm text-gray-700">Same Tab (Internal)</span>
                </label>
              </div>
            </div>
            
            <p class="text-xs text-gray-600 mt-2">
              <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
              </svg>
              When users click on this course, they will be redirected to this URL
            </p>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Description 
              <span v-if="!isExternalLinkCategory" class="text-red-500">*</span>
            </label>
            <textarea
              v-model="formData.course_description"
              rows="3"
              :required="!isExternalLinkCategory"
              placeholder="Provide a detailed description of the course"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
          </div>

          <!-- Level Section -->
          <div v-if="!isExternalLinkCategory" class="border-t pt-4">
            <label class="block text-gray-700 text-sm font-semibold mb-3">Level <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <select
                  v-model="formData.course_level_select"
                  @change="handleLevelChange"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                >
                  <option value="">Select Level</option>
                  <option value="Beginner">Beginner</option>
                  <option value="Intermediate">Intermediate</option>
                  <option value="Advanced">Advanced</option>
                  <option value="A1">A1</option>
                  <option value="A2">A2</option>
                  <option value="B1">B1</option>
                  <option value="B2">B2</option>
                  <option value="C1">C1</option>
                  <option value="C2">C2</option>
                  <option value="Beginner to Intermediate">Beginner to Intermediate</option>
                  <option value="other">Other (Custom)</option>
                </select>
              </div>
              <div v-if="formData.course_level_select === 'other'">
                <input
                  v-model="formData.course_level_custom"
                  type="text"
                  placeholder="Enter custom level"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                />
              </div>
            </div>
          </div>

          <!-- Number of Texts -->
          <div v-if="!isExternalLinkCategory">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Number of Texts/Readings</label>
            <input
              v-model.number="formData.course_total_texts"
              type="number"
              min="1"
              max="100"
              placeholder="5"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
          </div>

          <!-- Course Images Upload -->
          <div class="border-t pt-4 space-y-4">
            <!-- Course Image -->
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Course Image</label>
              <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-lg overflow-hidden border border-gray-300 bg-gray-100 flex items-center justify-center">
                  <img 
                    v-if="formData.course_image_preview" 
                    :src="formData.course_image_preview" 
                    alt="Course preview" 
                    class="w-full h-full object-cover"
                    @error="handleImageError"
                  />
                  <div v-else class="text-gray-400 text-xs text-center px-2">
                    <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Course preview</span>
                  </div>
                </div>
                <input
                  type="file"
                  accept="image/*"
                  @change="handleImageUpload"
                  class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                />
              </div>
            </div>

            <!-- Course Banner Image (hidden for Books/LingoPie) -->
            <div v-if="!isExternalLinkCategory">
              <label class="block text-gray-700 text-sm font-semibold mb-2">Course Banner Image</label>
              <div class="flex items-center gap-4">
                <div class="w-32 h-20 rounded-lg overflow-hidden border border-gray-300 bg-gray-100 flex items-center justify-center">
                  <img 
                    v-if="formData.course_banner_preview" 
                    :src="formData.course_banner_preview" 
                    alt="Banner preview" 
                    class="w-full h-full object-cover"
                    @error="handleBannerError"
                  />
                  <div v-else class="text-gray-400 text-xs text-center px-2">
                    <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Banner preview</span>
                  </div>
                </div>
                <input
                  type="file"
                  accept="image/*"
                  @change="handleBannerUpload"
                  class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                />
              </div>
            </div>
          </div>

          <!-- Course Content JSON Sections -->
          <div v-if="!isExternalLinkCategory" v-for="(section, index) in formData.course_sections" :key="`section-${index}-${section.content?.substring(0, 20) || ''}`" class="border-t pt-4">
            <div class="flex justify-between items-center mb-3">
              <label class="block text-gray-700 text-sm font-semibold">Course Content (JSON) <span v-if="index === 0" class="text-red-500">*</span></label>
              <div class="space-x-2">
                <button
                  type="button"
                  @click="addNewSectionBlock"
                  class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-sm font-medium transition-colors"
                >
                  + Add New Section
                </button>
                <button
                  v-if="formData.course_sections.length > 1"
                  type="button"
                  @click="removeSectionBlock(index)"
                  class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm font-medium transition-colors"
                >
                  Remove
                </button>
              </div>
            </div>

            <!-- JSON Editor -->
            <textarea
              v-model="formData.course_sections[index].content"
              :required="index === 0"
              rows="8"
              placeholder='[{"section": "Introduction", "content": "..."}, {"section": "Lesson 1", "content": "..."}]'
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] font-mono text-sm"
            />
            <p class="text-gray-500 text-xs mt-2">Enter JSON format with your section data</p>
          </div>

          <!-- Active Status -->
          <div class="border-t pt-4">
            <label class="flex items-center cursor-pointer">
              <input
                v-model="formData.course_is_active"
                type="checkbox"
                class="w-4 h-4 text-[#0055A4]"
              />
              <span class="ml-2 text-gray-700 text-sm font-medium">Active Course</span>
            </label>
          </div>

          <!-- Form Actions -->
          <div class="flex gap-4 pt-6 border-t">
            <LoadingButton
              type="submit"
              :loading="isLoading"
              variant="primary"
              size="md"
              :text="editingCourse ? 'Update Course' : 'Create Course'"
              :loading-text="editingCourse ? 'Updating...' : 'Creating...'"
              class="flex-1"
            />
            <LoadingButton
              type="button"
              @click="showCreateForm = false"
              :disabled="isLoading"
              variant="secondary"
              size="md"
              text="Cancel"
              class="flex-1"
            />
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useCourseStore } from '../../stores/course'
import LoadingButton from '../../components/LoadingButton.vue'
import { useLoading } from '../../composables/useLoading'
import { useToast } from '../../composables/useToast'

const router = useRouter()
const courseStore = useCourseStore()
const toast = useToast()
const courses = ref([])
const searchQuery = ref('')
const filterStatus = ref('')
const showCreateForm = ref(false)
const editingCourse = ref(null)
const loadingCourses = ref(false)

// Drag and drop state
const draggingIndex = ref(null)
const dragOverIndex = ref(null)
const savingOrder = ref(false)

// Use reusable loading composable
const { isLoading, setLoading, isIdLoading, setLoadingId, clearLoadingId } = useLoading()

const formData = ref({
  course_title: '',
  course_subtitle: '',
  course_description: '',
  course_category: '',
  course_language: '',
  course_level_select: '',
  course_level_custom: '',
  course_level: '',
  course_total_texts: 5,
  course_is_active: true,
  course_image: null,
  course_image_preview: null,
  course_banner: null,
  course_banner_preview: null,
  course_sections: [{ content: '' }],
  custom_url: '',
  custom_url_target: '_blank'
})

// Computed property to check if category requires external link
const isExternalLinkCategory = computed(() => {
  return formData.value.course_category === 'books' || formData.value.course_category === 'lingopie'
})



const filteredCourses = computed(() => {
  return courses.value.filter(course => {
    const matchesSearch = (course.course_title || '').toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesStatus = !filterStatus.value || (filterStatus.value === 'active' ? course.course_is_active : !course.course_is_active)
    return matchesSearch && matchesStatus
  })
})

const previewCourse = (course) => {
  // Open course preview in the same tab
  const courseSlug = course.course_title.toLowerCase().replace(/\s+/g, '-')
  router.push(`/courses/preview/${course.id}/${courseSlug}`)
}

const editCourse = async (course) => {
  setLoadingId(`edit-${course.id}`)
  editingCourse.value = course

  // Fetch fresh course data from server to ensure we have the latest JSON content
  try {
    const apiURL = import.meta.env.VITE_API_URL
    const fullUrl = apiURL + `/api/admin/courses/${course.id}`

    const response = await fetch(fullUrl, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.ok) {
      const data = await response.json()
      if (data.success && data.data) {
        course = data.data
      } else {
        course = courses.value.find(c => c.id === course.id) || course
      }
    } else {
      const errorData = await response.json().catch(() => ({ message: 'Unknown error' }))
      console.error('Failed to fetch course:', response.status, errorData)
      course = courses.value.find(c => c.id === course.id) || course
    }
  } catch (error) {
    console.error('Failed to fetch fresh course data:', error)
    course = courses.value.find(c => c.id === course.id) || course
  } finally {
    clearLoadingId(`edit-${course.id}`)
  }

  // Parse course_sections from the JSON content
  let courseSections = [{ content: '' }]

  if (course.course_json_content) {
    try {
      let parsed = course.course_json_content

      // If it's a string, parse it
      if (typeof parsed === 'string') {
        if (parsed.trim() === '') {
          courseSections = [{ content: '' }]
        } else {
        parsed = JSON.parse(parsed)
        }
      }

      // Only process if we have valid parsed data
      if (parsed) {
      // Handle NEW format: array of sections
      if (Array.isArray(parsed)) {
          // Ensure we have at least one section
          if (parsed.length === 0) {
            courseSections = [{ content: '' }]
          } else {
            courseSections = parsed.map((section) => {
              const content = typeof section === 'string' ? section : JSON.stringify(section, null, 2)
              return { content }
            })
          }
      }
      // Handle OLD format: object with {category, difficulty, activities}
      else if (parsed.activities && Array.isArray(parsed.activities)) {
        // Split the activities into separate sections, each with full structure
        courseSections = parsed.activities.map(activity => {
          const sectionJson = {
            category: parsed.category || 'Reading',
            difficulty: parsed.difficulty || 'Intensive',
            activities: [activity]
          }
          return {
            content: JSON.stringify(sectionJson, null, 2)
          }
        })
      }
      // Handle single object format
      else if (parsed.title || parsed.text) {
        courseSections = [{ content: JSON.stringify(parsed, null, 2) }]
      }
      // Fallback: show the whole object as one section
      else {
        courseSections = [{ content: JSON.stringify(parsed, null, 2) }]
        }
      }
    } catch (e) {
      console.error('Error parsing course JSON:', e)
      courseSections = [{ content: String(course.course_json_content) }]
    }
  }

  // Use Vue's reactivity to ensure sections are properly set
  formData.value = {
    ...course,
    course_sections: courseSections,
    // Set preview images from the database if they exist
    // Try multiple possible paths in case storage link is different
    course_image_preview: course.course_image ? (course.course_image.startsWith('http') ? course.course_image : `/storage/${course.course_image.replace(/\/+$/, '')}`) : null,
    course_banner_preview: course.course_banner ? (course.course_banner.startsWith('http') ? course.course_banner : `/storage/${course.course_banner.replace(/\/+$/, '')}`) : null,
    // Keep the original file references for later update
    course_image: null,
    course_banner: null
  }

  // Force reactivity update
  formData.value = { ...formData.value }

  showCreateForm.value = true
}

const saveCourse = async () => {
  try {
    // Validate required fields
    if (!formData.value.course_title) {
      toast.error('Course title is required')
      return
    }
    if (!formData.value.course_category) {
      toast.error('Category is required')
      return
    }

    // Check if this is an external link category
    const isExternal = formData.value.course_category === 'books' || formData.value.course_category === 'lingopie'

    // Conditional validation based on category
   if (isExternal) {
      // For external links, only custom_url is required
      if (!formData.value.custom_url) {
        toast.error('Custom URL is required for Books/Lingopie')
        return
      }
    } else {
      // For regular courses, validate all fields
      if (!formData.value.course_description) {
        toast.error('Description is required')
        return
      }
      if (!formData.value.course_language) {
        toast.error('Language is required')
        return
      }
      if (!formData.value.course_level_select && !formData.value.course_level) {
        toast.error('Level is required')
        return
      }
      if (!formData.value.course_sections || formData.value.course_sections.length === 0 || !formData.value.course_sections[0].content) {
        toast.error('Course content (JSON) is required')
        return
      }
    }

    setLoading(true)

    const formDataObj = new FormData()

    // Add all text fields (ensure all are sent even if empty)
    formDataObj.append('course_title', formData.value.course_title || '')
    formDataObj.append('course_subtitle', formData.value.course_subtitle || '')
    formDataObj.append('course_description', formData.value.course_description || '')
    formDataObj.append('course_category', formData.value.course_category || '')
    formDataObj.append('course_language', formData.value.course_language || '')
    formDataObj.append('course_level_custom', formData.value.course_level_custom || '')
    const finalLevel = formData.value.course_level || formData.value.course_level_custom || ''
    formDataObj.append('course_level', finalLevel)
    formDataObj.append('course_total_texts', formData.value.course_total_texts || 5)
    formDataObj.append('custom_url', formData.value.custom_url || '')
    formDataObj.append('custom_url_target', formData.value.custom_url_target || '_blank')

    // Store each section as a separate JSON entry in an array (NOT merged)
    let sectionsArray = []

    if (!isExternal && formData.value.course_sections && formData.value.course_sections.length > 0) {
      for (let i = 0; i < formData.value.course_sections.length; i++) {
        const section = formData.value.course_sections[i]
        if (section.content && section.content.trim()) {
          try {
            const parsed = JSON.parse(section.content)
            sectionsArray.push(parsed)
          } catch (e) {
            console.error(`Error parsing section ${i + 1}:`, e)
            toast.error(`Error in JSON section ${i + 1}: ${e.message}`)
            return
          }
        }
      }
    }

    // Store as array of sections (each keeps its own structure)
    const sectionsJSON = JSON.stringify(sectionsArray, null, 2)
    formDataObj.append('course_json_content', sectionsJSON)
    formDataObj.append('course_is_active', formData.value.course_is_active ? 1 : 0)

    // Add files if selected
    if (formData.value.course_image) {
      formDataObj.append('course_image', formData.value.course_image)
    }
    if (formData.value.course_banner) {
      formDataObj.append('course_banner', formData.value.course_banner)
    }

    const url = editingCourse.value
      ? `/api/admin/courses/${editingCourse.value.id}`
      : '/api/admin/courses'

    const token = localStorage.getItem('token')

    try {
      let responseData

      // Use native fetch for FormData - it handles multipart/form-data correctly
      // For PUT/DELETE methods with FormData, use POST with _method spoofing
      if (editingCourse.value) {
        // Add _method field for Laravel method spoofing
        formDataObj.append('_method', 'PUT')
      }

      // Get the full API URL
      const apiURL = import.meta.env.VITE_API_URL
      const fullUrl = apiURL + url

      // Get XSRF token from cookie for CSRF protection
      const xsrfToken = document.cookie
        .split('; ')
        .find(row => row.startsWith('XSRF-TOKEN='))
        ?.split('=')[1]

      const headers = {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Accept': 'application/json'
      }

      // Add XSRF token if available
      if (xsrfToken) {
        headers['X-XSRF-TOKEN'] = decodeURIComponent(xsrfToken)
      }

      const response = await fetch(fullUrl, {
        method: 'POST', // Always POST, let Laravel handle _method
        headers,
        credentials: 'include', // Include cookies for CSRF
        body: formDataObj
      })

      if (!response.ok) {
        let errorData
        try {
          errorData = await response.json()
        } catch (parseError) {
          errorData = { message: `HTTP ${response.status}` }
        }
        throw new Error(errorData.message || `HTTP ${response.status}`)
      }

      const data = await response.json()
      responseData = data

      if (editingCourse.value) {

        // Update the course in the list with the response data first
        const index = courses.value.findIndex(c => c.id === editingCourse.value.id)
        if (index !== -1) {
          courses.value.splice(index, 1, responseData.data)
        }

        // Reload courses from admin endpoint to ensure we have the latest data
        await loadCourses()
      } else {
        // Add to list
        courses.value.push(responseData.data)
      }

      toast.success(editingCourse.value ? 'Course updated successfully' : 'Course created successfully')
      resetForm()
      showCreateForm.value = false
    } catch (apiError) {
      console.error('API Error:', apiError)
      toast.error(apiError.message || 'Failed to save course')
    }
  } catch (error) {
    console.error('Save error:', error)
    toast.error('Error saving course: ' + error.message)
  } finally {
    setLoading(false)
  }
}

const toggleStatus = async (course) => {
  setLoadingId(`toggle-${course.id}`)
  try {
    // Use FormData for Laravel method spoofing (PUT via POST with _method)
    const formData = new FormData()
    formData.append('_method', 'PUT')
    formData.append('course_is_active', !course.course_is_active ? 1 : 0)

    const apiURL = import.meta.env.VITE_API_URL
    const fullUrl = apiURL + `/api/admin/courses/${course.id}`

    // Get XSRF token from cookie for CSRF protection
    const xsrfToken = document.cookie
      .split('; ')
      .find(row => row.startsWith('XSRF-TOKEN='))
      ?.split('=')[1]

    const headers = {
      'Authorization': `Bearer ${localStorage.getItem('token')}`,
      'Accept': 'application/json'
    }

    if (xsrfToken) {
      headers['X-XSRF-TOKEN'] = decodeURIComponent(xsrfToken)
    }

    const response = await fetch(fullUrl, {
      method: 'POST', // POST with _method=PUT for Laravel
      headers,
      credentials: 'include',
      body: formData
    })

    if (response.ok) {
      const responseData = await response.json()
      if (responseData.success) {
        // Update the course in the list
        const index = courses.value.findIndex(c => c.id === course.id)
        if (index !== -1) {
          courses.value[index].course_is_active = !course.course_is_active
        }
        // Reload courses to ensure we have the latest data
        await loadCourses()
        toast.success(`Course ${!course.course_is_active ? 'activated' : 'deactivated'} successfully`)
      } else {
        toast.error(responseData.message || 'Failed to update course status')
      }
    } else {
      const error = await response.json().catch(() => ({ message: 'Unknown error' }))
      toast.error(error.message || 'Failed to update course status')
    }
  } catch (error) {
    console.error('Toggle status error:', error)
    toast.error('Error updating course status: ' + error.message)
  } finally {
    clearLoadingId(`toggle-${course.id}`)
  }
}

const deleteCourse = async (courseId) => {
  if (confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
    setLoadingId(`delete-${courseId}`)
    try {
      const apiURL = import.meta.env.VITE_API_URL
      const fullUrl = apiURL + `/api/admin/courses/${courseId}`

      // Get XSRF token from cookie for CSRF protection
      const xsrfToken = document.cookie
        .split('; ')
        .find(row => row.startsWith('XSRF-TOKEN='))
        ?.split('=')[1]

      const headers = {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }

      if (xsrfToken) {
        headers['X-XSRF-TOKEN'] = decodeURIComponent(xsrfToken)
      }

      const response = await fetch(fullUrl, {
        method: 'DELETE',
        headers,
        credentials: 'include'
      })

      if (response.ok) {
        courses.value = courses.value.filter(c => c.id !== courseId)
        toast.success('Course deleted successfully')
      } else {
        const error = await response.json()
        toast.error(error.message || 'Failed to delete course')
      }
    } catch (error) {
      console.error('Delete error:', error)
      toast.error('Error deleting course: ' + error.message)
    } finally {
      clearLoadingId(`delete-${courseId}`)
    }
  }
}

// Drag and drop handlers
const handleDragStart = (event, index) => {
  draggingIndex.value = index
  event.dataTransfer.effectAllowed = 'move'
  event.dataTransfer.setData('text/plain', index.toString())
}

const handleDragEnd = () => {
  draggingIndex.value = null
  dragOverIndex.value = null
}

const handleDragOver = (event, index) => {
  if (draggingIndex.value !== null && draggingIndex.value !== index) {
    dragOverIndex.value = index
  }
}

const handleDragLeave = () => {
  dragOverIndex.value = null
}

const handleDrop = async (event, dropIndex) => {
  const dragIndex = draggingIndex.value

  if (dragIndex === null || dragIndex === dropIndex) {
    dragOverIndex.value = null
    return
  }

  // If filtering is active, show a message and don't reorder
  if (searchQuery.value || filterStatus.value) {
    toast.error('Please clear filters before reordering')
    dragOverIndex.value = null
    draggingIndex.value = null
    return
  }

  // Reorder the array
  const items = [...courses.value]
  const [draggedItem] = items.splice(dragIndex, 1)
  items.splice(dropIndex, 0, draggedItem)

  // Update display_order for all items based on new position
  items.forEach((item, index) => {
    item.display_order = index + 1
  })

  courses.value = items
  dragOverIndex.value = null
  draggingIndex.value = null

  // Save the new order to the server
  await saveOrder()
}

const saveOrder = async () => {
  savingOrder.value = true
  try {
    const orderData = courses.value.map((item, index) => ({
      id: item.id,
      display_order: index + 1
    }))

    const apiURL = import.meta.env.VITE_API_URL
    const xsrfToken = document.cookie
      .split('; ')
      .find(row => row.startsWith('XSRF-TOKEN='))
      ?.split('=')[1]

    const headers = {
      'Authorization': `Bearer ${localStorage.getItem('token')}`,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    }

    if (xsrfToken) {
      headers['X-XSRF-TOKEN'] = decodeURIComponent(xsrfToken)
    }

    await fetch(`${apiURL}/api/admin/courses/reorder`, {
      method: 'POST',
      headers,
      credentials: 'include',
      body: JSON.stringify({ order: orderData })
    })

    toast.success('Order saved successfully')
  } catch (error) {
    console.error('Failed to save order:', error)
    toast.error('Failed to save order')
    // Reload to get the correct order from server
    await loadCourses()
  } finally {
    savingOrder.value = false
  }
}

const resetForm = () => {
  formData.value = {
    course_title: '',
    course_subtitle: '',
    course_description: '',
    course_category: '',
    course_language: '',
    course_level_select: '',
    course_level_custom: '',
    course_level: '',
    course_total_texts: 5,
    course_is_active: true,
    course_image: null,
    course_image_preview: null,
    course_banner: null,
    course_banner_preview: null,
    course_sections: [{ content: '' }],
    custom_url: '',
    custom_url_target: '_blank'
  }
  editingCourse.value = null
}

const handleLevelChange = () => {
  if (formData.value.course_level_select === 'other') {
    formData.value.course_level = ''
  } else {
    formData.value.course_level = formData.value.course_level_select
    formData.value.course_level_custom = ''
  }
}

const handleImageUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    formData.value.course_image = file
    const reader = new FileReader()
    reader.onload = (e) => {
      formData.value.course_image_preview = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const handleBannerUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    formData.value.course_banner = file
    const reader = new FileReader()
    reader.onload = (e) => {
      formData.value.course_banner_preview = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const addNewSectionBlock = () => {
  formData.value.course_sections.push({ content: '' })
}

const removeSectionBlock = (index) => {
  if (formData.value.course_sections.length > 1) {
    formData.value.course_sections.splice(index, 1)
  }
}


const loadCourses = async () => {
  loadingCourses.value = true
  try {
    const response = await axios.get('/api/admin/courses')
    
    if (response.data.success) {
      // Handle paginated response
      if (response.data.data?.data) {
        courses.value = response.data.data.data
      } else if (Array.isArray(response.data.data)) {
        courses.value = response.data.data
      } else {
        courses.value = []
      }
    } else {
      courses.value = []
      toast.error(response.data.message || 'Failed to load courses')
    }
  } catch (error) {
    console.error('Failed to load courses:', error)
    toast.error(error.response?.data?.message || 'Failed to load courses')
    courses.value = []
  } finally {
    loadingCourses.value = false
  }
}

onMounted(async () => {
  await loadCourses()
})
</script>

<style scoped>
/* Additional styling if needed */
</style>
