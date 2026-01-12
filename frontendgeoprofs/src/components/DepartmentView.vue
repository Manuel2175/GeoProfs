<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

// Alle users (brondata)
const users = ref([])

// Ingelogde gebruiker
const currentUser = ref(null)

// Actieve filter
const selectedRole = ref('all')

// Loading en error state
const loading = ref(false)
const error = ref('')

// Bepaal of gebruiker admin of HR is
const isAdminOrHR = computed(() => {
  return ['admin', 'hr'].includes(currentUser.value?.role)
})

// Data ophalen bij mount
onMounted(async () => {
  loading.value = true
  error.value = ''

  try {
    currentUser.value = JSON.parse(localStorage.getItem('user'))

    if (currentUser.value?.token) {
      axios.defaults.headers.common['Authorization'] =
        `Bearer ${currentUser.value.token}`
    }

    const response = await axios.get('/user')
    users.value = response.data
  } catch (err) {
    console.error('Fout bij ophalen users:', err)
    error.value =
      err.response?.status === 403
        ? 'Toegang geweigerd'
        : 'Er is iets misgegaan bij het ophalen van gebruikers'
  } finally {
    loading.value = false
  }
})

/**
 * Gefilterde lijst op basis van rol + rechten
 */
const filteredUsers = computed(() => {
  // Geen admin of HR → alleen workers zien
  if (!isAdminOrHR.value) {
    return users.value.filter(u => u.role === 'worker')
  }

  // Admin / HR → normale filtering
  if (selectedRole.value === 'all') return users.value
  return users.value.filter(u => u.role === selectedRole.value)
})
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Gebruikers overzicht</h1>

    <div v-if="error" class="mb-4 text-red-600">{{ error }}</div>
    <div v-if="loading" class="mb-4 text-gray-600">Laden...</div>

    <!-- Filter knoppen -->
    <div class="flex gap-2 mb-6">
      <button
        v-if="isAdminOrHR"
        @click="selectedRole = 'all'"
        :class="selectedRole === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200'"
        class="px-4 py-2 rounded"
      >
        Iedereen
      </button>

      <button
        @click="selectedRole = 'worker'"
        :class="selectedRole === 'worker' ? 'bg-blue-600 text-white' : 'bg-gray-200'"
        class="px-4 py-2 rounded"
      >
        Werkers
      </button>

      <button
        v-if="isAdminOrHR"
        @click="selectedRole = 'admin'"
        :class="selectedRole === 'admin' ? 'bg-blue-600 text-white' : 'bg-gray-200'"
        class="px-4 py-2 rounded"
      >
        Admin
      </button>
    </div>

    <!-- Users tabel -->
    <div class="overflow-x-auto">
      <table class="w-full border-collapse">
        <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Naam</th>
          <th class="p-2 border">Achternaam</th>
          <th class="p-2 border">Verlofsaldo</th>
          <th class="p-2 border">Rol</th>
        </tr>
        </thead>

        <tbody>
        <tr
          v-for="user in filteredUsers"
          :key="user.id"
          class="hover:bg-gray-50"
        >
          <td class="p-2 border">{{ user.id }}</td>
          <td class="p-2 border">{{ user.name }}</td>
          <td class="p-2 border">{{ user.surname }}</td>
          <td class="p-2 border">{{ user.verlofsaldo }}</td>
          <td class="p-2 border">{{ user.role }}</td>
        </tr>

        <tr v-if="!loading && filteredUsers.length === 0">
          <td colspan="5" class="p-4 text-center text-gray-500">
            Geen gebruikers gevonden
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
button {
  transition: background-color 0.2s ease;
}
</style>
