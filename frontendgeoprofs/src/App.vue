<script setup>
import { useRoute } from "vue-router"; // Import useRoute to check the current route
import MyCalendar from "@/components/MyCalendar.vue";
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";
</script>

<template>
  <!-- Show Header/Footer except on /login -->
  <Header v-if="currentRoute !== 'Login'" />
  <router-view />
  <Footer v-if="currentRoute !== 'Login'" />
</template>

<script>
const currentRoute = useRoute(); // Detect the current route
</script>

<style scoped></style>
