<template>
  <div class="">
    <div class="flex justify-end items-center mb-6">
      <button
        @click="showPageForm = true; editingPage = null"
        class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
      >
        + Add New Page
      </button>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by name or slug..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            @input="loadPages"
          />
        </div>
        <div>
          <select
            v-model="statusFilter"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            @change="loadPages"
          >
            <option value="">All Status</option>
            <option value="draft">Draft</option>
            <option value="published">Published</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Pages Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
      <div v-if="loading" class="p-12 text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
        <p class="mt-2 text-gray-500">Loading pages...</p>
      </div>

      <table v-else class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Name</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Slug</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Updated At</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="page in pages" :key="page.id" class="border-b border-gray-200 hover:bg-gray-50">
            <td class="px-6 py-4 font-medium text-gray-800">{{ page.name }}</td>
            <td class="px-6 py-4">
              <code class="px-2 py-1 bg-gray-100 rounded text-sm font-mono">{{ page.slug }}</code>
            </td>
            <td class="px-6 py-4">
              <span
                :class="page.status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                class="px-3 py-1 rounded-full text-sm font-medium capitalize"
              >
                {{ page.status }}
              </span>
            </td>
            <td class="px-6 py-4 text-gray-700 text-sm">{{ formatDate(page.updated_at) }}</td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <a
                  v-if="page.status === 'published'"
                  :href="page.slug"
                  target="_blank"
                  class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-sm transition-colors inline-block"
                >
                  View
                </a>
                <button
                  @click="editPage(page)"
                  class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-sm transition-colors"
                >
                  Edit
                </button>
                <button
                  @click="deletePage(page.id)"
                  class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm transition-colors"
                >
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="!loading && pages.length === 0" class="p-12 text-center">
        <p class="text-gray-500">No pages found</p>
      </div>
    </div>

    <!-- Add/Edit Page Modal -->
    <div
      v-if="showPageForm"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto py-8"
    >
      <div class="bg-white rounded-lg p-8 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-800">
            {{ editingPage ? 'Edit Page' : 'Add New Page' }}
          </h2>
          <button
            @click="cancelPageForm"
            class="text-gray-500 hover:text-gray-700 text-2xl"
          >
            ×
          </button>
        </div>

        <form @submit.prevent="savePage" class="space-y-6">
          <!-- Name Field -->
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Name <span class="text-red-500">*</span>
            </label>
            <input
              v-model="pageForm.name"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              placeholder="e.g. About Us"
            />
          </div>

          <!-- Slug Field -->
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Slug <span class="text-red-500">*</span>
            </label>
            <input
              v-model="pageForm.slug"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              placeholder="e.g. /about-us"
            />
            <p class="text-gray-500 text-xs mt-1">URL path for this page (e.g., /about-us)</p>
          </div>

          <!-- Status Field -->
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Status <span class="text-red-500">*</span>
            </label>
            <select
              v-model="pageForm.status"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            >
              <option value="draft">Draft</option>
              <option value="published">Published</option>
            </select>
          </div>

          <!-- Content Field -->
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Content
            </label>
            <Editor
              v-model="pageForm.content"
              :init="editorConfig"
              license-key="gpl"
            />
          </div>

          <!-- SEO Section -->
          <div class="border-t border-gray-200 pt-6 mt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">SEO Settings</h3>
            
            <div class="space-y-4">
              <div>
                <div class="flex justify-between mb-2">
                  <label class="block text-gray-700 text-sm font-semibold">SEO Title</label>
                  <span class="text-xs text-gray-500" :class="{ 'text-red-500': (pageForm.seo_title?.length || 0) > 60 }">
                    {{ pageForm.seo_title?.length || 0 }} / 60
                  </span>
                </div>
                <input
                  v-model="pageForm.seo_title"
                  type="text"
                  maxlength="60"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                  placeholder="Page title for search engines"
                />
              </div>

              <div>
                <div class="flex justify-between mb-2">
                  <label class="block text-gray-700 text-sm font-semibold">Meta Description</label>
                  <span class="text-xs text-gray-500" :class="{ 'text-red-500': (pageForm.meta_description?.length || 0) > 160 }">
                    {{ pageForm.meta_description?.length || 0 }} / 160
                  </span>
                </div>
                <textarea
                  v-model="pageForm.meta_description"
                  rows="3"
                  maxlength="160"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                  placeholder="Brief description for search results"
                ></textarea>
              </div>

              <!-- Schema Type -->
              <div>
                <div class="flex justify-between mb-2">
                  <label class="block text-gray-700 text-sm font-semibold">Schema Type</label>
                </div>
                <select
                  v-model="pageForm.schema_type"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                >
                  <option v-for="(label, value) in schemaTypes" :key="value" :value="value">
                    {{ label }}
                  </option>
                </select>
                <p class="text-xs text-gray-500 mt-1">
                  Default rich snippet selected when displaying this page. If <code class="bg-gray-100 px-1 rounded">Article</code> is selected, it will be applied for all existing pages with no Schema selected.
                </p>
              </div>

              <!-- Robots Meta -->
              <div class="border-t border-gray-200 pt-4 mt-4">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Robots Settings</h4>

                <div>
                  <label class="block text-gray-700 text-sm font-semibold mb-2">Robots Meta Tag</label>
                  <select
                    v-model="pageForm.robots"
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
            </div>
          </div>

          <!-- Form Actions -->
          <div class="flex gap-4 pt-4">
            <button
              type="submit"
              :disabled="savingPage"
              class="flex-1 px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] disabled:opacity-50 text-white rounded-lg font-medium transition-colors"
            >
              {{ savingPage ? 'Saving...' : (editingPage ? 'Update Page' : 'Create Page') }}
            </button>
            <button
              type="button"
              @click="cancelPageForm"
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
import { useToast } from '../../composables/useToast'
import axios from 'axios'
import Editor from '@tinymce/tinymce-vue'
import 'tinymce/tinymce'
import 'tinymce/models/dom'
import 'tinymce/themes/silver'
import 'tinymce/icons/default'
import 'tinymce/skins/ui/oxide/skin.css'

