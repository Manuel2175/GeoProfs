<script setup>
import { useRoute } from "vue-router"
import Header from "@/components/Header.vue"
import Footer from "@/components/Footer.vue"
import VCalendar from "@/components/VCalendar.vue"

const route = useRoute()
</script>

<template>
  <Header />

  <VCalendar v-if="route.path === '/'" />

  <router-view />

  <Footer />
</template>
