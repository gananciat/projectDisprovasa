class MonthService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
      this.axios = axios
      this.baseUrl = `${baseUrl}months`
  }

  getAll() {
      let self = this;
      return self.axios.get(`${self.baseUrl}`);
  }
}

export default MonthService