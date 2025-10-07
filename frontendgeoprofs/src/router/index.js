import { createRouter, createWebHistory } from 'vue-router';
import AuthService from '@/services/AuthService';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('@/components/MyCalendar.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/components/LoginForm.vue')
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('@/components/Dashboard.vue'),
    meta: { requiresAuth: true }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const currentUser = AuthService.getCurrentUser();

  if (requiresAuth && !currentUser) {
    next('/login');
  } else {
    next();
  }
});

export default router;
