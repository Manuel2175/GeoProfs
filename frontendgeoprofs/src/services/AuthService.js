// src/services/auth.service.js
import axios from 'axios';

// const API_URL = '/api/auth';

class AuthService {
    async login(username, password) {
        try {
            await axios.get('/sanctum/csrf-cookie'); // Uses axios.defaults.baseURL

            const response = await axios.post('/auth/request', { // No need for API_URL
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
        return axios.delete('/auth/request').then(() => { // Use DELETE method for logout
            localStorage.removeItem('user'); // Clear user data from localStorage
            delete axios.defaults.headers.common['Authorization']; // Remove Authorization header
        }).catch((error) => {
            console.error('Logout failed:', error); // Log logout errors
            throw error;
        });
    }

    getCurrentUser() {
        const user = localStorage.getItem('user');
        return user ? JSON.parse(user) : null;
    }
}

export default new AuthService();
