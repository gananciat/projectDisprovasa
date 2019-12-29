import Vue from 'vue'
import Router from 'vue-router'
import store from '../store/index'
import multiguard from 'vue-router-multiguard'

import Default from '@/components/Default'
import ExampleIndex from '@/components/example/Index'
import Login from '@/components/login/Index'
import Category from '@/components/administracion/Category'

Vue.use(Router)

//validar authenticacion
const isLoggedIn = (to, from, next) => {
  return store.state.is_login ? next() : next('/login')
}

const isLoggedOut = (to, from, next) => {
  return store.state.is_login ? next('/') : next()
}

const routes = [
  { path: '/', name: 'Default', component: Default,beforeEnter: multiguard([isLoggedIn]) },
  { path: '/example', name: 'ExampleIndex', component: ExampleIndex, beforeEnter: multiguard([isLoggedIn]) },
  { path: '/login', name: 'Login', component: Login,beforeEnter: multiguard([isLoggedOut]) },
  { path: '/category', name: 'Category', component: Category, beforeEnter: multiguard([isLoggedIn]) },
]

export default new Router({
  routes
})
