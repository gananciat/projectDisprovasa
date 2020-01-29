class CheckSchoolService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}check_school`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    get(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }

    create(data) {
        let self = this;
        return self.axios.post(`${self.baseUrl}`, data);
    }
    
    destroy(data){
        let self = this;
        return self.axios.delete(`${self.baseUrl}/${data.id}`);
    }
}

export default CheckSchoolService