// Import TinyMCE plugins
import 'tinymce/plugins/advlist'
import 'tinymce/plugins/autolink'
import 'tinymce/plugins/lists'
import 'tinymce/plugins/link'
import 'tinymce/plugins/image'
import 'tinymce/plugins/charmap'
import 'tinymce/plugins/preview'
import 'tinymce/plugins/anchor'
import 'tinymce/plugins/searchreplace'
import 'tinymce/plugins/visualblocks'
import 'tinymce/plugins/code'
import 'tinymce/plugins/fullscreen'
import 'tinymce/plugins/insertdatetime'
import 'tinymce/plugins/media'
import 'tinymce/plugins/table'
import 'tinymce/plugins/help'
import 'tinymce/plugins/wordcount'

const toast = useToast()
const pages = ref([])
const loading = ref(false)
const showPageForm = ref(false)
const editingPage = ref(null)
const savingPage = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')
const schemaTypes = ref({
  'article': 'Article',
  'webpage': 'WebPage',
  'organization': 'Organization',
  'localbusiness': 'LocalBusiness',
  'product': 'Product',
  'service': 'Service',
  'faq': 'FAQPage',
  'course': 'Course',
  'book': 'Book',
  'event': 'Event',
  'person': 'Person',
  'breadcrumblist': 'BreadcrumbList',
  'none': 'None',
})

const pageForm = ref({
  name: '',
  slug: '',
  status: 'draft',
  content: '',
  seo_title: '',
  meta_description: '',
  robots: 'index, follow',
  schema_type: 'article'
})

const editorConfig = {
  height: 400,
  menubar: true,
  license_key: 'gpl',
  base_url: '/build/tinymce',
  suffix: '.min',
  skin_url: '/build/tinymce/skins/ui/oxide',
  content_css: '/build/tinymce/skins/content/default/content.css',
  plugins: [
    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
    'preview', 'anchor', 'searchreplace', 'visualblocks', 'code',
    'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount'
  ],
  toolbar: 'undo redo | formatselect | bold italic backcolor | ' +
    'alignleft aligncenter alignright alignjustify | ' +
    'bullist numlist outdent indent | removeformat | help',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
  promotion: false,
  setup: (editor) => {
    // Prevent form submission when interacting with TinyMCE
    editor.on('init', () => {
      // Ensure all buttons in TinyMCE toolbar don't submit the form
      const setButtonTypes = () => {
        const container = editor.getContainer()
        if (container) {
          const buttons = container.querySelectorAll('button')
          buttons.forEach(button => {
            if (!button.hasAttribute('type')) {
              button.setAttribute('type', 'button')
            }
          })
        }
      }
      
      // Set button types after editor is fully initialized
      setTimeout(setButtonTypes, 200)
      setTimeout(setButtonTypes, 1000)
    })
  }
}

