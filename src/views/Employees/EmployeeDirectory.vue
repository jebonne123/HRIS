<template>
  <div>
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Employees Directory</h2>
      <div class="flex items-center gap-2">
        <button @click="showCreate = true" class="px-4 py-2 rounded-full bg-green-600 hover:bg-green-700 text-white">Add Employee</button>
      </div>
    </div>

    <div class="mb-6">
      <div class="flex flex-wrap items-center gap-2">
        <!-- View mode buttons -->
        <div class="flex items-center gap-2 mr-2">
          <button @click="viewMode='table'" :aria-pressed="viewMode==='table'" class="w-9 h-9 rounded-xl border border-gray-200 dark:border-gray-700 bg-white/70 dark:bg-gray-800/70 shadow-sm flex items-center justify-center hover:bg-white dark:hover:bg-gray-700 transition"
            :class="{ 'ring-2 ring-blue-500': viewMode==='table' }" title="Table view">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
          </button>
          <button @click="viewMode='cards'" :aria-pressed="viewMode==='cards'" class="w-9 h-9 rounded-xl border border-gray-200 dark:border-gray-700 bg-white/70 dark:bg-gray-800/70 shadow-sm flex items-center justify-center hover:bg-white dark:hover:bg-gray-700 transition"
            :class="{ 'ring-2 ring-blue-500': viewMode==='cards' }" title="Card view">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h7v7H4V6zm9 0h7v7h-7V6zM4 15h7v7H4v-7zm9 0h7v7h-7v-7z"/></svg>
          </button>
        </div>
        <div class="relative">
          <input v-model="filters.search" @input="onSearchInput" placeholder="Search name or email" class="pl-10 pr-3 py-2 rounded-full bg-white/60 dark:bg-gray-800/70 backdrop-blur border border-gray-200 dark:border-gray-700 text-sm shadow-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50" />
          <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
        </div>

        <Listbox v-model="filters.department_id" @update:modelValue="loadDirectory" as="div" class="relative">
          <ListboxButton class="px-4 py-2 rounded-full bg-white/60 dark:bg-gray-800/70 backdrop-blur border border-gray-200 dark:border-gray-700 text-sm shadow-sm flex items-center gap-2">
            <span>{{ displayLabel(filters.department_id, departments, 'Department') }}</span>
            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </ListboxButton>
          <Transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <ListboxOptions class="absolute z-10 mt-2 w-48 max-h-60 overflow-auto rounded-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg focus:outline-none">
              <ListboxOption :value="''" class="px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">All Departments</ListboxOption>
              <ListboxOption v-for="d in departments" :key="d.id" :value="d.id" class="px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">{{ d.name }}</ListboxOption>
            </ListboxOptions>
          </Transition>
        </Listbox>

        <Listbox v-model="filters.designation_id" @update:modelValue="loadDirectory" as="div" class="relative">
          <ListboxButton class="px-4 py-2 rounded-full bg-white/60 dark:bg-gray-800/70 backdrop-blur border border-gray-200 dark:border-gray-700 text-sm shadow-sm flex items-center gap-2">
            <span>{{ displayLabel(filters.designation_id, designations, 'Designation') }}</span>
            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </ListboxButton>
          <Transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <ListboxOptions class="absolute z-10 mt-2 w-56 max-h-60 overflow-auto rounded-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg focus:outline-none">
              <ListboxOption :value="''" class="px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">All Designations</ListboxOption>
              <ListboxOption v-for="d in designations" :key="d.id" :value="d.id" class="px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">{{ d.name }}</ListboxOption>
            </ListboxOptions>
          </Transition>
        </Listbox>

        <Listbox v-model="filters.employment_status_id" @update:modelValue="loadDirectory" as="div" class="relative">
          <ListboxButton class="px-4 py-2 rounded-full bg-white/60 dark:bg-gray-800/70 backdrop-blur border border-gray-200 dark:border-gray-700 text-sm shadow-sm flex items-center gap-2">
            <span>{{ displayLabel(filters.employment_status_id, statuses, 'Employment Status') }}</span>
            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </ListboxButton>
          <Transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <ListboxOptions class="absolute z-10 mt-2 w-56 max-h-60 overflow-auto rounded-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg focus:outline-none">
              <ListboxOption :value="''" class="px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">All Statuses</ListboxOption>
              <ListboxOption v-for="s in statuses" :key="s.id" :value="s.id" class="px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">{{ s.name }}</ListboxOption>
            </ListboxOptions>
          </Transition>
        </Listbox>

        <Listbox v-model="filters.shift_id" @update:modelValue="loadDirectory" as="div" class="relative">
          <ListboxButton class="px-4 py-2 rounded-full bg-white/60 dark:bg-gray-800/70 backdrop-blur border border-gray-200 dark:border-gray-700 text-sm shadow-sm flex items-center gap-2">
            <span>{{ displayLabel(filters.shift_id, shifts, 'Work Shift') }}</span>
            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </ListboxButton>
          <Transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <ListboxOptions class="absolute z-10 mt-2 w-48 max-h-60 overflow-auto rounded-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg focus:outline-none">
              <ListboxOption :value="''" class="px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">All Shifts</ListboxOption>
              <ListboxOption v-for="s in shifts" :key="s.id" :value="s.id" class="px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">{{ s.name }}</ListboxOption>
            </ListboxOptions>
          </Transition>
        </Listbox>

        <!-- Date range temporarily removed for clarity; can add a daterange popover later -->

        <!-- Date Hired range popover -->
        <div class="relative" @keydown.esc="dateOpen=false">
          <button @click="dateOpen = !dateOpen" class="px-4 py-2 rounded-full bg-white/60 dark:bg-gray-800/70 backdrop-blur border border-gray-200 dark:border-gray-700 text-sm shadow-sm">
            {{ dateLabel }}
          </button>
          <div v-if="dateOpen" class="absolute z-20 mt-2 w-72 p-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg">
            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Date Hired range</div>
            <div class="grid grid-cols-2 gap-2">
              <input type="date" v-model="filters.hired_from" class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm" />
              <input type="date" v-model="filters.hired_to" class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm" />
            </div>
            <div class="mt-3 flex justify-end gap-2">
              <button @click="clearDateRange" class="px-3 py-1.5 rounded-md text-sm bg-gray-100 dark:bg-gray-700">Clear</button>
              <button @click="applyDateRange" class="px-3 py-1.5 rounded-md text-sm bg-blue-600 text-white">Apply</button>
            </div>
          </div>
        </div>

        <button @click="resetFilters" class="px-4 py-2 rounded-full bg-white/60 dark:bg-gray-800/70 backdrop-blur border border-gray-200 dark:border-gray-700 text-sm text-red-600 hover:bg-red-50/60 dark:hover:bg-gray-700">
          Reset
        </button>
        
      </div>
    </div>

    <div v-if="viewMode==='cards'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
      <div v-for="e in employees" :key="e.id" class="relative bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <button class="absolute top-2 right-2 p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
          <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.75a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 7.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 7.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/></svg>
        </button>
        <div class="flex flex-col items-center text-center">
          <div class="relative mb-3">
            <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-700 dark:text-gray-100 font-semibold">{{ initials(e.full_name) }}</div>
            <span class="absolute right-0 bottom-0 block w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-gray-800"></span>
          </div>
          <div class="mb-2">
            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ e.full_name }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ e.designation?.name || e.designation?.title || '—' }}</div>
            <div class="text-[11px] text-gray-400">{{ e.code || 'EMP-' + e.id }}</div>
          </div>
          <span class="inline-flex items-center text-[11px] px-2.5 py-0.5 rounded-full bg-blue-600/10 text-blue-600 dark:text-blue-400 mb-3">{{ e.employment_status?.name || 'N/A' }}</span>
          <div class="text-xs text-gray-600 dark:text-gray-300 space-y-0.5">
            <div>{{ e.department?.name || 'Main Department' }}</div>
            <div>{{ e.primary_shift?.name || e.current_shift?.name || 'Default Day Shift' }}</div>
          </div>
          <router-link :to="'/employees/' + e.id" class="mt-3 text-xs text-blue-600 hover:text-blue-700">View details</router-link>
        </div>
      </div>
    </div>

    <div v-else class="overflow-auto rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
          <tr>
            <th class="text-left px-4 py-2">Employee</th>
            <th class="text-left px-4 py-2">Designation</th>
            <th class="text-left px-4 py-2">Department</th>
            <th class="text-left px-4 py-2">Status</th>
            <th class="text-left px-4 py-2">Shift</th>
            <th class="text-left px-4 py-2"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="e in employees" :key="'row-'+e.id" class="border-t border-gray-200 dark:border-gray-700">
            <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ e.full_name }}</td>
            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ e.designation?.name || '—' }}</td>
            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ e.department?.name || '—' }}</td>
            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ e.employment_status?.name || '—' }}</td>
            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ e.primary_shift?.name || e.current_shift?.name || '—' }}</td>
            <td class="px-4 py-2"><router-link :to="'/employees/' + e.id" class="text-blue-600 hover:text-blue-700">View</router-link></td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <!-- Create Employee Modal -->
    <div v-if="showCreate" class="fixed inset-0 z-40">
      <div class="absolute inset-0 bg-black/50" @click="closeCreate"></div>
      <div class="absolute inset-0 flex items-start justify-center overflow-y-auto p-4">
        <div class="w-full max-w-3xl mt-10 bg-white dark:bg-gray-900 rounded-xl shadow-xl ring-1 ring-black/10 dark:ring-white/10">
          <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Add Employee</h3>
            <button @click="closeCreate" class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800">
              <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
          <div class="p-5">
            <EmployeeForm @saved="onEmployeeSaved" @cancel="closeCreate" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import api from '../../api'
