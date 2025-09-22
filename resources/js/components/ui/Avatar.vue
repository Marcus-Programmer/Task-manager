<template>
  <span
    :class="[
      'relative flex shrink-0 overflow-hidden rounded-full',
      sizeClass
    ]"
  >
    <slot />
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  size?: 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md'
})

const sizeClass = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'h-8 w-8'
    case 'lg':
      return 'h-12 w-12'
    case 'md':
    default:
      return 'h-10 w-10'
  }
})
</script>