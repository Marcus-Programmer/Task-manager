<template>
  <div class="relative inline-block text-left">
    <div>
      <button
        @click="toggleDropdown"
        type="button"
        class="flex items-center gap-2 rounded-full bg-white dark:bg-gray-800 p-2 text-sm text-gray-700 dark:text-gray-200 shadow-sm ring-1 ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        id="user-menu-button"
        aria-expanded="false"
        aria-haspopup="true"
      >
        <span class="sr-only">Open user menu</span>
        <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
          <User class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
        </div>
        <span class="hidden sm:block text-sm font-medium">{{ user.name }}</span>
        <ChevronDown class="h-4 w-4" />
      </button>
    </div>

    <Transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-show="isOpen"
        class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        role="menu"
        aria-orientation="vertical"
        aria-labelledby="user-menu-button"
        tabindex="-1"
      >
        <div class="py-1" role="none">
          <!-- User Info -->
          <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-600">
            <p class="text-sm text-gray-900 dark:text-white font-medium">{{ user.name }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</p>
          </div>

          <!-- Profile Link -->
          <Link
            :href="route('profile.edit')"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
            role="menuitem"
            @click="closeDropdown"
          >
            <Settings class="h-4 w-4" />
            Edit Profile
          </Link>

          <!-- Dark Mode Toggle -->
          <button
            @click="toggleDarkMode"
            class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
            role="menuitem"
          >
            <Sun v-if="isDark" class="h-4 w-4" />
            <Moon v-else class="h-4 w-4" />
            {{ isDark ? 'Light Mode' : 'Dark Mode' }}
          </button>

          <!-- Logout -->
          <button
            @click="handleLogout"
            class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
            role="menuitem"
          >
            <LogOut class="h-4 w-4" />
            Sign out
          </button>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import { User, ChevronDown, Settings, LogOut, Sun, Moon } from 'lucide-vue-next'

interface Props {
  user: {
    id: number
    name: string
    email: string
  }
}

const props = defineProps<Props>()

// Global route function
declare const route: any

const isOpen = ref(false)
const isDark = ref(false)

// Form for logout
const logoutForm = useForm({})

// Toggle dropdown
const toggleDropdown = () => {
  isOpen.value = !isOpen.value
}

const closeDropdown = () => {
  isOpen.value = false
}

// Handle logout
const handleLogout = () => {
  logoutForm.post(route('logout'), {
    onFinish: () => {
      closeDropdown()
    }
  })
}

// Dark mode toggle
const toggleDarkMode = () => {
  isDark.value = !isDark.value

  if (isDark.value) {
    document.documentElement.classList.add('dark')
    localStorage.setItem('darkMode', 'true')
  } else {
    document.documentElement.classList.remove('dark')
    localStorage.setItem('darkMode', 'false')
  }

  closeDropdown()
}

// Initialize dark mode
const initializeDarkMode = () => {
  const saved = localStorage.getItem('darkMode')

  if (saved === 'true') {
    isDark.value = true
    document.documentElement.classList.add('dark')
  } else if (saved === 'false') {
    isDark.value = false
    document.documentElement.classList.remove('dark')
  } else {
    // Default to system preference
    isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches
    if (isDark.value) {
      document.documentElement.classList.add('dark')
    }
  }
}

// Close dropdown when clicking outside
const handleClickOutside = (event: MouseEvent) => {
  const dropdown = event.target as HTMLElement
  if (!dropdown.closest('.relative')) {
    closeDropdown()
  }
}

onMounted(() => {
  initializeDarkMode()
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>