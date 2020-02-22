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

    getOrderProduct(start_date, end_date) {
        let self = this;
        return self.axios.get(`${self.baseUrl}products_orders/${start_date}/${end_date}`);
    }

    exportOrderProduct(start_date, end_date) {
        let self = this
        return self.axios.get(`${self.baseUrl}products_orders_export/${start_date}/${end_date}`, {
            responseType: 'arraybuffer'
        });
    }

    exportProductProviders() {
        let self = this
        return self.axios.get(`${self.baseUrl}products_providers_export`, {
            responseType: 'arraybuffer'
        });
    }
}

export default ReportService