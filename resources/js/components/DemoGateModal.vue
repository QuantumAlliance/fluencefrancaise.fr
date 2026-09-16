<template>
  <div
    v-if="isOpen"
    class="fixed inset-0 z-[60] flex items-center justify-center px-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="demo-gate-title"
  >
    <div class="absolute inset-0 bg-black/50" @click="close"></div>

    <div class="relative bg-white rounded-2xl shadow-xl p-8 w-full max-w-md text-center">
      <button
        type="button"
        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition"
        aria-label="Close"
        @click="close"
      >
        <X class="w-5 h-5" />
      </button>

      <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#0055A4]/10 flex items-center justify-center">
        <Lock class="w-8 h-8 text-[#0055A4]" />
      </div>

      <h3 id="demo-gate-title" class="text-xl font-bold text-gray-800 mb-2">
        Create an account {{ reason }}
      </h3>
      <p class="text-gray-600 mb-6">
        You're viewing a demo of the student portal. Create your account, then choose a course plan
        or subscription to become a student and unlock the full lessons, activities and quizzes.
      </p>

      <div class="space-y-3">
        <router-link
          to="/register"
          class="block w-full bg-[#0055A4] hover:bg-[#003d7a] text-white font-bold py-3 px-4 rounded-lg transition-colors"
          @click="close"
        >
          Create free account
        </router-link>
        <router-link
          to="/login"
          class="block w-full border border-gray-300 hover:bg-gray-50 text-gray-800 font-medium py-3 px-4 rounded-lg transition-colors"
          @click="close"
        >
          I already have an account
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Lock, X } from 'lucide-vue-next'
import { useDemoGate } from '../composables/useDemoGate'

const { isOpen, reason, close } = useDemoGate()
</script>
