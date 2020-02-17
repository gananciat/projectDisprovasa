class DashboardService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
      this.axios = axios
      this.baseUrl = `${baseUrl}dashboard`
  }


  getPurchases() {
      let self = this;
      return self.axios.get(`${self.baseUrl}_graph_purchases`)
  }

  getOrders() {
      let self = this;
      return self.axios.get(`${self.baseUrl}_graph_orders`)
  }
}

export default DashboardService