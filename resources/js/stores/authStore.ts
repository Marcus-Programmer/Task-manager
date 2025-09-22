import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import type { User } from '@/types'

interface LoginData {
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

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref<User | null>(null)
  const loading = ref(false)
  const errors = ref<Record<string, string>>({})

  // Getters
  const isAuthenticated = computed(() => !!user.value)
  const userName = computed(() => user.value?.name || '')
  const userEmail = computed(() => user.value?.email || '')
  const userInitials = computed(() => {
    if (!user.value?.name) return 'U'

    return user.value.name
      .split(' ')
      .map(name => name.charAt(0))
      .join('')
      .toUpperCase()
      .slice(0, 2)
  })

  // Actions
  const setUser = (userData: User | null) => {
    user.value = userData
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

  // Initialize user from page props (called when app starts)
  const initializeUser = (userData?: User) => {
    if (userData) {
      setUser(userData)
    }
  }

  // API Actions
  const login = async (credentials: LoginData) => {
    setLoading(true)
    clearErrors()

    return new Promise<boolean>((resolve) => {
      router.post('/login', credentials, {
        onSuccess: (page: any) => {
          if (page.props?.auth?.user) {
            setUser(page.props.auth.user)
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

  const register = async (userData: RegisterData) => {
    setLoading(true)
    clearErrors()

    return new Promise<boolean>((resolve) => {
      router.post('/register', userData, {
        onSuccess: (page: any) => {
          if (page.props?.auth?.user) {
            setUser(page.props.auth.user)
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

  const logout = async () => {
    setLoading(true)

    return new Promise<boolean>((resolve) => {
      router.post('/logout', {}, {
        onSuccess: () => {
          setUser(null)
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

  const updateProfile = async (profileData: Partial<User>) => {
    setLoading(true)
    clearErrors()

    return new Promise<boolean>((resolve) => {
      router.put('/profile', profileData, {
        onSuccess: (page: any) => {
          if (page.props?.auth?.user) {
            setUser(page.props.auth.user)
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

  const forgotPassword = async (email: string) => {
    setLoading(true)
    clearErrors()

    return new Promise<boolean>((resolve) => {
      router.post('/forgot-password', { email }, {
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

  const resetPassword = async (data: { token: string; email: string; password: string; password_confirmation: string }) => {
    setLoading(true)
    clearErrors()

    return new Promise<boolean>((resolve) => {
      router.post('/reset-password', data, {
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

  return {
    // State
    user,
    loading,
    errors,

    // Getters
    isAuthenticated,
    userName,
    userEmail,
    userInitials,

    // Actions
    setUser,
    setLoading,
    setErrors,
    clearErrors,
    initializeUser,
    login,
    register,
    logout,
    updateProfile,
    forgotPassword,
    resetPassword
  }
})