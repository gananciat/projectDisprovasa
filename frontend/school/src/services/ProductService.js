class productService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}products`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    getPrices($id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${$id}/prices`);
    }

    get(propierty) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${propierty}/edit`);
    }

    getCuadrar(product, price) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${product}/cuadrar/${price}`);
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

export default productService