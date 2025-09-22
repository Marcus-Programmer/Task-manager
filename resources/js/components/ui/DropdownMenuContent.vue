<template>
  <div>
    <slot />
  </div>
</template>

<script setup lang="ts">
interface Props {
  align?: 'start' | 'center' | 'end'
}

withDefaults(defineProps<Props>(), {
  align: 'end'
})
</script>