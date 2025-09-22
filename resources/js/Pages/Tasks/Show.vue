<template>
  <AppLayout title="Task Details">
    <div class="max-w-4xl mx-auto">
      <!-- Back Button -->
      <div class="mb-6">
        <Button variant="outline" @click="goBack" class="flex items-center gap-2">
          <ArrowLeft class="h-4 w-4" />
          Back to Tasks
        </Button>
      </div>

      <!-- Task Details -->
      <Card>
        <div class="p-8">
          <!-- Header -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex-1">
              <h1 class="text-3xl font-bold text-gray-900 mb-2">
                {{ task.title }}
              </h1>
              <div class="flex items-center gap-4 text-sm text-gray-500">
                <span class="flex items-center gap-1">
                  <Calendar class="h-4 w-4" />
                  Created {{ formatDate(task.created_at) }}
                </span>
                <span v-if="task.updated_at !== task.created_at" class="flex items-center gap-1">
                  <Clock class="h-4 w-4" />
                  Updated {{ formatDate(task.updated_at) }}
                </span>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-2">
              <Button variant="outline" @click="handleEdit">
                <Edit class="h-4 w-4 mr-2" />
                Edit
              </Button>
              <Button variant="destructive" @click="showDeleteModal = true">
                <Trash2 class="h-4 w-4 mr-2" />
                Delete
              </Button>
            </div>
          </div>

          <!-- Status -->
          <div class="mb-6">
            <Label class="text-sm font-medium text-gray-700 mb-2 block">Status</Label>
            <div class="flex gap-2">
              <Button
                v-for="status in availableStatuses"
                :key="status"
                :variant="task.status === status ? 'default' : 'outline'"
                size="sm"
                @click="handleStatusChange(status)"
                :disabled="loading"
              >
                {{ getStatusLabel(status) }}
              </Button>
            </div>
          </div>

          <!-- Description -->
          <div v-if="task.description" class="mb-6">
            <Label class="text-sm font-medium text-gray-700 mb-2 block">Description</Label>
            <div class="prose max-w-none">
              <p class="text-gray-600 whitespace-pre-wrap">{{ task.description }}</p>
            </div>
          </div>

          <!-- Task Info -->
          <div class="border-t pt-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div>
                <Label class="text-sm font-medium text-gray-700 mb-1 block">Created By</Label>
                <p class="text-gray-600">{{ task.user?.name || 'Unknown' }}</p>
              </div>
              <div>
                <Label class="text-sm font-medium text-gray-700 mb-1 block">Current Status</Label>
                <Badge :variant="statusConfig.variant" class="capitalize">
                  {{ statusConfig.label }}
                </Badge>
              </div>
            </div>
          </div>
        </div>
      </Card>

      <!-- Delete Confirmation Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <Card class="max-w-md w-full">
          <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Task</h3>
            <p class="text-sm text-gray-500 mb-6">
              Are you sure you want to delete "{{ task.title }}"? This action cannot be undone.
            </p>
            <div class="flex gap-3">
              <Button
                variant="destructive"
                @click="confirmDelete"
                :disabled="loading"
                class="flex-1"
              >
                <Loader2 v-if="loading" class="mr-2 h-4 w-4 animate-spin" />
                Delete
              </Button>
              <Button
                variant="outline"
                @click="showDeleteModal = false"
                :disabled="loading"
                class="flex-1"
              >
                Cancel
              </Button>
            </div>
          </div>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  ArrowLeft,
  Calendar,
  Clock,
  Edit,
  Trash2,
  Loader2
} from 'lucide-vue-next'
import type { TaskStatus, TaskShowProps } from '@/types'

// Components
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Badge from '@/components/ui/Badge.vue'
import Label from '@/components/ui/Label.vue'

// Composables
import { useTasks } from '@/composables/useTasks'

// Props from Inertia
const props = defineProps<TaskShowProps>()

// Use tasks composable
const { handleTaskDelete, handleStatusChange: updateStatus, loading } = useTasks()

// Local state
const showDeleteModal = ref(false)

// Constants
const availableStatuses: TaskStatus[] = ['pending', 'in_progress', 'done']

// Computed
const statusConfig = computed(() => {
  const configs = {
    pending: { label: 'Pending', variant: 'secondary' as const },
    in_progress: { label: 'In Progress', variant: 'default' as const },
    done: { label: 'Done', variant: 'success' as const }
  }
  return configs[props.task.status]
})

// Methods
const goBack = () => {
  router.visit('/tasks')
}

const handleEdit = () => {
  router.visit(`/tasks/${props.task.id}/edit`)
}

const handleStatusChange = async (status: TaskStatus) => {
  if (status !== props.task.status) {
    await updateStatus(props.task.id, status)
    // Refresh the page to get updated data
    router.reload()
  }
}

const confirmDelete = async () => {
  const success = await handleTaskDelete(props.task)
  if (success) {
    router.visit('/tasks')
  }
}

const getStatusLabel = (status: TaskStatus): string => {
  const labels = {
    pending: 'Pending',
    in_progress: 'In Progress',
    done: 'Done'
  }
  return labels[status]
}

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>