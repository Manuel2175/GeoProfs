// src/services/verlof.service.js
import axios from 'axios';

class VerlofService {
  async aanvragen(userId, data) {
    try {
      const response = await axios.post(`/user/${userId}/verlofaanvraag`, data);
      return response.data;
    } catch (error) {
      throw error;
    }
  }
}

export default new VerlofService();
