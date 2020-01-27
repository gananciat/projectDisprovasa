class VehicleBrandService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}vehicle_brand`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }
}

export default VehicleBrandService