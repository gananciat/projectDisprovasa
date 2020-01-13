class InformationSchoolService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}information_disbursement_school`
    }

    getDisbursement(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }
}

export default InformationSchoolService