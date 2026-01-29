<script>
import { useRouter } from 'vue-router';
import VerlofService from "@/services/VerlofService.js";
import AuthService from "@/services/AuthService.js";
import { ref, onMounted } from 'vue';
import {ensureCsrfToken} from "@/services/axios-config.js";
import verlofService from "@/services/VerlofService.js"; // onMounted toevoegen

export default {
  name: 'VerlofForm',
  setup() {
    const startdatum = ref('');
    const einddatum = ref('');
    const reden = ref('');
    const error = ref('');
    const loading = ref(false);
    const router = useRouter();

    onMounted(async () => {
      await ensureCsrfToken();
    });
    const handleSubmit = async () => {
      if (loading.value) return;

      loading.value = true;
      error.value = '';

      try {
        const currentUser = AuthService.getCurrentUser()
        const user = currentUser.id;
        await VerlofService.aanvragen(user, {
          reden: reden.value,
          startdatum: startdatum.value,
          einddatum: einddatum.value,
          status: 'aangevraagd',
        });

        router.push('/dashboard');
      } catch (err) {
        console.error('Verlof aanvraag error:', err);
        error.value =
          err.response?.data?.message || 'Er is een fout opgetreden. Probeer opnieuw.';
      } finally {
        loading.value = false;
      }
    };

    return {
      startdatum,
      einddatum,
      reden,
      error,
      loading,
      handleSubmit,
    }
  },
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-[#F3F4F6] font-[Inter]">
    <div class="bg-white shadow-md rounded-xl p-8 w-full max-w-md">
      <h1 class="text-2xl font-bold text-center text-[#0E3A5B] mb-6">Verlof aanvragen</h1>

      <form @submit.prevent="handleSubmit" class="space-y-4">

        <!-- Begin datum -->
        <div>
          <label for="startdatum" class="block text-sm font-medium text-[#0E3A5B] mb-1">Begin datum</label>
          <input
            required
            v-model="startdatum"
            type="date"
            :disabled="loading"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#3FB950] focus:border-[#3FB950]"
            id="startdatum"
          />
        </div>

        <!-- Eind datum -->
        <div>
          <label for="einddatum" class="block text-sm font-medium text-[#0E3A5B] mb-1">Eind datum</label>
          <input
            required
            v-model="einddatum"
            :disabled="loading"
            type="date"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#3FB950] focus:border-[#3FB950]"
            id="einddatum"
          />
        </div>

        <!-- Opmerking -->
        <div>
          <label for="reden" class="block text-sm font-medium text-[#0E3A5B] mb-1">Opmerking (optioneel)</label>
          <textarea
            rows="4"
            :disabled="loading"
            v-model="reden"
            maxlength="100"
            placeholder="Bijvoorbeeld: privé afspraak"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#3FB950] focus:border-[#3FB950]"
            id="reden"
          ></textarea>
        </div>

        <!-- Verstuur knop -->
        <div class="text-right">
          <button
            :disabled="loading"
            type="submit"
            class="bg-[#3FB950] text-white font-semibold px-6 py-2 rounded-lg hover:bg-green-600 transition-colors"
          >
            {{ loading ? 'Versturen...' : 'Verlof aanvragen' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Source+Sans+3:wght@400;600&display=swap');
</style>
