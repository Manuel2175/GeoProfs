// src/services/axios-config.js
import axios from 'axios';

// Set base URL for API calls
axios.defaults.baseURL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'; // Add "/api" explicitly
axios.defaults.withCredentials = true; // Add this line

console.log('Axios Base URL:', axios.defaults.baseURL);

const getCsrfToken = async () => {
  await axios.get('/sanctum/csrf-cookie');
};


// Add a request interceptor
axios.interceptors.request.use(
    (config) => {
        const user = JSON.parse(localStorage.getItem('user'));
        if (user && user.token) {
            config.headers['Authorization'] = `Bearer ${user.token}`;
        }

        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Add a response interceptor
axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Handle unauthorized access
            localStorage.removeItem('user');
            delete axios.defaults.headers.common['Authorization'];
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export const ensureCsrfToken = async () => {
  try {
    // Override de baseURL om de root van het domein te raken voor Sanctum
    await axios.get('/sanctum/csrf-cookie', { baseURL: import.meta.env.VITE_APP_URL || 'http://localhost:8000' });
    console.log('CSRF cookie succesvol opgehaald door centrale functie.');
  } catch (error) {
    console.warn('Waarschuwing: Het ophalen van de CSRF cookie is mislukt.', error.message);
  }
};
