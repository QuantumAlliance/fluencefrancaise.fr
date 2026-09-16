<template>
  <button
    :type="type"
    :disabled="loading || disabled"
    :class="[
      baseClasses,
      variantClasses[variant],
      sizeClasses[size],
      { 'opacity-50 cursor-not-allowed': loading || disabled },
      $attrs.class
    ]"
    @click="$emit('click', $event)"
  >
    <span class="inline-flex items-center">
      <span class="inline-flex items-center" :class="loading ? 'mr-2' : 'w-0 mr-0'">
        <svg
          v-if="loading"
          class="animate-spin h-4 w-4 flex-shrink-0"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
        >
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </span>
      <slot>{{ loading ? loadingText : text }}</slot>
    </span>
  </button>
</template>

<script setup>
defineProps({
  loading: {
    type: Boolean,
    default: false
  },
  disabled: {
    type: Boolean,
    default: false
  },
  type: {
    type: String,
    default: 'button'
  },
  variant: {
    type: String,
    default: 'primary', // primary, secondary, danger, success, warning
    validator: (value) => ['primary', 'secondary', 'danger', 'success', 'warning', 'info'].includes(value)
  },
  size: {
    type: String,
    default: 'md', // sm, md, lg
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  text: {
    type: String,
    default: ''
  },
  loadingText: {
    type: String,
    default: 'Loading...'
  }
})

defineEmits(['click'])

const baseClasses = 'inline-flex items-center justify-center font-medium rounded transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 align-middle'

const variantClasses = {
  primary: 'bg-brand-600 hover:bg-brand-700 text-white focus:ring-brand-600/20',
  secondary: 'bg-gray-300 hover:bg-gray-400 text-gray-800 focus:ring-gray-500',
  danger: 'bg-red-500 hover:bg-red-600 text-white focus:ring-red-500',
  success: 'bg-green-500 hover:bg-green-600 text-white focus:ring-green-500',
  warning: 'bg-yellow-500 hover:bg-yellow-600 text-white focus:ring-yellow-500',
  info: 'bg-blue-500 hover:bg-blue-600 text-white focus:ring-blue-500'
}

const sizeClasses = {
  sm: 'px-3 py-1 text-sm rounded',
  md: 'px-4 py-2 text-base rounded-lg',
  lg: 'px-6 py-3 text-lg rounded-lg'
}
</script>

