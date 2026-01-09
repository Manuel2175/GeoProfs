import axios from 'axios';

class RoosterService {
  async getRoosterByWeek(userId, weekId) {
    const response = await axios.get(`/user/${userId}/rooster_week/${weekId}`);
    return response.data;
  }

  async getAanwezigen() {
    const response = await axios.get('/geoprofs/aanwezigen');
    return response.data;
  }

  async getAfwezigen() {
    const response = await axios.get('/geoprofs/afwezigen');
    return response.data;
  }
}

export default new RoosterService();
