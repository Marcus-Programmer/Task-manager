// Base Types
export interface User {
  id: number
  name: string
  email: string
  email_verified_at?: string
  created_at: string
  updated_at: string
}

export interface Task {
  id: number
  title: string
  description?: string
  status: TaskStatus
  user_id: number
  user?: User
  created_at: string
  updated_at: string
}

export type TaskStatus = 'pending' | 'in_progress' | 'done'

// Form Types
export interface TaskFormData {
  title: string
  description?: string
  status?: TaskStatus
}

export interface TaskFilters {
  status?: TaskStatus | ''
  search?: string
}

// API Response Types
export interface PaginatedResponse<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number
  to: number
}

export interface ApiResponse<T = any> {
  data: T
  message?: string
  errors?: Record<string, string[]>
}

// Page Props Types (Inertia.js)
export interface PageProps {
  auth: {
    user: User
  }
  flash: {
    success?: string
    error?: string
  }
}

export interface TaskIndexProps extends PageProps {
  tasks: PaginatedResponse<Task>
  filters: TaskFilters
}

export interface TaskShowProps extends PageProps {
  task: Task
}

export interface TaskFormProps extends PageProps {
  task?: Task
}

// Component Props Types
export interface TaskCardProps {
  task: Task
  showActions?: boolean
  onStatusChange?: (taskId: number, status: TaskStatus) => void
  onEdit?: (task: Task) => void
  onDelete?: (task: Task) => void
}

export interface TaskFormComponentProps {
  task?: Task
  onSubmit: (data: TaskFormData) => void
  onCancel?: () => void
  loading?: boolean
}

export interface TaskFiltersProps {
  filters: TaskFilters
  onFiltersChange: (filters: TaskFilters) => void
  onReset: () => void
}

// Navigation Types
export interface NavItem {
  name: string
  href: string
  icon?: string
  active?: boolean
}

// Theme Types
export type Theme = 'light' | 'dark' | 'system'

// Status Badge Types
export interface StatusConfig {
  label: string
  color: string
  bgColor: string
  textColor: string
}

export type StatusConfigMap = Record<TaskStatus, StatusConfig>