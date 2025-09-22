<template>
  <Card>
    <div class="p-6">
      <h2 class="text-lg font-semibold mb-6">
        {{ task ? 'Edit Task' : 'Create New Task' }}
      </h2>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Title Field -->
        <div class="space-y-2">
          <Label for="title">Title *</Label>
          <Input
            id="title"
            v-model="form.title"
            placeholder="Enter task title..."
            :class="{ 'border-destructive': errors.title }"
            required
          />
          <p v-if="errors.title" class="text-sm text-destructive">
            {{ errors.title }}
          </p>
        </div>

        <!-- Description Field -->
        <div class="space-y-2">
          <Label for="description">Description</Label>
          <Textarea
            id="description"
            v-model="form.description"
            placeholder="Enter task description (optional)..."
            rows="4"
            :class="{ 'border-destructive': errors.description }"
          />
          <p v-if="errors.description" class="text-sm text-destructive">
            {{ errors.description }}
          </p>
        </div>

        <!-- Status Field -->
        <div class="space-y-2">
          <Label for="status">Status</Label>
          <Select id="status" v-model="form.status">
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="done">Done</option>
          </Select>
          <p v-if="errors.status" class="text-sm text-destructive">
            {{ errors.status }}
          </p>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 pt-4">
          <Button
            type="submit"
            :disabled="loading || !form.title.trim()"
            class="flex-1"
          >
            <Loader2 v-if="loading" class="mr-2 h-4 w-4 animate-spin" />
            {{ task ? 'Update Task' : 'Create Task' }}
          </Button>

          <Button
            type="button"
            variant="outline"
            @click="handleCancel"
            :disabled="loading"
          >
            Cancel
          </Button>
        </div>
      </form>
    </div>
  </Card>
</template>

<script setup lang="ts">
import { reactive, watch } from 'vue'
import { Loader2 } from 'lucide-vue-next'
import type { Task, TaskFormData, TaskStatus } from '@/types'

// Components
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Input from '@/components/ui/Input.vue'
import Label from '@/components/ui/Label.vue'
import Textarea from '@/components/ui/Textarea.vue'
import Select from '@/components/ui/Select.vue'

interface Props {
  task?: Task
  loading?: boolean
  errors?: Record<string, string>
}

const props = withDefaults(defineProps<Props>(), {
  loading: false,
  errors: () => ({})
})

const emit = defineEmits<{
  submit: [data: TaskFormData]
  cancel: []
}>()

const form = reactive<TaskFormData>({
  title: '',
  description: '',
  status: 'pending' as TaskStatus
})

// Initialize form with task data if editing
watch(
  () => props.task,
  (newTask) => {
    if (newTask) {
      form.title = newTask.title
      form.description = newTask.description || ''
      form.status = newTask.status
    }
  },
  { immediate: true }
)

const handleSubmit = () => {
  const data: TaskFormData = {
    title: form.title.trim(),
    description: form.description?.trim() || undefined,
    status: form.status
  }

  emit('submit', data)
}

const handleCancel = () => {
  emit('cancel')
}
</script>