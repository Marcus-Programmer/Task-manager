<template>
  <AppLayout title="Create Task">
    <div class="max-w-2xl mx-auto">
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create New Task</h1>
        <p class="mt-2 text-gray-600">
          Add a new task to your task list
        </p>
      </div>

      <!-- Task Form -->
      <TaskForm
        :loading="loading"
        :errors="errors"
        @submit="handleSubmit"
        @cancel="handleCancel"
      />
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import type { TaskFormData, TaskFormProps } from '@/types'

// Components
import AppLayout from '@/Layouts/AppLayout.vue'
import TaskForm from '@/Components/Task/TaskForm.vue'

// Composables
import { useTasks } from '@/composables/useTasks'

// Props from Inertia
defineProps<TaskFormProps>()

// Use tasks composable
const { handleTaskCreate, loading, errors, clearErrors } = useTasks()

// Methods
const handleSubmit = async (data: TaskFormData) => {
  clearErrors()

  const success = await handleTaskCreate(data)

  if (success) {
    router.visit('/tasks')
  }
}

const handleCancel = () => {
  router.visit('/tasks')
}
</script>