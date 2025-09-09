<template>
  <div class="p-6 text-gray-900 dark:text-gray-100">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
        {{ isEditing ? 'Edit Employee' : 'Add New Employee' }}
      </h1>
      <p class="text-gray-600 dark:text-gray-400 mt-2">
        {{ isEditing ? 'Update employee information' : 'Create a new employee record' }}
      </p>
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-6">
      <!-- Basic Information -->
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">First Name *</label>
            <input
              id="first_name"
              v-model="form.first_name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
              :class="{ 'border-red-500': errors.first_name }"
            />
            <p v-if="errors.first_name" class="mt-1 text-sm text-red-600">{{ errors.first_name }}</p>
          </div>

          <div>
            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Last Name *</label>
            <input
              id="last_name"
              v-model="form.last_name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
              :class="{ 'border-red-500': errors.last_name }"
            />
            <p v-if="errors.last_name" class="mt-1 text-sm text-red-600">{{ errors.last_name }}</p>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
              :class="{ 'border-red-500': errors.email }"
            />
            <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
          </div>

          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
            <input
              id="phone"
              v-model="form.phone"
              type="tel"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
              :class="{ 'border-red-500': errors.phone }"
            />
            <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</p>
          </div>

          <div>
            <label for="designation_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Designation *</label>
            <div class="relative">
              <select
                id="designation_id"
                v-model="form.designation_id"
                required
                class="w-full appearance-none pr-10 px-3 py-2 rounded-lg bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-100"
                :class="{ 'border-red-500': errors.designation_id }"
              >
                <option value="">Select Designation</option>
                <option v-for="d in designations" :key="d.id" :value="d.id">{{ d.name }}</option>
              </select>
              <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
            <p v-if="errors.designation_id" class="mt-1 text-sm text-red-600">{{ errors.designation_id }}</p>
          </div>

          <div>
            <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department *</label>
            <div class="relative">
              <select
                id="department_id"
                v-model="form.department_id"
                required
                class="w-full appearance-none pr-10 px-3 py-2 rounded-lg bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-100"
                :class="{ 'border-red-500': errors.department_id }"
              >
                <option value="">Select Department</option>
                <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                  {{ dept.name }}
                </option>
              </select>
              <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
            <p v-if="errors.department_id" class="mt-1 text-sm text-red-600">{{ errors.department_id }}</p>
          </div>

          

          <div>
            <label for="employment_status_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Employment Status *</label>
            <div class="relative">
              <select
                id="employment_status_id"
                v-model="form.employment_status_id"
                required
                class="w-full appearance-none pr-10 px-3 py-2 rounded-lg bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-100"
                :class="{ 'border-red-500': errors.employment_status_id }"
              >
                <option value="">Select Employment Status</option>
                <option v-for="s in filteredEmploymentStatuses" :key="s.id" :value="s.id">{{ s.name }}</option>
              </select>
              <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
            <p v-if="errors.employment_status_id" class="mt-1 text-sm text-red-600">{{ errors.employment_status_id }}</p>
          </div>
        </div>
      </div>

      <!-- Employment Details -->
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Employment Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="hire_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hire Date *</label>
            <input
              id="hire_date"
              v-model="form.hire_date"
              type="date"
              required
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-900 dark:text-gray-100"
              :class="{ 'border-red-500': errors.hire_date }"
            />
            <p v-if="errors.hire_date" class="mt-1 text-sm text-red-600">{{ errors.hire_date }}</p>
          </div>

          <div>
            <label for="salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Base Salary</label>
            <input
              id="salary"
              v-model="form.base_salary"
              type="number"
              step="0.01"
              min="0"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-900 dark:text-gray-100"
              :class="{ 'border-red-500': errors.base_salary }"
            />
            <p v-if="errors.base_salary" class="mt-1 text-sm text-red-600">{{ errors.base_salary }}</p>
          </div>

          <div>
            <label for="shift_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Default Shift</label>
            <div class="relative">
              <select
                id="shift_id"
                v-model="form.shift_id"
                class="w-full appearance-none pr-10 px-3 py-2 rounded-lg bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-gray-100"
                :class="{ 'border-red-500': errors.shift_id }"
              >
                <option value="">Select Shift</option>
                <option v-for="shift in shifts" :key="shift.id" :value="shift.id">
                  {{ shift.name }}
                </option>
              </select>
              <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
            <p v-if="errors.shift_id" class="mt-1 text-sm text-red-600">{{ errors.shift_id }}</p>
          </div>
        </div>
      </div>

      <!-- Security & Access -->
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Security & Access</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ isEditing ? 'New Password (leave blank to keep current)' : 'Password *' }}
            </label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              :required="!isEditing"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-900 dark:text-gray-100"
              :class="{ 'border-red-500': errors.password }"
            />
            <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ isEditing ? 'Confirm New Password' : 'Confirm Password *' }}
            </label>
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              :required="!isEditing"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-900 dark:text-gray-100"
              :class="{ 'border-red-500': errors.password_confirmation }"
            />
            <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600">{{ errors.password_confirmation }}</p>
          </div>

          <div>
            <label for="fingerprint_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fingerprint ID</label>
            <input
              id="fingerprint_id"
              v-model="form.fingerprint_id"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-900 dark:text-gray-100"
              :class="{ 'border-red-500': errors.fingerprint_id }"
            />
            <p v-if="errors.fingerprint_id" class="mt-1 text-sm text-red-600">{{ errors.fingerprint_id }}</p>
          </div>

          <div>
            <label for="card_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Card ID</label>
            <input
              id="card_id"
              v-model="form.card_id"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-900 dark:text-gray-100"
              :class="{ 'border-red-500': errors.card_id }"
            />
            <p v-if="errors.card_id" class="mt-1 text-sm text-red-600">{{ errors.card_id }}</p>
          </div>
        </div>
      </div>

      <!-- Photo Upload -->
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Employee Photo</h3>
        <div class="flex items-center space-x-6">
          <div class="flex-shrink-0">
            <div v-if="photoPreview || (employee && employee.photo)" class="relative">
              <img
                :src="photoPreview || employee.photo"
                alt="Employee photo"
                class="h-24 w-24 rounded-full object-cover"
              />
              <button
                v-if="photoPreview"
                @click="removePhoto"
                type="button"
                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
            <div
              v-else
              class="h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center"
            >
              <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
          </div>
          <div>
            <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Photo</label>
            <input
              id="photo"
              ref="photoInput"
              type="file"
              accept="image/*"
              @change="handlePhotoChange"
              class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
            />
            <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 2MB</p>
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="flex justify-end space-x-4">
        <button type="button" @click="emit('cancel')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
          Cancel
        </button>
        <button
          type="submit"
          :disabled="loading"
          class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="loading" class="flex items-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ isEditing ? 'Updating...' : 'Creating...' }}
          </span>
          <span v-else>{{ isEditing ? 'Update Employee' : 'Create Employee' }}</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import api from '../../api'

