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
import School from '@/components/administracion/School'
import InformationSchool from '@/components/administracion_escuela/InformationSchool'
import NewSchool from '@/components/administracion_escuela/NewSchool'

/* IMPORT SCHOOL */
import OrderSchool from '@/components/gestion_escuela/Order'
import NewOrder from '@/components/gestion_escuela/NewOrder'

Vue.use(Router)

//validar authenticacion
const isLoggedIn = (to, from, next) => {
    var user = store.state.usuario
    if(!_.isEmpty(user)){
        console.log(user)
    }
    return store.state.is_login ? next() : next('/login')
}

const isLoggedOut = (to, from, next) => {
    return store.state.is_login ? next('/') : next()
}

const routes = [
    { path: '/', name: 'Default', component: Default, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/example', name: 'ExampleIndex', component: ExampleIndex, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/login', name: 'Login', component: Login, beforeEnter: multiguard([isLoggedOut]) },
    { path: '/category', name: 'Category', component: Category, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/company', name: 'Company', component: Company, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/presentation', name: 'Presentation', component: Presentation, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/product', name: 'Product', component: Product, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/school', name: 'School', component: School, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/new/school', name: 'NewSchool', component: NewSchool, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/information/school/:id', name: 'InformationSchool', component: InformationSchool, beforeEnter: multiguard([isLoggedIn]) },

    /* ROUTER SCHOOL */
    { path: '/school/management/order', name: 'OrderSchool', component: OrderSchool, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/school/:id/management/order/new/alimentacion/:type_order', name: 'NewOrder', component: NewOrder, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/school/:id/management/order/new/gratuidad/:type_order', name: 'NewOrder', component: NewOrder, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/school/:id/management/order/new/utiles/:type_order', name: 'NewOrder', component: NewOrder, beforeEnter: multiguard([isLoggedIn]) },
]

export default new Router({
    routes
})
