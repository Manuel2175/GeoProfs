<script setup>
import { useRoute } from "vue-router";
import MyCalendar from "@/components/MyCalendar.vue";
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";

const currentRoute = useRoute();
</script>

<template>
  <!-- Show Header/Footer except on /login -->
  <Header v-if="currentRoute !== 'Login'" />
  <router-view />
  <Footer v-if="currentRoute.path !== '/login'" />
</template>

<script>
const currentRoute = useRoute(); // Detect the current route
</script>

<style scoped></style>
