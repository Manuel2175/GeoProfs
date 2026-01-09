<script setup>
import {ref, onMounted} from "vue";
import RoosterService from "@/services/RoosterService.js";
import AuthService from "@/services/AuthService.js";

const totalEmployees = ref(0)
const employeesAtOffice = ref(0)
const employeesOnLeave = ref(0)
const attendancePercentage = ref(0)
const currentWeek = ref(1)

const daySlots = [
  {id: 1, name: "Ochtend (09:00 - 12:30)", key: "ochtend"},
  {id: 2, name: "Middag (12:30 - 17:00)", key: "middag"},
]

const weekDays = ref([]);

const loadWeekData = async (weekNumber) => {
  const user = await AuthService.getCurrentUser();
  const data = await RoosterService.getRoosterByWeek(user.id, weekNumber);
  weekDays.value = data.dagen;
}

const nextWeek = async () => {
  currentWeek.value++;
  await loadWeekData(currentWeek.value);
}

const previousWeek = async () => {
  if (currentWeek.value > 1) {
    currentWeek.value--;
    await loadWeekData(currentWeek.value);
  }
}

onMounted(async () => {
  await loadWeekData(currentWeek.value);

  const aanwezigen = await RoosterService.getAanwezigen();
  const afwezigen = await RoosterService.getAfwezigen();

  totalEmployees.value = aanwezigen;
  employeesAtOffice.value = aanwezigen;
  employeesOnLeave.value = afwezigen;

  attendancePercentage.value = Math.round(
    (employeesAtOffice.value / totalEmployees.value) * 100
  );
})
</script>

<template>
  <div class="p-6 space-y-4 bg-[#F3F4F6] font-[Inter]">
    <!-- Week Navigation -->
    <div class="flex items-center justify-between bg-white shadow-md rounded-xl p-4">
      <button
        @click="previousWeek"
        :disabled="currentWeek <= 1"
        class="flex items-center justify-center w-10 h-10 rounded-lg transition-colors"
        :class="currentWeek <= 1 ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-[#0E3A5B] text-white hover:bg-[#0A2A43]'">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 19l-7-7 7-7"/>
        </svg>
      </button>

      <div class="text-center">
        <h2 class="text-xl font-bold text-[#0E3A5B]">Week {{ currentWeek }}</h2>
      </div>

      <button
        @click="nextWeek"
        class="flex items-center justify-center w-10 h-10 rounded-lg bg-[#0E3A5B] text-white hover:bg-[#0A2A43] transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
    </div>

    <!-- Stats Header -->
    <div class="grid grid-cols-4 gap-4">
      <div class="bg-white shadow-md rounded-xl p-4 text-center">
        <h3 class="font-semibold text-[#0E3A5B] text-sm mb-1">Totaal werknemers</h3>
        <p class="text-2xl font-bold text-[#0E3A5B]">{{ totalEmployees }}</p>
      </div>
      <div class="bg-white shadow-md rounded-xl p-4 text-center">
        <h3 class="font-semibold text-[#0E3A5B] text-sm mb-1">Aanwezig op kantoor</h3>
        <p class="text-2xl font-bold text-[#3FB950]">{{ employeesAtOffice }}</p>
      </div>
      <div class="bg-white shadow-md rounded-xl p-4 text-center">
        <h3 class="font-semibold text-[#0E3A5B] text-sm mb-1">Verlof</h3>
        <p class="text-2xl font-bold text-[#0E3A5B]">{{ employeesOnLeave }}</p>
      </div>
      <div class="bg-white shadow-md rounded-xl p-4 text-center">
        <h3 class="font-semibold text-[#0E3A5B] text-sm mb-1">Aanwezigheid (%)</h3>
        <p class="text-2xl font-bold text-[#3FB950]">{{ attendancePercentage }}%</p>
      </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white shadow-md rounded-xl overflow-hidden">
      <!-- Day Headers -->
      <div class="grid border-b border-gray-200"
           :style="{ gridTemplateColumns: `200px repeat(${weekDays.length}, 1fr)` }">
        <div class="bg-[#0E3A5B] border-r border-gray-200"></div>
        <div v-for="day in weekDays" :key="day.id"
             class="bg-[#0E3A5B] p-3 text-center border-r border-gray-200 last:border-r-0">
          <div class="font-semibold text-white">{{ day.name }}</div>
          <div class="text-sm text-[#F3F4F6]">{{ day.date }}</div>
        </div>
      </div>

      <!-- Time Slots and Schedule -->
      <div class="grid" :style="{ gridTemplateColumns: `200px repeat(${weekDays.length}, 1fr)` }">
        <!-- Time Labels Column -->
        <div class="flex flex-col border-r border-gray-200 bg-[#F3F4F6]">
          <div v-for="slot in daySlots" :key="slot.id"
               class="h-[5.4rem] flex items-center justify-end pr-4 text-sm text-[#0E3A5B] font-medium border-b border-gray-200 last:border-b-0">
            {{ slot.name }}
          </div>
        </div>

        <!-- Days Columns -->
        <div v-for="day in weekDays" :key="day.id"
             class="flex flex-col border-r border-gray-200 last:border-r-0">
          <div v-for="slot in daySlots" :key="slot.id"
               class="h-[5.4rem] flex items-center justify-center border-b border-gray-200 last:border-b-0 transition-all"
               :class="((slot.id === 1 && day.ochtend) || (slot.id === 2 && day.middag))
                 ? 'bg-[#3FB950] text-white'
                 : 'bg-gray-300'">
            <span class="text-sm font-medium">
              {{
                (slot.id === 1 && day.ochtend) || (slot.id === 2 && day.middag) ? 'Aanwezig' : 'Vrij'
              }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
