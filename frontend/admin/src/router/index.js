import Vue from 'vue'
import Router from 'vue-router'
import store from '../store/index'
import multiguard from 'vue-router-multiguard'

import Default from '@/components/Default'
import ExampleIndex from '@/components/example/Index'
import Login from '@/components/login/Index'
import Category from '@/components/administracion/Category'
import Company from '@/components/administracion/Company'
import Presentation from '@/components/administracion/Presentation'
import Product from '@/components/administracion/Product'
import Provider from '@/components/ingresos/Provider'
import Purchase from '@/components/ingresos/compra/Index'
import Inventory from '@/components/ingresos/inventario/Index'
import PurchaseCreate from '@/components/ingresos/compra/Create'
import PurchaseViewInfo from '@/components/ingresos/compra/ViewInfo'
import Balance from '@/components/administracion_escuela/balance/Index'
import ProgressOrder from '@/components/logistica/ProgressOrder'
import AssignProduct from '@/components/logistica/AssignProduct'
import Transport from '@/components/logistica/Transport'
import TransportCheck from '@/components/logistica/TransportCheck'
import CreateBalance from '@/components/administracion_escuela/balance/Create'
import CalendarySchool from '@/components/logistica/CalendarySchool'
import ProductsOrdered from '@/components/logistica/ProductsOrdered'
import Serie from '@/components/facturacion/Serie'
import Vat from '@/components/facturacion/Vat'
import Person from '@/components/acceso/Person'
import Rol from '@/components/acceso/Rol'
import InvoiceIndex from '@/components/facturacion/factura/Index'
import InvoiceCreate from '@/components/facturacion/factura/Create'
import InvoiceDownload from '@/components/facturacion/factura/InvoiceDownload'

/* IMPORT DE ROUTER SCHOOL */
import School from '@/components/administracion/School'
import InformationSchool from '@/components/administracion_escuela/InformationSchool'
import NewSchool from '@/components/administracion_escuela/NewSchool'


Vue.use(Router)

//validar authenticacion
const isLoggedIn = (to, from, next) => {
    var user = store.state.usuario
    if (!_.isEmpty(user)) {}
    return store.state.is_login ? next() : next('/login')
}

const isLoggedOut = (to, from, next) => {
    return store.state.is_login ? next('/') : next()
}

const permissionsValidations = (to, from, next) => {
    var permissions = store.state.permissions
    var perm = _.includes(permissions, to.name)
    console.log(perm)
    return perm ? next() : next('/') //redireccionamos al home del sistema
}

const routes = [
    { path: '/', name: 'Default', component: Default, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/example', name: 'ExampleIndex', component: ExampleIndex, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/login', name: 'Login', component: Login, beforeEnter: multiguard([isLoggedOut]) },
    { path: '/category', name: 'Category', component: Category, beforeEnter: multiguard([isLoggedIn, permissionsValidations]) },
    { path: '/company', name: 'Company', component: Company, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/presentation', name: 'Presentation', component: Presentation, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/product', name: 'Product', component: Product, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/school', name: 'School', component: School, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/provider', name: 'Provider', component: Provider, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/inventory', name: 'Inventory', component: Inventory, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/purchase', name: 'Purcharse', component: Purchase, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/purchase_create', name: 'PurchaseCreate', component: PurchaseCreate, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/purchase_view_info/:id', name: 'PurchaseViewInfo', component: PurchaseViewInfo, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/school_balance/:id', name: 'Balance', component: Balance, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/progressorder', name: 'ProgressOrder', component: ProgressOrder, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/assign_product/:id', name: 'AssignProduct', component: AssignProduct, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/transport', name: 'Transport', component: Transport, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/transport/check/:id', name: 'TransportCheck', component: TransportCheck, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/school_create_balance/:id', name: 'CreateBalance', component: CreateBalance, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/school/calendar', name: 'CalendarySchool', component: CalendarySchool, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/user', name: 'Person', component: Person, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/rol', name: 'Rol', component: Rol, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/products_ordered', name: 'ProductsOrdered', component: ProductsOrdered, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/vat', name: 'Vat', component: Vat, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/serie', name: 'Serie', component: Serie, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/invoice_index', name: 'InvoiceIndex', component: InvoiceIndex, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/invoice_create/:id', name: 'InvoiceCreate', component: InvoiceCreate, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/invoice_download', name: 'InvoiceDownload', component: InvoiceDownload, beforeEnter: multiguard([isLoggedIn]) },

    { path: '/new/school', name: 'NewSchool', component: NewSchool, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/information/school/:id', name: 'InformationSchool', component: InformationSchool, beforeEnter: multiguard([isLoggedIn]) },
]

export default new Router({
    routes
})