<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Homework</h1>
      <button
        @click="showUploadModal = true"
        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors flex items-center gap-2"
      >
        <Upload class="w-5 h-5" />
        Upload Homework
      </button>
    </div>

    <!-- Homework List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="loading" class="p-8 text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-green-600"></div>
        <p class="mt-2 text-gray-500">Loading homework...</p>
      </div>

      <div v-else-if="homework.length === 0" class="p-12 text-center">
        <FileText class="w-16 h-16 text-gray-300 mx-auto mb-4" />
        <p class="text-gray-500 mb-4">No homework uploaded yet</p>
        <button
          @click="showUploadModal = true"
          class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg transition-colors"
        >
          Upload First Homework
        </button>
      </div>

      <table v-else class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Title</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Student</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Uploaded</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Submitted</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in homework" :key="item.id" class="border-b border-gray-200 hover:bg-gray-50">
            <td class="px-6 py-4">
              <div class="font-medium text-gray-800">{{ item.title }}</div>
              <div v-if="item.description" class="text-sm text-gray-500 truncate max-w-xs">{{ item.description }}</div>
            </td>
            <td class="px-6 py-4 text-gray-700">
              {{ item.student?.first_name }} {{ item.student?.last_name }}
            </td>
            <td class="px-6 py-4 text-gray-600 text-sm">
              {{ formatDate(item.uploaded_at) }}
            </td>
            <td class="px-6 py-4">
              <span
                :class="{
                  'bg-yellow-100 text-yellow-800': item.status === 'pending',
                  'bg-green-100 text-green-800': item.status === 'submitted',
                  'bg-blue-100 text-blue-800': item.status === 'reviewed'
                }"
                class="px-3 py-1 rounded-full text-sm font-medium"
              >
                {{ item.status.charAt(0).toUpperCase() + item.status.slice(1) }}
              </span>
            </td>
            <td class="px-6 py-4 text-gray-600 text-sm">
              {{ item.submitted_at ? formatDate(item.submitted_at) : '-' }}
            </td>
            <td class="px-6 py-4 align-top">
              <div class="space-y-2">
                <!-- Homework files (tutor's assignment PDFs) -->
                <div v-if="homeworkFiles(item).length" class="space-y-1">
                  <p class="text-[10px] font-bold tracking-wider uppercase text-gray-400">Homework</p>
                  <div class="flex flex-wrap gap-1.5">
                    <button
                      v-for="att in homeworkFiles(item)"
                      :key="`hw-${att.id || att.path}`"
                      @click="downloadAttachment(att)"
                      :disabled="downloadingAttachmentId === att.id"
                      class="inline-flex items-center gap-1 px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs transition-colors disabled:opacity-50"
                      :title="`Download ${att.name}`"
                    >
                      <Loader v-if="downloadingAttachmentId === att.id" class="w-3.5 h-3.5 animate-spin" />
                      <Download v-else class="w-3.5 h-3.5" />
                      <span class="truncate max-w-[120px]">{{ att.name }}</span>
                    </button>
                  </div>
                </div>

                <!-- Submission files (student's answer PDFs) -->
                <div v-if="submissionFiles(item).length" class="space-y-1">
                  <p class="text-[10px] font-bold tracking-wider uppercase text-gray-400">Submission</p>
                  <div class="flex flex-wrap gap-1.5">
                    <button
                      v-for="att in submissionFiles(item)"
                      :key="`sub-${att.id || att.path}`"
                      @click="downloadAttachment(att)"
                      :disabled="downloadingAttachmentId === att.id"
                      class="inline-flex items-center gap-1 px-2 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs transition-colors disabled:opacity-50"
                      :title="`Download ${att.name}`"
                    >
                      <Loader v-if="downloadingAttachmentId === att.id" class="w-3.5 h-3.5 animate-spin" />
                      <FileCheck v-else class="w-3.5 h-3.5" />
                      <span class="truncate max-w-[120px]">{{ att.name }}</span>
                    </button>
                  </div>
                </div>

                <button
                  @click="deleteHomework(item)"
                  class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm transition-colors inline-flex items-center gap-1"
                  title="Delete"
                >
                  <Trash2 class="w-4 h-4" />
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Upload Modal -->
    <div
      v-if="showUploadModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Upload Homework</h2>

        <form @submit.prevent="uploadHomework" class="space-y-4">
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Title *</label>
            <input
              v-model="form.title"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              placeholder="Homework title"
            />
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Description</label>
            <textarea
              v-model="form.description"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              placeholder="Optional description"
            />
          </div>

          <div class="relative student-dropdown-container">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Assign to Student *</label>
            <button
              type="button"
              @click.stop="showStudentDropdown = !showStudentDropdown"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-left flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-green-500"
            >
              <span :class="form.student_id ? 'text-gray-800' : 'text-gray-500'">
                {{ selectedStudentName }}
              </span>
              <svg
                class="w-4 h-4 text-gray-400 transition-transform"
                :class="{ 'rotate-180': showStudentDropdown }"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
            <div
              v-if="showStudentDropdown"
              class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg"
              @click.stop
            >
              <div class="p-2 border-b border-gray-100">
                <input
                  v-model="studentSearchQuery"
                  type="text"
                  placeholder="Search student..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                  @click.stop
                />
              </div>
              <ul class="max-h-48 overflow-y-auto py-1">
                <li
                  v-for="student in filteredStudents"
                  :key="student.id"
                  @click="selectStudent(student)"
                  class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm"
                  :class="form.student_id === student.id ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-700'"
                >
                  {{ student.first_name }} {{ student.last_name }} ({{ student.email }})
                </li>
                <li v-if="filteredStudents.length === 0" class="px-4 py-2 text-gray-400 text-sm">
                  No students found
                </li>
              </ul>
            </div>
            <!-- Hidden required input for form validation -->
            <input type="hidden" v-model="form.student_id" required />
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">PDF Files * (up to {{ MAX_FILES }})</label>
            <input
              type="file"
              accept=".pdf"
              multiple
              @change="handleFileSelect"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            />
            <p class="text-sm text-gray-500 mt-1">Max {{ MAX_FILES }} files · 10MB each</p>

            <ul v-if="selectedFiles.length" class="mt-3 space-y-1.5">
              <li
                v-for="(f, idx) in selectedFiles"
                :key="idx"
                class="flex items-center justify-between gap-2 px-3 py-1.5 rounded bg-gray-50 border border-gray-200 text-sm"
              >
                <span class="truncate text-gray-800 flex-1">{{ f.name }}</span>
                <span class="text-xs text-gray-500 whitespace-nowrap">{{ (f.size / 1024 / 1024).toFixed(2) }} MB</span>
                <button
                  type="button"
                  @click="removeSelectedFile(idx)"
                  class="text-red-500 hover:text-red-700"
                  aria-label="Remove file"
                >
                  <Trash2 class="w-4 h-4" />
                </button>
              </li>
            </ul>
          </div>

          <div class="flex gap-4 pt-4">
            <button
              type="submit"
              :disabled="uploading"
              class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors disabled:opacity-50"
            >
              {{ uploading ? 'Uploading...' : 'Upload' }}
            </button>
            <button
              type="button"
              @click="closeModal"
              class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition-colors"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Upload, FileText, Download, FileCheck, Trash2, Loader } from 'lucide-vue-next'
import axios from 'axios'
import { useToast } from '../../composables/useToast'

const toast = useToast()

const MAX_FILES = 5

const homework = ref([])
const students = ref([])
const loading = ref(false)
const uploading = ref(false)
const showUploadModal = ref(false)
const selectedFiles = ref([])

// Student dropdown state
const showStudentDropdown = ref(false)
const studentSearchQuery = ref('')

const form = ref({
  title: '',
  description: '',
  student_id: ''
})
const downloadingId = ref(null)
const downloadingSubmissionId = ref(null)
const downloadingAttachmentId = ref(null)

const homeworkFiles = (item) => {
  if (Array.isArray(item?.attachments) && item.attachments.length) {
    return item.attachments.filter(a => a.kind === 'homework')
  }
  // Legacy single-file fallback (no attachments row but file_path exists)
  if (item?.file_path) {
    return [{ id: `legacy-hw-${item.id}`, path: item.file_path, name: item.file_name || 'homework.pdf', kind: 'homework', _legacy: true, _homeworkId: item.id }]
  }
  return []
}
const submissionFiles = (item) => {
  if (Array.isArray(item?.attachments) && item.attachments.length) {
    return item.attachments.filter(a => a.kind === 'submission')
  }
  if (item?.submission_path) {
    return [{ id: `legacy-sub-${item.id}`, path: item.submission_path, name: item.submission_name || 'submission.pdf', kind: 'submission', _legacy: true, _homeworkId: item.id }]
  }
  return []
}
const downloadAttachment = async (att) => {
  downloadingAttachmentId.value = att.id
  try {
    let url
    if (att._legacy) {
      url = att.kind === 'submission'
        ? `/api/tutor/homework/${att._homeworkId}/download-submission`
        : `/api/tutor/homework/${att._homeworkId}/download`
    } else {
      url = `/api/tutor/homework/attachments/${att.id}/download`
    }
    const response = await axios.get(url, { responseType: 'blob' })
    const blobUrl = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = blobUrl
    link.setAttribute('download', att.name || 'file.pdf')
    document.body.appendChild(link)
    link.click()
    link.remove()
    window.URL.revokeObjectURL(blobUrl)
  } catch (error) {
    console.error('Failed to download:', error)
    toast.error('Failed to download file')
  } finally {
    downloadingAttachmentId.value = null
  }
}

// Computed: filtered and sorted students
const filteredStudents = computed(() => {
  let result = students.value
  if (studentSearchQuery.value.trim()) {
    const query = studentSearchQuery.value.toLowerCase()
    result = result.filter(student => {
      const name = `${student.first_name || ''} ${student.last_name || ''}`.toLowerCase()
      const email = (student.email || '').toLowerCase()
      return name.includes(query) || email.includes(query)
    })
  }
  // Sort alphabetically by name
  return [...result].sort((a, b) => {
    const nameA = `${a.first_name || ''} ${a.last_name || ''}`.trim().toLowerCase()
    const nameB = `${b.first_name || ''} ${b.last_name || ''}`.trim().toLowerCase()
    return nameA.localeCompare(nameB)
  })
})

// Computed: selected student name
const selectedStudentName = computed(() => {
  if (!form.value.student_id) return 'Select Student'
  const student = students.value.find(s => s.id === form.value.student_id)
  if (!student) return 'Select Student'
  return `${student.first_name || ''} ${student.last_name || ''} (${student.email})`.trim()
})

// Select a student
const selectStudent = (student) => {
  form.value.student_id = student.id
  showStudentDropdown.value = false
  studentSearchQuery.value = ''
}

// Handle click outside to close dropdown
const handleClickOutside = (event) => {
  const dropdown = document.querySelector('.student-dropdown-container')
  if (dropdown && !dropdown.contains(event.target)) {
    showStudentDropdown.value = false
  }
}

const loadHomework = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/tutor/homework')
    if (response.data.success) {
      homework.value = response.data.data
    }
  } catch (error) {
    console.error('Failed to load homework:', error)
    toast.error('Failed to load homework')
  } finally {
    loading.value = false
  }
}

