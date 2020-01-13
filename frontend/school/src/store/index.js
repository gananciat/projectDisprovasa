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
    token: null,
    is_login: false,
    school: {},
    people: {},
    school_name: '',
    token_expired: null,
    client_id: 2,

    base_url: 'http://sistematio.test/',
    client_secret: '3qMfdqd3XmG5WmlhtrEfYDmBvlFmFB4BU5WPhybe'

    //base_url: 'http://sistemapro.test:8000/',
    //client_secret: 'M6hb75GefIsUa0LOQzTYIHPyKKJnsgpNbgyKoeIo'
}

const mutations = {
    setUser(state, usuario) {
        state.usuario = usuario.user
        state.people = usuario.people
        state.school = usuario.school
        state.school_name = state.school.school.name
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
        console.log(user)
        var school = user.user.current_school
        if(!school){
            window.location.href = state.base_url
        }
        commit('setUser', user)
    }
}

export default new Vuex.Store({
    state,
    mutations,
    actions
})