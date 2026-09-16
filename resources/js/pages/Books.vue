<template>
  <div class="min-h-screen bg-gray-50">
    <PublicHeader />

    <!-- Main Content -->
    <main class="pt-32 pb-16">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <div class="relative py-16 mb-12 rounded-3xl overflow-hidden bg-[#002654] text-white">
          <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
              <path d="M0 0 L100 0 L100 100 L0 100 Z" />
              <circle cx="20" cy="20" r="30" />
              <circle cx="80" cy="80" r="40" />
            </svg>
          </div>
          <div class="relative z-10 text-center px-4">
            <h1 class="text-3xl md:text-4xl font-extrabold mb-6 tracking-tight">Our <span class="text-[#0055A4]">Curated</span> Books</h1>
            <p class="text-xl text-gray-200 max-w-2xl mx-auto leading-relaxed">
              Unlock a world of knowledge with our carefully selected collection of books and educational resources.
            </p>
          </div>
        </div>



        <!-- Loading State -->
        <div v-if="loading" class="flex flex-col items-center justify-center py-20">
          <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-[#0055A4]/30 border-t-[#0055A4]"></div>
          <p class="mt-6 text-gray-500 font-medium">Curating your library...</p>
        </div>

        <!-- Books Grid -->
        <div v-else-if="books.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
          <div
            v-for="book in books"
            :key="book.id"
            class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden cursor-pointer transform hover:-translate-y-2"
            @click="previewBook(book)"
          >
            <!-- Cover Image -->
            <div class="aspect-[3/4] bg-gray-100 overflow-hidden relative">
              <img
                v-if="book.cover_image"
                :src="getImageUrl(book.cover_image)"
                :alt="book.title"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
              <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 text-gray-400">
                <i class="fas fa-book text-6xl opacity-20 transition-transform group-hover:scale-110"></i>
              </div>
              <!-- Overlay -->
              <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-500 flex items-center justify-center opacity-0 group-hover:opacity-100">
                <span class="bg-white text-gray-900 px-6 py-2 rounded-full font-bold shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                  Quick View
                </span>
              </div>
            </div>

            <!-- Book Info -->
            <div class="p-6">
              <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2 leading-tight group-hover:text-[#0055A4] transition-colors">
                {{ book.title }}
              </h3>
              <div class="w-10 h-1 bg-[#0055A4]/30 rounded-full group-hover:w-full transition-all duration-500"></div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
          <p class="text-gray-500 text-lg mb-2">No books found</p>
          <p class="text-gray-400 text-sm">We'll be adding more books soon!</p>
        </div>

        <!-- Pagination -->
        <div v-if="pagination && pagination.last_page > 1" class="mt-8 flex justify-center">
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
      </div>
    </main>

    <PublicFooter />

    <!-- Preview Modal -->
    <div
      v-if="previewingBook"
      class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click.self="closePreview"
    >
      <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden shadow-2xl animate-modal-in">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-[#002654] to-[#4a4a6a] px-6 py-4 flex justify-between items-center">
          <h2 class="text-xl font-bold text-white truncate pr-4">{{ previewingBook.title }}</h2>
          <button
            @click="closePreview"
            class="text-white/80 hover:text-white hover:bg-white/10 rounded-full p-2 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 md:p-8">
          <div class="flex flex-col md:flex-row gap-8">
            <!-- Cover Image -->
            <div class="flex-shrink-0 mx-auto md:mx-0">
              <div class="w-48 h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl overflow-hidden shadow-lg ring-1 ring-gray-200">
                <img
                  v-if="previewingBook.cover_image"
                  :src="getImageUrl(previewingBook.cover_image)"
                  :alt="previewingBook.title"
                  class="w-full h-full object-cover"
                />
                <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                  <i class="fas fa-book text-5xl"></i>
                </div>
              </div>
            </div>

            <!-- Book Details -->
            <div class="flex-1 flex flex-col">
              <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ previewingBook.title }}</h3>
                <div class="w-16 h-1 bg-[#0055A4] rounded-full"></div>
              </div>

              <div class="flex-1"></div>

              <!-- Action Buttons -->
              <div v-if="previewingBook.link" class="flex flex-wrap gap-3 mt-6">
                <a
                  :href="getLinkUrl(previewingBook.link)"
                  target="_blank"
                  class="inline-flex items-center gap-2 px-6 py-3 bg-[#0055A4] text-white rounded-xl font-semibold hover:bg-[#003d7a] transition-all shadow-lg shadow-[#0055A4]/25 hover:shadow-xl hover:shadow-[#0055A4]/30 hover:-translate-y-0.5"
                >
                  <i :class="isFileLink(previewingBook.link) ? 'fas fa-download' : 'fas fa-external-link-alt'"></i>
                  {{ isFileLink(previewingBook.link) ? 'Download Book' : 'Open Link' }}
                </a>
              </div>

              <div v-else class="mt-6">
                <p class="text-gray-500 italic">No download link available for this book.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useSettingsStore } from '../stores/settings'
