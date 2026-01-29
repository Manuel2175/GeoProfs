<script setup>
import { useRoute } from "vue-router";
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";

const route = useRoute();
</script>

<template>
  <Header v-if="route.name !== 'Login' && route.path !== '/login'" />

  <router-view />

  <Footer v-if="route.name !== 'Login' && route.path !== '/login'" />
</template>
