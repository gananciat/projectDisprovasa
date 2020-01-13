class ReportService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
      this.axios = axios
      this.baseUrl = `${baseUrl}`
  }

  getNotifications() {
      let self = this;
      return self.axios.get(`${self.baseUrl}notifications`);
  }
}

export default ReportService