class InvoiceService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}invoices`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    get(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }

    cancel(id) {
        let self = this;
        return self.axios.put(`${self.baseUrl}_cancel/${id}`);
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

    //peticion a funcion create
    printInvoice(id) {
        let self = this
        return self.axios.get(`${self.baseUrl}_invoice/${id}`, { responseType: 'blob' });
    }
}

export default InvoiceService