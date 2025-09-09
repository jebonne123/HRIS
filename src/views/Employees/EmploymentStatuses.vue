<template>
  <div>
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-medium">Employment Statuses</h3>
      <button @click="openCreate" class="px-3 py-2 bg-blue-600 text-white rounded">Add Status</button>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded shadow">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium">Name</th>
            <th class="px-4 py-2"/>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="s in statuses" :key="s.id">
            <td class="px-4 py-2">{{ s.name }}</td>
            <td class="px-4 py-2 text-right space-x-2">
              <button @click="edit(s)" class="px-2 py-1 text-sm bg-gray-200 rounded">Edit</button>
              <button @click="remove(s)" class="px-2 py-1 text-sm bg-red-600 text-white rounded">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'

const statuses = ref([])

async function load() {
  try {
    const res = await api.get('/employment-statuses')
    statuses.value = res.data?.data || res.data || []
  } catch (e) { console.error(e) }
}

function openCreate() {}
function edit(row) {}
function remove(row) {}

onMounted(load)
</script>



