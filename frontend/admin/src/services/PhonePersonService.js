class PhonePersonService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
      this.axios = axios
      this.baseUrl = `${baseUrl}phone_people`
  }

  create(data) {
      let self = this;
      return self.axios.post(`${self.baseUrl}`, data);
  }

  destroy(data){
      let self = this;
      return self.axios.delete(`${self.baseUrl}/${data.id}`);
  }
}

export default PhonePersonService