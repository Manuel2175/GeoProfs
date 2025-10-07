import axios from 'axios';

class AuthService {
    async login(username, password) {
        try {
            await axios.get('/sanctum/csrf-cookie');

            const response = await axios.post('/auth/request', {
                name: username,
                password: password
            });

            if (response.data) {
                localStorage.setItem('user', JSON.stringify(response.data));
                axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
            }

            return response.data;
        } catch (error) {
            throw error;
        }
    }

    logout() {
        return axios.delete('/auth/request').then(() => {
            localStorage.removeItem('user');
            delete axios.defaults.headers.common['Authorization'];
        }).catch((error) => {
            console.error('Logout failed:', error);
            throw error;
        });
    }

    getCurrentUser() {
        const user = localStorage.getItem('user');
        return user ? JSON.parse(user) : null;
    }
}

export default new AuthService();
