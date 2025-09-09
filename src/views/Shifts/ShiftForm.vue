<template>
  <div class="p-6">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">
        {{ isEditing ? 'Edit Shift' : 'Add New Shift' }}
      </h1>
      <p class="text-gray-600 mt-2">
        {{ isEditing ? 'Update shift configuration' : 'Create a new shift schedule' }}
      </p>
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-6">
      <!-- Basic Information -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Shift Name *</label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'border-red-500': errors.name }"
            />
            <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
          </div>

          <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
            <select
              id="status"
              v-model="form.status"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'border-red-500': errors.status }"
            >
              <option value="">Select Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
            <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status }}</p>
          </div>

          <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea
              id="description"
              v-model="form.description"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'border-red-500': errors.description }"
              placeholder="Optional description of the shift"
            ></textarea>
            <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
          </div>
        </div>
      </div>

      <!-- Time Configuration -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Time Configuration</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
            <input
              id="start_time"
              v-model="form.start_time"
              type="time"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'border-red-500': errors.start_time }"
            />
            <p v-if="errors.start_time" class="mt-1 text-sm text-red-600">{{ errors.start_time }}</p>
          </div>

          <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time *</label>
            <input
              id="end_time"
              v-model="form.end_time"
              type="time"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'border-red-500': errors.end_time }"
            />
            <p v-if="errors.end_time" class="mt-1 text-sm text-red-600">{{ errors.end_time }}</p>
          </div>

          <div>
            <label for="break_duration" class="block text-sm font-medium text-gray-700 mb-2">Break Duration (minutes)</label>
            <input
              id="break_duration"
              v-model="form.break_duration"
              type="number"
              min="0"
              max="120"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'border-red-500': errors.break_duration }"
            />
            <p v-if="errors.break_duration" class="mt-1 text-sm text-red-600">{{ errors.break_duration }}</p>
          </div>

          <div>
            <label for="break_start_time" class="block text-sm font-medium text-gray-700 mb-2">Break Start Time</label>
            <input
              id="break_start_time"
              v-model="form.break_start_time"
              type="time"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'border-red-500': errors.break_start_time }"
            />
            <p v-if="errors.break_start_time" class="mt-1 text-sm text-red-600">{{ errors.break_start_time }}</p>
            <p class="mt-1 text-sm text-gray-500">Leave empty for flexible break time</p>
          </div>
        </div>
      </div>

      <!-- Overtime Rules -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Overtime Rules</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="overtime_threshold" class="block text-sm font-medium text-gray-700 mb-2">Overtime Threshold (hours)</label>
            <input
              id="overtime_threshold"
              v-model="form.overtime_threshold"
              type="number"
              min="0"
              step="0.5"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'border-red-500': errors.overtime_threshold }"
              placeholder="e.g., 8.0"
            />
            <p v-if="errors.overtime_threshold" class="mt-1 text-sm text-red-600">{{ errors.overtime_threshold }}</p>
            <p class="mt-1 text-sm text-gray-500">Hours worked before overtime applies</p>
          </div>

          <div>
            <label for="overtime_rate" class="block text-sm font-medium text-gray-700 mb-2">Overtime Rate Multiplier</label>
            <input
              id="overtime_rate"
              v-model="form.overtime_rate"
              type="number"
              min="1"
              step="0.1"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'border-red-500': errors.overtime_rate }"
              placeholder="e.g., 1.5"
            />
            <p v-if="errors.overtime_rate" class="mt-1 text-sm text-red-600">{{ errors.overtime_rate }}</p>
            <p class="mt-1 text-sm text-gray-500">e.g., 1.5 for time and a half</p>
          </div>

          <div>
            <label for="late_threshold" class="block text-sm font-medium text-gray-700 mb-2">Late Threshold (minutes)</label>
            <input
              id="late_threshold"
              v-model="form.late_threshold"
              type="number"
              min="0"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'border-red-500': errors.late_threshold }"
              placeholder="e.g., 15"
            />
            <p v-if="errors.late_threshold" class="mt-1 text-sm text-red-600">{{ errors.late_threshold }}</p>
            <p class="mt-1 text-sm text-gray-500">Minutes after start time before marked late</p>
          </div>

          <div>
            <label for="early_threshold" class="block text-sm font-medium text-gray-700 mb-2">Early Leave Threshold (minutes)</label>
            <input
              id="early_threshold"
              v-model="form.early_threshold"
              type="number"
              min="0"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'border-red-500': errors.early_threshold }"
              placeholder="e.g., 15"
            />
            <p v-if="errors.early_threshold" class="mt-1 text-sm text-red-600">{{ errors.early_threshold }}</p>
            <p class="mt-1 text-sm text-gray-500">Minutes before end time before marked early</p>
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="flex justify-end space-x-4">
        <router-link
          to="/shifts"
          class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
        >
          Cancel
        </router-link>
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
          <span v-else>{{ isEditing ? 'Update Shift' : 'Create Shift' }}</span>
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

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

