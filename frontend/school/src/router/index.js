import Vue from 'vue'
import Router from 'vue-router'
import store from '../store/index'
import multiguard from 'vue-router-multiguard'

import Default from '@/components/Default'
import ExampleIndex from '@/components/example/Index'
import Login from '@/components/login/Index'

/* IMPORT SCHOOL */
import OrderSchool from '@/components/gestion_escuela/Order'
import NewOrder from '@/components/gestion_escuela/NewOrder'
import DetailOrder from '@/components/gestion_escuela/DetailOrder'
import CalendarySchool from '@/components/gestion_escuela/CalendarySchool'

Vue.use(Router)

//validar authenticacion
const isLoggedIn = (to, from, next) => {
  //validar si es usuario escuela
  var user = store.state.usuario
  return store.state.is_login ? next() : next('/login')
}

const isLoggedOut = (to, from, next) => {
  return store.state.is_login ? next('/') : next()
}

const routes = [
  { path: '/', name: 'Default', component: Default,beforeEnter: multiguard([isLoggedIn]) },
  { path: '/example', name: 'ExampleIndex', component: ExampleIndex, beforeEnter: multiguard([isLoggedIn]) },
  { path: '/login', name: 'Login', component: Login,beforeEnter: multiguard([isLoggedOut]) },

    /* ROUTER SCHOOL */
    { path: '/school/management/order', name: 'OrderSchool', component: OrderSchool, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/school/:id/management/order/new/alimentacion/:type_order', name: 'NewOrderA', component: NewOrder, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/school/:id/management/order/new/gratuidad/:type_order', name: 'NewOrderG', component: NewOrder, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/school/:id/management/order/new/utiles/:type_order', name: 'NewOrderU', component: NewOrder, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/school/:id/management/order/new/valijas/:type_order', name: 'NewOrderV', component: NewOrder, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/school/management/order/detail/:id', name: 'DetailOrder', component: DetailOrder, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/school/management/calendar', name: 'CalendarySchool', component: CalendarySchool, beforeEnter: multiguard([isLoggedIn]) },
]

export default new Router({
  routes
})
