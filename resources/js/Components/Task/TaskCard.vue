<template>
  <Card class="transition-all hover:shadow-lg">
    <div class="p-6">
      <!-- Header -->
      <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
          <!-- Title Edit Mode -->
          <div v-if="editingTitle" class="mb-2">
            <Input
              v-model="editForm.title"
              @blur="saveTitle"
              @keydown.enter="saveTitle"
              @keydown.escape="cancelTitleEdit"
              ref="titleInput"
              class="text-lg font-semibold"
              :class="{ 'border-red-500': titleError }"
            />
            <p v-if="titleError" class="text-xs text-red-500 mt-1">{{ titleError }}</p>
          </div>
          <!-- Title Display Mode -->
          <h3
            v-else
            @click="startTitleEdit"
            class="text-lg font-semibold text-foreground mb-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 rounded px-2 py-1 -mx-2 -my-1 transition-colors"
            :title="'Click to edit: ' + task.title"
          >
            {{ task.title }}
          </h3>

          <!-- Description Edit Mode -->
          <div v-if="editingDescription" class="mb-2">
            <textarea
              v-model="editForm.description"
              @blur="saveDescription"
              @keydown.escape="cancelDescriptionEdit"
              ref="descriptionTextarea"
              class="w-full min-h-[60px] text-sm p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"
              :class="{ 'border-red-500': descriptionError }"
              placeholder="Add a description..."
            ></textarea>
            <p v-if="descriptionError" class="text-xs text-red-500 mt-1">{{ descriptionError }}</p>
            <div class="flex gap-2 mt-2">
              <Button size="sm" @click="saveDescription">Save</Button>
              <Button size="sm" variant="outline" @click="cancelDescriptionEdit">Cancel</Button>
            </div>
          </div>
          <!-- Description Display Mode -->
          <p
            v-else-if="task.description"
            @click="startDescriptionEdit"
            class="text-sm text-muted-foreground line-clamp-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 rounded px-2 py-1 -mx-2 -my-1 transition-colors"
            :title="'Click to edit: ' + task.description"
          >
            {{ task.description }}
          </p>
          <!-- Add Description -->
          <p
            v-else
            @click="startDescriptionEdit"
            class="text-sm text-gray-400 dark:text-gray-500 italic cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 rounded px-2 py-1 -mx-2 -my-1 transition-colors"
          >
            Click to add description...
          </p>
        </div>

        <!-- Delete Action -->
        <Button
          v-if="showActions"
          variant="ghost"
          size="sm"
          class="h-8 w-8 p-0 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
          @click="handleDelete"
          :title="'Delete task: ' + task.title"
        >
          <Trash2 class="h-4 w-4" />
        </Button>
      </div>

      <!-- Status Badge and Actions -->
      <div class="mb-4">
        <div class="flex items-center justify-between mb-3">
          <Badge :variant="statusConfig.variant" class="capitalize text-xs px-2 py-1">
            {{ statusConfig.label }}
          </Badge>
        </div>

        <!-- Enhanced Quick Status Actions -->
        <div class="grid grid-cols-3 gap-2">
          <Button
            v-for="status in availableStatuses"
            :key="status"
            :variant="task.status === status ? 'default' : 'outline'"
            size="sm"
            class="h-9 text-xs font-medium transition-all"
            :class="{
              'bg-yellow-500 hover:bg-yellow-600 text-white border-yellow-500 dark:bg-yellow-600 dark:hover:bg-yellow-700': task.status === status && status === 'pending',
              'bg-blue-500 hover:bg-blue-600 text-white border-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700': task.status === status && status === 'in_progress',
              'bg-green-500 hover:bg-green-600 text-white border-green-500 dark:bg-green-600 dark:hover:bg-green-700': task.status === status && status === 'done',
              'hover:bg-yellow-50 hover:border-yellow-300 text-yellow-700 dark:hover:bg-yellow-900/20 dark:hover:border-yellow-600 dark:text-yellow-400': task.status !== status && status === 'pending',
              'hover:bg-blue-50 hover:border-blue-300 text-blue-700 dark:hover:bg-blue-900/20 dark:hover:border-blue-600 dark:text-blue-400': task.status !== status && status === 'in_progress',
              'hover:bg-green-50 hover:border-green-300 text-green-700 dark:hover:bg-green-900/20 dark:hover:border-green-600 dark:text-green-400': task.status !== status && status === 'done'
            }"
            @click="handleStatusChange(status)"
          >
            <component :is="getStatusIcon(status)" class="mr-1 h-3 w-3" />
            {{ getStatusLabel(status) }}
          </Button>
        </div>
      </div>

      <!-- Footer -->
      <div class="mt-4 pt-4 border-t border-border">
        <div class="flex items-center justify-between text-xs text-muted-foreground">
          <span class="flex items-center">
            <Calendar class="mr-1 h-3 w-3" />
            {{ formatDate(task.created_at) }}
          </span>
          <span v-if="task.updated_at !== task.created_at" class="flex items-center">
            <Clock class="mr-1 h-3 w-3" />
            Updated {{ formatDate(task.updated_at) }}
          </span>
        </div>
      </div>
    </div>
  </Card>
