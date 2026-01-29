import axios from 'axios';

class VerlofService {
  async aanvragen(user, data) {
    const response = await axios.post(`/user/${user}/verlofaanvraag`, data);
    return response.data;
  }

  async getVerloven() {
    const response = await axios.get(`/verlofaanvraag`);
    return response.data;
  }

  async approve(user, verlofaanvraag) {
    const response = await axios.put(
      `/user/${user}/verlofaanvraag/${verlofaanvraag}/approve`,
      { status: 'goedgekeurd' }
    );
    return response.data;
  }

  async reject(user, verlofaanvraag, reden) {
    const response = await axios.put(
      `/user/${user}/verlofaanvraag/${verlofaanvraag}/reject`,
      { status: 'afgewezen', afkeuringsreden: reden }
    );
    return response.data;
  }
}

export default new VerlofService();