const formatDate = (date) => {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })
}

const loadPages = async () => {
  loading.value = true
  try {
    const params = {}
    if (searchQuery.value) params.search = searchQuery.value
    if (statusFilter.value) params.status = statusFilter.value

    const response = await axios.get('/api/admin/pages', {
      params,
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success) {
      let pagesData = response.data.data
      // Handle paginated response
      if (pagesData && pagesData.data && Array.isArray(pagesData.data)) {
        pagesData = pagesData.data
      }
      pages.value = pagesData || []
    } else {
      pages.value = []
    }
  } catch (error) {
    console.error('Failed to load pages:', error)
    toast.error('Failed to load pages')
    pages.value = []
  } finally {
    loading.value = false
  }
}

const savePage = async (event) => {
  // Prevent form submission if triggered by TinyMCE
  if (event && event.target) {
    const activeElement = document.activeElement
    // Check if the submit was triggered from within TinyMCE
    const tinyMCEContainer = document.querySelector('.tox-tinymce')
    if (tinyMCEContainer && activeElement && tinyMCEContainer.contains(activeElement)) {
      event.preventDefault()
      return false
    }
  }
  
  savingPage.value = true
  try {
    // Convert robots dropdown to boolean fields
    const robotsValue = pageForm.value.robots || 'index, follow'
    const no_index = robotsValue.includes('noindex')
    const no_follow = robotsValue.includes('nofollow')

    const data = {
      name: pageForm.value.name.trim(),
      slug: pageForm.value.slug.trim(),
      status: pageForm.value.status,
      content: pageForm.value.content || null,
      seo_title: pageForm.value.seo_title || null,
      meta_description: pageForm.value.meta_description || null,
      no_index: no_index,
      no_follow: no_follow,
      schema_type: pageForm.value.schema_type || 'article'
    }

    if (editingPage.value) {
      const response = await axios.put(`/api/admin/pages/${editingPage.value.id}`, data, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      if (response.data.success) {
        toast.success('Page updated successfully')
        await loadPages()
        cancelPageForm()
      }
    } else {
      const response = await axios.post('/api/admin/pages', data, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      if (response.data.success) {
        toast.success('Page created successfully')
        await loadPages()
        cancelPageForm()
      }
    }
  } catch (error) {
    console.error('Failed to save page:', error)
    const errorMessage = error.response?.data?.message || error.response?.data?.error || 'Failed to save page'
    toast.error(errorMessage)
  } finally {
    savingPage.value = false
  }
}

const editPage = (page) => {
  if (!page || !page.id) {
    toast.error('Invalid page data')
    return
  }
  editingPage.value = page

  // Convert boolean fields to robots dropdown value
  const noIndex = page.no_index || false
  const noFollow = page.no_follow || false
  let robotsValue = 'index, follow'
  if (noIndex && noFollow) {
    robotsValue = 'noindex, nofollow'
  } else if (noIndex) {
    robotsValue = 'noindex, follow'
  } else if (noFollow) {
    robotsValue = 'index, nofollow'
  }

  pageForm.value = {
    name: page.name || '',
    slug: page.slug || '',
    status: page.status || 'draft',
    content: page.content || '',
    seo_title: page.seo_title || '',
    meta_description: page.meta_description || '',
    robots: robotsValue,
    schema_type: page.schema_type || 'article'
  }
  showPageForm.value = true
}

const deletePage = async (pageId) => {
  if (!confirm('Are you sure you want to delete this page?')) return

  try {
    const response = await axios.delete(`/api/admin/pages/${pageId}`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success) {
      toast.success('Page deleted successfully')
      await loadPages()
    }
  } catch (error) {
    console.error('Failed to delete page:', error)
    toast.error('Failed to delete page')
  }
}

const cancelPageForm = () => {
  showPageForm.value = false
  editingPage.value = null
  pageForm.value = {
    name: '',
    slug: '',
    status: 'draft',
    content: '',
    seo_title: '',
    meta_description: '',
    robots: 'index, follow',
    schema_type: 'article'
  }
}

onMounted(async () => {
  await loadPages()
})
</script>
