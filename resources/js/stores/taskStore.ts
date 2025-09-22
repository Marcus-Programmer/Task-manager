import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import type { Task, TaskStatus, TaskFilters, TaskFormData, PaginatedResponse } from '@/types'

export const useTaskStore = defineStore('task', () => {
  // State
  const tasks = ref<PaginatedResponse<Task> | null>(null)
  const currentTask = ref<Task | null>(null)
  const filters = ref<TaskFilters>({})
  const loading = ref(false)
  const errors = ref<Record<string, string>>({})

  // Getters
  const taskList = computed(() => tasks.value?.data || [])
  const hasFilters = computed(() => !!(filters.value.search || filters.value.status))
  const totalTasks = computed(() => tasks.value?.total || 0)
  const currentPage = computed(() => tasks.value?.current_page || 1)
  const lastPage = computed(() => tasks.value?.last_page || 1)

  // Task counts by status
  const taskCounts = computed(() => {
    const counts = { pending: 0, in_progress: 0, done: 0, total: 0 }
    taskList.value.forEach(task => {
      counts[task.status]++
      counts.total++
    })
    return counts
  })

  // Actions
  const setTasks = (data: PaginatedResponse<Task>) => {
    tasks.value = data
  }

  const setCurrentTask = (task: Task | null) => {
    currentTask.value = task
  }

  const setFilters = (newFilters: TaskFilters) => {
    filters.value = { ...newFilters }
  }

  const setLoading = (state: boolean) => {
    loading.value = state
  }

  const setErrors = (newErrors: Record<string, string>) => {
    errors.value = { ...newErrors }
  }

  const clearErrors = () => {
    errors.value = {}
  }

  // API Actions
  const fetchTasks = async (page = 1) => {
    setLoading(true)
    clearErrors()

    try {
      const params = new URLSearchParams({
        page: page.toString(),
        ...(filters.value.search && { search: filters.value.search }),
        ...(filters.value.status && { status: filters.value.status })
      })

      router.get(`/tasks?${params}`, {}, {
        preserveState: true,
        preserveScroll: true,
        only: ['tasks', 'filters'],
        onSuccess: (page: any) => {
          setTasks(page.props.tasks)
          setFilters(page.props.filters)
        },
        onError: (errors: any) => {
          setErrors(errors)
        },
        onFinish: () => {
          setLoading(false)
        }
      })
    } catch (error) {
      console.error('Failed to fetch tasks:', error)
      setLoading(false)
    }
  }

  const createTask = async (data: TaskFormData) => {
    setLoading(true)
    clearErrors()

    return new Promise<boolean>((resolve) => {
      router.post('/tasks', data, {
        onSuccess: () => {
          resolve(true)
        },
        onError: (errors: any) => {
          setErrors(errors)
          resolve(false)
        },
        onFinish: () => {
          setLoading(false)
        }
      })
    })
  }

  const updateTask = async (task: Task, data: TaskFormData) => {
    setLoading(true)
    clearErrors()

    return new Promise<boolean>((resolve) => {
      router.put(`/tasks/${task.id}`, data, {
        onSuccess: () => {
          // Update task in local state
          if (tasks.value) {
            const index = tasks.value.data.findIndex(t => t.id === task.id)
            if (index !== -1) {
              tasks.value.data[index] = { ...task, ...data }
            }
          }
          resolve(true)
        },
        onError: (errors: any) => {
          setErrors(errors)
          resolve(false)
        },
        onFinish: () => {
          setLoading(false)
        }
      })
    })
  }

  const deleteTask = async (task: Task) => {
    setLoading(true)
    clearErrors()

    return new Promise<boolean>((resolve) => {
      router.delete(`/tasks/${task.id}`, {
        onSuccess: () => {
          // Remove task from local state
          if (tasks.value) {
            tasks.value.data = tasks.value.data.filter(t => t.id !== task.id)
            tasks.value.total--
          }
          resolve(true)
        },
        onError: (errors: any) => {
          setErrors(errors)
          resolve(false)
        },
        onFinish: () => {
          setLoading(false)
        }
      })
    })
  }

  const changeTaskStatus = async (task: Task, status: TaskStatus) => {
    const originalStatus = task.status

    // Optimistic update
    if (tasks.value) {
      const index = tasks.value.data.findIndex(t => t.id === task.id)
      if (index !== -1) {
        tasks.value.data[index].status = status
      }
    }

    return new Promise<boolean>((resolve) => {
      router.post(`/tasks/${task.id}/status`, { status }, {
        onSuccess: () => {
          resolve(true)
        },
        onError: (errors: any) => {
          // Revert optimistic update
          if (tasks.value) {
            const index = tasks.value.data.findIndex(t => t.id === task.id)
            if (index !== -1) {
              tasks.value.data[index].status = originalStatus
            }
          }
          setErrors(errors)
          resolve(false)
        }
      })
    })
  }

  const searchTasks = async (searchTerm: string) => {
    setFilters({ ...filters.value, search: searchTerm })
    await fetchTasks(1)
  }

  const filterByStatus = async (status: TaskStatus | '') => {
    setFilters({ ...filters.value, status: status || undefined })
    await fetchTasks(1)
  }

  const resetFilters = async () => {
    setFilters({})
    await fetchTasks(1)
  }

  const goToPage = async (page: number) => {
    if (page >= 1 && page <= lastPage.value) {
      await fetchTasks(page)
    }
  }

  return {
    // State
    tasks,
    currentTask,
    filters,
    loading,
    errors,

    // Getters
    taskList,
    hasFilters,
    totalTasks,
    currentPage,
    lastPage,
    taskCounts,

    // Actions
    setTasks,
    setCurrentTask,
    setFilters,
    setLoading,
    setErrors,
    clearErrors,
    fetchTasks,
    createTask,
    updateTask,
    deleteTask,
    changeTaskStatus,
    searchTasks,
    filterByStatus,
    resetFilters,
    goToPage
  }
})