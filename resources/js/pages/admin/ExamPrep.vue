<template>
  <div class="">
    <div class="flex justify-end items-center mb-6">
      <button
        @click="showCreateForm = true"
        class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
      >
        + Add New Exam Prep
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
        placeholder="Search exam preps..."
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

    <!-- Exam Preps Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
      <div v-if="loadingExamPreps" class="p-8 text-center">
        <svg class="animate-spin h-8 w-8 text-[#0055A4] mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-500 mt-4">Loading exam preps...</p>
      </div>
      <table v-else class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-3 py-3 text-left text-sm font-semibold text-gray-700 w-10"></th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Exam Prep Name</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Category</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(examPrep, index) in filteredExamPreps"
            :key="examPrep.id"
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
            <td class="px-3 py-4 cursor-move">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
              </svg>
            </td>
            <td class="px-6 py-4">
              <div>
                <p class="font-medium text-gray-800">{{ examPrep.exam_prep_title }}</p>
                <p class="text-gray-600 text-sm">{{ examPrep.exam_prep_description?.substring(0, 50) }}...</p>
              </div>
            </td>
            <td class="px-6 py-4 text-gray-700">{{ formatCategory(examPrep.exam_prep_category) }}</td>
            <td class="px-6 py-4">
              <span
                :class="examPrep.exam_prep_is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                class="px-3 py-1 rounded-full text-sm font-medium"
              >
                {{ examPrep.exam_prep_is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <LoadingButton
                  v-if="examPrep.exam_prep_category !== 'books' && examPrep.exam_prep_category !== 'lingopie'"
                  @click="previewExamPrep(examPrep)"
                  variant="success"
                  size="sm"
                  text="Preview"
                />
                <LoadingButton
                  @click="editExamPrep(examPrep)"
                  :loading="isIdLoading(`edit-${examPrep.id}`)"
                  :disabled="isLoading || isIdLoading(`delete-${examPrep.id}`) || isIdLoading(`toggle-${examPrep.id}`)"
                  variant="info"
                  size="sm"
                  text="Edit"
                  loading-text="Loading..."
                />
                <LoadingButton
                  @click="toggleStatus(examPrep)"
                  :loading="isIdLoading(`toggle-${examPrep.id}`)"
                  :disabled="isLoading || isIdLoading(`delete-${examPrep.id}`) || isIdLoading(`edit-${examPrep.id}`)"
                  variant="warning"
                  size="sm"
                  :text="examPrep.exam_prep_is_active ? 'Deactivate' : 'Activate'"
                  loading-text="Updating..."
                />
                <LoadingButton
                  @click="deleteExamPrep(examPrep.id)"
                  :loading="isIdLoading(`delete-${examPrep.id}`)"
                  :disabled="isLoading || isIdLoading(`toggle-${examPrep.id}`) || isIdLoading(`edit-${examPrep.id}`)"
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

      <div v-if="!loadingExamPreps && filteredExamPreps.length === 0" class="p-8 text-center">
        <p class="text-gray-500">No exam preps found</p>
      </div>
    </div>

    <!-- Create/Edit Form Modal -->
    <div
      v-if="showCreateForm"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto py-8"
    >
      <div class="bg-white rounded-lg max-w-3xl w-full mx-4 my-auto max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 z-20 flex justify-end px-4 pt-4 pb-2 bg-white/90 backdrop-blur-sm">
          <button
            type="button"
            @click="closeForm"
            aria-label="Close"
            class="w-9 h-9 flex items-center justify-center rounded-full text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="px-8 pb-8 -mt-2">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
          {{ editingExamPrep ? 'Edit Exam Prep' : 'Create New Exam Prep' }}
        </h2>

        <form @submit.prevent="saveExamPrep" class="space-y-6">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Exam Prep Title <span class="text-red-500">*</span></label>
              <input
                v-model="formData.exam_prep_title"
                type="text"
                required
                placeholder="e.g., TCF Preparation"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Category <span class="text-red-500">*</span></label>
              <select
                v-model="formData.exam_prep_category"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              >
                <option value="">Select Category</option>
                <option value="written">Written</option>
                <option value="orale">Orale</option>
                <option value="expression">Oral Expression</option>
                <option value="written_expression">Written Expression</option>
              </select>
            </div>

            <div v-if="isExpressionCategory">
              <label class="block text-gray-700 text-sm font-semibold mb-2">
                Layout <span class="text-red-500">*</span>
              </label>
              <select
                v-model="formData.exam_prep_oral_layout"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              >
                <option value="">Select Layout</option>
                <option value="parties">Layout 1</option>
                <option value="essais">Layout 2</option>
              </select>
              <p class="text-xs text-gray-500 mt-1.5">
                <span v-if="formData.exam_prep_oral_layout === 'parties'">
                  Use a JSON with a <code class="px-1 py-0.5 bg-gray-100 rounded">parties</code> array of role-play scenarios.
                </span>
                <span v-else-if="formData.exam_prep_oral_layout === 'essais'">
                  Use a JSON with an <code class="px-1 py-0.5 bg-gray-100 rounded">essais</code> array of <code class="px-1 py-0.5 bg-gray-100 rounded">{ sujet, correction }</code> entries.
                </span>
                <span v-else class="text-gray-400">
                  Pick the layout that matches your JSON shape.
                </span>
              </p>
            </div>
          </div>

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
              When users click on this exam prep, they will be redirected to this URL
            </p>
          </div>

          <!-- Description popup title — only for Oral Expression category -->
          <div v-if="isExpressionCategory">
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Description Popup Title
            </label>
            <input
              v-model="formData.exam_prep_description_title"
              type="text"
              maxlength="200"
              placeholder="About this exam prep"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
            <p class="text-xs text-gray-500 mt-1.5">
              Shown as the header of the description popup (the <code class="px-1 py-0.5 bg-gray-100 rounded">?</code> icon on the Partie selector). Leave blank to use the default "About this exam prep".
            </p>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Description
              <span v-if="!isExternalLinkCategory" class="text-red-500">*</span>
            </label>
            <textarea
              v-model="formData.exam_prep_description"
              rows="3"
              :required="!isExternalLinkCategory"
              placeholder="Provide a detailed description of the exam prep"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
          </div>

          <div class="border-t pt-4 space-y-4">
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Exam Prep Image</label>
              <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-lg overflow-hidden border border-gray-300 bg-gray-100 flex items-center justify-center">
                  <img
                    v-if="formData.exam_prep_image_preview"
                    :src="formData.exam_prep_image_preview"
                    alt="Exam prep preview"
                    class="w-full h-full object-cover"
                  />
                  <div v-else class="text-gray-400 text-xs text-center px-2">
                    <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Image preview</span>
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

            <div v-if="!isExternalLinkCategory">
              <label class="block text-gray-700 text-sm font-semibold mb-2">Exam Prep Banner Image</label>
              <div class="flex items-center gap-4">
                <div class="w-32 h-20 rounded-lg overflow-hidden border border-gray-300 bg-gray-100 flex items-center justify-center">
                  <img
                    v-if="formData.exam_prep_banner_preview"
                    :src="formData.exam_prep_banner_preview"
                    alt="Banner preview"
                    class="w-full h-full object-cover"
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

          <div v-if="!isExternalLinkCategory" v-for="(section, index) in formData.exam_prep_sections" :key="`section-${index}-${section.content?.substring(0, 20) || ''}`" class="border-t pt-4">
            <div class="flex justify-between items-center mb-3">
              <label class="block text-gray-700 text-sm font-semibold">Exam Prep Content (JSON) <span v-if="index === 0" class="text-red-500">*</span></label>
              <div class="space-x-2">
                <button
                  type="button"
                  @click="addNewSectionBlock"
                  class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-sm font-medium transition-colors"
                >
                  + Add New Section
                </button>
                <button
                  v-if="formData.exam_prep_sections.length > 1"
                  type="button"
                  @click="removeSectionBlock(index)"
                  class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm font-medium transition-colors"
                >
                  Remove
                </button>
              </div>
            </div>

            <textarea
              v-model="formData.exam_prep_sections[index].content"
              :required="index === 0"
              rows="8"
              placeholder='[{"section": "Introduction", "content": "..."}, {"section": "Lesson 1", "content": "..."}]'
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] font-mono text-sm"
            />
            <p class="text-gray-500 text-xs mt-2">Enter JSON format with your section data</p>
          </div>

          <div class="border-t pt-4">
            <label class="flex items-center cursor-pointer">
              <input
                v-model="formData.exam_prep_is_active"
                type="checkbox"
                class="w-4 h-4 text-[#0055A4]"
              />
              <span class="ml-2 text-gray-700 text-sm font-medium">Active Exam Prep</span>
            </label>
          </div>

          <div class="flex gap-4 pt-6 border-t">
            <LoadingButton
              type="submit"
              :loading="isLoading"
              variant="primary"
              size="md"
              :text="editingExamPrep ? 'Update Exam Prep' : 'Create Exam Prep'"
              :loading-text="editingExamPrep ? 'Updating...' : 'Creating...'"
              class="flex-1"
            />
            <LoadingButton
              type="button"
              @click="closeForm"
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

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import LoadingButton from '../../components/LoadingButton.vue'
import { useLoading } from '../../composables/useLoading'
import { useToast } from '../../composables/useToast'
import { reportDebug } from '../../utils/errorReporter'

// DEBUG: confirms the component chunk actually loaded & <script setup> ran.
// If this line never appears in the log, the blank page is BEFORE the
// component (router guard redirect, chunk load failure, or layout crash).
reportDebug('ExamPrep.vue <script setup> executing', { step: 'setup' })

const router = useRouter()
const toast = useToast()
const examPreps = ref([])
const searchQuery = ref('')
const filterStatus = ref('')
const showCreateForm = ref(false)
const editingExamPrep = ref(null)
const loadingExamPreps = ref(false)

const draggingIndex = ref(null)
const dragOverIndex = ref(null)
const savingOrder = ref(false)

const { isLoading, setLoading, isIdLoading, setLoadingId, clearLoadingId } = useLoading()

const formData = ref({
  exam_prep_title: '',
  exam_prep_subtitle: '',
  exam_prep_description: '',
  exam_prep_description_title: '',
  exam_prep_category: '',
  exam_prep_oral_layout: '',
  exam_prep_is_active: true,
  exam_prep_image: null,
  exam_prep_image_preview: null,
  exam_prep_banner: null,
  exam_prep_banner_preview: null,
  exam_prep_sections: [{ content: '' }],
  custom_url: '',
  custom_url_target: '_blank'
})

const isExternalLinkCategory = computed(() => {
  return formData.value.exam_prep_category === 'books' || formData.value.exam_prep_category === 'lingopie'
})

const isExpressionCategory = computed(() => {
  return (formData.value.exam_prep_category || '').toLowerCase() === 'expression'
})

const categoryLabels = {
  written: 'Written',
  grammar: 'Grammar',
  orale: 'Orale',
  expression: 'Oral Expression',
  written_expression: 'Written Expression',
  vocabulary: 'Vocabulary',
  books: 'Books',
  lingopie: 'Lingopie',
}

const formatCategory = (value) => {
  if (!value) return ''
  return categoryLabels[value.toLowerCase()] || value
}

const filteredExamPreps = computed(() => {
  return examPreps.value.filter(examPrep => {
    const matchesSearch = (examPrep.exam_prep_title || '').toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesStatus = !filterStatus.value || (filterStatus.value === 'active' ? examPrep.exam_prep_is_active : !examPrep.exam_prep_is_active)
    return matchesSearch && matchesStatus
  })
})

const previewExamPrep = (examPrep) => {
  const slug = (examPrep.exam_prep_title || '').toLowerCase().replace(/\s+/g, '-')
  router.push(`/exam-preps/preview/${examPrep.id}/${slug}`)
}

const editExamPrep = async (examPrep) => {
  setLoadingId(`edit-${examPrep.id}`)
  editingExamPrep.value = examPrep

  try {
    const apiURL = import.meta.env.VITE_API_URL
    const fullUrl = apiURL + `/api/admin/exam-preps/${examPrep.id}`

    const response = await fetch(fullUrl, {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    })

    if (response.ok) {
      const data = await response.json()
      if (data.success && data.data) {
        examPrep = data.data
      } else {
        examPrep = examPreps.value.find(c => c.id === examPrep.id) || examPrep
      }
    } else {
      examPrep = examPreps.value.find(c => c.id === examPrep.id) || examPrep
    }
  } catch (error) {
    examPrep = examPreps.value.find(c => c.id === examPrep.id) || examPrep
  } finally {
    clearLoadingId(`edit-${examPrep.id}`)
  }

  let sections = [{ content: '' }]

  if (examPrep.exam_prep_json_content) {
    try {
      let parsed = examPrep.exam_prep_json_content
      if (typeof parsed === 'string') {
        if (parsed.trim() === '') {
          sections = [{ content: '' }]
        } else {
          parsed = JSON.parse(parsed)
        }
      }

      if (parsed) {
        if (Array.isArray(parsed)) {
          if (parsed.length === 0) {
            sections = [{ content: '' }]
          } else {
            sections = parsed.map((section) => {
              const content = typeof section === 'string' ? section : JSON.stringify(section, null, 2)
              return { content }
            })
          }
        } else if (parsed.activities && Array.isArray(parsed.activities)) {
          sections = parsed.activities.map(activity => {
            const sectionJson = {
              category: parsed.category || 'Reading',
              difficulty: parsed.difficulty || 'Intensive',
              activities: [activity]
            }
            return { content: JSON.stringify(sectionJson, null, 2) }
          })
        } else if (parsed.title || parsed.text) {
          sections = [{ content: JSON.stringify(parsed, null, 2) }]
        } else {
          sections = [{ content: JSON.stringify(parsed, null, 2) }]
        }
      }
    } catch (e) {
      sections = [{ content: String(examPrep.exam_prep_json_content) }]
    }
  }

  formData.value = {
    ...examPrep,
    exam_prep_oral_layout: examPrep.exam_prep_oral_layout || '',
    exam_prep_description_title: examPrep.exam_prep_description_title || '',
    exam_prep_sections: sections,
    exam_prep_image_preview: examPrep.exam_prep_image ? (examPrep.exam_prep_image.startsWith('http') ? examPrep.exam_prep_image : `/storage/${examPrep.exam_prep_image.replace(/\/+$/, '')}`) : null,
    exam_prep_banner_preview: examPrep.exam_prep_banner ? (examPrep.exam_prep_banner.startsWith('http') ? examPrep.exam_prep_banner : `/storage/${examPrep.exam_prep_banner.replace(/\/+$/, '')}`) : null,
    exam_prep_image: null,
    exam_prep_banner: null
  }

  formData.value = { ...formData.value }

  showCreateForm.value = true
}

const saveExamPrep = async () => {
  try {
    if (!formData.value.exam_prep_title) {
      toast.error('Exam prep title is required')
      return
    }
    if (!formData.value.exam_prep_category) {
      toast.error('Category is required')
      return
    }

    if (isExpressionCategory.value && !formData.value.exam_prep_oral_layout) {
      toast.error('Layout is required for Expression categories')
      return
    }

    const isExternal = formData.value.exam_prep_category === 'books' || formData.value.exam_prep_category === 'lingopie'

    if (isExternal) {
      if (!formData.value.custom_url) {
        toast.error('Custom URL is required for Books/Lingopie')
        return
      }
    } else {
      if (!formData.value.exam_prep_description) {
        toast.error('Description is required')
        return
      }
      if (!formData.value.exam_prep_sections || formData.value.exam_prep_sections.length === 0 || !formData.value.exam_prep_sections[0].content) {
        toast.error('Exam prep content (JSON) is required')
        return
      }
    }

    setLoading(true)

    const formDataObj = new FormData()

    formDataObj.append('exam_prep_title', formData.value.exam_prep_title || '')
    formDataObj.append('exam_prep_subtitle', formData.value.exam_prep_subtitle || '')
    formDataObj.append('exam_prep_description', formData.value.exam_prep_description || '')
    if (isExpressionCategory.value && formData.value.exam_prep_description_title) {
      formDataObj.append('exam_prep_description_title', formData.value.exam_prep_description_title)
    }
    formDataObj.append('exam_prep_category', formData.value.exam_prep_category || '')
    if (isExpressionCategory.value && formData.value.exam_prep_oral_layout) {
      formDataObj.append('exam_prep_oral_layout', formData.value.exam_prep_oral_layout)
    }
    formDataObj.append('custom_url', formData.value.custom_url || '')
    formDataObj.append('custom_url_target', formData.value.custom_url_target || '_blank')

    let sectionsArray = []

    if (!isExternal && formData.value.exam_prep_sections && formData.value.exam_prep_sections.length > 0) {
      for (let i = 0; i < formData.value.exam_prep_sections.length; i++) {
        const section = formData.value.exam_prep_sections[i]
        if (section.content && section.content.trim()) {
          try {
            const parsed = JSON.parse(section.content)
            sectionsArray.push(parsed)
          } catch (e) {
            toast.error(`Error in JSON section ${i + 1}: ${e.message}`)
            return
          }
        }
      }
    }

    const sectionsJSON = JSON.stringify(sectionsArray, null, 2)
    formDataObj.append('exam_prep_json_content', sectionsJSON)
    formDataObj.append('exam_prep_is_active', formData.value.exam_prep_is_active ? 1 : 0)

    if (formData.value.exam_prep_image) {
      formDataObj.append('exam_prep_image', formData.value.exam_prep_image)
    }
    if (formData.value.exam_prep_banner) {
      formDataObj.append('exam_prep_banner', formData.value.exam_prep_banner)
    }

    const url = editingExamPrep.value
      ? `/api/admin/exam-preps/${editingExamPrep.value.id}`
      : '/api/admin/exam-preps'

    try {
      if (editingExamPrep.value) {
        formDataObj.append('_method', 'PUT')
      }

      const apiURL = import.meta.env.VITE_API_URL
      const fullUrl = apiURL + url

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
        method: 'POST',
        headers,
        credentials: 'include',
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

      const responseData = await response.json()

      if (editingExamPrep.value) {
        const index = examPreps.value.findIndex(c => c.id === editingExamPrep.value.id)
        if (index !== -1) {
          examPreps.value.splice(index, 1, responseData.data)
        }
        await loadExamPreps()
      } else {
        examPreps.value.push(responseData.data)
      }

      toast.success(editingExamPrep.value ? 'Exam prep updated successfully' : 'Exam prep created successfully')
      resetForm()
      showCreateForm.value = false
    } catch (apiError) {
      toast.error(apiError.message || 'Failed to save exam prep')
    }
  } catch (error) {
    toast.error('Error saving exam prep: ' + error.message)
  } finally {
    setLoading(false)
  }
}

const toggleStatus = async (examPrep) => {
  setLoadingId(`toggle-${examPrep.id}`)
  try {
    const fd = new FormData()
    fd.append('_method', 'PUT')
    fd.append('exam_prep_is_active', !examPrep.exam_prep_is_active ? 1 : 0)

    const apiURL = import.meta.env.VITE_API_URL
    const fullUrl = apiURL + `/api/admin/exam-preps/${examPrep.id}`

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
      method: 'POST',
      headers,
      credentials: 'include',
      body: fd
    })

    if (response.ok) {
      const responseData = await response.json()
      if (responseData.success) {
        const index = examPreps.value.findIndex(c => c.id === examPrep.id)
        if (index !== -1) {
          examPreps.value[index].exam_prep_is_active = !examPrep.exam_prep_is_active
        }
        await loadExamPreps()
        toast.success(`Exam prep ${!examPrep.exam_prep_is_active ? 'activated' : 'deactivated'} successfully`)
      } else {
        toast.error(responseData.message || 'Failed to update exam prep status')
      }
    } else {
      const error = await response.json().catch(() => ({ message: 'Unknown error' }))
      toast.error(error.message || 'Failed to update exam prep status')
    }
  } catch (error) {
    toast.error('Error updating exam prep status: ' + error.message)
  } finally {
    clearLoadingId(`toggle-${examPrep.id}`)
  }
}

