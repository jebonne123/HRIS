<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <router-view />
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useAuthStore } from './stores/auth'
import { useSetupStore } from './stores/setup'

const authStore = useAuthStore()
const setupStore = useSetupStore()
const initialized = ref(false)

onMounted(async () => {
  // Prevent multiple initializations
  if (initialized.value) {
    console.log('Already initialized, skipping')
    return
  }
  
  console.log('App.vue mounted - checking if initialization is needed')
  initialized.value = true
  
  // Only run initialization if not already authenticated
  if (!authStore.isAuthenticated) {
    console.log('Starting initialization...')
    
    try {
      // Check if user is authenticated
      console.log('Checking authentication...')
      const authResult = await authStore.checkAuth()
      console.log('Auth check result:', authResult)
      
      // Only check setup status if authenticated
      if (authResult) {
        console.log('Checking setup status...')
        const setupResult = await setupStore.checkSetupStatus()
        console.log('Setup check result:', setupResult)
      }
      
      console.log('Initialization complete')
    } catch (error) {
      console.error('Initialization error:', error)
    }
  } else {
    console.log('User already authenticated, skipping initialization')
  }
})
</script>

