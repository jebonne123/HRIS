<template>
  <div>
    <!-- Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 rounded-md flex items-center justify-center bg-blue-500">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A3 3 0 017 17h10a3 3 0 011.879.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300 truncate">Total Employees</dt>
                <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ cards.total_employees || 0 }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 rounded-md flex items-center justify-center bg-indigo-500">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300 truncate">Total departments</dt>
                <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ cards.total_departments || 0 }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 rounded-md flex items-center justify-center bg-sky-500">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300 truncate">Total leave requests</dt>
                <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ cards.total_leave_requests || 0 }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 rounded-md flex items-center justify-center bg-emerald-500">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5" />
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300 truncate">On leave today</dt>
                <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ cards.on_leave_today || 0 }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
      <!-- Employee Statistics -->
      <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow rounded-lg min-h-[500px]">
        <div class="px-6 py-6 sm:p-8">
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">Employee Statistics</h3>

          <div class="flex items-center space-x-6 text-sm mb-4">
            <button @click="activeTab = 'status'" :class="tabClass('status')">By employment status</button>
            <button @click="activeTab = 'department'" :class="tabClass('department')">By Department</button>
          </div>

          <div>
            <template v-if="hasData">
              <div v-for="row in currentData" :key="row.label" class="py-3">
                <div class="flex items-center justify-between mb-2">
                  <span class="text-sm text-gray-600 dark:text-gray-300">{{ row.label }}</span>
                  <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ row.count }}</span>
                </div>
                <div class="w-full h-3 bg-gray-200 dark:bg-gray-700 rounded relative overflow-visible">
                  <!-- Bar -->
                  <div class="h-3 bg-blue-500 rounded" :style="{ width: barWidth(row.count) }"></div>
                </div>
              </div>

              <!-- X Axis with ticks -->
              <div class="mt-6">
                <div class="relative">
                  <!-- Axis line -->
                  <div class="h-px bg-gray-300 dark:bg-gray-600 w-full"></div>
                  <!-- Tick marks -->
                  <div class="absolute inset-0 flex justify-between" aria-hidden="true">
                    <span v-for="tick in ticks" :key="'tick-'+tick" class="h-2 w-px bg-gray-300 dark:bg-gray-600 translate-y-[-3px]"></span>
                  </div>
                </div>
                <!-- Tick labels -->
                <div class="mt-1 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                  <span v-for="tick in ticks" :key="'label-'+tick">{{ tick }}</span>
                </div>
              </div>
            </template>
            <div v-else class="text-sm text-gray-500 dark:text-gray-400">No data to display.</div>
          </div>
        </div>
      </div>

      <!-- Total Attendance Today -->
      <div class="bg-white dark:bg-gray-800 shadow rounded-lg min-h-[360px]">
        <div class="px-6 py-6 sm:p-8">
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">Total Attendance Today - {{ attendance.total }}</h3>
          <ul class="space-y-3 text-sm">
            <li class="flex items-center">
              <span class="inline-block w-2 h-2 rounded-sm mr-2" style="background:#F97316"></span> Early - {{ attendance.early }}
            </li>
            <li class="flex items-center">
              <span class="inline-block w-2 h-2 rounded-sm mr-2" style="background:#EF4444"></span> Late - {{ attendance.late }}
            </li>
            <li class="flex items-center">
              <span class="inline-block w-2 h-2 rounded-sm mr-2" style="background:#22C55E"></span> Regular - {{ attendance.regular }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import api from '../api'

const data = ref({ cards: {}, employee_stats: { by_status: [], by_department: [] }, attendance_today: {} })
const activeTab = ref('status')

const cards = computed(() => data.value.cards || {})
const employeeStats = computed(() => data.value.employee_stats || { by_status: [], by_department: [] })
const attendance = computed(() => data.value.attendance_today || { total: 0, early: 0, late: 0, regular: 0 })

const currentData = computed(() => activeTab.value === 'status' ? employeeStats.value.by_status : employeeStats.value.by_department)
const hasData = computed(() => Array.isArray(currentData.value) && currentData.value.length > 0)

onMounted(async () => {
  const response = await api.get('/dashboard')
  data.value = response.data
})

function tabClass(key) {
  return [
    'px-2 py-1 rounded-md',
    activeTab.value === key
      ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-100'
      : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'
  ]
}

// Axis helpers (default to 0-20 but scale up nicely)
const axisMax = computed(() => {
  const values = currentData.value.map(r => Number(r.count) || 0)
  const rawMax = Math.max(0, ...values)
  const base = 20
  if (rawMax <= base) return base
  // Round up to a "nice" number (nearest 10, 20, 25, 50, 100 etc.)
  const magnitude = Math.pow(10, Math.floor(Math.log10(rawMax)))
  const normalized = rawMax / magnitude
  let nice;
  if (normalized <= 1) nice = 1
  else if (normalized <= 2) nice = 2
  else if (normalized <= 2.5) nice = 2.5
  else if (normalized <= 5) nice = 5
  else nice = 10
  return Math.ceil(nice * magnitude)
})

const tickStep = computed(() => {
  const max = axisMax.value
  if (max <= 20) return 5
  if (max <= 50) return 10
  if (max <= 100) return 20
  if (max <= 250) return 50
  if (max <= 500) return 100
  return Math.ceil(max / 10)
})

const ticks = computed(() => {
  const max = axisMax.value
  const step = tickStep.value
  const vals = []
  for (let v = 0; v <= max; v += step) vals.push(v)
  if (vals[vals.length - 1] !== max) vals.push(max)
  return vals
})

function barWidth(count) {
  const max = axisMax.value || 1
  const pct = Math.min(100, Math.round((Number(count) || 0) / max * 100))
  return pct + '%'
}

// (no JSX used)
</script>


