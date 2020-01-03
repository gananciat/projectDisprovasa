import Vue from 'vue'
import Vuex from 'Vuex'
import services from './services'
import moment from 'moment'
import auth from '../auth'
import Axios from 'axios'
import router from '../router'

Vue.use(Vuex)

const state = {
    services,
    usuario: {},
    escuela: false,
    token: null,
    is_login: false,
    token_expired: null,
    client_id: 2,
    base_url: 'http://sistemapro.test:8000/',
    client_secret: 'HLTbGKzzRnN4zvWvZ0kNiRDZO0H2LKjukL9KtCwK'
}

const mutations = {
    setUser(state, usuario) {
        state.usuario = usuario
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
        if(school){
            window.location.href = state.base_url+'escuela'
        }
        commit('setUser', user)
    },
}

export default new Vuex.Store({
    state,
    mutations,
    actions
})