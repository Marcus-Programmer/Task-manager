import { Page } from '@inertiajs/core'
import { PageProps, TaskIndexProps, TaskShowProps, TaskFormProps } from './index'

// Extend Inertia's Page type with our custom props
declare module '@inertiajs/core' {
  interface PageProps extends PageProps {}
}

// Page-specific types for Inertia components
export interface InertiaPage<T = Record<string, any>> extends Page<T & PageProps> {}

// Specific page types
export type TasksIndexPage = InertiaPage<TaskIndexProps>
export type TasksShowPage = InertiaPage<TaskShowProps>
export type TasksCreatePage = InertiaPage<TaskFormProps>
export type TasksEditPage = InertiaPage<TaskFormProps>

// Form helpers
export interface InertiaFormData {
  data: Record<string, any>
  isDirty: boolean
  errors: Record<string, string>
  hasErrors: boolean
  processing: boolean
  progress: any
  wasSuccessful: boolean
  recentlySuccessful: boolean
}

// Route parameters
export interface TaskRouteParams {
  task?: string | number
}

export interface SearchParams {
  search?: string
  status?: string
  page?: string | number
}