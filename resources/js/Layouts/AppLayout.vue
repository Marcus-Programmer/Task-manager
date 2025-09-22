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
            <DropdownMenu align="end">
              <template #trigger>
                <Button variant="ghost" class="relative h-8 w-8 rounded-full">
                  <Avatar class="h-8 w-8">
                    <AvatarFallback>
                      {{ userInitials }}
                    </AvatarFallback>
                  </Avatar>
                </Button>
              </template>

              <DropdownMenuContent class="w-56">
                <DropdownMenuLabel class="font-normal">
                  <div class="flex flex-col space-y-1">
                    <p class="text-sm font-medium leading-none">
                      {{ $page.props.auth.user.name }}
                    </p>
                    <p class="text-xs leading-none text-muted-foreground">
                      {{ $page.props.auth.user.email }}
                    </p>
                  </div>
                </DropdownMenuLabel>
                <DropdownMenuSeparator />
                <DropdownMenuItem @click="logout">
                  <LogOut class="mr-2 h-4 w-4" />
                  Log out
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
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
              <Avatar class="h-10 w-10">
                <AvatarFallback>
                  {{ userInitials }}
                </AvatarFallback>
              </Avatar>
            </div>
            <div class="ml-3">
              <div class="text-base font-medium text-foreground">
                {{ $page.props.auth.user.name }}
              </div>
              <div class="text-sm font-medium text-muted-foreground">
                {{ $page.props.auth.user.email }}
              </div>
            </div>
          </div>

          <div class="mt-3 space-y-1">
            <button
              @click="logout"
              class="block w-full px-4 py-2 text-left text-base font-medium text-muted-foreground hover:text-foreground hover:bg-muted transition-colors"
            >
              Log out
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
import { ref, computed, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { route } from '../../vendor/tightenco/ziggy/dist/index.esm.js'
import {
  ListTodo,
  Menu,
  Sun,
  Moon,
  LogOut,
  CheckCircle,
  AlertCircle
} from 'lucide-vue-next'

// Components
import Button from '@/components/ui/Button.vue'
import DropdownMenu from '@/components/ui/DropdownMenu.vue'
import DropdownMenuTrigger from '@/components/ui/DropdownMenuTrigger.vue'
import DropdownMenuContent from '@/components/ui/DropdownMenuContent.vue'
import DropdownMenuItem from '@/components/ui/DropdownMenuItem.vue'
import DropdownMenuLabel from '@/components/ui/DropdownMenuLabel.vue'
import DropdownMenuSeparator from '@/components/ui/DropdownMenuSeparator.vue'
import Avatar from '@/components/ui/Avatar.vue'
import AvatarFallback from '@/components/ui/AvatarFallback.vue'

// Composables
import { useAuth } from '@/composables/useAuth'

// Props
interface Props {
  title?: string
}

const props = withDefaults(defineProps<Props>(), {
  title: 'TaskManager'
})

// State
const showingNavigationDropdown = ref(false)
const isDark = ref(false)

// Computed
const { logout: authLogout, userInitials } = useAuth()

// Methods
const toggleTheme = () => {
  isDark.value = !isDark.value
  document.documentElement.classList.toggle('dark', isDark.value)
  localStorage.setItem('theme', isDark.value ? 'dark' : 'light')
}

const logout = async () => {
  await authLogout()
}

// Initialize theme
onMounted(() => {
  const savedTheme = localStorage.getItem('theme')
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches

  isDark.value = savedTheme === 'dark' || (!savedTheme && prefersDark)
  document.documentElement.classList.toggle('dark', isDark.value)
})
</script>