// Reactive data
const shift = ref(null)
const loading = ref(false)
const errors = ref({})

// Form data
const form = ref({
  name: '',
  description: '',
  status: '',
  start_time: '',
  end_time: '',
  break_duration: '',
  break_start_time: '',
  overtime_threshold: '',
  overtime_rate: '',
  late_threshold: '',
  early_threshold: ''
})

// Computed properties
const isEditing = computed(() => route.params.id !== undefined)

// Methods
const loadShift = async () => {
  if (!isEditing.value) return
  
  try {
    const response = await api.get(`/shifts/${route.params.id}`)
    shift.value = response.data
    
    // Populate form with existing data
    Object.keys(form.value).forEach(key => {
      if (shift.value[key] !== null && shift.value[key] !== undefined) {
        form.value[key] = shift.value[key]
      }
    })
  } catch (error) {
    console.error('Error loading shift:', error)
    router.push('/shifts')
  }
}

const validateForm = () => {
  errors.value = {}
  
  if (!form.value.name.trim()) {
    errors.value.name = 'Shift name is required'
  }
  
  if (!form.value.status) {
    errors.value.status = 'Status is required'
  }
  
  if (!form.value.start_time) {
    errors.value.start_time = 'Start time is required'
  }
  
  if (!form.value.end_time) {
    errors.value.end_time = 'End time is required'
  }
  
  if (form.value.start_time && form.value.end_time && form.value.start_time >= form.value.end_time) {
    errors.value.end_time = 'End time must be after start time'
  }
  
  if (form.value.break_duration && form.value.break_duration < 0) {
    errors.value.break_duration = 'Break duration cannot be negative'
  }
  
  if (form.value.overtime_threshold && form.value.overtime_threshold < 0) {
    errors.value.overtime_threshold = 'Overtime threshold cannot be negative'
  }
  
  if (form.value.overtime_rate && form.value.overtime_rate < 1) {
    errors.value.overtime_rate = 'Overtime rate must be at least 1.0'
  }
  
  if (form.value.late_threshold && form.value.late_threshold < 0) {
    errors.value.late_threshold = 'Late threshold cannot be negative'
  }
  
  if (form.value.early_threshold && form.value.early_threshold < 0) {
    errors.value.early_threshold = 'Early threshold cannot be negative'
  }
  
  return Object.keys(errors.value).length === 0
}

const handleSubmit = async () => {
  if (!validateForm()) return
  
  loading.value = true
  errors.value = {}
  
  try {
    let response
    if (isEditing.value) {
      response = await api.put(`/shifts/${route.params.id}`, form.value)
    } else {
      response = await api.post('/shifts', form.value)
    }
    
    router.push('/shifts')
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      console.error('Error saving shift:', error)
    }
  } finally {
    loading.value = false
  }
}

// Lifecycle
onMounted(() => {
  loadShift()
})
</script>



