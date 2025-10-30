import axios from 'axios';

axios.defaults.baseURL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';
axios.defaults.withCredentials = true;

console.log('Axios Base URL:', axios.defaults.baseURL);

const getCsrfToken = async () => {
  await axios.get('/sanctum/csrf-cookie');
};


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

axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
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
