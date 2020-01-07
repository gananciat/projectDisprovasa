class schoolService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
      this.axios = axios
      this.baseUrl = `${baseUrl}schools`
  }

  getBase64(file) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => resolve(reader.result);
      reader.onerror = error => reject(error);
    });
  }

  getAll() {
      let self = this;
      return self.axios.get(`${self.baseUrl}`);
  }

  get(id) {
      let self = this;
      return self.axios.get(`${self.baseUrl}/${id}`);
  }

  create(data) {
      let self = this;
      return self.axios.post(`${self.baseUrl}`, data);
  }

  update(data) {
      let self = this;
      return self.axios.put(`${self.baseUrl}/${data.id}`, data);
  }

  destroy(data) {
      let self = this;
      return self.axios.delete(`${self.baseUrl}/${data.id}`);
  }
}

export default schoolService