const deleteExamPrep = async (examPrepId) => {
  if (confirm('Are you sure you want to delete this exam prep? This action cannot be undone.')) {
    setLoadingId(`delete-${examPrepId}`)
    try {
      const apiURL = import.meta.env.VITE_API_URL
      const fullUrl = apiURL + `/api/admin/exam-preps/${examPrepId}`

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
        examPreps.value = examPreps.value.filter(c => c.id !== examPrepId)
        toast.success('Exam prep deleted successfully')
      } else {
        const error = await response.json()
        toast.error(error.message || 'Failed to delete exam prep')
      }
    } catch (error) {
      toast.error('Error deleting exam prep: ' + error.message)
    } finally {
      clearLoadingId(`delete-${examPrepId}`)
    }
  }
}

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

  if (searchQuery.value || filterStatus.value) {
    toast.error('Please clear filters before reordering')
    dragOverIndex.value = null
    draggingIndex.value = null
    return
  }

  const items = [...examPreps.value]
  const [draggedItem] = items.splice(dragIndex, 1)
  items.splice(dropIndex, 0, draggedItem)

  items.forEach((item, index) => {
    item.display_order = index + 1
  })

  examPreps.value = items
  dragOverIndex.value = null
  draggingIndex.value = null

  await saveOrder()
}

