<template>
  <div class="min-h-screen flex items-center justify-center bg-[#F3F4F6] font-[Inter]">
    <div class="bg-white shadow-md rounded-xl p-8 w-full max-w-md">
      <h1 class="text-2xl font-bold text-center text-[#0E3A5B] mb-6">Login</h1>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-[#0E3A5B] mb-1">Naam</label>
          <input
            type="text"
            v-model="username"
            required
            placeholder="Voorbeeldnaam"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#3FB950] focus:border-[#3FB950]"
            :disabled="loading"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-[#0E3A5B] mb-1">Wachtwoord</label>
          <input
            type="password"
            v-model="password"
            required
            placeholder="********"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#3FB950] focus:border-[#3FB950]"
            :disabled="loading"
          />
        </div>

        <div v-if="error" class="text-red-500 text-sm">{{ error }}</div>

        <div class="text-right">
          <button
            type="submit"
            :disabled="loading"
            class="bg-[#3FB950] text-white font-semibold px-6 py-2 rounded-lg hover:bg-green-600 transition-colors"
          >
            {{ loading ? 'Loading...' : 'Inloggen' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import AuthService from '@/services/AuthService';

export default {
  name: 'LoginForm',
  setup() {
    const username = ref('');
    const password = ref('');
    const error = ref('');
    const loading = ref(false);
    const router = useRouter();

    const handleLogin = async () => {
      if (loading.value) return;

      loading.value = true;
      error.value = '';

      try {
        await AuthService.login(username.value, password.value);

        router.push('/dashboard');
      } catch (err) {
        console.error('Login error:', err);
        error.value =
          err.response?.data?.message || 'Er is een fout opgetreden. Probeer opnieuw.';
        password.value = '';
      } finally {
        loading.value = false;
      }
    };

    return {
      username,
      password,
      error,
      loading,
      handleLogin,
    };
  },
};
</script>

<style scoped> </style>