const loadStudents = async () => {
  try {
    const response = await axios.get('/api/tutor/students')
    if (response.data.success) {
      // Handle paginated response
      students.value = response.data.data.data || response.data.data
    }
  } catch (error) {
    console.error('Failed to load students:', error)
  }
}

const handleFileSelect = (event) => {
  const incoming = Array.from(event.target.files || [])
  // Merge with existing selection but enforce MAX_FILES cap
  const merged = [...selectedFiles.value, ...incoming]
  if (merged.length > MAX_FILES) {
    toast.error(`You can upload a maximum of ${MAX_FILES} files`)
    selectedFiles.value = merged.slice(0, MAX_FILES)
  } else {
    selectedFiles.value = merged
  }
  // Reset the input so the user can re-add the same file if they removed it
  event.target.value = ''
}

const removeSelectedFile = (idx) => {
  selectedFiles.value.splice(idx, 1)
}

const uploadHomework = async () => {
  if (!selectedFiles.value.length) {
    toast.error('Please select at least one PDF file')
    return
  }
  if (selectedFiles.value.length > MAX_FILES) {
    toast.error(`You can upload a maximum of ${MAX_FILES} files`)
    return
  }

  uploading.value = true
  try {
    const formData = new FormData()
    formData.append('title', form.value.title)
    formData.append('description', form.value.description)
    formData.append('student_id', form.value.student_id)
    for (const f of selectedFiles.value) {
      formData.append('files[]', f)
    }

    const response = await axios.post('/api/tutor/homework', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })

    if (response.data.success) {
      toast.success('Homework uploaded successfully')
      homework.value.unshift(response.data.data)
      closeModal()
    }
  } catch (error) {
    console.error('Failed to upload homework:', error)
    toast.error(error.response?.data?.message || 'Failed to upload homework')
  } finally {
    uploading.value = false
  }
}

