import { useTaskStore } from '@/stores/taskStore'
import type { Task, TaskFormData, TaskStatus } from '@/types'

export function useTasks() {
  const taskStore = useTaskStore()

  return {
    // State
    tasks: taskStore.tasks,
    taskList: taskStore.taskList,
    currentTask: taskStore.currentTask,
    filters: taskStore.filters,
    loading: taskStore.loading,
    errors: taskStore.errors,

    // Getters
    hasFilters: taskStore.hasFilters,
    totalTasks: taskStore.totalTasks,
    currentPage: taskStore.currentPage,
    lastPage: taskStore.lastPage,
    taskCounts: taskStore.taskCounts,

    // Actions
    fetchTasks: taskStore.fetchTasks,
    createTask: taskStore.createTask,
    updateTask: taskStore.updateTask,
    deleteTask: taskStore.deleteTask,
    changeTaskStatus: taskStore.changeTaskStatus,
    searchTasks: taskStore.searchTasks,
    filterByStatus: taskStore.filterByStatus,
    resetFilters: taskStore.resetFilters,
    goToPage: taskStore.goToPage,
    setCurrentTask: taskStore.setCurrentTask,
    clearErrors: taskStore.clearErrors,

    // Convenience methods
    async handleTaskCreate(data: TaskFormData) {
      const success = await taskStore.createTask(data)
      if (success) {
        await taskStore.fetchTasks()
      }
      return success
    },

    async handleTaskUpdate(task: Task, data: TaskFormData) {
      return await taskStore.updateTask(task, data)
    },

    async handleTaskDelete(task: Task) {
      const success = await taskStore.deleteTask(task)
      if (success && taskStore.taskList.length === 1 && taskStore.currentPage > 1) {
        // If we deleted the last item on a page, go to previous page
        await taskStore.goToPage(taskStore.currentPage - 1)
      }
      return success
    },

    async handleStatusChange(taskId: number, status: TaskStatus) {
      const task = taskStore.taskList.find(t => t.id === taskId)
      if (task) {
        return await taskStore.changeTaskStatus(task, status)
      }
      return false
    },

    // Filter helpers
    async applyFilters(newFilters: { search?: string; status?: TaskStatus | '' }) {
      if (newFilters.search !== undefined) {
        await taskStore.searchTasks(newFilters.search)
      }
      if (newFilters.status !== undefined) {
        await taskStore.filterByStatus(newFilters.status || '')
      }
    },

    // Utility methods
    getTaskById(id: number): Task | undefined {
      return taskStore.taskList.find(task => task.id === id)
    },

    getTasksByStatus(status: TaskStatus): Task[] {
      return taskStore.taskList.filter(task => task.status === status)
    },

    hasTasksInStatus(status: TaskStatus): boolean {
      return taskStore.taskCounts[status] > 0
    }
  }
}