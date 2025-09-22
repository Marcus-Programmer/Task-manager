import { useAuthStore } from '@/stores/authStore'
import type { User } from '@/types'

interface LoginCredentials {
  email: string
  password: string
  remember?: boolean
}

interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
}

export function useAuth() {
  const authStore = useAuthStore()

  return {
    // State
    user: authStore.user,
    loading: authStore.loading,
    errors: authStore.errors,

    // Getters
    isAuthenticated: authStore.isAuthenticated,
    userName: authStore.userName,
    userEmail: authStore.userEmail,
    userInitials: authStore.userInitials,

    // Actions
    login: authStore.login,
    register: authStore.register,
    logout: authStore.logout,
    updateProfile: authStore.updateProfile,
    forgotPassword: authStore.forgotPassword,
    resetPassword: authStore.resetPassword,
    clearErrors: authStore.clearErrors,

    // Convenience methods
    async handleLogin(credentials: LoginCredentials) {
      const success = await authStore.login(credentials)
      return success
    },

    async handleRegister(userData: RegisterData) {
      const success = await authStore.register(userData)
      return success
    },

    async handleLogout() {
      const success = await authStore.logout()
      return success
    },

    async handleProfileUpdate(profileData: Partial<User>) {
      const success = await authStore.updateProfile(profileData)
      return success
    },

    async handleForgotPassword(email: string) {
      const success = await authStore.forgotPassword(email)
      return success
    },

    // Utility methods
    hasError(field: string): boolean {
      return !!authStore.errors[field]
    },

    getError(field: string): string {
      return authStore.errors[field] || ''
    },

    isCurrentUser(userId: number): boolean {
      return authStore.user?.id === userId
    },

    canPerformAction(requiredAuth = true): boolean {
      return requiredAuth ? authStore.isAuthenticated : true
    }
  }
}