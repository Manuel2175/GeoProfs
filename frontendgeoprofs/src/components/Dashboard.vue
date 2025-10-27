<script>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import AuthService from '@/services/AuthService';

export default {
  name: 'Dashboard',
  setup() {
    const router = useRouter();
    const user = ref({});
    const loginTime = ref('');

    onMounted(() => {
      const currentUser = AuthService.getCurrentUser();

      if (currentUser) {
        user.value = currentUser;
        loginTime.value = new Date().toLocaleString();
      } else {
        router.push('/login');
      }
    });

    const handleLogout = async () => {
      try {
        await AuthService.logout();
        router.push('/login');
      } catch (error) {
        console.error('Logout error:', error);
      }
    };

    return {
      user,
      loginTime,
      handleLogout
    };
  }
};
</script>

<template>
    <div class="dashboard-container">
        <h1>Welcome, {{ user.name }}</h1>
      <p>Login time: {{ loginTime }}</p>
      <p>verlof: {{ user.verlofsaldo }}</p>

        <button @click="handleLogout" class="logout-btn">
            Logout
        </button>
    </div>
</template>

<style scoped>
.dashboard-container {
    padding: 2rem;
    max-width: 800px;
    margin: 0 auto;
}

.logout-btn {
    margin-top: 1rem;
    padding: 0.5rem 1rem;
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.logout-btn:hover {
    background-color: #c82333;
}
</style>
