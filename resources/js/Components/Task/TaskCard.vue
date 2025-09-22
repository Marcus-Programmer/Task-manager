<template>
  <Card class="transition-all hover:shadow-lg">
    <div class="p-6">
      <!-- Header -->
      <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
          <h3 class="text-lg font-semibold text-foreground mb-2">
            {{ task.title }}
          </h3>
          <p v-if="task.description" class="text-sm text-muted-foreground line-clamp-2">
            {{ task.description }}
          </p>
        </div>

        <!-- Actions Dropdown -->
        <DropdownMenu v-if="showActions" align="end">
          <template #trigger>
            <Button variant="ghost" size="sm" class="h-8 w-8 p-0">
              <MoreVertical class="h-4 w-4" />
            </Button>
          </template>

          <DropdownMenuContent>
            <DropdownMenuItem @click="handleEdit">
              <Edit class="mr-2 h-4 w-4" />
              Edit
            </DropdownMenuItem>
            <DropdownMenuItem @click="handleDelete" class="text-destructive">
              <Trash2 class="mr-2 h-4 w-4" />
              Delete
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>

      <!-- Status Badge -->
      <div class="flex items-center justify-between">
        <Badge :variant="statusConfig.variant" class="capitalize">
          {{ statusConfig.label }}
        </Badge>

        <!-- Quick Status Actions -->
        <div class="flex gap-1">
          <Button
            v-for="status in availableStatuses"
            :key="status"
            :variant="task.status === status ? 'default' : 'outline'"
            size="sm"
            class="h-7 px-2 text-xs"
            @click="handleStatusChange(status)"
          >
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
import { computed } from 'vue'
import { Calendar, Clock, Edit, MoreVertical, Trash2 } from 'lucide-vue-next'
import type { Task, TaskStatus } from '@/types'

// Components
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Badge from '@/components/ui/Badge.vue'
import DropdownMenu from '@/components/ui/DropdownMenu.vue'
import DropdownMenuContent from '@/components/ui/DropdownMenuContent.vue'
import DropdownMenuItem from '@/components/ui/DropdownMenuItem.vue'

interface Props {
  task: Task
  showActions?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  showActions: true
})

const emit = defineEmits<{
  statusChange: [taskId: number, status: TaskStatus]
  edit: [task: Task]
  delete: [task: Task]
}>()

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

const handleStatusChange = (status: TaskStatus) => {
  if (status !== props.task.status) {
    emit('statusChange', props.task.id, status)
  }
}

const handleEdit = () => {
  emit('edit', props.task)
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