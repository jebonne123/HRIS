import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useSetupStore } from '../stores/setup'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue'),
    meta: { requiresGuest: true }
  },
  {
    path: '/setup',
    name: 'Setup',
    component: () => import('../views/SetupWizard.vue'),
    meta: { requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/',
    component: () => import('../layouts/AppLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        redirect: '/dashboard'
      },
      {
        path: 'dashboard',
        name: 'Dashboard',
        component: () => import('../views/Dashboard.vue')
      },
      {
        path: 'employees/directory',
        name: 'EmployeeDirectory',
        component: () => import('../views/Employees/EmployeeDirectory.vue'),
        meta: { requiresPermission: 'view_employees' }
      },
      {
        path: 'employees/departments',
        name: 'EmployeeDepartments',
        component: () => import('../views/Employees/EmployeeDepartments.vue'),
        meta: { requiresPermission: 'view_departments' }
      },
      {
        path: 'employees/designations',
        name: 'EmployeeDesignations',
        component: () => import('../views/Employees/EmployeeDesignations.vue'),
        meta: { requiresPermission: 'view_employees' }
      },
      {
        path: 'employees/statuses',
        name: 'EmploymentStatuses',
        component: () => import('../views/Employees/EmploymentStatuses.vue'),
        meta: { requiresPermission: 'view_employees' }
      },
      {
        path: 'employees/create',
        name: 'CreateEmployee',
        component: () => import('../views/Employees/EmployeeForm.vue'),
        meta: { requiresPermission: 'create_employees' }
      },
      {
        path: 'employees/:id',
        name: 'ShowEmployee',
        component: () => import('../views/Employees/EmployeeForm.vue'),
        meta: { requiresPermission: 'view_employees' }
      },
      {
        path: 'employees/:id/edit',
        name: 'EditEmployee',
        component: () => import('../views/Employees/EmployeeForm.vue'),
        meta: { requiresPermission: 'edit_employees' }
      },
      {
        path: 'shifts',
        name: 'Shifts',
        component: () => import('../views/Shifts/ShiftList.vue'),
        meta: { requiresPermission: 'view_shifts' }
      },
      {
        path: 'shifts/create',
        name: 'CreateShift',
        component: () => import('../views/Shifts/ShiftForm.vue'),
        meta: { requiresPermission: 'create_shifts' }
      },
      {
        path: 'shifts/:id/edit',
        name: 'EditShift',
        component: () => import('../views/Shifts/ShiftForm.vue'),
        meta: { requiresPermission: 'edit_shifts' }
      },
      {
        path: 'departments',
        name: 'Departments',
        component: () => import('../views/Departments/DepartmentList.vue'),
        meta: { requiresPermission: 'view_departments' }
      },
      {
        path: 'attendance',
        name: 'Attendance',
        component: () => import('../views/Attendance/AttendanceList.vue'),
        meta: { requiresPermission: 'view_attendance' }
      },
      {
        path: 'payroll',
        name: 'Payroll',
        component: () => import('../views/Payroll/PayrollList.vue'),
        meta: { requiresPermission: 'view_payroll' }
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, from, next) => {
  console.log('Router guard - navigating to:', to.path, 'from:', from.path)
  
  const authStore = useAuthStore()
  const setupStore = useSetupStore()

  console.log('Auth store state:', {
    isAuthenticated: authStore.isAuthenticated,
    user: authStore.user,
    isAdmin: authStore.isAdmin
  })
  console.log('Setup store state:', {
    setupCompleted: setupStore.setupCompleted
  })

  // Check authentication (await server on hard refresh)
  if (to.meta.requiresAuth) {
    if (!authStore.isAuthenticated) {
      try {
        const res = await authStore.checkAuth()
        if (!res) {
          console.log('Redirecting to login - not authenticated after check')
          next('/login')
          return
        }
      } catch (e) {
        console.log('Auth check failed, redirecting to login')
        next('/login')
        return
      }
    }
  }

  // Check if guest is trying to access authenticated routes
  if (to.meta.requiresGuest) {
    if (authStore.isAuthenticated) {
      console.log('Redirecting to dashboard - already authenticated')
      next('/dashboard')
      return
    }
    // If not marked authenticated, verify with server once to allow persisted sessions
    try {
      const res = await authStore.checkAuth()
      if (res) {
        next('/dashboard')
        return
      }
    } catch (e) {
      // ignore, will stay on login
    }
  }

  // Check setup completion for admin users; verify from server on first load
  if (authStore.isAdmin && to.path !== '/setup') {
    if (!setupStore.setupCompleted) {
      try {
        const res = await setupStore.checkSetupStatus()
        if (res && !res.setup_completed) {
          console.log('Redirecting to setup - admin needs setup (server says incomplete)')
          next('/setup')
          return
        }
      } catch (e) {
        console.log('Setup check failed; proceed without redirect')
      }
    }
  }

  // Check permissions
  if (to.meta.requiresPermission && !(authStore.isAdmin || authStore.hasPermission(to.meta.requiresPermission))) {
    console.log('Redirecting to dashboard - missing permission:', to.meta.requiresPermission)
    next('/dashboard')
    return
  }

  // Check admin requirement
  if (to.meta.requiresAdmin && !authStore.isAdmin) {
    console.log('Redirecting to dashboard - requires admin but not admin')
    next('/dashboard')
    return
  }

  console.log('Navigation allowed to:', to.path)
  next()
})

export default router

