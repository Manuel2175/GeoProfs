import { createRouter, createWebHistory } from 'vue-router'
import VerlofForm from "@/components/VerlofForm.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/verlof',
      name: 'verlof',
      component: VerlofForm,
    }
  ],
})

export default router
