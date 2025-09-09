import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useAuthStore } from './auth'
import { apiClient, API_ENDPOINTS, ensureCsrfToken } from '../api'

export const useSetupStore = defineStore('setup', () => {
  const setupCompleted = ref(false)
  const loading = ref(false)
  const authStore = useAuthStore()

  const needsSetup = computed(() => {
    return authStore.isAdmin && !setupCompleted.value
  })

  async function checkSetupStatus() {
    try {
      // Get CSRF token before making authenticated request
      await ensureCsrfToken()
      
      const response = await apiClient.get(API_ENDPOINTS.SETUP_STATUS)
      setupCompleted.value = response.data.setup_completed
      return response.data
    } catch (error) {
      // Don't log 401 errors as they're expected for unauthenticated users
      if (error.response?.status !== 401) {
        console.error('Setup status check error:', error)
      }
      return null
    }
  }

  async function completeSetup(setupData, router) {
    try {
      loading.value = true
      
      // Get CSRF token before making authenticated request
      await ensureCsrfToken()
      
      const response = await apiClient.post(API_ENDPOINTS.SETUP_COMPLETE, setupData)
      
      setupCompleted.value = true
      
      // Redirect to dashboard after setup
      if (router) {
        router.push('/dashboard')
      }
      
      return response.data
    } catch (error) {
      throw error
    } finally {
      loading.value = false
    }
  }

  return {
    setupCompleted,
    loading,
    needsSetup,
    checkSetupStatus,
    completeSetup
  }
})

