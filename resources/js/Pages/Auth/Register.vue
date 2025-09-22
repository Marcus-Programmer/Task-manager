<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Create your account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Or
          <Link :href="route('login')" class="font-medium text-indigo-600 hover:text-indigo-500">
            sign in to your existing account
          </Link>
        </p>
      </div>

      <!-- Register Form -->
      <Card>
        <div class="p-6">
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <!-- Name Field -->
            <div>
              <Label for="name">Full name</Label>
              <Input
                id="name"
                v-model="form.name"
                type="text"
                autocomplete="name"
                required
                class="mt-1"
                :class="{ 'border-red-500': hasError('name') }"
                placeholder="Enter your full name"
              />
              <p v-if="hasError('name')" class="mt-2 text-sm text-red-600">
                {{ getError('name') }}
              </p>
            </div>

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
                autocomplete="new-password"
                required
                class="mt-1"
                :class="{ 'border-red-500': hasError('password') }"
                placeholder="Create a password"
              />
              <p v-if="hasError('password')" class="mt-2 text-sm text-red-600">
                {{ getError('password') }}
              </p>
              <p class="mt-1 text-xs text-gray-500">
                Must be at least 8 characters long
              </p>
            </div>

            <!-- Password Confirmation Field -->
            <div>
              <Label for="password_confirmation">Confirm password</Label>
              <Input
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                autocomplete="new-password"
                required
                class="mt-1"
                :class="{ 'border-red-500': hasError('password_confirmation') }"
                placeholder="Confirm your password"
              />
              <p v-if="hasError('password_confirmation')" class="mt-2 text-sm text-red-600">
                {{ getError('password_confirmation') }}
              </p>
            </div>

            <!-- Terms Agreement -->
            <div class="flex items-center">
              <input
                id="terms"
                v-model="form.terms"
                type="checkbox"
                required
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <Label for="terms" class="ml-2 block text-sm text-gray-900">
                I agree to the
                <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms of Service</a>
                and
                <a href="#" class="text-indigo-600 hover:text-indigo-500">Privacy Policy</a>
              </Label>
            </div>

            <!-- Submit Button -->
            <div>
              <Button
                type="submit"
                class="w-full flex justify-center"
                :disabled="loading || !form.terms"
              >
                <Loader2 v-if="loading" class="mr-2 h-4 w-4 animate-spin" />
                Create account
              </Button>
            </div>

            <!-- General Error -->
            <div v-if="hasError('general')" class="rounded-md bg-red-50 p-4">
              <div class="flex">
                <AlertCircle class="h-5 w-5 text-red-400" />
                <div class="ml-3">
                  <h3 class="text-sm font-medium text-red-800">
                    Registration failed
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

      <!-- Security Note -->
      <div class="mt-6">
        <Card>
          <div class="p-4 bg-green-50">
            <h3 class="text-sm font-medium text-green-800 mb-2">Secure & Private</h3>
            <p class="text-xs text-green-700">
              Your data is encrypted and secure. We never share your information with third parties.
            </p>
          </div>
        </Card>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import { Link } from '@inertiajs/vue3'
import { route } from '../../../vendor/tightenco/ziggy/dist/index.esm.js'
import { Loader2, AlertCircle } from 'lucide-vue-next'

// Components
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Input from '@/components/ui/Input.vue'
import Label from '@/components/ui/Label.vue'

// Composables
import { useAuth } from '@/composables/useAuth'

// Use auth store
const { handleRegister, loading, hasError, getError, clearErrors } = useAuth()

// Form data
const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false
})

// Handle form submission
const handleSubmit = async () => {
  clearErrors()

  const success = await handleRegister({
    name: form.name,
    email: form.email,
    password: form.password,
    password_confirmation: form.password_confirmation
  })

  if (success) {
    // Redirect will be handled by Inertia
    console.log('Registration successful')
  }
}

// Set page title
defineOptions({
  layout: false
})
</script>