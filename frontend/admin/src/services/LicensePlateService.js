class LicensePlateService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}license_plate`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }
}

export default LicensePlateService