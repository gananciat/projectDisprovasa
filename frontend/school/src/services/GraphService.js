class GraphService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}graph_school_order`
    }

    getSchoolOrder() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }
}

export default GraphService