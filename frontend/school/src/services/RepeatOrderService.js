class RepeatOrderService {
    axios
    baseUrl
  
    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}repeat_order`
    }
  
    get(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }
  
    create(data) {
        let self = this;
        return self.axios.post(`${self.baseUrl}`, data);
    }
  }
  
  export default RepeatOrderService