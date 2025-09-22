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
      <Card>
        <div class="p-6">
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <!-- Email Field -->
            <div>
              <Label for="email">Email address</Label>
              <Input
                id="email"
                v-model="form.email"
                type="email"
                autocomplete="email"
                required
                class="mt-1"
                :class="{ 'border-red-500': hasError('email') }"
                placeholder="Enter your email"
              />
              <p v-if="hasError('email')" class="mt-2 text-sm text-red-600">
                {{ getError('email') }}
              </p>
            </div>

            <!-- Password Field -->
            <div>
              <Label for="password">Password</Label>
              <Input
                id="password"
                v-model="form.password"
                type="password"
                autocomplete="current-password"
                required
                class="mt-1"
                :class="{ 'border-red-500': hasError('password') }"
                placeholder="Enter your password"
              />
              <p v-if="hasError('password')" class="mt-2 text-sm text-red-600">
                {{ getError('password') }}
              </p>
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
                <Label for="remember" class="ml-2 block text-sm text-gray-900">
                  Remember me
                </Label>
              </div>

              <div class="text-sm">
                <Link :href="route('password.request')" class="font-medium text-indigo-600 hover:text-indigo-500">
                  Forgot your password?
                </Link>
              </div>
            </div>

            <!-- Submit Button -->
            <div>
              <Button
                type="submit"
                class="w-full flex justify-center"
                :disabled="loading"
              >
                <Loader2 v-if="loading" class="mr-2 h-4 w-4 animate-spin" />
                Sign in
              </Button>
            </div>

            <!-- General Error -->
            <div v-if="hasError('general')" class="rounded-md bg-red-50 p-4">
              <div class="flex">
                <AlertCircle class="h-5 w-5 text-red-400" />
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
      </Card>

      <!-- Demo Credentials -->
      <div class="mt-6">
        <Card>
          <div class="p-4 bg-blue-50">
            <h3 class="text-sm font-medium text-blue-800 mb-2">Demo Credentials</h3>
            <p class="text-xs text-blue-700">
              Email: demo@taskmanager.com<br>
              Password: password
            </p>
            <Button
              variant="outline"
              size="sm"
              class="mt-2"
              @click="fillDemoCredentials"
            >
              Use Demo Credentials
            </Button>
          </div>
        </Card>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { Loader2, AlertCircle } from 'lucide-vue-next'

// Components
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Input from '@/components/ui/Input.vue'
import Label from '@/components/ui/Label.vue'

// Composables
import { useAuth } from '@/composables/useAuth'

// Use auth store
const { handleLogin, loading, hasError, getError, clearErrors } = useAuth()

// Form data
const form = reactive({
  email: '',
  password: '',
  remember: false
})

// Handle form submission
const handleSubmit = async () => {
  clearErrors()

  const success = await handleLogin({
    email: form.email,
    password: form.password,
    remember: form.remember
  })

  if (success) {
    // Redirect will be handled by Inertia
    console.log('Login successful')
  }
}

// Fill demo credentials
const fillDemoCredentials = () => {
  form.email = 'demo@taskmanager.com'
  form.password = 'password'
}

// Set page title
defineOptions({
  layout: false
})
</script>