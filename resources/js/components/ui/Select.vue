<template>
  <select
    :class="[
      'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
      $attrs.class
    ]"
    v-bind="$attrs"
    :value="modelValue"
    @change="$emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
  >
    <slot />
  </select>
</template>

<script setup lang="ts">
interface Props {
  modelValue?: string
}

withDefaults(defineProps<Props>(), {
  modelValue: ''
})

defineEmits<{
  'update:modelValue': [value: string]
}>()
</script>