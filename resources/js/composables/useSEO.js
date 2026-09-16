import { watch } from 'vue'
import { useRoute } from 'vue-router'
import { useSettingsStore } from '../stores/settings'

export function useSEO(options = {}) {
  const route = useRoute()
  const settingsStore = useSettingsStore()

  const defaultOptions = {
    title: '',
    description: '',
    image: '',
    type: 'website',
    url: '',
    keywords: '',
    noindex: false,
    nofollow: false,
    structuredData: null
  }

  const seoOptions = { ...defaultOptions, ...options }

  // Update or create meta tag
  const updateMetaTag = (name, content, attribute = 'name') => {
    if (!content) return

    let meta = document.querySelector(`meta[${attribute}="${name}"]`)
    if (!meta) {
      meta = document.createElement('meta')
      meta.setAttribute(attribute, name)
      document.head.appendChild(meta)
    }
    meta.setAttribute('content', content)
  }

  // Update or create property tag (for Open Graph)
  const updatePropertyTag = (property, content) => {
    updateMetaTag(property, content, 'property')
  }

  // Update title
  const updateTitle = (title) => {
    if (title) {
      const fullTitle = title.includes(settingsStore.siteName)
        ? title
        : `${title} | ${settingsStore.siteName}`
      document.title = fullTitle
    }
  }

  // Update canonical URL
  const updateCanonical = (url) => {
    if (!url) return

    let canonical = document.querySelector('link[rel="canonical"]')
    if (!canonical) {
      canonical = document.createElement('link')
      canonical.setAttribute('rel', 'canonical')
      document.head.appendChild(canonical)
    }
    canonical.setAttribute('href', url)
  }

  // Add structured data (JSON-LD)
  const addStructuredData = (data) => {
    if (!data) return

    // Remove existing structured data script
    const existingScript = document.querySelector('script[type="application/ld+json"]')
    if (existingScript) {
      existingScript.remove()
    }

    const script = document.createElement('script')
    script.type = 'application/ld+json'
    script.textContent = JSON.stringify(data)
    document.head.appendChild(script)
  }

  // Apply SEO settings
  const applySEO = () => {
    const baseUrl = window.location.origin
    const currentUrl = seoOptions.url || `${baseUrl}${route.path}`

    // Title
    if (seoOptions.title) {
      updateTitle(seoOptions.title)
    }

    // Meta Description
    if (seoOptions.description) {
      updateMetaTag('description', seoOptions.description)
    }

    // Keywords
    if (seoOptions.keywords) {
      updateMetaTag('keywords', seoOptions.keywords)
    }

    // Robots
    const robotsDirectives = []
    if (seoOptions.noindex) {
      robotsDirectives.push('noindex')
    } else {
      robotsDirectives.push('index')
    }
    if (seoOptions.nofollow) {
      robotsDirectives.push('nofollow')
    } else {
      robotsDirectives.push('follow')
    }
    updateMetaTag('robots', robotsDirectives.join(', '))

    // Open Graph Tags
    updatePropertyTag('og:iso', seoOptions.locale || 'en_US') // Standard is og:locale
    updatePropertyTag('og:locale', seoOptions.locale || 'en_US')
    updatePropertyTag('og:title', seoOptions.title || document.title)
    updatePropertyTag('og:description', seoOptions.description)
    updatePropertyTag('og:type', seoOptions.type)
    updatePropertyTag('og:url', currentUrl)
    if (seoOptions.image) {
      updatePropertyTag('og:image', seoOptions.image.startsWith('http') ? seoOptions.image : `${baseUrl}${seoOptions.image}`)
    }
    updatePropertyTag('og:site_name', settingsStore.siteName)

    if (seoOptions.modifiedTime || seoOptions.updatedAt) {
      updatePropertyTag('og:updated_time', seoOptions.modifiedTime || seoOptions.updatedAt)
      if (seoOptions.type === 'article') {
        updatePropertyTag('article:modified_time', seoOptions.modifiedTime || seoOptions.updatedAt)
      }
    }

    if ((seoOptions.publishedTime || seoOptions.publishedAt) && seoOptions.type === 'article') {
      updatePropertyTag('article:published_time', seoOptions.publishedTime || seoOptions.publishedAt)
    }

    // Twitter Card Tags
    updateMetaTag('twitter:card', 'summary_large_image')
    updateMetaTag('twitter:title', seoOptions.title || document.title)
    updateMetaTag('twitter:description', seoOptions.description)
    if (seoOptions.image) {
      updateMetaTag('twitter:image', seoOptions.image.startsWith('http') ? seoOptions.image : `${baseUrl}${seoOptions.image}`)
    }

    if (seoOptions.readTime) {
      updateMetaTag('twitter:label1', 'Time to read')
      updateMetaTag('twitter:data1', seoOptions.readTime)
    }

    // Canonical URL
    updateCanonical(currentUrl)

    // Structured Data
    if (seoOptions.structuredData) {
      addStructuredData(seoOptions.structuredData)
    }
  }

  // Watch for route changes and apply SEO
  watch(() => route.path, () => {
    applySEO()
  }, { immediate: true })

  return {
    applySEO,
    updateTitle,
    updateMetaTag,
    updatePropertyTag,
    updateCanonical,
    addStructuredData
  }
}
