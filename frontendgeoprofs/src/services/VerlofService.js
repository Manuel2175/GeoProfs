// src/services/verlof.service.js
import axios from 'axios';

class VerlofService {
  async aanvragen(user, data) {
    try {
      const response = await axios.post(`/user/${user}/verlofaanvraag`, data);
      return response.data;
    } catch (error) {
      throw error;
    }
  }
}

export default new VerlofService();
