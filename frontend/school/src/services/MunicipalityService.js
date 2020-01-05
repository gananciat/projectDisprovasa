class MunicipalityService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
      this.axios = axios
      this.baseUrl = `${baseUrl}municipalities`
  }

  getAll() {
      let self = this;
      return self.axios.get(`${self.baseUrl}`);
  }

  get(id) {
      let self = this;
      return self.axios.get(`${self.baseUrl}/${id}`);
  }
}

export default MunicipalityService