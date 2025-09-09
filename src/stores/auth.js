import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { apiClient, API_ENDPOINTS, ensureCsrfToken } from '../api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const isAuthenticated = ref(false)
  const loading = ref(false)

  const isAdmin = computed(() => user.value?.role === 'admin' || user.value?.roles?.some(r => r.name === 'admin'))
  const isHR = computed(() => user.value?.role === 'hr' || user.value?.roles?.some(r => r.name === 'hr'))
  const isPayroll = computed(() => user.value?.role === 'payroll' || user.value?.roles?.some(r => r.name === 'payroll'))
  const isEmployee = computed(() => user.value?.role === 'employee' || user.value?.roles?.some(r => r.name === 'employee'))

  async function login(credentials) {
    try {
      loading.value = true
      console.log('Attempting login with credentials:', credentials)
      
      // Get CSRF token before login
      await ensureCsrfToken()
      
      const response = await apiClient.post(API_ENDPOINTS.LOGIN, credentials)
      console.log('Login response:', response)
      console.log('Response data:', response.data)
      console.log('User data:', response.data.user)
      
      user.value = response.data.user
      isAuthenticated.value = true
      
      console.log('Store updated - user:', user.value, 'isAuthenticated:', isAuthenticated.value)
      
      return response.data
    } catch (error) {
      console.error('Login error in store:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  async function logout(router) {
    try {
      await apiClient.post(API_ENDPOINTS.LOGOUT)
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      user.value = null
      isAuthenticated.value = false
      if (router) {
        router.push('/login')
      }
    }
  }

  async function checkAuth() {
    try {
      const response = await apiClient.get(API_ENDPOINTS.USER)
      user.value = response.data.user
      isAuthenticated.value = true
      return response.data
    } catch (error) {
      // Don't log 401 errors as they're expected for unauthenticated users
      if (error.response?.status !== 401) {
        console.error('Auth check error:', error)
      }
      user.value = null
      isAuthenticated.value = false
      return null
    }
  }

  function hasPermission(permission) {
    if (!user.value?.roles) return false
    return user.value.roles.some(role => 
      role.permissions?.some(p => p.name === permission)
    )
  }

  return {
    user,
    isAuthenticated,
    loading,
    isAdmin,
    isHR,
    isPayroll,
    isEmployee,
    login,
    logout,
    checkAuth,
    hasPermission
  }
})

