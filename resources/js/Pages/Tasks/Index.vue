<template>
  <AppLayout title="Tasks">
    <div class="space-y-6">
      <!-- Page Header -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Tasks</h1>
          <p class="mt-2 text-gray-600">
            Manage your tasks efficiently
          </p>
        </div>

        <Button @click="showCreateModal = true" class="flex items-center gap-2">
          <Plus class="h-4 w-4" />
          New Task
        </Button>
      </div>

      <!-- Task Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <Card>
          <div class="p-4">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <ListTodo class="h-8 w-8 text-blue-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Tasks</p>
                <p class="text-2xl font-semibold text-gray-900">{{ taskCounts.total }}</p>
              </div>
            </div>
          </div>
        </Card>

        <Card>
          <div class="p-4">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <Clock class="h-8 w-8 text-yellow-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Pending</p>
                <p class="text-2xl font-semibold text-gray-900">{{ taskCounts.pending }}</p>
              </div>
            </div>
          </div>
        </Card>

        <Card>
          <div class="p-4">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <Zap class="h-8 w-8 text-orange-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">In Progress</p>
                <p class="text-2xl font-semibold text-gray-900">{{ taskCounts.in_progress }}</p>
              </div>
            </div>
          </div>
        </Card>

        <Card>
          <div class="p-4">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <CheckCircle class="h-8 w-8 text-green-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Completed</p>
                <p class="text-2xl font-semibold text-gray-900">{{ taskCounts.done }}</p>
              </div>
            </div>
          </div>
        </Card>
      </div>

      <!-- Filters -->
      <TaskFilters
        :filters="filters"
        @filters-change="handleFiltersChange"
        @reset="handleResetFilters"
      />

      <!-- Tasks Grid -->
      <div v-if="taskList.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <TaskCard
          v-for="task in taskList"
          :key="task.id"
          :task="task"
          @status-change="handleStatusChange"
          @edit="handleEditTask"
          @delete="handleDeleteTask"
        />
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <ListTodo class="mx-auto h-12 w-12 text-gray-400" />
        <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks found</h3>
        <p class="mt-1 text-sm text-gray-500">
          {{ hasFilters ? 'Try adjusting your filters' : 'Get started by creating your first task' }}
        </p>
        <div class="mt-6">
          <Button v-if="!hasFilters" @click="showCreateModal = true">
            <Plus class="mr-2 h-4 w-4" />
            Create Task
          </Button>
          <Button v-else variant="outline" @click="handleResetFilters">
            Clear Filters
          </Button>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="tasks && tasks.last_page > 1" class="flex items-center justify-between">
        <div class="flex items-center text-sm text-gray-700">
          <span>
            Showing {{ tasks.from }} to {{ tasks.to }} of {{ tasks.total }} results
          </span>
        </div>

        <div class="flex items-center space-x-2">
          <Button
            variant="outline"
            size="sm"
            :disabled="currentPage <= 1"
            @click="goToPage(currentPage - 1)"
          >
            <ChevronLeft class="h-4 w-4" />
            Previous
          </Button>

          <div class="flex items-center space-x-1">
            <Button
              v-for="page in visiblePages"
              :key="page"
              :variant="page === currentPage ? 'default' : 'outline'"
              size="sm"
              @click="goToPage(page)"
            >
              {{ page }}
            </Button>
          </div>

          <Button
            variant="outline"
            size="sm"
            :disabled="currentPage >= lastPage"
            @click="goToPage(currentPage + 1)"
          >
            Next
            <ChevronRight class="h-4 w-4" />
          </Button>
        </div>
      </div>
    </div>

    <!-- Create/Edit Task Modal -->
    <div v-if="showCreateModal || editingTask" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <TaskForm
          :task="editingTask"
          :loading="loading"
          :errors="errors"
          @submit="handleTaskSubmit"
          @cancel="handleCloseModal"
        />
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="deletingTask" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <Card class="max-w-md w-full">
        <div class="p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Task</h3>
          <p class="text-sm text-gray-500 mb-6">
            Are you sure you want to delete "{{ deletingTask.title }}"? This action cannot be undone.
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
              @click="deletingTask = null"
              :disabled="loading"
              class="flex-1"
            >
              Cancel
            </Button>
          </div>
        </div>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import {
  Plus,
  ListTodo,
  Clock,
  Zap,
  CheckCircle,
  ChevronLeft,
  ChevronRight,
  Loader2
} from 'lucide-vue-next'
import type { Task, TaskFormData, TaskFilters, TaskIndexProps } from '@/types'

// Components
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import TaskCard from '@/Components/Task/TaskCard.vue'
import TaskForm from '@/Components/Task/TaskForm.vue'
import TaskFilters from '@/Components/Task/TaskFilters.vue'

// Composables
import { useTasks } from '@/composables/useTasks'

// Props from Inertia
const props = defineProps<TaskIndexProps>()

// Use tasks composable
const {
  tasks,
  taskList,
  filters,
  loading,
  errors,
  hasFilters,
  taskCounts,
  currentPage,
  lastPage,
  handleTaskCreate,
  handleTaskUpdate,
  handleTaskDelete,
  handleStatusChange,
  applyFilters,
  resetFilters,
  goToPage,
  setCurrentTask,
  clearErrors
} = useTasks()

// Local state
const showCreateModal = ref(false)
const editingTask = ref<Task | null>(null)
const deletingTask = ref<Task | null>(null)

// Initialize data from props
onMounted(() => {
  // Set initial data from Inertia props
  tasks.value = props.tasks
  filters.value = props.filters
})

// Computed
const visiblePages = computed(() => {
  const pages = []
  const start = Math.max(1, currentPage.value - 2)
  const end = Math.min(lastPage.value, currentPage.value + 2)

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }

  return pages
})

// Methods
const handleFiltersChange = (newFilters: TaskFilters) => {
  applyFilters(newFilters)
}

const handleResetFilters = () => {
  resetFilters()
}

const handleEditTask = (task: Task) => {
  editingTask.value = task
  setCurrentTask(task)
}

const handleDeleteTask = (task: Task) => {
  deletingTask.value = task
}

const handleTaskSubmit = async (data: TaskFormData) => {
  clearErrors()

  let success = false

  if (editingTask.value) {
    success = await handleTaskUpdate(editingTask.value, data)
  } else {
    success = await handleTaskCreate(data)
  }

  if (success) {
    handleCloseModal()
  }
}

const handleCloseModal = () => {
  showCreateModal.value = false
  editingTask.value = null
  clearErrors()
}

const confirmDelete = async () => {
  if (deletingTask.value) {
    const success = await handleTaskDelete(deletingTask.value)
    if (success) {
      deletingTask.value = null
    }
  }
}
</script>