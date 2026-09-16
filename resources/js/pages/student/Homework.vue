<template>
  <div class="p-8">
    <div class="mb-8">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-800">My Homework</h1>
      <p class="text-gray-600 mt-2">View assigned homework and submit your completed work</p>
    </div>

    <!-- Homework List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="loading" class="p-8 text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
        <p class="mt-2 text-gray-500">Loading homework...</p>
      </div>

      <div v-else-if="homework.length === 0" class="p-12 text-center">
        <FileText class="w-16 h-16 text-gray-300 mx-auto mb-4" />
        <p class="text-gray-500">No homework assigned yet</p>
      </div>

      <div v-else class="divide-y divide-gray-200">
        <div v-for="item in homework" :key="item.id" class="p-6 hover:bg-gray-50">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-gray-800">{{ item.title }}</h3>
              <p v-if="item.description" class="text-gray-600 mt-1">{{ item.description }}</p>
              <div class="flex flex-wrap gap-4 mt-3 text-sm">
                <div class="flex items-center gap-1 text-gray-500">
                  <User class="w-4 h-4" />
                  <span>From: {{ item.tutor?.first_name }} {{ item.tutor?.last_name }}</span>
                </div>
                <div class="flex items-center gap-1 text-gray-500">
                  <Calendar class="w-4 h-4" />
                  <span>Assigned: {{ formatDate(item.uploaded_at) }}</span>
                </div>
              </div>
            </div>

            <div class="flex flex-col gap-2">
              <!-- Status Badge -->
              <span
                :class="{
                  'bg-yellow-100 text-yellow-800': item.status === 'pending',
                  'bg-green-100 text-green-800': item.status === 'submitted',
                  'bg-blue-100 text-blue-800': item.status === 'reviewed'
                }"
                class="px-3 py-1 rounded-full text-sm font-medium text-center"
              >
                {{ item.status === 'pending' ? 'Pending Submission' : item.status === 'submitted' ? 'Submitted' : 'Reviewed' }}
              </span>

              <!-- Submission Date -->
              <div v-if="item.submitted_at" class="text-sm text-green-600 text-center">
                Submitted: {{ formatDate(item.submitted_at) }}
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="mt-4 space-y-3">
            <!-- Homework files (tutor's assignment) -->
            <div v-if="homeworkFiles(item).length">
              <p class="text-xs font-bold tracking-wider uppercase text-gray-500 mb-1.5">Homework Files</p>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="att in homeworkFiles(item)"
                  :key="`hw-${att.id || att.path}`"
                  @click="downloadAttachment(att)"
                  :disabled="downloadingAttachmentId === att.id"
                  class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm transition-colors flex items-center gap-2 disabled:opacity-50"
                >
                  <Loader v-if="downloadingAttachmentId === att.id" class="w-4 h-4 animate-spin" />
                  <Download v-else class="w-4 h-4" />
                  <span class="truncate max-w-[180px]">{{ att.name }}</span>
                </button>
              </div>
            </div>

            <!-- Submission files (student's answers) -->
            <div v-if="submissionFiles(item).length">
              <p class="text-xs font-bold tracking-wider uppercase text-gray-500 mb-1.5">My Submission</p>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="att in submissionFiles(item)"
                  :key="`sub-${att.id || att.path}`"
                  @click="downloadAttachment(att)"
                  :disabled="downloadingAttachmentId === att.id"
                  class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm transition-colors flex items-center gap-2 disabled:opacity-50"
                >
                  <Loader v-if="downloadingAttachmentId === att.id" class="w-4 h-4 animate-spin" />
                  <FileCheck v-else class="w-4 h-4" />
                  <span class="truncate max-w-[180px]">{{ att.name }}</span>
                </button>
              </div>
            </div>

            <button
              @click="openSubmitModal(item)"
              class="px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg text-sm transition-colors flex items-center gap-2"
            >
              <Upload class="w-4 h-4" />
              {{ submissionFiles(item).length ? 'Submit more / Resubmit' : 'Submit Homework' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Submit Modal -->
    <div
      v-if="showSubmitModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Submit Homework</h2>
        <p class="text-gray-600 mb-6">{{ selectedHomework?.title }}</p>

        <form @submit.prevent="submitHomework" class="space-y-4">
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Upload Completed Work — PDF * (up to {{ MAX_FILES }})</label>
            <input
              type="file"
              accept=".pdf"
              multiple
              @change="handleFileSelect"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]"
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
                  ×
                </button>
              </li>
            </ul>
          </div>

          <div v-if="submissionFiles(selectedHomework).length" class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-yellow-800 text-sm">
              <strong>Note:</strong> You've already submitted {{ submissionFiles(selectedHomework).length }} file(s). New uploads will be added to your existing submission.
            </p>
          </div>

          <div class="flex gap-4 pt-4">
            <button
              type="submit"
              :disabled="uploading"
              class="flex-1 px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50"
            >
              {{ uploading ? 'Submitting...' : 'Submit' }}
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
import { ref, onMounted } from 'vue'
import { FileText, Download, FileCheck, Upload, User, Calendar, Loader } from 'lucide-vue-next'
import axios from 'axios'
import { useToast } from '../../composables/useToast'

const toast = useToast()

const MAX_FILES = 5

const homework = ref([])
const loading = ref(false)
const uploading = ref(false)
const showSubmitModal = ref(false)
const selectedHomework = ref(null)
const selectedFiles = ref([])
const downloadingId = ref(null)
const downloadingSubmissionId = ref(null)
const downloadingAttachmentId = ref(null)

const homeworkFiles = (item) => {
  if (Array.isArray(item?.attachments) && item.attachments.length) {
    return item.attachments.filter(a => a.kind === 'homework')
  }
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
const removeSelectedFile = (idx) => {
  selectedFiles.value.splice(idx, 1)
}
const downloadAttachment = async (att) => {
  downloadingAttachmentId.value = att.id
  try {
    let url
    if (att._legacy) {
      url = att.kind === 'submission'
        ? `/api/student/homework/${att._homeworkId}/download-submission`
        : `/api/student/homework/${att._homeworkId}/download`
    } else {
      url = `/api/student/homework/attachments/${att.id}/download`
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

const loadHomework = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/student/homework')
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

const openSubmitModal = (item) => {
  selectedHomework.value = item
  showSubmitModal.value = true
}

const handleFileSelect = (event) => {
  const incoming = Array.from(event.target.files || [])
  const merged = [...selectedFiles.value, ...incoming]
  if (merged.length > MAX_FILES) {
    toast.error(`You can upload a maximum of ${MAX_FILES} files`)
    selectedFiles.value = merged.slice(0, MAX_FILES)
  } else {
    selectedFiles.value = merged
  }
  event.target.value = ''
}

const submitHomework = async () => {
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
    for (const f of selectedFiles.value) {
      formData.append('files[]', f)
    }

    const response = await axios.post(`/api/student/homework/${selectedHomework.value.id}/submit`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })

    if (response.data.success) {
      toast.success('Homework submitted successfully')
      // Update the item in the list
      const index = homework.value.findIndex(h => h.id === selectedHomework.value.id)
      if (index !== -1) {
        homework.value[index] = { ...homework.value[index], ...response.data.data }
      }
      closeModal()
    }
  } catch (error) {
    console.error('Failed to submit homework:', error)
    toast.error(error.response?.data?.message || 'Failed to submit homework')
  } finally {
    uploading.value = false
  }
}

const downloadHomework = async (item) => {
  downloadingId.value = item.id
  try {
    const response = await axios.get(`/api/student/homework/${item.id}/download`, {
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
    const response = await axios.get(`/api/student/homework/${item.id}/download-submission`, {
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

const closeModal = () => {
  showSubmitModal.value = false
  selectedHomework.value = null
  selectedFiles.value = []
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
})
</script>
