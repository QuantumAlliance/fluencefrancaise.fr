import { defineStore } from 'pinia'
import axios from 'axios'

export const useSettingsStore = defineStore('settings', {
  state: () => ({
    siteName: 'Focus Frame French',
    siteUrl: '',
    siteLogo: null,
    footer: {
      quickLinks: [{ text: 'Home', url: '/#home' }, { text: 'Courses', url: '/#courses' }, { text: 'Books', url: '/books' }, { text: 'Referral', url: '/#referral' }, { text: 'FAQ', url: '/#faq' }, { text: 'Contact Us', url: '/contact-us' }],
      resources: [], legalLinks: [{ text: 'Privacy Policy', url: '/new-policies' }],
      contact: {
        email: 'apply@fluencefrancaise.fr',
        phone: '+1 (236) 565-0077',
        address: 'Canada'
      },
      social: {
        facebook: '#',
        twitter: '#',
        instagram: '#',
        linkedin: '#'
      }
    },
    isLoading: false
  }),

  actions: {
    async fetchSettings() {
      this.isLoading = true
      try {
        const token = localStorage.getItem('token')

        // Use public endpoint if no token, otherwise use admin endpoint for full settings
        const endpoint = token ? '/api/admin/settings' : '/api/settings/public'
        const headers = token ? { 'Authorization': `Bearer ${token}` } : {}

        const response = await axios.get(endpoint, {
          headers
        })

        if (response.data.success) {
          const settings = response.data.data
          this.siteName = settings.site_name || 'FocusFrame'
          this.siteUrl = settings.site_url || ''
          this.siteLogo = settings.site_logo || null

          // Parse footer settings if they exist
          try {
            if (settings.footer_settings) {
              const footerSettings = typeof settings.footer_settings === 'string'
                ? JSON.parse(settings.footer_settings)
                : settings.footer_settings

              this.footer = { ...this.footer, ...footerSettings }
            }
          } catch (e) {
            console.error('Error parsing footer settings:', e)
          }
        }
      } catch (error) {
        console.error('Error fetching settings:', error)
        // Keep default values on error
      } finally {
        this.isLoading = false
      }
    },

    updateSiteName(name) {
      this.siteName = name
    },

    updateSiteUrl(url) {
      this.siteUrl = url
    }
  }
})
