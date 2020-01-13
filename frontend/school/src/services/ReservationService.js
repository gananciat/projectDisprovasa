class ReservationService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
      this.axios = axios
      this.baseUrl = `${baseUrl}reservations`
  }

  create(data) {
      let self = this;
      return self.axios.post(`${self.baseUrl}`, data);
  }

  get(code) {
    let self = this;
    return self.axios.get(`${self.baseUrl}/${code}`);
  }   

  getMoney(code,type_order) {
    let self = this;
    return self.axios.get(`${self.baseUrl}_money/${code}/${type_order}`);
  }
}

export default ReservationService