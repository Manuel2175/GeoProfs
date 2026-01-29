<script setup>
import {ref} from 'vue';
import {useRouter} from 'vue-router';
import {LogIn, LogOut, User, Calendar} from "lucide-vue-next";
import AuthService from '@/services/AuthService';

const router = useRouter();
const currentUser = (AuthService.getCurrentUser());

const updateCurrentUser = () => {
  currentUser.value = AuthService.getCurrentUser();
};

const handleLogin = () => {
  // Redirect to the login page
  router.push('/login');
};

const handleLogout = async () => {
  try {
    await AuthService.logout();
    updateCurrentUser();
    router.push('/'); // Redirect to home after logout
  } catch (error) {
    console.error('Logout error:', error);
  }
};
</script>

<template>
  <header class="flex items-center justify-between bg-[#0E3A5B] text-white px-6 py-6 shadow-md">
    <a href="/">
      <h1 class="text-3xl font-bold font-[Inter] text-[#F3F4F6]">GeoProfs</h1>
    </a>
    <nav
      class="flex flex-wrap items-center gap-3 md:gap-6 text-sm md:text-base font-[Inter] w-full sm:w-auto justify-center sm:justify-end">
      <!-- Show login button if user is not logged in -->
      <template v-if="!currentUser">
        <button @click="handleLogin"
                class="flex text-lg text-[#F3F4F6] items-center gap-2 hover:text-[#3FB950] transition-colors">
          <LogIn class="w-4 h-4"/>
          Login
        </button>
      </template>

      <!-- Show these items only if user is logged in -->
      <template v-else>
        <button @click="handleLogout"
                class="flex text-lg text-[#F3F4F6] items-center gap-2 hover:text-[#3FB950] transition-colors">
          <LogOut class="w-4 h-4"/>
          Logout
        </button>
        <span class="flex text-lg text-[#F3F4F6] items-center gap-2">
                    <User class="w-4 h-4"/> {{ currentUser.name }}
                </span>
        <a href="/verlof-aanvragen"
           class="flex text-lg text-[#F3F4F6] items-center gap-2 hover:text-[#3FB950] transition-colors">
          <Calendar class="w-4 h-4"/>
          Verlof aanvragen (manager)
        </a>
        <a href="/verlof"
           class="flex items-center text-lg gap-2 bg-[#3FB950] text-[#0E3A5B] px-3 py-1 rounded-lg font-semibold hover:bg-[#34a843] transition-colors">
          <Calendar class="w-4 h-4"/>
          Verlof aanvragen
        </a>

      </template>
    </nav>
  </header>
</template>
