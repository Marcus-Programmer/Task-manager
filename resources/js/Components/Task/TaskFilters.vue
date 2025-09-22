<template>
  <Card>
    <div class="p-4">
      <div class="flex flex-col sm:flex-row gap-4">
        <!-- Search Input -->
        <div class="flex-1">
          <div class="relative">
            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
            <Input
              v-model="localFilters.search"
              placeholder="Search tasks..."
              class="pl-10"
              @input="debouncedUpdate"
            />
          </div>
        </div>

        <!-- Status Filter -->
        <div class="sm:w-40">
          <Select v-model="localFilters.status" @change="handleFiltersChange">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="done">Done</option>
          </Select>
        </div>

        <!-- Reset Button -->
        <Button
          variant="outline"
          @click="handleReset"
          :disabled="!hasActiveFilters"
          class="sm:w-auto"
        >
          <RotateCcw class="h-4 w-4 mr-2" />
          Reset
        </Button>
      </div>

      <!-- Active Filters Display -->
      <div v-if="hasActiveFilters" class="mt-4 flex flex-wrap gap-2">
        <Badge v-if="localFilters.search" variant="secondary" class="gap-1">
          Search: "{{ localFilters.search }}"
          <button @click="clearSearch" class="ml-1 hover:bg-secondary-foreground/20 rounded-full p-0.5">
            <X class="h-3 w-3" />
          </button>
        </Badge>

        <Badge v-if="localFilters.status" variant="secondary" class="gap-1">
          Status: {{ getStatusLabel(localFilters.status) }}
          <button @click="clearStatus" class="ml-1 hover:bg-secondary-foreground/20 rounded-full p-0.5">
            <X class="h-3 w-3" />
          </button>
        </Badge>
      </div>
    </div>
  </Card>
</template>

<script setup lang="ts">
import { reactive, computed, watch } from 'vue'
import { Search, RotateCcw, X } from 'lucide-vue-next'
import type { TaskFilters, TaskStatus } from '@/types'

// Components
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Input from '@/components/ui/Input.vue'
import Select from '@/components/ui/Select.vue'
import Badge from '@/components/ui/Badge.vue'

interface Props {
  filters: TaskFilters
}

const props = defineProps<Props>()

const emit = defineEmits<{
  filtersChange: [filters: TaskFilters]
  reset: []
}>()

const localFilters = reactive<TaskFilters>({
  search: props.filters.search || '',
  status: props.filters.status || ''
})

// Watch for external filter changes
watch(
  () => props.filters,
  (newFilters) => {
    localFilters.search = newFilters.search || ''
    localFilters.status = newFilters.status || ''
  },
  { deep: true }
)

const hasActiveFilters = computed(() => {
  return !!(localFilters.search || localFilters.status)
})

// Debounced update for search
let debounceTimeout: ReturnType<typeof setTimeout>
const debouncedUpdate = () => {
  clearTimeout(debounceTimeout)
  debounceTimeout = setTimeout(() => {
    handleFiltersChange()
  }, 300)
}

const handleFiltersChange = () => {
  const filters: TaskFilters = {
    search: localFilters.search || undefined,
    status: (localFilters.status as TaskStatus) || undefined
  }
  emit('filtersChange', filters)
}

const handleReset = () => {
  localFilters.search = ''
  localFilters.status = ''
  emit('reset')
}

const clearSearch = () => {
  localFilters.search = ''
  handleFiltersChange()
}

const clearStatus = () => {
  localFilters.status = ''
  handleFiltersChange()
}

const getStatusLabel = (status: string): string => {
  const labels = {
    pending: 'Pending',
    in_progress: 'In Progress',
    done: 'Done'
  }
  return labels[status as TaskStatus] || status
}
</script>