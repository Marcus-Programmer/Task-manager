<template>
  <div class="min-h-screen bg-background">
    <!-- Navigation -->
    <nav class="border-b bg-card">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
          <div class="flex">
            <!-- Logo -->
            <div class="flex flex-shrink-0 items-center">
              <Link :href="route('tasks.index')" class="text-xl font-bold text-primary">
                TaskManager
              </Link>
            </div>

            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
              <Link
                :href="route('tasks.index')"
                :class="[
                  'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors',
                  route().current('tasks.*')
                    ? 'border-primary text-foreground'
                    : 'border-transparent text-muted-foreground hover:text-foreground hover:border-border'
                ]"
              >
                <ListTodo class="mr-2 h-4 w-4" />
                Tasks
              </Link>
            </div>
          </div>

          <!-- Right side -->
          <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
            <!-- Theme Toggle -->
            <Button variant="ghost" size="sm" @click="toggleTheme">
              <Sun v-if="isDark" class="h-4 w-4" />
              <Moon v-else class="h-4 w-4" />
            </Button>

            <!-- User Dropdown -->
            <div class="relative">
              <Button
                variant="ghost"
                class="relative h-8 w-8 rounded-full"
                @click="toggleUserDropdown"
              >
                <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                  <span class="text-sm font-medium text-primary">
                    {{ userInitials }}
                  </span>
                </div>
              </Button>

              <!-- Dropdown Menu -->
              <div
                v-if="showUserDropdown"
                class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-card shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
              >
                <div class="py-1">
                  <!-- User Info -->
                  <div class="px-4 py-3 border-b border-border">
                    <p class="text-sm font-medium text-card-foreground">{{ currentUser?.name }}</p>
                    <p class="text-xs text-muted-foreground">{{ currentUser?.email }}</p>
                  </div>

                  <!-- Profile Link -->
                  <Link
                    :href="route('profile.edit')"
                    class="block px-4 py-2 text-sm text-card-foreground hover:bg-muted flex items-center gap-2"
                    @click="closeUserDropdown"
                  >
                    <Settings class="h-4 w-4" />
                    Edit Profile
                  </Link>

                  <!-- Logout -->
                  <button
                    @click="handleLogout"
                    class="w-full text-left px-4 py-2 text-sm text-card-foreground hover:bg-muted flex items-center gap-2"
                  >
                    <LogOut class="h-4 w-4" />
                    Sign out
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Mobile menu button -->
          <div class="-mr-2 flex items-center sm:hidden">
            <Button variant="ghost" @click="showingNavigationDropdown = !showingNavigationDropdown">
              <Menu class="h-6 w-6" />
            </Button>
          </div>
        </div>
      </div>

      <!-- Mobile Navigation Menu -->
      <div v-show="showingNavigationDropdown" class="sm:hidden">
        <div class="space-y-1 pt-2 pb-3">
          <Link
            :href="route('tasks.index')"
            :class="[
              'block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors',
              route().current('tasks.*')
                ? 'border-primary bg-primary/10 text-primary'
                : 'border-transparent text-muted-foreground hover:text-foreground hover:bg-muted hover:border-border'
            ]"
          >
            Tasks
          </Link>
        </div>

        <!-- Mobile user menu -->
        <div class="border-t pt-4 pb-1">
          <div class="flex items-center px-4">
            <div class="flex-shrink-0">
              <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                <span class="text-sm font-medium text-primary">
                  {{ userInitials }}
                </span>
              </div>
            </div>
            <div class="ml-3">
              <div class="text-base font-medium text-foreground">
                {{ currentUser?.name }}
              </div>
              <div class="text-sm font-medium text-muted-foreground">
                {{ currentUser?.email }}
              </div>
            </div>
          </div>

          <div class="mt-3 space-y-1">
            <Link
              :href="route('profile.edit')"
              class="block px-4 py-2 text-base font-medium text-muted-foreground hover:text-foreground hover:bg-muted transition-colors"
            >
              Edit Profile
            </Link>
            <button
              @click="handleLogout"
              class="block w-full px-4 py-2 text-left text-base font-medium text-muted-foreground hover:text-foreground hover:bg-muted transition-colors"
            >
              Sign out
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Flash Messages -->
    <div v-if="$page.props.flash?.success" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 mx-4 mt-4 rounded">
      <div class="flex">
        <CheckCircle class="h-4 w-4 mr-2 mt-0.5" />
        {{ $page.props.flash.success }}
      </div>
    </div>

    <div v-if="$page.props.flash?.error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 mx-4 mt-4 rounded">
      <div class="flex">
        <AlertCircle class="h-4 w-4 mr-2 mt-0.5" />
        {{ $page.props.flash.error }}
      </div>
    </div>

    <!-- Page Content -->
    <main class="py-8">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import {
  ListTodo,
  Menu,
  Sun,
  Moon,
  LogOut,
  CheckCircle,
  AlertCircle,
  Settings
} from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'

// Components
import Button from '@/components/ui/Button.vue'

// Props
interface Props {
  title?: string
}

const props = withDefaults(defineProps<Props>(), {
  title: 'TaskManager'
})

// Global route function (available via ZiggyVue plugin)
declare const route: any

// State
const showingNavigationDropdown = ref(false)
const showUserDropdown = ref(false)

// Theme management
const { isDark, toggleTheme } = useTheme()

// Get current user from Inertia page props
const page = usePage()
const currentUser = computed(() => page.props.auth?.user)

// Computed
const userInitials = computed(() => {
  if (!currentUser.value?.name) return 'U'
  return currentUser.value.name
    .split(' ')
    .map((n: string) => n[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
})

// Methods
const toggleUserDropdown = () => {
  showUserDropdown.value = !showUserDropdown.value
}

const closeUserDropdown = () => {
  showUserDropdown.value = false
}

const handleLogout = () => {
  router.post(route('logout'), {}, {
    onFinish: () => {
      closeUserDropdown()
    }
  })
}

// Close dropdown when clicking outside
const handleClickOutside = (event: MouseEvent) => {
  const target = event.target as HTMLElement
  if (!target.closest('.relative')) {
    closeUserDropdown()
  }
}

// Initialize click outside handler
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>