</template>

<script setup lang="ts">
import { computed, ref, reactive, nextTick } from 'vue'
import { Calendar, Clock, Trash2, Clock4, Play, CheckCircle } from 'lucide-vue-next'
import { router } from '@inertiajs/vue3'
import type { Task, TaskStatus } from '@/types'

// Components
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Badge from '@/components/ui/Badge.vue'
import Input from '@/components/ui/Input.vue'

interface Props {
  task: Task
  showActions?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  showActions: true
})

const emit = defineEmits<{
  statusChange: [taskId: number, status: TaskStatus]
  delete: [task: Task]
  update: [task: Task]
}>()

// State for inline editing
const editingTitle = ref(false)
const editingDescription = ref(false)
const titleInput = ref<HTMLInputElement>()
const descriptionTextarea = ref<HTMLTextAreaElement>()
const titleError = ref('')
const descriptionError = ref('')

const editForm = reactive({
  title: props.task.title,
  description: props.task.description || ''
})

const availableStatuses: TaskStatus[] = ['pending', 'in_progress', 'done']

const statusConfig = computed(() => {
  const configs = {
    pending: { label: 'Pending', variant: 'secondary' as const },
    in_progress: { label: 'In Progress', variant: 'default' as const },
    done: { label: 'Done', variant: 'success' as const }
  }
  return configs[props.task.status]
})

const getStatusLabel = (status: TaskStatus): string => {
  const labels = {
    pending: 'Pending',
    in_progress: 'In Progress',
    done: 'Done'
  }
  return labels[status]
}

const getStatusIcon = (status: TaskStatus) => {
  const icons = {
    pending: Clock4,
    in_progress: Play,
    done: CheckCircle
  }
  return icons[status]
}

// Inline editing functions
const startTitleEdit = () => {
  editForm.title = props.task.title
  editingTitle.value = true
  titleError.value = ''
  nextTick(() => {
    titleInput.value?.focus()
    titleInput.value?.select()
  })
}

const cancelTitleEdit = () => {
  editForm.title = props.task.title
  editingTitle.value = false
  titleError.value = ''
}

const saveTitle = async () => {
  if (!editForm.title.trim()) {
    titleError.value = 'Title is required'
    return
  }

  if (editForm.title.trim().length < 3) {
    titleError.value = 'Title must be at least 3 characters'
    return
  }

  if (editForm.title.trim() === props.task.title) {
    editingTitle.value = false
    return
  }

  try {
    await updateTask({ title: editForm.title.trim() })
    editingTitle.value = false
    titleError.value = ''
  } catch (error) {
    titleError.value = 'Failed to update title'
  }
}

const startDescriptionEdit = () => {
  editForm.description = props.task.description || ''
  editingDescription.value = true
  descriptionError.value = ''
  nextTick(() => {
    descriptionTextarea.value?.focus()
  })
}

const cancelDescriptionEdit = () => {
  editForm.description = props.task.description || ''
  editingDescription.value = false
  descriptionError.value = ''
}

const saveDescription = async () => {
  const trimmedDescription = editForm.description.trim()

  if (trimmedDescription === (props.task.description || '')) {
    editingDescription.value = false
    return
  }

  try {
    await updateTask({ description: trimmedDescription })
    editingDescription.value = false
    descriptionError.value = ''
  } catch (error) {
    descriptionError.value = 'Failed to update description'
  }
}

const updateTask = async (data: Partial<{ title: string; description: string }>) => {
  return new Promise((resolve, reject) => {
    router.put(`/tasks/${props.task.id}`, data, {
      preserveScroll: true,
      onSuccess: () => resolve(true),
      onError: () => reject(new Error('Update failed'))
    })
  })
}

const handleStatusChange = (status: TaskStatus) => {
  if (status !== props.task.status) {
    emit('statusChange', props.task.id, status)
  }
}

const handleDelete = () => {
  emit('delete', props.task)
}

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>