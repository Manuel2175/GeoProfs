<script setup>
import { useRoute } from "vue-router";
import MyCalendar from "@/components/MyCalendar.vue";
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";

const currentRoute = useRoute();
</script>

<template>
  <Header v-if="currentRoute.path !== '/login'" />
  <router-view />
  <Footer v-if="currentRoute.path !== '/login'" />
</template>

<style scoped></style>