const downloadHomework = async (item) => {
  downloadingId.value = item.id
  try {
    const response = await axios.get(`/api/tutor/homework/${item.id}/download`, {
      responseType: 'blob'
    })
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', item.file_name || 'homework.pdf')
    document.body.appendChild(link)
    link.click()
    link.remove()
    window.URL.revokeObjectURL(url)
  } catch (error) {
    console.error('Failed to download:', error)
    toast.error('Failed to download file')
  } finally {
    downloadingId.value = null
  }
}

const downloadSubmission = async (item) => {
  downloadingSubmissionId.value = item.id
  try {
    const response = await axios.get(`/api/tutor/homework/${item.id}/download-submission`, {
      responseType: 'blob'
    })
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', item.submission_name || 'submission.pdf')
    document.body.appendChild(link)
    link.click()
    link.remove()
    window.URL.revokeObjectURL(url)
  } catch (error) {
    console.error('Failed to download:', error)
    toast.error('Failed to download submission')
  } finally {
    downloadingSubmissionId.value = null
  }
}

const deleteHomework = async (item) => {
  if (!confirm('Are you sure you want to delete this homework?')) return

  try {
    const response = await axios.delete(`/api/tutor/homework/${item.id}`)
    if (response.data.success) {
      homework.value = homework.value.filter(h => h.id !== item.id)
      toast.success('Homework deleted successfully')
    }
  } catch (error) {
    console.error('Failed to delete homework:', error)
    toast.error('Failed to delete homework')
  }
}

const closeModal = () => {
  showUploadModal.value = false
  form.value = { title: '', description: '', student_id: '' }
  selectedFiles.value = []
  showStudentDropdown.value = false
  studentSearchQuery.value = ''
}

const formatDate = (date) => {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  loadHomework()
  loadStudents()
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>
