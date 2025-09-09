import axios from 'axios'
import { API_CONFIG, API_ENDPOINTS, HTTP_METHODS } from './config'

// Create axios instance with global config
const api = axios.create({
  baseURL: API_CONFIG.BASE_URL,
  timeout: API_CONFIG.TIMEOUT,
  headers: API_CONFIG.HEADERS,
  withCredentials: API_CONFIG.WITH_CREDENTIALS,
})

// Request interceptor
api.interceptors.request.use(
  (config) => {
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor
api.interceptors.response.use(
  (response) => {
    return response
  },
  async (error) => {
    const status = error.response?.status
    const originalConfig = error.config || {}

    // Normalize the request URL we are checking
    const url = (originalConfig?.url || '').toString()

    // Avoid infinite loops: only retry once
    if (originalConfig._retriedOnce) {
      return Promise.reject(error)
    }

    // Do not retry auth check endpoint; allow 401 to propagate
    const isAuthCheck = url.includes('/user')

    // If we have a CSRF/unauthenticated issue, try to fetch CSRF cookie then retry once
    // - 419: CSRF token mismatch/expired
    // - 401: unauthenticated (but skip for GET /user to avoid loops)
    if ((status === 419 || (status === 401 && !isAuthCheck)) && !url.includes('sanctum/csrf-cookie')) {
      try {
        await axios.get('/sanctum/csrf-cookie', { withCredentials: true })
        originalConfig._retriedOnce = true
        return api.request(originalConfig)
      } catch (csrfError) {
        console.error('CSRF token fetch failed:', csrfError)
      }
    }

    // Let components handle other errors gracefully
    return Promise.reject(error)
  }
)

// Helper methods for common HTTP operations
export const apiClient = {
  // GET request
  get: (endpoint, config = {}) => api.get(endpoint, config),
  
  // POST request
  post: (endpoint, data = {}, config = {}) => api.post(endpoint, data, config),
  
  // PUT request
  put: (endpoint, data = {}, config = {}) => api.put(endpoint, data, config),
  
  // PATCH request
  patch: (endpoint, data = {}, config = {}) => api.patch(endpoint, data, config),
  
  // DELETE request
  delete: (endpoint, config = {}) => api.delete(endpoint, config),
}

// Function to get CSRF token before making authenticated requests
export const ensureCsrfToken = async () => {
  try {
    await axios.get('/sanctum/csrf-cookie', { withCredentials: true })
    return true
  } catch (error) {
    console.error('Failed to get CSRF token:', error)
    return false
  }
}

// Export the raw axios instance for backward compatibility
export default api

// Export endpoints and methods for easy access
export { API_ENDPOINTS, HTTP_METHODS, API_CONFIG }

