import Axios from 'axios'
import VueCookies from 'vue-cookies'
import store from './index'
import auth from '../auth'
import moment from 'moment'
import { isNullOrUndefined } from 'util';


import exampleService from '../services/ExampleService'
import loginService from '../services/LoginService'
import CategoryService from '../services/CategoryService'
import MunicipalityService from '../services/MunicipalityService'
import CompanyService from '../services/CompanyService'
import PresentationService from '../services/PresentationService'
import ProductService from '../services/ProductService'
import PriceService from '../services/PriceService'
import SchoolService from '../services/SchoolService'

// Axios Configuration
let baseUrl = 'http://sistematio.test:8081/' //base url desarrollo
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
    loginService: new loginService(Axios, baseUrl),
    categoryService: new CategoryService(Axios, baseUrl),
    municipalityService: new MunicipalityService(Axios, baseUrl),
    companyService: new CompanyService(Axios, baseUrl),
    presentationService: new PresentationService(Axios, baseUrl),
    productService: new ProductService(Axios, baseUrl),
    priceService: new PriceService(Axios, baseUrl),
    schoolService: new SchoolService(Axios, baseUrl)
}