import { Listbox, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue'
import EmployeeForm from './EmployeeForm.vue'

const filters = ref({
  search: '', department_id: '', designation_id: '', employment_status_id: '', shift_id: '', hired_from: '', hired_to: ''
})

const employees = ref([])
const departments = ref([])
const designations = ref([])
const statuses = ref([])
const shifts = ref([])
const showCreate = ref(false)
const viewMode = ref('cards')

async function loadDirectory() {
  try {
    const params = { ...filters.value, with: 'department,designation,employmentStatus,primaryShift', page: 1 }
    console.log('Employees: load with params ->', params)
    const [e, d, g, s, sh] = await Promise.all([
      api.get('/employees', { params }),
      api.get('/departments'),
      api.get('/designations'),
      api.get('/employment-statuses'),
      api.get('/shifts')
    ])
    employees.value = e.data?.data || e.data || []
    departments.value = d.data?.data || d.data || []
    designations.value = g.data?.data || g.data || []
    statuses.value = s.data?.data || s.data || []
    shifts.value = sh.data?.data || sh.data || []
  } catch (err) {
    console.error('Directory init error', err)
  }
}

function resetFilters() {
  filters.value = { search: '', department_id: '', designation_id: '', employment_status_id: '', shift_id: '', hired_from: '', hired_to: '' }
  loadDirectory()
}

let searchTimer = null
function onSearchInput() {
  if (searchTimer) clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    loadDirectory()
  }, 300)
}

