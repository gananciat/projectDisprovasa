import Axios from 'axios'
import VueCookies from 'vue-cookies'
import store from './index'
import auth from '../auth'
import moment from 'moment'
import { isNullOrUndefined } from 'util';

//import toastr
import VueToastr2 from 'vue-toastr-2'
import 'vue-toastr-2/dist/vue-toastr-2.min.css'

import exampleService from '../services/ExampleService'
import loginService from '../services/LoginService'
import YearService from '../services/YearService'
import MonthService from '../services/MonthService'
import CategoryService from '../services/CategoryService'
import MunicipalityService from '../services/MunicipalityService'
import CompanyService from '../services/CompanyService'
import PresentationService from '../services/PresentationService'
import ProductService from '../services/ProductService'
import PriceService from '../services/PriceService'
import SchoolService from '../services/SchoolService'
import PhoneSchoolService from '../services/PhoneSchoolService'
import PersonSchoolService from '../services/PersonSchoolService'
import PhonePersonService from '../services/PhonePersonService'
import ProviderService from '../services/ProviderService'
import PurchaseService from '../services/PurchaseService'
import BalanceService from '../services/BalanceService'
import DisbursementService from '../services/DisbursementService'
import ProgressOrderService from '../services/ProgressOrderService'
import QuantifyService from '../services/QuantifyService'
import ReportService from '../services/ReportService'
import RolService from '../services/RolService'
import PersonService from '../services/PersonService'
import DashboardService from '../services/DashboradService'
import ProductExpirationService from '../services/ProductExpirationService'
import CheckDeliveryManService from '../services/CheckDeliveryManService'
import VehicleService from '../services/VehicleService'
import VehicleModelService from '../services/VehicleModelService'
import VehicleBrandService from '../services/VehicleBrandService'
import LicensePlateService from '../services/LicensePlateService'
import TypeLicenseService from '../services/TypeLicenseService'
import DeliveryManService from '../services/DeliveryManService'
import VatService from '../services/VatService'
import SerieService from '../services/SerieService'
import InvoiceService from '../services/InvoiceService'
import DetailSuggestionService from '../services/DetailSuggestionService'


/* IMPORT SCHOOL */
import ReservationService from '../services/ReservationService'
import OrderService from '../services/OrderService'
import DetailOrderService from '../services/DetailOrderService'
import CalendarySchoolService from '../services/CalendarySchoolService'
import MenuSuggestionService from '../services/MenuSuggestionService'

// Axios Configuration
//let baseUrl = 'http://www.project.com/' //base url desarrollo
//let baseUrl = 'http://sistematio.test/' //base url desarrollo
let token_data = $cookies.get('token_data')
let baseUrl = 'http://sistemapro.test:8000/'

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
    yearService: new YearService(Axios, baseUrl),
    monthService: new MonthService(Axios, baseUrl),
    categoryService: new CategoryService(Axios, baseUrl),
    municipalityService: new MunicipalityService(Axios, baseUrl),
    companyService: new CompanyService(Axios, baseUrl),
    presentationService: new PresentationService(Axios, baseUrl),
    productService: new ProductService(Axios, baseUrl),
    priceService: new PriceService(Axios, baseUrl),
    schoolService: new SchoolService(Axios, baseUrl),
    personschoolService: new PersonSchoolService(Axios, baseUrl),
    phoneschoolService: new PhoneSchoolService(Axios, baseUrl),
    phonepersonService: new PhonePersonService(Axios, baseUrl),
    purchaseService: new PurchaseService(Axios, baseUrl),
    providerService: new ProviderService(Axios, baseUrl),
    balanceService: new BalanceService(Axios, baseUrl),
    disbursementService: new DisbursementService(Axios, baseUrl),
    progressorderService: new ProgressOrderService(Axios, baseUrl),
    reportService: new ReportService(Axios, baseUrl),
    rolService: new RolService(Axios, baseUrl),
    personService: new PersonService(Axios, baseUrl),
    quantifyService: new QuantifyService(Axios, baseUrl),
    dashboardService: new DashboardService(Axios, baseUrl),
    productExpirationService: new ProductExpirationService(Axios, baseUrl),
    checkdeliverymanService: new CheckDeliveryManService(Axios, baseUrl),
    vehicleService: new VehicleService(Axios, baseUrl),
    vehiclemodelService: new VehicleModelService(Axios, baseUrl),
    vehiclebrandService: new VehicleBrandService(Axios, baseUrl),
    licenseplateService: new LicensePlateService(Axios, baseUrl),
    typelicenseService: new TypeLicenseService(Axios, baseUrl),
    deliverymanService: new DeliveryManService(Axios, baseUrl),
    serieService: new SerieService(Axios, baseUrl),
    vatService: new VatService(Axios, baseUrl),
    invoiceService: new InvoiceService(Axios, baseUrl),
    detailsuggestionService: new DetailSuggestionService(Axios, baseUrl),

    /* EXPORT SERVICE SCHOOL */
    reservationService: new ReservationService(Axios, baseUrl),
    orderService: new OrderService(Axios, baseUrl),
    detailorderService: new DetailOrderService(Axios, baseUrl),
    calendaryschoolService: new CalendarySchoolService(Axios, baseUrl),
    menusuggestionService: new MenuSuggestionService(Axios, baseUrl),
}
