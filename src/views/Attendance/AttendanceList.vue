<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Attendance</h1>
      <div class="flex gap-2">
        <button
          @click="timeIn"
          :disabled="loading || isClockedIn"
          class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white px-4 py-2 rounded-lg flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Time In
        </button>
        <button
          @click="timeOut"
          :disabled="loading || !isClockedIn"
          class="bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white px-4 py-2 rounded-lg flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
          Time Out
        </button>
      </div>
    </div>

    <!-- Current Status -->
    <div v-if="currentAttendance" class="bg-white p-4 rounded-lg shadow mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-lg font-medium text-gray-900">Current Session</h3>
          <p class="text-sm text-gray-600">
            Started at {{ formatTime(currentAttendance.time_in) }} 
            <span v-if="currentAttendance.time_out">- Ended at {{ formatTime(currentAttendance.time_out) }}</span>
          </p>
        </div>
        <div class="text-right">
          <div class="text-2xl font-bold text-blue-600">
            {{ formatDuration(currentAttendance.duration) }}
          </div>
          <div class="text-sm text-gray-500">Duration</div>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
          <input
            v-model="filters.start_date"
            type="date"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">To</label>
          <input
            v-model="filters.end_date"
            type="date"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
          <select
            v-model="filters.employee_id"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="">All Employees</option>
            <option v-for="emp in employees" :key="emp.id" :value="emp.id">
              {{ emp.name }}
            </option>
          </select>
        </div>
        <div class="flex items-end">
          <button
            @click="loadAttendance"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
          >
            Search
          </button>
        </div>
      </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="attendance in attendanceRecords" :key="attendance.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-8 w-8">
                    <img
                      v-if="attendance.employee.photo"
                      :src="attendance.employee.photo"
                      :alt="attendance.employee.name"
                      class="h-8 w-8 rounded-full object-cover"
                    />
                    <div
                      v-else
                      class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center"
                    >
                      <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900">{{ attendance.employee.name }}</div>
                    <div class="text-sm text-gray-500">{{ attendance.employee.position }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDate(attendance.date) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatTime(attendance.time_in) }}
                <span v-if="attendance.is_late" class="ml-2 text-xs text-red-600">(Late)</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ attendance.time_out ? formatTime(attendance.time_out) : '-' }}
                <span v-if="attendance.is_early" class="ml-2 text-xs text-red-600">(Early)</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDuration(attendance.duration) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="[
                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                    attendance.status === 'complete' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'
                  ]"
                >
                  {{ attendance.status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <div v-if="attendanceRecords.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No attendance records found</h3>
        <p class="mt-1 text-sm text-gray-500">No attendance data for the selected criteria.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useAuthStore } from '../../stores/auth'
import api from '../../api'

const authStore = useAuthStore()

// Reactive data
const attendanceRecords = ref([])
const employees = ref([])
const currentAttendance = ref(null)
const loading = ref(false)
const filters = ref({
  start_date: '',
  end_date: '',
  employee_id: ''
})

// Computed properties
const isClockedIn = computed(() => currentAttendance.value && !currentAttendance.value.time_out)

// Methods
const loadAttendance = async () => {
  loading.value = true
  try {
    const params = { ...filters.value }
    const response = await api.get('/attendance', { params })
    attendanceRecords.value = response.data.data || response.data
  } catch (error) {
    console.error('Error loading attendance:', error)
  } finally {
    loading.value = false
  }
}

const loadEmployees = async () => {
  try {
    const response = await api.get('/employees')
    employees.value = response.data.data || response.data
  } catch (error) {
    console.error('Error loading employees:', error)
  }
}

const loadCurrentAttendance = async () => {
  try {
    const response = await api.get('/attendance/current')
    currentAttendance.value = response.data
  } catch (error) {
    console.error('Error loading current attendance:', error)
  }
}

const timeIn = async () => {
  loading.value = true
  try {
    const response = await api.post('/attendance/time-in')
    currentAttendance.value = response.data
    await loadAttendance()
  } catch (error) {
    console.error('Error clocking in:', error)
  } finally {
    loading.value = false
  }
}

const timeOut = async () => {
  loading.value = true
  try {
    const response = await api.post('/attendance/time-out')
    currentAttendance.value = response.data
    await loadAttendance()
  } catch (error) {
    console.error('Error clocking out:', error)
  } finally {
    loading.value = false
  }
}

const formatTime = (time) => {
  if (!time) return 'N/A'
  return new Date(time).toLocaleTimeString('en-US', { 
    hour: '2-digit', 
    minute: '2-digit',
    hour12: true 
  })
}

const formatDate = (date) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  })
}

const formatDuration = (minutes) => {
  if (!minutes) return 'N/A'
  const hours = Math.floor(minutes / 60)
  const mins = minutes % 60
  return `${hours}h ${mins}m`
}

// Lifecycle
onMounted(() => {
  const today = new Date()
  filters.value.start_date = today.toISOString().split('T')[0]
  filters.value.end_date = today.toISOString().split('T')[0]
  
  loadCurrentAttendance()
  loadEmployees()
  loadAttendance()
})
</script>



