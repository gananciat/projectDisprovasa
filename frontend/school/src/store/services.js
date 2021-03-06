import Axios from 'axios'
import VueCookies from 'vue-cookies'
import store from './index'
import auth from '../auth'
import moment from 'moment'
import { isNullOrUndefined } from 'util';


import exampleService from '../services/ExampleService'
import loginService from '../services/LoginService'
import ProductService from '../services/ProductService'

/* IMPORT SCHOOL */
import ReservationService from '../services/ReservationService'
import OrderService from '../services/OrderService'
import DetailOrderService from '../services/DetailOrderService'
import CalendarySchoolService from '../services/CalendarySchoolService'
import RepeatOrderService from '../services/RepeatOrderService'
import MenuSuggestionService from '../services/MenuSuggestionService'
import CheckSchoolService from '../services/CheckSchoolService'

/* IMPORT DASHBOARD */
import GraphService from '../services/GraphService'
import InformationSchoolService from '../services/InformationSchoolService'

// Axios Configuration

// Para desarrollo
//let baseUrl = 'http://www.project.com/' //base url desarrollo
//let baseUrl = 'http://sistemapro.test:8000/'
let baseUrl = 'http://sistematio.test/' //base url desarrollo

// Para producción
//let baseUrl = 'http://www.empresa.disprovasa.org/' //base url produccion disprovasa
//let baseUrl = 'http://www.proalsa.disprovasa.org/' //base url produccion proalsa

let token_data = $cookies.get('token_data')


// Axios Configuration
Axios.defaults.headers.common.Accept = 'application/json'
if (token_data !== null) {
    Axios.defaults.headers.common.Authorization = `Bearer ${token_data.access_token}`
}

const instance = Axios.create();
Axios.interceptors.response.use(response => {
    return response
}, error => {
    if (error.response.status === 401) {
        var token_data = $cookies.get('token_data')
        if (isNullOrUndefined(token_data)) { return error }
        
        var original_request = error.config
        return refreshToken().then(res => {
            auth.saveToken(res.data)
            original_request.headers['Authorization'] = 'Bearer ' + res.data.access_token
            return Axios.request(original_request)
        })
    }

    return error
});

function refreshToken() {
    var data = auth.getRefreshToken()
    return new Promise(function(resolve, reject) {
        instance.post(baseUrl + 'oauth/token', data)
            .then(r => {
                resolve(r)
            }).catch(e => {
                reject(r)
            })
    })
}

instance.interceptors.response.use(response => {
    return response
}, error => {
    if (error.response.status === 401) {
        auth.noAcceso()
    }

    return error
});

export default {
    exampleService: new exampleService(Axios),
    loginService: new loginService(Axios,baseUrl),
    productService: new ProductService(Axios, baseUrl),
    
    /* EXPORT SERVICE SCHOOL */
    reservationService: new ReservationService(Axios, baseUrl),
    orderService: new OrderService(Axios, baseUrl),
    detailorderService: new DetailOrderService(Axios, baseUrl),
    calendaryschoolService: new CalendarySchoolService(Axios, baseUrl),
    repeatorderService: new RepeatOrderService(Axios, baseUrl),
    checkschoolService: new CheckSchoolService(Axios, baseUrl),

    /* EXPORT DASHBOARD */
    graphService: new GraphService(Axios, baseUrl),
    informationschoolService: new InformationSchoolService(Axios, baseUrl),
    menusuggestionService: new MenuSuggestionService(Axios, baseUrl),
}