const emit = defineEmits(['saved', 'cancel'])

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

// Reactive data
const employee = ref(null)
const departments = ref([])
const designations = ref([])
const employmentStatuses = ref([])
const shifts = ref([])
const loading = ref(false)
const errors = ref({})
const photoPreview = ref(null)
const photoInput = ref(null)

// Form data
const form = ref({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  department_id: '',
  designation_id: '',
  status: 'active',
  employment_status_id: '',
  hire_date: '',
  salary: '',
  shift_id: '',
  password: '',
  password_confirmation: '',
  fingerprint_id: '',
  card_id: '',
  photo: null
})

// Computed properties
const isEditing = computed(() => route.params.id !== undefined)
const filteredEmploymentStatuses = computed(() => {
  // Hide "Terminated" from creation; allow while editing existing records
  return employmentStatuses.value.filter(s => s.name !== 'Terminated')
})

// Methods
const loadEmployee = async () => {
  if (!isEditing.value) return
  
  try {
    const response = await api.get(`/employees/${route.params.id}`)
    employee.value = response.data
    
    // Populate form with existing data
    Object.keys(form.value).forEach(key => {
      if (key !== 'password' && key !== 'password_confirmation' && key !== 'photo') {
        form.value[key] = employee.value[key] || ''
      }
    })
  } catch (error) {
    console.error('Error loading employee:', error)
    router.push('/employees')
  }
}

