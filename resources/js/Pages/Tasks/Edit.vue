<template>
  <AppLayout title="Edit Task">
    <div class="max-w-2xl mx-auto">
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Task</h1>
        <p class="mt-2 text-gray-600">
          Update your task details
        </p>
      </div>

      <!-- Task Form -->
      <TaskForm
        :task="task"
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
const props = defineProps<TaskFormProps>()

// Use tasks composable
const { handleTaskUpdate, loading, errors, clearErrors } = useTasks()

// Methods
const handleSubmit = async (data: TaskFormData) => {
  if (!props.task) return

  clearErrors()

  const success = await handleTaskUpdate(props.task, data)

  if (success) {
    router.visit('/tasks')
  }
}

const handleCancel = () => {
  router.visit('/tasks')
}
</script>