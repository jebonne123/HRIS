<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome to HRIS Setup</h1>
        <p class="mt-2 text-lg text-gray-600">Let's configure your system for first use</p>
      </div>

      <div class="bg-white shadow-lg rounded-lg">
        <!-- Progress Steps -->
        <div class="border-b border-gray-200">
          <nav class="flex justify-center">
            <div class="flex space-x-8">
              <div v-for="(step, index) in steps" :key="index" 
                   class="flex items-center py-4">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                         :class="currentStep >= index ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-400'">
                      {{ index + 1 }}
                    </div>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm font-medium"
                       :class="currentStep >= index ? 'text-primary-600' : 'text-gray-500'">
                      {{ step.title }}
                    </p>
                  </div>
                </div>
                <div v-if="index < steps.length - 1" class="ml-8">
                  <div class="w-16 h-0.5"
                       :class="currentStep > index ? 'bg-primary-600' : 'bg-gray-200'"></div>
                </div>
              </div>
            </div>
          </nav>
        </div>

        <!-- Step Content -->
        <div class="p-6">
          <!-- Step 1: Company Info -->
          <div v-if="currentStep === 0">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Company Information</h3>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <label class="form-label">Company Name *</label>
                <input v-model="form.company_name" type="text" class="form-input" required />
              </div>
              <div>
                <label class="form-label">Company Email</label>
                <input v-model="form.company_email" type="email" class="form-input" />
                <p v-if="emailError" class="mt-1 text-sm text-red-600">{{ emailError }}</p>
              </div>
              <div>
                <label class="form-label">Company Phone</label>
                <input v-model="form.company_phone" type="text" class="form-input" />
              </div>
              <div>
                <label class="form-label">Timezone</label>
                <select v-model="form.timezone" class="form-input">
                  <option value="UTC">UTC</option>
                  <option value="America/New_York">Eastern Time</option>
                  <option value="America/Chicago">Central Time</option>
                  <option value="America/Denver">Mountain Time</option>
                  <option value="America/Los_Angeles">Pacific Time</option>
                </select>
              </div>
              <div class="sm:col-span-2">
                <label class="form-label">Company Address</label>
                <textarea v-model="form.company_address" rows="3" class="form-input"></textarea>
              </div>
            </div>
          </div>

          <!-- Step 2: Default Shift -->
          <div v-if="currentStep === 1">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Default Shift Configuration</h3>
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
              <p class="text-sm text-blue-800">
                A default day shift has been pre-configured for you. You can modify these settings or keep the defaults.
              </p>
            </div>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <label class="form-label">Shift Name</label>
                <input v-model="form.shift_name" type="text" class="form-input" />
              </div>
              <div>
                <label class="form-label">Bell Enabled</label>
                <select v-model="form.bell_enabled" class="form-input">
                  <option :value="false">No</option>
                  <option :value="true">Yes</option>
                </select>
              </div>
              <div>
                <label class="form-label">Start Time 1</label>
                <input v-model="form.start_time_1" type="time" class="form-input" />
              </div>
              <div>
                <label class="form-label">End Time 1</label>
                <input v-model="form.end_time_1" type="time" class="form-input" />
              </div>
              <div>
                <label class="form-label">Start Time 2</label>
                <input v-model="form.start_time_2" type="time" class="form-input" />
              </div>
              <div>
                <label class="form-label">End Time 2</label>
                <input v-model="form.end_time_2" type="time" class="form-input" />
              </div>
              <div>
                <label class="form-label">Allow Late (minutes)</label>
                <input v-model="form.allow_late_minutes" type="number" class="form-input" min="0" />
              </div>
              <div>
                <label class="form-label">Allow Early (minutes)</label>
                <input v-model="form.allow_early_minutes" type="number" class="form-input" min="0" />
              </div>
              <div>
                <label class="form-label">Rest Days</label>
                <input v-model="form.rest_days" type="number" class="form-input" min="0" max="7" />
              </div>
              <div>
                <label class="form-label">Enable Overtime</label>
                <select v-model="form.enable_ot" class="form-input" @change="onToggleOvertime">
                  <option :value="true">Yes</option>
                  <option :value="false">No</option>
                </select>
              </div>
              <div>
                <label class="form-label">OT Start (minutes)</label>
                <input v-model="form.ot_start_minutes" type="number" class="form-input" min="0" />
              </div>
              <div>
                <label class="form-label">OT Valid (minutes)</label>
                <input v-model="form.ot_valid_minutes" type="number" class="form-input" min="1" />
              </div>
            </div>
          </div>

          <!-- Step 3: Review & Complete -->
          <div v-if="currentStep === 2">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Review & Complete Setup</h3>
            <div class="bg-gray-50 rounded-lg p-4">
              <h4 class="font-medium text-gray-900 mb-3">Company Information</h4>
              <dl class="grid grid-cols-1 gap-2 text-sm">
                <div class="flex justify-between">
                  <dt class="text-gray-600">Company Name:</dt>
                  <dd class="font-medium">{{ form.company_name }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-gray-600">Email:</dt>
                  <dd class="font-medium">{{ form.company_email || 'Not specified' }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-gray-600">Phone:</dt>
                  <dd class="font-medium">{{ form.company_phone || 'Not specified' }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-gray-600">Timezone:</dt>
                  <dd class="font-medium">{{ form.timezone }}</dd>
                </div>
              </dl>
              
              <h4 class="font-medium text-gray-900 mb-3 mt-4">Default Shift</h4>
              <dl class="grid grid-cols-1 gap-2 text-sm">
                <div class="flex justify-between">
                  <dt class="text-gray-600">Shift Name:</dt>
                  <dd class="font-medium">{{ form.shift_name }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-gray-600">Schedule:</dt>
                  <dd class="font-medium">{{ form.start_time_1 }} - {{ form.end_time_1 }}, {{ form.start_time_2 }} - {{ form.end_time_2 }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-gray-600">Overtime:</dt>
                  <dd class="font-medium">{{ form.enable_ot ? 'Enabled' : 'Disabled' }}</dd>
                </div>
              </dl>
            </div>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="border-t border-gray-200 px-6 py-4 flex justify-between">
          <button v-if="currentStep > 0" 
                  @click="previousStep"
                  type="button"
                  class="btn btn-secondary">
            Previous
          </button>
          <div v-else></div>
          
          <button v-if="currentStep < steps.length - 1" 
                  @click="nextStep"
                  type="button"
                  class="btn btn-primary">
            Next
          </button>
          
          <button v-if="currentStep === steps.length - 1" 
                  @click="completeSetup"
                  :disabled="setupStore.loading"
                  type="button"
                  class="btn btn-primary">
            {{ setupStore.loading ? 'Completing...' : 'Complete Setup' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useSetupStore } from '../stores/setup'
import api from '../api'

const setupStore = useSetupStore()
const router = useRouter()

const currentStep = ref(0)
const steps = [
  { title: 'Company Info' },
  { title: 'Default Shift' },
  { title: 'Review & Complete' }
]

const form = ref({
  company_name: '',
  company_email: '',
  company_phone: '',
  company_address: '',
  timezone: 'UTC',
  shift_name: 'Default Day Shift',
  start_time_1: '08:00',
  end_time_1: '12:00',
  start_time_2: '13:00',
  end_time_2: '17:00',
  bell_enabled: false,
  allow_late_minutes: 0,
  allow_early_minutes: 0,
  rest_days: 2,
  enable_ot: true,
  ot_start_minutes: 60,
  ot_valid_minutes: 30
})

const emailError = ref('')

function validateStep1() {
  emailError.value = ''
  const email = (form.value.company_email || '').trim()
  if (email.length > 0) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailRegex.test(email)) {
      emailError.value = 'Please enter a valid email address'
      return false
    }
  }
  return true
}

function onToggleOvertime() {
  if (form.value.enable_ot === false) {
    // When OT disabled, null out OT fields to avoid backend validation
    form.value.ot_start_minutes = null
    form.value.ot_valid_minutes = null
  } else {
    // Restore sensible defaults when re-enabled
    if (form.value.ot_start_minutes == null) form.value.ot_start_minutes = 60
    if (form.value.ot_valid_minutes == null) form.value.ot_valid_minutes = 30
  }
}

onMounted(async () => {
  // Check if shifts already exist
  try {
    const response = await api.get('/shifts')
    if (response.data.length > 0) {
      // Update form with existing shift data
      const shift = response.data[0]
      form.value.shift_name = shift.name
      form.value.start_time_1 = shift.start_time_1
      form.value.end_time_1 = shift.end_time_1
      form.value.start_time_2 = shift.start_time_2
      form.value.end_time_2 = shift.end_time_2
      form.value.bell_enabled = shift.bell_enabled
      form.value.allow_late_minutes = shift.allow_late_minutes
      form.value.allow_early_minutes = shift.allow_early_minutes
      form.value.rest_days = shift.rest_days
      form.value.enable_ot = shift.enable_ot
      form.value.ot_start_minutes = shift.ot_start_minutes
      form.value.ot_valid_minutes = shift.ot_valid_minutes
    }
  } catch (error) {
    console.error('Error fetching shifts:', error)
  }
})

function nextStep() {
  if (currentStep.value === 0 && !validateStep1()) {
    return
  }
  if (currentStep.value < steps.length - 1) {
    currentStep.value++
  }
}

function previousStep() {
  if (currentStep.value > 0) {
    currentStep.value--
  }
}

async function completeSetup() {
  try {
    // Validate email before proceeding
    if (!validateStep1()) {
      return
    }
    await setupStore.completeSetup({
      company_name: form.value.company_name,
      company_email: form.value.company_email,
      company_phone: form.value.company_phone,
      company_address: form.value.company_address,
      timezone: form.value.timezone,
      // Include optional default shift payload
      shift_name: form.value.shift_name,
      start_time_1: form.value.start_time_1,
      end_time_1: form.value.end_time_1,
      start_time_2: form.value.start_time_2,
      end_time_2: form.value.end_time_2,
      bell_enabled: form.value.bell_enabled,
      allow_late_minutes: form.value.allow_late_minutes,
      allow_early_minutes: form.value.allow_early_minutes,
      rest_days: form.value.rest_days,
      enable_ot: form.value.enable_ot,
      ot_start_minutes: form.value.ot_start_minutes,
      ot_valid_minutes: form.value.ot_valid_minutes
    }, router)
  } catch (error) {
    console.error('Setup completion error:', error)
    alert('An error occurred while completing setup. Please try again.')
  }
}
</script>


