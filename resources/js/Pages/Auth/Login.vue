<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Sign in to TaskManager
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Or
          <Link :href="route('register')" class="font-medium text-indigo-600 hover:text-indigo-500">
            create a new account
          </Link>
        </p>
      </div>

      <!-- Login Form -->
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email address
            </label>
            <div class="mt-1">
              <input
                id="email"
                v-model="form.email"
                type="email"
                autocomplete="email"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :class="{ 'border-red-500': hasError('email') }"
                placeholder="Enter your email"
              />
              <p v-if="hasError('email')" class="mt-2 text-sm text-red-600">
                {{ getError('email') }}
              </p>
            </div>
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
              Password
            </label>
            <div class="mt-1">
              <input
                id="password"
                v-model="form.password"
                type="password"
                autocomplete="current-password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :class="{ 'border-red-500': hasError('password') }"
                placeholder="Enter your password"
              />
              <p v-if="hasError('password')" class="mt-2 text-sm text-red-600">
                {{ getError('password') }}
              </p>
            </div>
          </div>

          <!-- Remember Me -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember"
                v-model="form.remember"
                type="checkbox"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label for="remember" class="ml-2 block text-sm text-gray-900">
                Remember me
              </label>
            </div>
          </div>

          <!-- Submit Button -->
          <div>
            <button
              type="submit"
              class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              :disabled="form.processing"
            >
              <span v-if="form.processing" class="mr-2">
                ⏳
              </span>
              Sign in
            </button>
          </div>

          <!-- General Error -->
          <div v-if="hasError('general')" class="rounded-md bg-red-50 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                ⚠️
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                  Authentication failed
                </h3>
                <p class="mt-2 text-sm text-red-700">
                  {{ getError('general') }}
                </p>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- Demo Credentials -->
      <div class="mt-6">
        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
          <h3 class="text-sm font-medium text-blue-800 mb-2">Demo Credentials</h3>
          <p class="text-xs text-blue-700">
            Email: demo@taskmanager.com<br>
            Password: password
          </p>
          <button
            type="button"
            class="mt-2 inline-flex items-center px-3 py-1 border border-blue-300 text-xs leading-4 font-medium rounded text-blue-700 bg-white hover:bg-blue-50"
            @click="fillDemoCredentials"
          >
            Use Demo Credentials
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'

console.log('Login.vue: Component loading!')

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
  email: '',
  password: '',
  remember: false
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
  console.log('Submitting login form:', {
    email: form.email,
    password: form.password,
    remember: form.remember
  })

  form.post(route('login'), {
    onFinish: () => {
      console.log('Login request completed')
    },
    onError: (errors) => {
      console.error('Login errors:', errors)
    },
    onSuccess: () => {
      console.log('Login successful! Redirecting...')
    }
  })
}

// Fill demo credentials
const fillDemoCredentials = () => {
  form.email = 'demo@taskmanager.com'
  form.password = 'password'
}

console.log('Login.vue: Component setup complete!')

// Set page title
defineOptions({
  layout: false
})
</script>