const saveOrder = async () => {
  savingOrder.value = true
  try {
    const orderData = examPreps.value.map((item, index) => ({
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

    await fetch(`${apiURL}/api/admin/exam-preps/reorder`, {
      method: 'POST',
      headers,
      credentials: 'include',
      body: JSON.stringify({ order: orderData })
    })

    toast.success('Order saved successfully')
  } catch (error) {
    toast.error('Failed to save order')
    await loadExamPreps()
  } finally {
    savingOrder.value = false
  }
}

const closeForm = () => {
  showCreateForm.value = false
  resetForm()
}

const resetForm = () => {
  formData.value = {
    exam_prep_title: '',
    exam_prep_subtitle: '',
    exam_prep_description: '',
    exam_prep_description_title: '',
    exam_prep_category: '',
    exam_prep_oral_layout: '',
    exam_prep_is_active: true,
    exam_prep_image: null,
    exam_prep_image_preview: null,
    exam_prep_banner: null,
    exam_prep_banner_preview: null,
    exam_prep_sections: [{ content: '' }],
    custom_url: '',
    custom_url_target: '_blank'
  }
  editingExamPrep.value = null
}

const handleImageUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    formData.value.exam_prep_image = file
    const reader = new FileReader()
    reader.onload = (e) => {
      formData.value.exam_prep_image_preview = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const handleBannerUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    formData.value.exam_prep_banner = file
    const reader = new FileReader()
    reader.onload = (e) => {
      formData.value.exam_prep_banner_preview = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const addNewSectionBlock = () => {
  formData.value.exam_prep_sections.push({ content: '' })
}

const removeSectionBlock = (index) => {
  if (formData.value.exam_prep_sections.length > 1) {
    formData.value.exam_prep_sections.splice(index, 1)
  }
}

const loadExamPreps = async () => {
  loadingExamPreps.value = true
  reportDebug('loadExamPreps: requesting /api/admin/exam-preps', {
    step: 'api-request',
    apiURL: import.meta.env.VITE_API_URL || '(unset)',
  })
  try {
    const response = await axios.get('/api/admin/exam-preps')

    reportDebug('loadExamPreps: response received', {
      step: 'api-response',
      status: response.status,
      success: response.data?.success,
      shape: Array.isArray(response.data?.data) ? 'array'
        : (response.data?.data?.data ? 'paginated' : typeof response.data?.data),
    })

    if (response.data.success) {
      if (response.data.data?.data) {
        examPreps.value = response.data.data.data
      } else if (Array.isArray(response.data.data)) {
        examPreps.value = response.data.data
      } else {
        examPreps.value = []
      }
    } else {
      examPreps.value = []
      toast.error(response.data.message || 'Failed to load exam preps')
    }
  } catch (error) {
    // DEBUG: capture WHY the call failed (401/403/500/network). Note the global
    // axios interceptor skips 401/422, so this is the only place they get logged.
    reportDebug('loadExamPreps: request FAILED', {
      step: 'api-error',
      status: error.response?.status ?? 'no-response(network/CORS)',
      message: error.response?.data?.message || error.message,
      request_url: error.config?.url,
    })
    toast.error(error.response?.data?.message || 'Failed to load exam preps')
    examPreps.value = []
  } finally {
    loadingExamPreps.value = false
  }
}

onMounted(async () => {
  reportDebug('ExamPrep.vue onMounted fired', { step: 'mounted' })
  await loadExamPreps()
})
</script>

<style scoped>
</style>
