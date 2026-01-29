<template>
  <div class="p-6 font-[Inter]">
    <h1 class="text-2xl font-bold text-[#0E3A5B] mb-4">Verlofaanvragen medewerkers</h1>

    <div v-if="loading" class="text-gray-500">Verlofaanvragen worden geladen...</div>
    <div v-else-if="error" class="text-red-600">{{ error }}</div>

    <ul v-else class="space-y-2">
      <li
        v-for="verlof in verloven"
        :key="verlof.id"
        class="border border-gray-200 rounded-lg p-3 flex justify-between items-center">
        <div>
          <p><strong>Naam:</strong> {{ verlof.user.name }}</p>
          <p><strong>Periode:</strong> {{ verlof.startdatum }} – {{ verlof.einddatum }}</p>
          <p><strong>Status:</strong> {{ verlof.status }}</p>
          <p v-if="verlof.reden"><strong>Reden:</strong> {{ verlof.reden }}</p>
        </div>

        <div class="flex flex-col space-y-2">
          <div class="flex space-x-2">
            <button
              @click="approveVerlof(verlof)"
              class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
              Approve
            </button>
            <button
              @click="denyVerlof(verlof)"
              class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
              Deny
            </button>
          </div>
          <input
            type="text"
            placeholder="Reden voor afkeur"
            v-model="afkeurReden[verlof.id]"
            class="border border-gray-300 rounded-lg px-2 py-1 w-full"
          />
        </div>
      </li>
    </ul>

    <div v-if="!loading && verloven.length === 0 && !error" class="text-gray-500 mt-3">
      Geen verlofaanvragen gevonden.
    </div>
  </div>
</template>

<script setup>
import {ref, onMounted} from 'vue';
import AuthService from "@/services/AuthService.js";
import VerlofService from "@/services/VerlofService.js";
import {ensureCsrfToken} from "@/services/axios-config.js";

const verloven = ref([]);
const loading = ref(true);
const error = ref('');
const afkeurReden = ref({});

const haalVerlovenOp = async () => {
  try {
    loading.value = true;
    await ensureCsrfToken();

    const alleVerloven = await VerlofService.getVerloven();

    // Filter alleen de aanvragen met status 'aangevraagd'
    verloven.value = alleVerloven.filter(verlof => verlof.status === 'aangevraagd');

  } catch (err) {
    console.error('Fout bij ophalen verloven:', err);
    error.value = err.response?.data?.message || 'Kon verlofaanvragen niet ophalen.';
  } finally {
    loading.value = false;
  }
};

onMounted(haalVerlovenOp);

const approveVerlof = async (verlof) => {
  try {
    loading.value = true;
    await ensureCsrfToken();

    await VerlofService.approve(verlof.user_id, verlof.id);

    alert('Verlof goedgekeurd!');
    await haalVerlovenOp();
  } catch (err) {
    console.error('Fout bij goedkeuren:', err);
    alert('Kon verlof niet goedkeuren.');
  } finally {
    loading.value = false;
  }
};

const denyVerlof = async (verlof) => {
  try {
    loading.value = true;
    await ensureCsrfToken();

    const reden = afkeurReden.value[verlof.id] || 'Geen reden opgegeven';
    await VerlofService.reject(verlof.user_id, verlof.id, reden);

    alert('Verlof afgekeurd!');
    await haalVerlovenOp();
  } catch (err) {
    console.error('Fout bij afkeuren:', err);
    alert('Kon verlof niet afkeuren.');
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
</style>