import { useSEO } from '../composables/useSEO'
import axios from 'axios'
import PublicFooter from '../components/PublicFooter.vue'
import PublicHeader from '../components/PublicHeader.vue'

const route = useRoute()

const auth = useAuthStore()
const settingsStore = useSettingsStore()
const loading = ref(false)
const books = ref([])
const pagination = ref(null)
const previewingBook = ref(null)

const { applySEO, addStructuredData, updateTitle, updateMetaTag } = useSEO()



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
    alert('Link copied to clipboard!')
  } catch (error) {
    alert('Failed to copy link')
  }
}

const getDashboardLink = computed(() => {
  if (auth.user?.user_type === 'admin' || auth.user?.user_type === 'super_admin') {
    return '/admin/dashboard'
  }
  if (auth.user?.user_type === 'tutor') {
    return '/tutor/dashboard'
  }
  return '/student/dashboard'
})

const loadBooks = async (page = 1) => {
  loading.value = true
  try {
    const params = new URLSearchParams({
      per_page: 12,
      page: page,
      active_only: '1' // Only show active books
    })

    const response = await axios.get(`/api/books?${params.toString()}`)
    books.value = response.data.data.data || []
    pagination.value = {
      current_page: response.data.data.current_page,
      last_page: response.data.data.last_page,
      from: response.data.data.from,
      to: response.data.data.to,
      total: response.data.data.total
    }
    
    return Promise.resolve()
  } catch (error) {
    console.error('Failed to load books:', error)
    return Promise.reject(error)
  } finally {
    loading.value = false
  }
}

// SEO Setup
const baseUrl = window.location.origin

// Generate structured data for books collection
const generateStructuredData = () => {
  if (books.value.length === 0) return null

  const bookItems = books.value.map(book => ({
    '@type': 'Book',
    'name': book.title,
    'image': book.cover_image ? getImageUrl(book.cover_image) : null,
    'url': `${baseUrl}/books`
  }))

  return {
    '@context': 'https://schema.org',
    '@type': 'CollectionPage',
    'name': 'Our Books',
    'description': 'Explore our collection of books and resources',
    'url': `${baseUrl}/books`,
    'mainEntity': {
      '@type': 'ItemList',
      'numberOfItems': bookItems.length,
      'itemListElement': bookItems.map((book, index) => ({
        '@type': 'ListItem',
        'position': index + 1,
        'item': book
      }))
    }
  }
}

// Load SEO settings and apply on mount
onMounted(async () => {
  // Load current user if authenticated but user data not loaded
  if (auth.isAuthenticated && !auth.user) {
    auth.getCurrentUser().catch((error) => {
      console.error('Failed to load user data:', error)
    })
  }
  
  // Load books first to ensure UI is populated
  loadBooks().then(() => {
    const structuredData = generateStructuredData()
    if (structuredData) {
      addStructuredData(structuredData)
    }
  })

  // Load SEO settings from API
  try {
    const settingsResponse = await axios.get('/api/settings/public')
    const settings = settingsResponse.data.data || {}
    
    const seoTitle = settings.books_page_seo_title || 'Our Books - Explore Our Collection'
    const seoDescription = settings.books_page_meta_description || 'Browse our extensive collection of books and educational resources.'
    const noIndex = settings.books_page_no_index === '1' || settings.books_page_no_index === true
    const noFollow = settings.books_page_no_follow === '1' || settings.books_page_no_follow === true

    // Update SEO dynamically
    updateTitle(seoTitle)
    updateMetaTag('description', seoDescription)
    
    // Update robots tag
    const robotsDirectives = []
    robotsDirectives.push(noIndex ? 'noindex' : 'index')
    robotsDirectives.push(noFollow ? 'nofollow' : 'follow')
    updateMetaTag('robots', robotsDirectives.join(', '))
  } catch (error) {
    console.error('Failed to load SEO settings:', error)
    // Fallback SEO
    updateTitle('Our Books')
    updateMetaTag('description', 'Browse our extensive collection of books and educational resources.')
  }
})

// Watch books to update structured data when they change
watch(() => books.value, () => {
  const structuredData = generateStructuredData()
  if (structuredData) {
    const script = document.querySelector('script[type="application/ld+json"]')
    if (script) script.remove()
    
    const newScript = document.createElement('script')
    newScript.type = 'application/ld+json'
    newScript.textContent = JSON.stringify(structuredData)
    document.head.appendChild(newScript)
  }
}, { deep: true })
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

@keyframes modal-in {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(10px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.animate-modal-in {
  animation: modal-in 0.2s ease-out;
}
</style>
