// Global API Configuration
export const API_CONFIG = {
  BASE_URL: '/api',
  TIMEOUT: 30000,
  HEADERS: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  WITH_CREDENTIALS: true,
}

// API Endpoints
export const API_ENDPOINTS = {
  // Auth
  LOGIN: '/login',
  LOGOUT: '/logout',
  USER: '/user',
  
  // Setup
  SETUP_STATUS: '/setup/status',
  SETUP_COMPLETE: '/setup/complete',
  
  // Dashboard
  DASHBOARD: '/dashboard',
  
  // Employees
  EMPLOYEES: '/employees',
  EMPLOYEE: (id) => `/employees/${id}`,
  
  // Shifts
  SHIFTS: '/shifts',
  SHIFT: (id) => `/shifts/${id}`,
  
  // Departments
  DEPARTMENTS: '/departments',
  DEPARTMENT: (id) => `/departments/${id}`,
  
  // Attendance
  ATTENDANCE: '/attendance',
  ATTENDANCE_RECORD: (id) => `/attendance/${id}`,
  
  // Payroll
  PAYROLL: '/payroll',
  PAYROLL_RECORD: (id) => `/payroll/${id}`,
}

// HTTP Methods
export const HTTP_METHODS = {
  GET: 'GET',
  POST: 'POST',
  PUT: 'PUT',
  PATCH: 'PATCH',
  DELETE: 'DELETE',
}



