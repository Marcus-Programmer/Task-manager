<template>
  <div class="z-50 min-w-[8rem] overflow-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-md">
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