<template>
  <div @click="$emit('click', $event)">
    <slot />
  </div>
</template>

<script setup lang="ts">
defineEmits<{
  click: [event: MouseEvent]
}>()
</script>