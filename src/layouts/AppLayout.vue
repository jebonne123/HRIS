<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 dark:text-gray-100">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out"
         :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0 lg:translate-x-0': sidebarOpen }">
      <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">HRIS</h1>
        <button @click="sidebarOpen = false" class="lg:hidden">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      
      <nav class="px-4 py-6 space-y-2">
        <router-link to="/dashboard" 
                     class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white"
                     :class="{ 'bg-primary-100 text-primary-700 dark:bg-gray-700 dark:text-white': $route.path === '/dashboard' }">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
          </svg>
          Dashboard
        </router-link>
        
        <div>
          <button @click="toggleEmployeesMenu" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
            <span class="flex items-center">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
              </svg>
              Employees
            </span>
            <svg :class="{ 'rotate-180': employeesMenuOpen }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div v-show="employeesMenuOpen" class="mt-1 ml-8 space-y-1">
            <router-link to="/employees/directory" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-700" :class="{ 'bg-gray-100 dark:bg-gray-700': $route.path === '/employees/directory' }">Employee Directory</router-link>
            <router-link to="/employees/departments" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-700" :class="{ 'bg-gray-100 dark:bg-gray-700': $route.path === '/employees/departments' }">Employee Departments</router-link>
            <router-link to="/employees/designations" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-700" :class="{ 'bg-gray-100 dark:bg-gray-700': $route.path === '/employees/designations' }">Employee Designations</router-link>
            <router-link to="/employees/statuses" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-700" :class="{ 'bg-gray-100 dark:bg-gray-700': $route.path === '/employees/statuses' }">Employment Statuses</router-link>
          </div>
        </div>
        
        <router-link to="/shifts" 
                     class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white"
                     :class="{ 'bg-primary-100 text-primary-700 dark:bg-gray-700 dark:text-white': $route.path.startsWith('/shifts') }">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Shifts
        </router-link>
        
        <router-link to="/departments" 
                     class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white"
                     :class="{ 'bg-primary-100 text-primary-700 dark:bg-gray-700 dark:text-white': $route.path.startsWith('/departments') }">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          Departments
        </router-link>
        
        <router-link to="/attendance" 
                     class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white"
                     :class="{ 'bg-primary-100 text-primary-700 dark:bg-gray-700 dark:text-white': $route.path.startsWith('/attendance') }">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Attendance
        </router-link>
        
        <router-link to="/payroll" 
                     class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white"
                     :class="{ 'bg-primary-100 text-primary-700 dark:bg-gray-700 dark:text-white': $route.path.startsWith('/payroll') }">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
          </svg>
          Payroll
        </router-link>

        <!-- Quick Actions removed per request -->
      </nav>
    </div>

    <!-- Main content -->
    <div :class="{ 'lg:pl-64': sidebarOpen, 'lg:pl-0': !sidebarOpen }">
      <!-- Top navbar -->
      <div class="sticky top-0 z-40 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
          <div class="flex items-center">
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
            <!-- page title removed per request -->
          </div>
          
          <div class="flex items-center space-x-4">
            <!-- Theme toggle -->
            <button @click="toggleTheme" class="p-2 rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700" :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'">
              <svg v-if="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.485-8.485h1M3.515 12.515h1M16.95 7.05l.707-.707M6.343 17.657l.707-.707m9.9 0l.707.707M7.05 7.05l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 118.646 3.646 7 7 0 0020.354 15.354z" />
              </svg>
            </button>
            <div class="relative">
              <button @click="userMenuOpen = !userMenuOpen" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <span class="sr-only">Open user menu</span>
                <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
                  <span class="text-white font-medium">{{ userInitials }}</span>
                </div>
              </button>
              
              <div v-if="userMenuOpen" @click.away="userMenuOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-200">
                  <div class="font-medium">{{ authStore.user?.name }}</div>
                  <div class="text-gray-500">{{ authStore.user?.email }}</div>
                </div>
                <button @click="logout" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                  Sign out
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Page content -->
      <main class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
          <router-view />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const sidebarOpen = ref(false)
const userMenuOpen = ref(false)
const isDark = ref(false)
const employeesMenuOpen = ref(true)

const pageTitle = computed(() => {
  const routeNames = {
    'Dashboard': 'Dashboard',
    'Employees': 'Employees',
    'CreateEmployee': 'Create Employee',
    'EditEmployee': 'Edit Employee',
    'Shifts': 'Shifts',
    'CreateShift': 'Create Shift',
    'EditShift': 'Edit Shift',
    'Departments': 'Departments',
    'Attendance': 'Attendance',
    'Payroll': 'Payroll'
  }
  return routeNames[route.name] || 'HRIS'
})

const userInitials = computed(() => {
  if (!authStore.user?.name) return 'U'
  return authStore.user.name.split(' ').map(n => n[0]).join('').toUpperCase()
})

function logout() {
  authStore.logout(router)
}

function applyTheme() {
  const root = document.documentElement
  if (isDark.value) {
    root.classList.add('dark')
  } else {
    root.classList.remove('dark')
  }
}

function toggleTheme() {
  isDark.value = !isDark.value
  localStorage.setItem('hris_theme', isDark.value ? 'dark' : 'light')
  applyTheme()
}

function toggleEmployeesMenu() {
  employeesMenuOpen.value = !employeesMenuOpen.value
}

onMounted(() => {
  const stored = localStorage.getItem('hris_theme')
  if (stored === 'dark') {
    isDark.value = true
  } else if (stored === 'light') {
    isDark.value = false
  } else {
    // No preference stored: respect system preference
    isDark.value = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
  }
  applyTheme()
})
</script>


