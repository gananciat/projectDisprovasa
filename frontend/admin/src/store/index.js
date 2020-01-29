import Vue from 'vue'
import Vuex from 'Vuex'
import services from './services'
import moment from 'moment'
import auth from '../auth'
import Axios from 'axios'
import router from '../router'
import global from '../components/sharedjs/global'

Vue.use(Vuex)

const state = {
    services,
    global,
    usuario: {},
    escuela: false,
    token: null,
    is_login: false,
    token_expired: null,
    client_id: 2,
    menu: [],
    permissions: [],
    base_url: 'http://www.project.com/',
    client_secret: 't6LqVNQoiMx7aYceb44vBupKptrzXLF9MJ5OGdOD'

    //base_url: 'http://sistematio.test/',
    //client_secret: 't6LqVNQoiMx7aYceb44vBupKptrzXLF9MJ5OGdOD',

    //base_url: 'http://sistemapro.test:8000/',
    //client_secret: 't6LqVNQoiMx7aYceb44vBupKptrzXLF9MJ5OGdOD',
}

const mutations = {
    setUser(state, usuario) {
        state.usuario = usuario
    },

    setMenu(state, menu) {
        state.menu = menu.items
        state.permissions = menu.permissions
    },

    setToken(state, token) {
        state.token = token
        state.is_login = true
    },

    logout(state) {
        state.is_login = false
        state.token = null
    },

    setState(state) {
        state.is_login = false
        state.token = null
    },

    setTokenExpired(state, time) {
        state.token_expired = time
    }
}

const actions = {
    guardarToken({ commit }, data_user) {
        Axios.defaults.headers.common.Authorization = `Bearer ${data_user.access_token}`
        commit("setToken", data_user.access_token)
        $cookies.set('token_data', data_user)
    },

    logout({ commit }) {
        $cookies.remove('token_data')
        commit("logout")
    },

    autoLogin({ commit }) {
        let token = $cookies.get('token_data')
        if (token) {
            commit('setToken', token)
            auth.getUser()
            router.push('/')
        } else {
            commit('setState')
        }
    },

    setUser({ commit }, user) {
        var school = user.current_school
        if (school) {
            window.location.href = state.base_url + 'escuela'
        }
        commit('setUser', user)
    },

    setMenu({ commit }, menu) {
        commit('setMenu', menu)
    },
}

export default new Vuex.Store({
    state,
    mutations,
    actions
})