const loadDepartments = async () => {
  try {
    const response = await api.get('/departments')
    departments.value = response.data
  } catch (error) {
    console.error('Error loading departments:', error)
  }
}

const loadShifts = async () => {
  try {
    const response = await api.get('/shifts')
    shifts.value = response.data
  } catch (error) {
    console.error('Error loading shifts:', error)
  }
}

const loadDesignations = async () => {
  try {
    const response = await api.get('/designations')
    designations.value = response.data
  } catch (error) {
    console.error('Error loading designations:', error)
  }
}

const loadEmploymentStatuses = async () => {
  try {
    const response = await api.get('/employment-statuses')
    employmentStatuses.value = response.data
  } catch (error) {
    console.error('Error loading employment statuses:', error)
  }
}

const handlePhotoChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    if (file.size > 2 * 1024 * 1024) { // 2MB limit
      alert('Photo size must be less than 2MB')
      event.target.value = ''
      return
    }
    
    form.value.photo = file
    const reader = new FileReader()
    reader.onload = (e) => {
      photoPreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const removePhoto = () => {
  photoPreview.value = null
  form.value.photo = null
  if (photoInput.value) {
    photoInput.value.value = ''
  }
}

const validateForm = () => {
  errors.value = {}
  
  if (!form.value.first_name.trim()) {
    errors.value.first_name = 'First name is required'
  }
  if (!form.value.last_name.trim()) {
    errors.value.last_name = 'Last name is required'
  }
  
  if (!form.value.email.trim()) {
    errors.value.email = 'Email is required'
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
    errors.value.email = 'Please enter a valid email'
  }
  
  if (!form.value.designation_id) {
    errors.value.designation_id = 'Designation is required'
  }
  
  if (!form.value.department_id) {
    errors.value.department_id = 'Department is required'
  }
  if (!form.value.employment_status_id) {
    errors.value.employment_status_id = 'Employment status is required'
  }
  
  // status defaults to active; no user input required
  
  if (!form.value.hire_date) {
    errors.value.hire_date = 'Hire date is required'
  }
  
  if (!isEditing.value && !form.value.password) {
    errors.value.password = 'Password is required'
  }
  
  if (form.value.password && form.value.password !== form.value.password_confirmation) {
    errors.value.password_confirmation = 'Passwords do not match'
  }
  
  return Object.keys(errors.value).length === 0
}

const handleSubmit = async () => {
  if (!validateForm()) return
  
  loading.value = true
  errors.value = {}
  
  try {
    const formData = new FormData()
    
    // Add form fields to FormData
    const payload = { ...form.value }
    // Ensure full_name is sent for backend convenience
    payload.full_name = `${form.value.first_name} ${form.value.last_name}`.trim()
    Object.keys(payload).forEach(key => {
      if (payload[key] !== null && payload[key] !== '') {
        formData.append(key, payload[key])
      }
    })
    
    let response
    if (isEditing.value) {
      response = await api.post(`/employees/${route.params.id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
    } else {
      response = await api.post('/employees', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
    }
    emit('saved', response.data)
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      console.error('Error saving employee:', error)
    }
  } finally {
    loading.value = false
  }
}

// Lifecycle
onMounted(() => {
  loadDepartments()
  loadShifts()
  loadDesignations()
  loadEmploymentStatuses()
  loadEmployee()
})
</script>


