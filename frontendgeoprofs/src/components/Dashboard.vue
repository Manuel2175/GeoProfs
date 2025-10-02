<template>
    <div class="dashboard-container">
        <h1>Welcome, {{ user.name }}</h1>
        <p>Login time: {{ loginTime }}</p>
        
        <button @click="handleLogout" class="logout-btn">
            Logout
        </button>
    </div>
</template>

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
            if (currentUser && currentUser.user) {
                user.value = currentUser.user;
                loginTime.value = new Date().toLocaleString();
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