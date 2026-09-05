<template>
  <div class="p-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8">Homework</h1>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
      <div v-for="stat in stats" :key="stat.label" class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm mb-2">{{ stat.label }}</p>
        <p class="text-3xl font-bold text-gray-300">—</p>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="font-bold text-gray-800">Assignments</h3>
      </div>

      <!-- No assignments exist without an account, so this is the same empty state a
           newly enrolled student sees. -->
      <div class="p-12 text-center">
        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
          <FileText class="w-7 h-7 text-gray-400" />
        </div>
        <h4 class="font-bold text-gray-800 mb-1">No assignments yet</h4>
        <p class="text-gray-600 text-sm max-w-md mx-auto">
          Homework set by your tutor appears here with its due date, your submission and the
          feedback you receive.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { FileText } from 'lucide-vue-next'

const stats = [
  { label: 'Pending' },
  { label: 'Submitted' },
  { label: 'Reviewed' },
]
</script>
