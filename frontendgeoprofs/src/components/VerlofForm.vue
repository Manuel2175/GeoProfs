<script setup>
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-[#F3F4F6] font-[Inter]">
    <div class="bg-white shadow-md rounded-xl p-8 w-full max-w-md">
      <h1 class="text-2xl font-bold text-center text-[#0E3A5B] mb-6">Verlof aanvragen</h1>

      <form class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-[#0E3A5B] mb-1">Begin datum</label>
          <input
            type="date"
            required
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#3FB950] focus:border-[#3FB950]"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-[#0E3A5B] mb-1">Eind datum</label>
          <input
            type="date"
            required
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#3FB950] focus:border-[#3FB950]"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-[#0E3A5B] mb-1">Opmerking (optioneel)</label>
          <textarea
            rows="4"
            placeholder="Bijvoorbeeld: privé afspraak"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#3FB950] focus:border-[#3FB950]"
          ></textarea>
        </div>

        <div class="text-right">
          <button
            type="submit"
            class="bg-[#3FB950] text-white font-semibold px-6 py-2 rounded-lg hover:bg-green-600 transition-colors"
          >
            Verstuur
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Source+Sans+3:wght@400;600&display=swap');
</style>