onMounted(loadDirectory)

function initials(name) {
  if (!name) return 'NA'
  const parts = String(name).trim().split(/\s+/)
  const letters = parts.slice(0, 2).map(p => p[0] || '').join('')
  return letters.toUpperCase() || 'NA'
}

function displayLabel(id, items, placeholder) {
  if (!id) return placeholder
  const found = items.find(i => String(i.id) === String(id))
  return found?.name || placeholder
}

const dateOpen = ref(false)
const dateLabel = computed(() => {
  if (filters.value.hired_from && filters.value.hired_to) return `${filters.value.hired_from} → ${filters.value.hired_to}`
  if (filters.value.hired_from) return `From ${filters.value.hired_from}`
  if (filters.value.hired_to) return `Until ${filters.value.hired_to}`
  return 'Date Hired'
})

function applyDateRange() {
  dateOpen.value = false
  loadDirectory()
}

function clearDateRange() {
  filters.value.hired_from = ''
  filters.value.hired_to = ''
  dateOpen.value = false
  loadDirectory()
}

function closeCreate() {
  showCreate.value = false
}

function onEmployeeSaved() {
  showCreate.value = false
  loadDirectory()
}

function toggleView() {
  viewMode.value = viewMode.value === 'cards' ? 'table' : 'cards'
}
</script>


