class ProductService {
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

    getAllFilter(search) {
        let self = this
        if (search == '') {
            search = 0
        }
        return self.axios.get(`${self.baseUrl}_paginate/${search}`);
    }

    getPrices($id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${$id}/prices`);
    }

    getPurchases($id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${$id}/purchases`);
    }

    get(propierty) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${propierty}/edit`);
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

export default ProductService