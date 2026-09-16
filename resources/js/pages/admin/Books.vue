<template>
  <div class="">
    <div class="flex justify-end items-center mb-6">
      <button
        @click="showCreateForm = true"
        class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
      >
        + Add New Book
      </button>
    </div>

    <!-- Books Page SEO Settings -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <h2 class="text-xl font-bold text-gray-800 mb-4">Books Page SEO Settings</h2>
      <p class="text-sm text-gray-600 mb-4">Configure SEO settings for the public /books/ page</p>
      
      <div class="space-y-4">
        <div>
          <div class="flex justify-between mb-2">
            <label class="block text-gray-700 text-sm font-semibold">SEO Title</label>
            <span class="text-xs text-gray-500" :class="{ 'text-red-500': (pageSeoSettings.seo_title?.length || 0) > 60 }">
              {{ pageSeoSettings.seo_title?.length || 0 }} / 60
            </span>
          </div>
          <input
            v-model="pageSeoSettings.seo_title"
            type="text"
            maxlength="60"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            placeholder="Page title for search engines"
          />
        </div>

        <div>
          <div class="flex justify-between mb-2">
            <label class="block text-gray-700 text-sm font-semibold">Meta Description</label>
            <span class="text-xs text-gray-500" :class="{ 'text-red-500': (pageSeoSettings.meta_description?.length || 0) > 160 }">
              {{ pageSeoSettings.meta_description?.length || 0 }} / 160
            </span>
          </div>
          <textarea
            v-model="pageSeoSettings.meta_description"
            rows="3"
            maxlength="160"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            placeholder="Brief description for search results"
          ></textarea>
        </div>

        <!-- Robots Meta -->
        <div class="border-t border-gray-200 pt-4 mt-4">
          <h4 class="text-md font-semibold text-gray-800 mb-4">Robots Settings</h4>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Robots Meta Tag</label>
            <select
              v-model="pageSeoSettings.robots"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            >
              <option value="index, follow">Index, Follow</option>
              <option value="noindex, follow">Noindex, Follow</option>
              <option value="index, nofollow">Index, Nofollow</option>
              <option value="noindex, nofollow">Noindex, Nofollow</option>
            </select>
          </div>
          <p class="text-xs text-gray-500 mt-2">
            Controls how search engines index this page and follow links.
          </p>
        </div>

        <!-- Schema Type -->
        <div class="border-t border-gray-200 pt-4 mt-4">
          <h4 class="text-md font-semibold text-gray-800 mb-4">Schema Type (Rich Snippets)</h4>
          <select
            v-model="pageSeoSettings.schema_type"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
          >
            <option v-for="(label, value) in schemaTypes" :key="value" :value="value">
              {{ label }}
            </option>
          </select>
          <p class="text-xs text-gray-500 mt-1">
            Default rich snippet type for the Books page. <code class="bg-gray-100 px-1 rounded">CollectionPage</code> is recommended for book listings.
          </p>
        </div>

        <div class="flex justify-end pt-4 border-t">
          <button
            @click="savePageSeoSettings"
            :disabled="savingPageSEO"
            class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg transition-colors disabled:opacity-50"
          >
            {{ savingPageSEO ? 'Saving...' : 'Save SEO Settings' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search books..."
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
        @input="loadBooks"
      />
    </div>

    <!-- Books Grid -->
    <div v-if="loading" class="p-8 text-center">
      <svg class="animate-spin h-8 w-8 text-[#0055A4] mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <p class="text-gray-500 mt-4">Loading books...</p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <div
        v-for="book in filteredBooks"
        :key="book.id"
        class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden"
      >
        <!-- Cover Image -->
        <div class="aspect-[3/4] bg-gray-200 overflow-hidden">
          <img
            v-if="book.cover_image"
            :src="getImageUrl(book.cover_image)"
            :alt="book.title"
            class="w-full h-full object-cover"
          />
          <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
        </div>

        <!-- Book Info -->
        <div class="p-4 flex flex-col">
          <h3 class="font-semibold text-gray-800 mb-3 line-clamp-2 flex-1">{{ book.title }}</h3>
          <div class="flex items-center justify-start gap-2 pt-2 border-t border-gray-100">
            <button
              @click="previewBook(book)"
              class="p-2 text-green-600 hover:bg-green-50 rounded transition-colors"
              title="Preview"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
            <button
              @click="editBook(book)"
              class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-colors"
              title="Edit"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </button>
            <button
              @click="deleteBook(book.id)"
              class="p-2 text-red-600 hover:bg-red-50 rounded transition-colors"
              title="Delete"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!loading && filteredBooks.length === 0" class="bg-white rounded-lg shadow p-12 text-center">
      <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
      </svg>
      <p class="text-gray-500 text-lg mb-2">No books found</p>
      <p class="text-gray-400 text-sm">Click "Add New Book" to get started</p>
    </div>

    <!-- Pagination -->
    <div v-if="pagination && pagination.last_page > 1" class="mt-6 flex justify-center">
      <div class="flex gap-2">
        <button
          @click="loadBooks(pagination.current_page - 1)"
          :disabled="pagination.current_page === 1"
          class="px-4 py-2 border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
        >
          Previous
        </button>
        <span class="px-4 py-2 text-gray-700">
          Page {{ pagination.current_page }} of {{ pagination.last_page }}
        </span>
        <button
          @click="loadBooks(pagination.current_page + 1)"
          :disabled="pagination.current_page === pagination.last_page"
          class="px-4 py-2 border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
        >
          Next
        </button>
      </div>
    </div>

    <!-- Preview Modal -->
    <div
      v-if="previewingBook"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closePreview"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-800">Book Preview</h2>
          <button
            @click="closePreview"
            class="text-gray-400 hover:text-gray-600 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="p-6 space-y-6">
          <!-- Cover Image -->
          <div class="flex justify-center">
            <div class="w-48 h-64 bg-gray-200 rounded-lg overflow-hidden">
              <img
                v-if="previewingBook.cover_image"
                :src="getImageUrl(previewingBook.cover_image)"
                :alt="previewingBook.title"
                class="w-full h-full object-cover"
              />
              <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Book Details -->
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
              <p class="text-lg font-semibold text-gray-800">{{ previewingBook.title }}</p>
            </div>

            <div v-if="previewingBook.link">
              <label class="block text-sm font-medium text-gray-700 mb-2">Link</label>
              <div class="flex gap-2">
                <a
                  :href="getLinkUrl(previewingBook.link)"
                  target="_blank"
                  class="flex-1 px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors text-center"
                >
                  {{ isFileLink(previewingBook.link) ? 'Download File' : 'Open Link' }}
                </a>
                <button
                  @click="copyLink(previewingBook.link)"
                  class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                  title="Copy Link"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                </button>
              </div>
              <p class="text-xs text-gray-500 mt-1 break-all">{{ getLinkUrl(previewingBook.link) }}</p>
            </div>
            <div v-else>
              <label class="block text-sm font-medium text-gray-700 mb-1">Link</label>
              <p class="text-gray-500 italic">No link provided</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
              <p class="text-gray-600">{{ formatDate(previewingBook.created_at) }}</p>
            </div>
          </div>
        </div>

        <div class="p-6 border-t border-gray-200 flex justify-end">
          <button
            @click="closePreview"
            class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors"
          >
            Close
          </button>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div
      v-if="showCreateForm || editingBook"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-2xl font-bold text-gray-800">
            {{ editingBook ? 'Edit Book' : 'Add New Book' }}
          </h2>
        </div>

        <form @submit.prevent="saveBook" class="p-6 space-y-4">
          <!-- Title -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
            <input
              v-model="form.title"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              placeholder="Enter book title"
            />
          </div>

          <!-- Link - URL or File Upload -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Link</label>
            
            <!-- Toggle between URL and File Upload -->
            <div class="flex gap-4 mb-3">
              <label class="flex items-center space-x-2 cursor-pointer">
                <input
                  v-model="linkType"
                  type="radio"
                  value="url"
                  class="text-[#0055A4] focus:ring-[#0055A4]/20"
                />
                <span class="text-sm text-gray-700">URL</span>
              </label>
              <label class="flex items-center space-x-2 cursor-pointer">
                <input
                  v-model="linkType"
                  type="radio"
                  value="file"
                  class="text-[#0055A4] focus:ring-[#0055A4]/20"
                />
                <span class="text-sm text-gray-700">Upload File</span>
              </label>
            </div>

            <!-- URL Input -->
            <div v-if="linkType === 'url'">
              <input
                v-model="form.link"
                type="url"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                placeholder="https://example.com/book"
              />
            </div>

            <!-- File Upload -->
            <div v-else>
              <div v-if="form.link_file_preview" class="mb-3">
                <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                  <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <span class="flex-1 text-sm text-gray-700">{{ form.link_file_preview.name }}</span>
                  <button
                    type="button"
                    @click="clearLinkFile"
                    class="text-red-600 hover:text-red-700"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
              <input
                type="file"
                ref="linkFileInput"
                @change="handleLinkFileChange"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                accept=".pdf,.doc,.docx,.txt,.epub,.mobi"
              />
              <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX, TXT, EPUB, MOBI (Max: 60MB)</p>
            </div>
          </div>

          <!-- Cover Image -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
            <div v-if="form.cover_image_preview" class="mb-4">
              <img :src="form.cover_image_preview" alt="Preview" class="w-32 h-40 object-cover rounded border border-gray-300" />
            </div>
            <input
              type="file"
              ref="coverImageInput"
              accept="image/*"
              @change="handleImageChange"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
            <p class="text-xs text-gray-500 mt-1">Max file size: 5MB</p>
          </div>

          <!-- Actions -->
          <div class="flex justify-end gap-4 pt-4 border-t">
            <button
              type="button"
              @click="closeModal"
              class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="saving"
              class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg transition-colors disabled:opacity-50"
            >
              {{ saving ? 'Saving...' : (editingBook ? 'Update' : 'Create') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useToast } from '../../composables/useToast'

const toast = useToast()
const loading = ref(false)
const saving = ref(false)
const showCreateForm = ref(false)
const editingBook = ref(null)
const previewingBook = ref(null)
const searchQuery = ref('')
const books = ref([])
const pagination = ref(null)

const linkType = ref('url') // 'url' or 'file'
const savingPageSEO = ref(false)
const linkFileInput = ref(null)
const coverImageInput = ref(null)

const pageSeoSettings = ref({
  seo_title: '',
  meta_description: '',
  no_index: false,
  no_follow: false,
  robots: 'index, follow',
  schema_type: 'book'
})

const schemaTypes = ref({
  'book': 'Book Collection',
  'article': 'Article',
  'webpage': 'WebPage',
  'product': 'Product',
  'none': 'None',
})

const form = ref({
  title: '',
  link: '',
  link_file: null,
  link_file_preview: null,
  cover_image: null,
  cover_image_preview: null
})

const filteredBooks = computed(() => {
  let filtered = books.value

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(book => 
      book.title.toLowerCase().includes(query)
    )
  }

  return filtered
})

const getImageUrl = (imagePath) => {
  if (!imagePath) return null
  return `${import.meta.env.VITE_API_URL}/storage/${imagePath}`
}

const getLinkUrl = (link) => {
  if (!link) return null
  // If it's a URL, return as is
  if (link.startsWith('http://') || link.startsWith('https://')) {
    return link
  }
  // If it's a file path, return storage URL
  return `${import.meta.env.VITE_API_URL}/storage/${link}`
}

const isFileLink = (link) => {
  if (!link) return false
  return !link.startsWith('http://') && !link.startsWith('https://')
}

const previewBook = (book) => {
  previewingBook.value = book
}

const closePreview = () => {
  previewingBook.value = null
}

const copyLink = async (link) => {
  const url = getLinkUrl(link)
  try {
    await navigator.clipboard.writeText(url)
    toast.success('Link copied to clipboard!')
  } catch (error) {
    toast.error('Failed to copy link')
  }
}

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const loadBooks = async (page = 1) => {
  loading.value = true
  try {
    const params = new URLSearchParams({
      per_page: 12,
      page: page
    })

    if (searchQuery.value) {
      params.append('search', searchQuery.value)
    }

    const response = await axios.get(`/api/books?${params.toString()}`)
    books.value = response.data.data.data || []
    pagination.value = {
      current_page: response.data.data.current_page,
      last_page: response.data.data.last_page,
      from: response.data.data.from,
      to: response.data.data.to,
      total: response.data.data.total
    }
  } catch (error) {
    toast.error(error.response?.data?.message || 'Failed to load books')
  } finally {
    loading.value = false
  }
}

const handleImageChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    if (file.size > 5242880) { // 5MB
      toast.error('Image size must not exceed 5MB')
      if (coverImageInput.value) coverImageInput.value.value = ''
      return
    }
    form.value.cover_image = file
    form.value.cover_image_preview = URL.createObjectURL(file)
  }
}

const handleLinkFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    if (file.size > 62914560) { // 60MB
      toast.error('File size must not exceed 60MB')
      return
    }
    form.value.link_file = file
    form.value.link_file_preview = file
    form.value.link = '' // Clear URL if file is selected
  }
}

const clearLinkFile = () => {
  form.value.link_file = null
  form.value.link_file_preview = null
  if (linkFileInput.value) {
    linkFileInput.value.value = ''
  }
}

const editBook = (book) => {
  editingBook.value = book
  // Determine if link is a file path or URL
  const isFileLink = book.link && !book.link.startsWith('http')
  linkType.value = isFileLink ? 'file' : 'url'
  
  form.value = {
    title: book.title,
    link: isFileLink ? '' : (book.link || ''),
    link_file: null,
    link_file_preview: isFileLink ? { name: book.link.split('/').pop() } : null,
    cover_image: null,
    cover_image_preview: book.cover_image ? getImageUrl(book.cover_image) : null
  }
  showCreateForm.value = true
}

const closeModal = () => {
  showCreateForm.value = false
  editingBook.value = null
  linkType.value = 'url'
  form.value = {
    title: '',
    link: '',
    link_file: null,
    link_file_preview: null,
    cover_image: null,
    cover_image_preview: null
  }
}

const saveBook = async () => {
  saving.value = true
  try {
    const formData = new FormData()
    formData.append('title', form.value.title)
    
    // Handle link - either URL or file upload
    if (linkType.value === 'url' && form.value.link) {
      formData.append('link', form.value.link)
    } else if (linkType.value === 'file' && form.value.link_file) {
      formData.append('link_file', form.value.link_file)
    }
    
    if (form.value.cover_image) {
      formData.append('cover_image', form.value.cover_image)
    }

    if (editingBook.value) {
      // Use POST with _method: 'PUT' for multipart/form-data updates in Laravel
      formData.append('_method', 'PUT')
      await axios.post(`/api/books/${editingBook.value.id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      toast.success('Book updated successfully')
    } else {
      await axios.post('/api/books', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      toast.success('Book created successfully')
    }

    closeModal()
    loadBooks(pagination.value?.current_page || 1)
  } catch (error) {
    toast.error(error.response?.data?.message || 'Failed to save book')
  } finally {
    saving.value = false
  }
}

const deleteBook = async (id) => {
  if (!confirm('Are you sure you want to delete this book?')) return

  try {
    await axios.delete(`/api/books/${id}`)
    toast.success('Book deleted successfully')
    loadBooks(pagination.value?.current_page || 1)
  } catch (error) {
    toast.error(error.response?.data?.message || 'Failed to delete book')
  }
}

const loadPageSeoSettings = async () => {
  try {
    const response = await axios.get('/api/admin/settings')
    const settings = response.data.data || {}
    
    pageSeoSettings.value = {
      seo_title: settings.books_page_seo_title || '',
      meta_description: settings.books_page_meta_description || '',
      no_index: settings.books_page_no_index === '1' || settings.books_page_no_index === true || false,
      no_follow: settings.books_page_no_follow === '1' || settings.books_page_no_follow === true || false,
      robots: 'index, follow', // Default
      schema_type: settings.books_page_schema_type || 'book'
    }

    // Set initial robots value based on loaded settings
    const noIndex = pageSeoSettings.value.no_index
    const noFollow = pageSeoSettings.value.no_follow
    
    if (noIndex && noFollow) {
      pageSeoSettings.value.robots = 'noindex, nofollow'
    } else if (noIndex) {
      pageSeoSettings.value.robots = 'noindex, follow'
    } else if (noFollow) {
      pageSeoSettings.value.robots = 'index, nofollow'
    } else {
      pageSeoSettings.value.robots = 'index, follow'
    }
  } catch (error) {
    console.error('Failed to load page SEO settings:', error)
  }
}

const savePageSeoSettings = async () => {
  savingPageSEO.value = true
  try {
    // Convert robots dropdown to boolean fields
    const robotsValue = pageSeoSettings.value.robots || 'index, follow'
    const no_index = robotsValue.includes('noindex')
    const no_follow = robotsValue.includes('nofollow')

    await axios.put('/api/admin/settings/books_page_seo_title', { value: pageSeoSettings.value.seo_title || '' })
    await axios.put('/api/admin/settings/books_page_meta_description', { value: pageSeoSettings.value.meta_description || '' })
    await axios.put('/api/admin/settings/books_page_no_index', { value: no_index ? '1' : '0' })
    await axios.put('/api/admin/settings/books_page_no_follow', { value: no_follow ? '1' : '0' })
    await axios.put('/api/admin/settings/books_page_schema_type', { value: pageSeoSettings.value.schema_type || 'book' })
    
    toast.success('SEO settings saved successfully')
  } catch (error) {
    console.error('Failed to save page SEO settings:', error)
    toast.error(error.response?.data?.message || 'Failed to save SEO settings')
  } finally {
    savingPageSEO.value = false
  }
}

onMounted(() => {
  loadBooks()
  loadPageSeoSettings()
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
