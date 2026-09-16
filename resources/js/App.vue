<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <RouterView />
    <Toast />
  </div>
</template>

<script setup>
import { useAuthStore } from './stores/auth'
import { watch } from 'vue'
import { useRouter } from 'vue-router'
import Toast from './components/Toast.vue'

const auth = useAuthStore()
const router = useRouter()

// Auth state is handled by the router's beforeEach guard in router/index.js
// This watcher was forcing guest redirects on public pages, so it's safer to remove it.
</script>
