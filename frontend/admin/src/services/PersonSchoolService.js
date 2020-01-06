class PersonSchoolService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
      this.axios = axios
      this.baseUrl = `${baseUrl}person_schools`
  }

  create(data) {
      let self = this;
      return self.axios.post(`${self.baseUrl}`, data);
  }

  update(data) {
      let self = this;
      return self.axios.put(`${self.baseUrl}/${data.id}`,data);
  }

  destroy(data){
      let self = this;
      return self.axios.delete(`${self.baseUrl}/${data.id}`);
  }
}

export default PersonSchoolService