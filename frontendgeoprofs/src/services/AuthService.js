// src/services/auth.service.js
import axios from 'axios';

class AuthService {
  async login(username, password) {
    try {
      const response = await axios.post('/auth/request', {
        name: username,
        password: password
      });

      // Verwacht dat de API een JWT of ander bearer token teruggeeft
      if (response.data?.token) {
        const userData = {
          ...response.data.user,
          token: response.data.token
        };

        // Sla gebruiker + token op
        localStorage.setItem('user', JSON.stringify(userData));

        // Zet standaard Authorization-header
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
      }

      return response.data;
    } catch (error) {
      throw error;
    }
  }

  logout() {
    try {
      // Optioneel: maak logout-aanroep naar server als endpoint bestaat
      axios.delete('/auth/request').catch(() => {});

      // Verwijder lokale data
      localStorage.removeItem('user');
      delete axios.defaults.headers.common['Authorization'];
    } catch (error) {
      console.error('Logout failed:', error);
      throw error;
    }
  }

  getCurrentUser() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
  }

  // Handige helper om header opnieuw te zetten bij page refresh
}

export default new AuthService();
