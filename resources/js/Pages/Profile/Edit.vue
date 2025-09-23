<template>
  <AppLayout title="Profile">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="space-y-6">
        <!-- Page Header -->
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profile Settings</h1>
          <p class="mt-2 text-gray-600 dark:text-gray-300">
            Manage your account settings and preferences
          </p>
        </div>

        <!-- Profile Information Section -->
        <Card>
          <div class="p-6">
            <h2 class="text-lg font-semibold text-card-foreground mb-6">Profile Information</h2>

            <form @submit.prevent="updateProfile" class="space-y-6">
              <!-- Name Field -->
              <div class="space-y-2">
                <Label for="name">Full Name</Label>
                <Input
                  id="name"
                  v-model="profileForm.name"
                  type="text"
                  required
                  :class="{ 'border-destructive': profileForm.errors.name }"
                />
                <p v-if="profileForm.errors.name" class="text-sm text-destructive">
                  {{ profileForm.errors.name }}
                </p>
              </div>

              <!-- Email Field -->
              <div class="space-y-2">
                <Label for="email">Email Address</Label>
                <Input
                  id="email"
                  v-model="profileForm.email"
                  type="email"
                  required
                  :class="{ 'border-destructive': profileForm.errors.email }"
                />
                <p v-if="profileForm.errors.email" class="text-sm text-destructive">
                  {{ profileForm.errors.email }}
                </p>
              </div>

              <!-- Submit Button -->
              <div class="flex gap-3">
                <Button
                  type="submit"
                  :disabled="profileForm.processing"
                  class="flex items-center gap-2"
                >
                  <Loader2 v-if="profileForm.processing" class="h-4 w-4 animate-spin" />
                  Save Changes
                </Button>
              </div>
            </form>
          </div>
        </Card>

        <!-- Change Password Section -->
        <Card>
          <div class="p-6">
            <h2 class="text-lg font-semibold text-card-foreground mb-6">Change Password</h2>

            <form @submit.prevent="updatePassword" class="space-y-6">
              <!-- Current Password -->
              <div class="space-y-2">
                <Label for="current_password">Current Password</Label>
                <Input
                  id="current_password"
                  v-model="passwordForm.current_password"
                  type="password"
                  :class="{ 'border-destructive': passwordForm.errors.current_password }"
                />
                <p v-if="passwordForm.errors.current_password" class="text-sm text-destructive">
                  {{ passwordForm.errors.current_password }}
                </p>
              </div>

              <!-- New Password -->
              <div class="space-y-2">
                <Label for="password">New Password</Label>
                <Input
                  id="password"
                  v-model="passwordForm.password"
                  type="password"
                  :class="{ 'border-destructive': passwordForm.errors.password }"
                />
                <p v-if="passwordForm.errors.password" class="text-sm text-destructive">
                  {{ passwordForm.errors.password }}
                </p>
              </div>

              <!-- Confirm New Password -->
              <div class="space-y-2">
                <Label for="password_confirmation">Confirm New Password</Label>
                <Input
                  id="password_confirmation"
                  v-model="passwordForm.password_confirmation"
                  type="password"
                  :class="{ 'border-destructive': passwordForm.errors.password_confirmation }"
                />
                <p v-if="passwordForm.errors.password_confirmation" class="text-sm text-destructive">
                  {{ passwordForm.errors.password_confirmation }}
                </p>
              </div>

              <!-- Submit Button -->
              <div class="flex gap-3">
                <Button
                  type="submit"
                  :disabled="passwordForm.processing || !passwordForm.password"
                  class="flex items-center gap-2"
                >
                  <Loader2 v-if="passwordForm.processing" class="h-4 w-4 animate-spin" />
                  Update Password
                </Button>
              </div>
            </form>
          </div>
        </Card>

        <!-- Danger Zone -->
        <Card>
          <div class="p-6">
            <h2 class="text-lg font-semibold text-destructive mb-6">Danger Zone</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
              Once you delete your account, all of your data will be permanently deleted.
              Please be certain this is what you want.
            </p>

            <Button
              variant="destructive"
              @click="showDeleteModal = true"
              class="flex items-center gap-2"
            >
              <Trash2 class="h-4 w-4" />
              Delete Account
            </Button>
          </div>
        </Card>
      </div>
    </div>

    <!-- Delete Account Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <Card class="max-w-md w-full">
        <div class="p-6">
          <h3 class="text-lg font-medium text-card-foreground mb-4">Delete Account</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            Are you sure you want to delete your account? This action cannot be undone.
            All your tasks and data will be permanently deleted.
          </p>

          <form @submit.prevent="deleteAccount" class="space-y-4">
            <div class="space-y-2">
              <Label for="delete_password">Confirm your password</Label>
              <Input
                id="delete_password"
                v-model="deleteForm.password"
                type="password"
                placeholder="Enter your password to confirm"
                required
                :class="{ 'border-destructive': deleteForm.errors.password }"
              />
              <p v-if="deleteForm.errors.password" class="text-sm text-destructive">
                {{ deleteForm.errors.password }}
              </p>
            </div>

            <div class="flex gap-3">
              <Button
                type="submit"
                variant="destructive"
                :disabled="deleteForm.processing || !deleteForm.password"
                class="flex-1 flex items-center justify-center gap-2"
              >
                <Loader2 v-if="deleteForm.processing" class="h-4 w-4 animate-spin" />
                Delete Account
              </Button>
              <Button
                type="button"
                variant="outline"
                @click="showDeleteModal = false; deleteForm.reset()"
                :disabled="deleteForm.processing"
                class="flex-1"
              >
                Cancel
              </Button>
            </div>
          </form>
        </div>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Loader2, Trash2 } from 'lucide-vue-next'

// Components
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Input from '@/components/ui/Input.vue'
import Label from '@/components/ui/Label.vue'

// Global route function
declare const route: any

interface Props {
  user: {
    id: number
    name: string
    email: string
    email_verified_at: string | null
  }
  status?: string
}

const props = defineProps<Props>()

// State
const showDeleteModal = ref(false)

// Profile form
const profileForm = useForm({
  name: props.user.name,
  email: props.user.email,
})

// Password form
const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

// Delete form
const deleteForm = useForm({
  password: '',
})

// Methods
const updateProfile = () => {
  profileForm.patch(route('profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
      // Success message will be handled by the controller
    },
  })
}

const updatePassword = () => {
  passwordForm.patch(route('profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
      passwordForm.reset()
    },
  })
}

const deleteAccount = () => {
  deleteForm.delete(route('profile.destroy'), {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false
    },
  })
}
</script>