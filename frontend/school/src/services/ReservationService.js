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
}

export default ReservationService