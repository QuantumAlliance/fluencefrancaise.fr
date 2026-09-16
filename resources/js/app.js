import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import toastPlugin from './plugins/toast'
import Toggle from './components/Toggle.vue'
import { useSettingsStore } from './stores/settings'
import { useAuthStore } from './stores/auth'
import '../css/app.css'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)
app.use(toastPlugin) // Global toast plugin
app.component('Toggle', Toggle) // Register Toggle globally

// Load settings and initialize auth in parallel BEFORE mounting the app
const settingsStore = useSettingsStore()
const authStore = useAuthStore()

// Run both initializations concurrently to reduce blank screen time
Promise.all([
  settingsStore.fetchSettings(),
  authStore.initializeAuth()
]).then(() => {
  app.mount('#app')
}).catch(err => {
  console.error('Failed to initialize app:', err)
  // Mount anyway to show fallback UI or login page
  app.mount('#app')
})
