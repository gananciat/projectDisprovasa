class ProgressOrderService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}progress_orders`
    }

    getOrder(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }

    getProgress(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}/edit`);
    }

    create(data) {
        let self = this;
        return self.axios.post(`${self.baseUrl}`, data);
    }

    update(data) {
        let self = this;
        return self.axios.put(`${self.baseUrl}/${data.id}`, data);
    }
}

export default ProgressOrderService