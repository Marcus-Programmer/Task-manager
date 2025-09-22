<template>
  <div class="relative inline-block text-left">
    <!-- Trigger -->
    <div @click="isOpen = !isOpen">
      <slot name="trigger" />
    </div>

    <!-- Dropdown Content -->
    <Transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-if="isOpen"
        class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-card border border-border shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        @click.away="isOpen = false"
      >
        <div class="py-1">
          <slot />
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const isOpen = ref(false)
</script>