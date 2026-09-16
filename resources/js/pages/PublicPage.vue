<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center min-h-screen pt-20">
      <div class="text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
        <p class="mt-4 text-gray-600">Loading page...</p>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="flex items-center justify-center min-h-screen pt-20">
      <div class="text-center">
        <h1 class="text-6xl font-bold text-gray-300 mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-2">Page Not Found</h2>
        <p class="text-gray-600 mb-6">{{ error }}</p>
        <router-link
          to="/"
          class="inline-block px-6 py-3 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
        >
          Go Home
        </router-link>
      </div>
    </div>

    <!-- Page Content -->
    <div v-else-if="page" class="pt-32 pb-16">
      <div class="max-w-4xl mx-auto px-4">
        <!-- Page Header -->
        <div class="mb-8">
          <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ page.name }}</h1>
          <div class="flex items-center text-sm text-gray-500">
            <span>Last updated: {{ formatDate(page.updated_at) }}</span>
          </div>
        </div>

        <!-- Page Content with WordPress-like styling -->
        <div
          class="bg-white rounded-lg shadow-sm p-8 prose prose-lg max-w-none"
          v-html="page.content"
        ></div>
      </div>
    </div>

    <PublicFooter />
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useSettingsStore } from '../stores/settings'
import { useAuthStore } from '../stores/auth'
import axios from 'axios'
import PublicFooter from '../components/PublicFooter.vue'

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()
const settingsStore = useSettingsStore()
const page = ref(null)
const loading = ref(true)
const error = ref(null)
const mobileMenuOpen = ref(false)

const getDashboardLink = () => {
  const userType = auth.user?.user_type
  console.log('Current user type:', userType)
  console.log('Current user:', auth.user)
  if (userType === 'admin' || userType === 'super_admin') return '/admin/dashboard'
  if (userType === 'tutor') return '/tutor/dashboard'
  return '/student/dashboard'
}

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// Function to update meta tags
const updateMetaTags = (pageData) => {
  if (!pageData) return

  // 1. Update Title
  document.title = pageData.seo_title 
    ? `${pageData.seo_title} | ${settingsStore.siteName}` 
    : `${pageData.name} | ${settingsStore.siteName}`

  // 2. Update Meta Description
  let metaDesc = document.querySelector('meta[name="description"]')
  if (!metaDesc) {
    metaDesc = document.createElement('meta')
    metaDesc.setAttribute('name', 'description')
    document.head.appendChild(metaDesc)
  }
  metaDesc.setAttribute('content', pageData.meta_description || '')

  // 3. Update Robots Meta using the standard names
  // Create or select the robots meta tag
  let robotsMeta = document.querySelector('meta[name="robots"]')
  if (!robotsMeta) {
    robotsMeta = document.createElement('meta')
    robotsMeta.setAttribute('name', 'robots')
    document.head.appendChild(robotsMeta)
  }

  const robotsDirectives = []
  if (pageData.no_index) {
    robotsDirectives.push('noindex')
  } else {
    robotsDirectives.push('index')
  }

  if (pageData.no_follow) {
    robotsDirectives.push('nofollow')
  } else {
    robotsDirectives.push('follow')
  }

  robotsMeta.setAttribute('content', robotsDirectives.join(', '))
}

const fetchPage = async () => {
  loading.value = true
  error.value = null

  try {
    // Use route.path from Vue Router for reactivity
    let slug = route.path
    
    // Normalize: remove trailing slash (unless it's just '/')
    if (slug.length > 1 && slug.endsWith('/')) {
      slug = slug.slice(0, -1)
    }

    console.log('[DEBUG] Calling API for slug:', slug)

    const response = await axios.get('/api/pages/by-slug', {
      params: { slug }
    })

    if (response.data.success) {
      page.value = response.data.data
      console.log('[DEBUG] Page data received:', page.value?.name)
    } else {
      error.value = response.data.message || 'Page not found'
      console.warn('[DEBUG] API returned success:false', response.data)
    }
  } catch (err) {
    console.error('[DEBUG] API error:', err)
    error.value = err.response?.data?.message || 'Failed to load page'
  } finally {
    loading.value = false
  }
}

// Watch for route changes to re-fetch the page (important for router-link navigation)
watch(() => route.path, () => {
  fetchPage()
})

// Watch for page changes to update SEO tags
watch(page, (newPage) => {
  if (newPage) {
    updateMetaTags(newPage)
  }
})

onMounted(async () => {
  // Fetch settings first to ensure we have site name
  await settingsStore.fetchSettings()

  // Ensure auth state is current - load user data if token exists but user is not loaded
  if (auth.token && !auth.user) {
    try {
      await auth.getCurrentUser()
    } catch (error) {
      console.error('Failed to load user data:', error)
    }
  }

  fetchPage()
})
</script>

<style scoped>
/* WordPress-like content styling */
:deep(.prose) {
  color: #333;
  line-height: 1.8;
}

:deep(.prose h1),
:deep(.prose h2),
:deep(.prose h3),
:deep(.prose h4),
:deep(.prose h5),
:deep(.prose h6) {
  color: #1a1a1a;
  font-weight: 600;
  margin-top: 1.5em;
  margin-bottom: 0.5em;
  line-height: 1.3;
}

:deep(.prose h1) {
  font-size: 2.25em;
}

:deep(.prose h2) {
  font-size: 1.875em;
}

:deep(.prose h3) {
  font-size: 1.5em;
}

:deep(.prose p) {
  margin-bottom: 1em;
}

:deep(.prose ul),
:deep(.prose ol) {
  margin-bottom: 1em;
  padding-left: 1.5em;
}

:deep(.prose li) {
  margin-bottom: 0.5em;
}

:deep(.prose a) {
  color: #0055A4;
  text-decoration: underline;
}

:deep(.prose a:hover) {
  color: #003d7a;
}

:deep(.prose img) {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  margin: 1.5em 0;
}

:deep(.prose blockquote) {
  border-left: 4px solid #0055A4;
  padding-left: 1em;
  margin-left: 0;
  font-style: italic;
  color: #666;
}

:deep(.prose code) {
  background-color: #f5f5f5;
  padding: 0.2em 0.4em;
  border-radius: 3px;
  font-size: 0.9em;
}

:deep(.prose pre) {
  background-color: #f5f5f5;
  padding: 1em;
  border-radius: 8px;
  overflow-x: auto;
}

:deep(.prose table) {
  width: 100%;
  border-collapse: collapse;
  margin: 1.5em 0;
}

:deep(.prose th),
:deep(.prose td) {
  border: 1px solid #ddd;
  padding: 0.75em;
  text-align: left;
}

:deep(.prose th) {
  background-color: #f5f5f5;
  font-weight: 600;
}
</style>
