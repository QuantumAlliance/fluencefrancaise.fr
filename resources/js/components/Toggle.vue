<template>
  <label class="flex items-center cursor-pointer">
    <input
      :checked="modelValue"
      type="checkbox"
      class="sr-only"
      :disabled="disabled"
      @change="$emit('update:modelValue', $event.target.checked)"
    />
    <div
      :class="[
        'relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 ease-in-out focus-within:outline-none focus-within:ring-2 focus-within:ring-brand-600/20 focus-within:ring-offset-2',
        modelValue ? 'bg-brand-600' : 'bg-gray-300',
        disabled && 'opacity-50 cursor-not-allowed'
      ]"
    >
      <span
        :class="[
          'inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ease-in-out',
          modelValue ? 'translate-x-6' : 'translate-x-1'
        ]"
      />
    </div>
    <span
      v-if="label"
      :class="[
        'ml-3 text-sm font-medium',
        modelValue ? 'text-gray-900' : 'text-gray-600',
        disabled && 'opacity-50'
      ]"
    >
      {{ label }}
    </span>
  </label>
</template>

<script setup>
defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  label: {
    type: String,
    default: ''
  },
  disabled: {
    type: Boolean,
    default: false
  }
})

defineEmits(['update:modelValue'])
</script>

