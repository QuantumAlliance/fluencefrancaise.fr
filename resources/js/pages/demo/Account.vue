<template>
  <!-- Blurred and fully locked by the layout. Carries no real or invented personal
       data — only the shape of the page behind the blur. -->
  <div class="p-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8">Account</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <h3 class="font-bold text-gray-800 mb-6">Profile</h3>
        <div class="space-y-5">
          <div v-for="field in profileFields" :key="field">
            <p class="text-sm text-gray-600 mb-1">{{ field }}</p>
            <div class="h-10 rounded-lg bg-gray-100 border border-gray-200"></div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-bold text-gray-800 mb-6">Plan</h3>
        <div class="space-y-4">
          <div v-for="row in planRows" :key="row">
            <p class="text-sm text-gray-600 mb-1">{{ row }}</p>
            <div class="h-8 rounded-lg bg-gray-100 border border-gray-200"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const profileFields = ['Full name', 'Email address', 'Phone number', 'Preferred language']
const planRows = ['Current plan', 'Renews on', 'Payment method']
</script>
