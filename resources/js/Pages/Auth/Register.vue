<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
          Create your account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-300">
          Or
          <Link :href="route('login')" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
            sign in to your existing account
          </Link>
        </p>
      </div>

      <!-- Register Form -->
      <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Name Field -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Full name
            </label>
            <div class="mt-1">
              <input
                id="name"
                v-model="form.name"
                type="text"
                autocomplete="name"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :class="{ 'border-red-500': hasError('name') }"
                placeholder="Enter your full name"
              />
              <p v-if="hasError('name')" class="mt-2 text-sm text-red-600 dark:text-red-400">
                {{ getError('name') }}
              </p>
            </div>
          </div>

          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Email address
            </label>
            <div class="mt-1">
              <input
                id="email"
                v-model="form.email"
                type="email"
                autocomplete="email"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :class="{ 'border-red-500': hasError('email') }"
                placeholder="Enter your email"
              />
              <p v-if="hasError('email')" class="mt-2 text-sm text-red-600 dark:text-red-400">
                {{ getError('email') }}
              </p>
            </div>
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Password
            </label>
            <div class="mt-1">
              <input
                id="password"
                v-model="form.password"
                type="password"
                autocomplete="new-password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :class="{ 'border-red-500': hasError('password') }"
                placeholder="Create a password"
              />
              <p v-if="hasError('password')" class="mt-2 text-sm text-red-600 dark:text-red-400">
                {{ getError('password') }}
              </p>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Must be at least 8 characters long
              </p>
            </div>
          </div>

          <!-- Password Confirmation Field -->
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Confirm password
            </label>
            <div class="mt-1">
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                autocomplete="new-password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :class="{ 'border-red-500': hasError('password_confirmation') }"
                placeholder="Confirm your password"
              />
              <p v-if="hasError('password_confirmation')" class="mt-2 text-sm text-red-600 dark:text-red-400">
                {{ getError('password_confirmation') }}
              </p>
            </div>
          </div>

          <!-- Terms Agreement -->
          <div class="flex items-center">
            <input
              id="terms"
              v-model="form.terms"
              type="checkbox"
              required
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700"
            />
            <label for="terms" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
              I agree to the
              <a href="#" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">Terms of Service</a>
              and
              <a href="#" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">Privacy Policy</a>
            </label>
          </div>

          <!-- Submit Button -->
          <div>
            <button
              type="submit"
              class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              :disabled="form.processing || !form.terms"
            >
              <span v-if="form.processing" class="mr-2">
                ⏳
              </span>
              Create account
            </button>
          </div>

          <!-- General Error -->
          <div v-if="hasError('general')" class="rounded-md bg-red-50 dark:bg-red-900/20 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                ⚠️
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800 dark:text-red-400">
                  Registration failed
                </h3>
                <p class="mt-2 text-sm text-red-700 dark:text-red-300">
                  {{ getError('general') }}
                </p>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- Security Note -->
      <div class="mt-6">
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md p-4">
          <h3 class="text-sm font-medium text-green-800 dark:text-green-400 mb-2">Secure & Private</h3>
          <p class="text-xs text-green-700 dark:text-green-300">
            Your data is encrypted and secure. We never share your information with third parties.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3'

console.log('Register.vue: Component loading!')

// Global route function (available via ZiggyVue plugin)
declare const route: any

// Props from backend (for error handling)
interface Props {
  errors?: Record<string, string>
}

const props = withDefaults(defineProps<Props>(), {
  errors: () => ({})
})

// Inertia.js form handling
const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false
})

// Helper functions for error handling
const hasError = (field: string): boolean => {
  return !!(props.errors[field] || form.errors[field])
}

const getError = (field: string): string => {
  return props.errors[field] || form.errors[field] || ''
}

// Handle form submission
const handleSubmit = () => {
  console.log('Submitting registration form:', {
    name: form.name,
    email: form.email,
    password: '***',
    password_confirmation: '***',
    terms: form.terms
  })

  form.post(route('register'), {
    onFinish: () => {
      console.log('Registration request completed')
    },
    onError: (errors) => {
      console.error('Registration errors:', errors)
    },
    onSuccess: () => {
      console.log('Registration successful! Redirecting...')
    }
  })
}

console.log('Register.vue: Component setup complete!')

// Set page title
defineOptions({
  layout: false
})
</script>