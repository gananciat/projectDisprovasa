class VehicleModelService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}vehicle_model`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }
}

export default VehicleModelService