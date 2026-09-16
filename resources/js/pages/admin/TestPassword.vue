<template>
  <div class="">
    <div class="max-w-2xl mx-auto">

      
      <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 mb-6">
          Test if WordPress password verification is working correctly. Enter an email and password to verify.
        </p>

        <form @submit.prevent="testPassword" class="space-y-4">
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
            <input
              v-model="formData.email"
              type="email"
              required
              placeholder="user@example.com"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]"
            />
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
            <input
              v-model="formData.password"
              type="password"
              required
              placeholder="Enter password to test"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]"
            />
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full px-6 py-3 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
          >
            <svg v-if="loading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ loading ? 'Testing...' : 'Test Password' }}</span>
          </button>
        </form>

        <!-- Results -->
        <div v-if="result" class="mt-6">
          <div
            :class="[
              'p-4 rounded-lg',
              result.success ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'
            ]"
          >
            <div class="flex items-start gap-3">
              <svg
                v-if="result.success"
                class="w-5 h-5 text-green-600 mt-0.5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <svg
                v-else
                class="w-5 h-5 text-red-600 mt-0.5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <div class="flex-1">
                <h3
                  :class="[
                    'font-semibold mb-2',
                    result.success ? 'text-green-800' : 'text-red-800'
                  ]"
                >
                  {{ result.success ? 'Password Verified Successfully!' : 'Password Verification Failed' }}
                </h3>
                <p
                  :class="[
                    'text-sm mb-3',
                    result.success ? 'text-green-700' : 'text-red-700'
                  ]"
                >
                  {{ result.message }}
                </p>

                <!-- Details -->
                <div v-if="result.details" class="mt-4 space-y-2">
                  <div class="text-sm">
                    <span class="font-medium text-gray-700">User Found:</span>
                    <span :class="result.details.userFound ? 'text-green-600' : 'text-red-600'">
                      {{ result.details.userFound ? 'Yes' : 'No' }}
                    </span>
                  </div>
                  
                  <div v-if="result.details.userFound" class="text-sm">
                    <span class="font-medium text-gray-700">Password Format:</span>
                    <span class="text-gray-600">{{ result.details.passwordFormat }}</span>
                  </div>
                  
                  <div v-if="result.details.userFound" class="text-sm">
                    <span class="font-medium text-gray-700">Hash Prefix:</span>
                    <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ result.details.hashPrefix }}</code>
                  </div>
                  
                  <div v-if="result.details.userFound && result.details.hashLength" class="text-sm">
                    <span class="font-medium text-gray-700">Hash Length:</span>
                    <span class="text-gray-600">{{ result.details.hashLength }} characters</span>
                  </div>
                  
                  <div v-if="result.details.userFound && result.details.fullHashPreview" class="text-sm">
                    <span class="font-medium text-gray-700">Full Hash Preview:</span>
                    <code class="text-xs bg-gray-100 px-2 py-1 rounded block mt-1 break-all">{{ result.details.fullHashPreview }}</code>
                  </div>
                  
                  <div v-if="result.details.debugInfo" class="text-sm mt-2">
                    <span class="font-medium text-gray-700">Debug:</span>
                    <span class="text-gray-600">{{ result.details.debugInfo }}</span>
                  </div>
                  
                  <div v-if="result.details.verificationMethod" class="text-sm">
                    <span class="font-medium text-gray-700">Verification Method:</span>
                    <span class="text-gray-600">{{ result.details.verificationMethod }}</span>
                  </div>
                  
                  <div v-if="result.details.converted" class="text-sm">
                    <span class="font-medium text-gray-700">Converted to Laravel Format:</span>
                    <span :class="result.details.converted ? 'text-green-600' : 'text-gray-600'">
                      {{ result.details.converted ? 'Yes' : 'No' }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const formData = ref({
  email: '',
  password: ''
})

const loading = ref(false)
const result = ref(null)

const testPassword = async () => {
  loading.value = true
  result.value = null

  try {
    const response = await axios.post('/api/admin/test-password', formData.value)

    result.value = {
      success: response.data.success,
      message: response.data.message,
      details: response.data.details || null
    }
  } catch (error) {
    result.value = {
      success: false,
      message: error.response?.data?.message || error.message || 'Password verification failed',
      details: error.response?.data?.details || null
    }
  } finally {
    loading.value = false
  }
}
</script>

