<script setup>
import { User, LogIn, Calendar } from "lucide-vue-next";
</script>

<template>
  <header class="flex items-center justify-between bg-[#0E3A5B] text-white px-6 py-6 shadow-md">
    <a href="/">
      <h1 class="text-3xl font-bold font-[Inter] text-[#F3F4F6]">GeoProfs</h1>
    </a>

    <nav class="flex items-center gap-6 text-sm font-[Inter]">
      <a href="#" class="flex text-lg text-[#F3F4F6] items-center gap-2 hover:text-[#3FB950] transition-colors">
        <LogIn class="w-4 h-4" /> Login
      </a>
      <a href="#" class="flex text-lg text-[#F3F4F6] items-center gap-2 hover:text-[#3FB950] transition-colors">
        <User class="w-4 h-4" /> Profiel
      </a>
      <a
        href="#"
        class="flex items-center text-lg gap-2 bg-[#3FB950] text-[#0E3A5B] px-3 py-1 rounded-lg font-semibold hover:bg-[#34a843] transition-colors"
      >
        <Calendar class="w-4 h-4" /> Verlof aanvragen
      </a>
    </nav>
  </header>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Source+Sans+3:wght@400;600&display=swap');

header {
  font-family: 'Inter', 'Source Sans 3', sans-serif;
}
</style>
