class YearService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
      this.axios = axios
      this.baseUrl = `${baseUrl}years`
  }

  getAll() {
      let self = this;
      return self.axios.get(`${self.baseUrl}`);
  }
}

export default YearService