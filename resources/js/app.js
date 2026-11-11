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

// Mount immediately so the UI appears instantly
app.mount('#app')

const settingsStore = useSettingsStore()
const authStore = useAuthStore()

// Initialize settings and auth in the background (non-blocking)
settingsStore.fetchSettings()
authStore.initializeAuth()
