<script setup>
import { useRoute } from "vue-router";
import MyCalendar from "@/components/MyCalendar.vue";
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";
</script>

<template>
  <Header v-if="currentRoute !== 'Login'" />
  <router-view />
  <Footer v-if="currentRoute !== 'Login'" />
</template>

<script>
const currentRoute = useRoute();
</script>

<style scoped></style>
