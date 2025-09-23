<script setup>
import { Mail, Phone, MapPin } from "lucide-vue-next";
</script>

<template>
  <footer class="bg-[#0E3A5B] text-[#F3F4F6] px-6 py-8 mt-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
      <h1 class="text-2xl font-bold font-[Inter]">GeoProfs</h1>

      <nav class="flex flex-col md:flex-row gap-4 text-sm font-[Inter]">
        <a href="#" class="flex items-center gap-2 hover:text-[#3FB950] transition-colors">
          <Mail class="w-4 h-4" /> info@geoprofs.nl
        </a>
        <a href="#" class="flex items-center gap-2 hover:text-[#3FB950] transition-colors">
          <Phone class="w-4 h-4" /> +31 (0)6 12345678
        </a>
        <a href="#" class="flex items-center gap-2 hover:text-[#3FB950] transition-colors">
          <MapPin class="w-4 h-4" /> Nijmegen, Nederland
        </a>
      </nav>
    </div>

    <div class="mt-6 border-t border-[#F3F4F6]/30 pt-4 text-xs text-[#F3F4F6]">
      © 2025 GeoProfs. Alle rechten voorbehouden.
    </div>
  </footer>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Source+Sans+3:wght@400;600&display=swap');

footer {
  font-family: 'Inter', 'Source Sans 3', sans-serif;
}
</style>
