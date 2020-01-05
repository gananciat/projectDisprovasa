// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'

//Tooltipe
import VTooltip from 'v-tooltip'
Vue.use(VTooltip)

import VueCurrencyFilter from 'vue-currency-filter'
Vue.use(VueCurrencyFilter)
Vue.use(VueCurrencyFilter, {
  symbol : 'Q',
  thousandsSeparator: ',',
  fractionCount: 2,
  fractionSeparator: '.',
  symbolPosition: 'front',
  symbolSpacing: true
})

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);

import { VBModal } from 'bootstrap-vue'
// Note: Vue automatically prefixes the directive name with 'v-'
Vue.directive('b-modal', VBModal)

import '../static/template/font-awesome/css/font-awesome.min.css'
import '../static/template/dist/css/adminlte.min.css'

import '../static/template/jquery/jquery.min.js'
import '../static/template/bootstrap/js/bootstrap.bundle.min.js'
import '../static/template/dist/js/adminlte.min.js'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

/*bootstrap 
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/js/bootstrap.min.js'
/*
//adminLTE
import './dist/css/skins/_all-skins.min.css'
import './dist/css/AdminLTE.min.css'
import './dist/js/app.min.js'
import './dist/js/adminlte.min.js'
//font-awesome
import 'font-awesome/css/font-awesome.css'*/
//jquery pligins

import { library } from '@fortawesome/fontawesome-svg-core'
import { faUserSecret } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
 
library.add(faUserSecret)
Vue.component('font-awesome-icon', FontAwesomeIcon)
// App
import App from './App'

// Vue Router
import router from './router'

// Vuex: for services, shared components, etc
import store from './store/index'

//import lodash
import _ from 'lodash'

import Multiselect from 'vue-multiselect'
Vue.component('multiselect', Multiselect)
import 'vue-multiselect/dist/vue-multiselect.min.css'

//validators
import VeeValidate from 'vee-validate'
const VueValidationEs = require('vee-validate/dist/locale/es');

const config = {
  locale: 'es',
  validity: true,
  dictionary: {
    es: VueValidationEs
  },
  fieldsBagName: 'campos',
  errorBagName: 'errors', // change if property conflicts
};

Vue.use(VeeValidate, config);

//import toastr
import VueToastr2 from 'vue-toastr-2'
import 'vue-toastr-2/dist/vue-toastr-2.min.css'
window.toastr = require('toastr')
Vue.use(VueToastr2)

import VueCookies from 'vue-cookies'
Vue.use(VueCookies)

// Our Style
import '../static/style.css'

Vue.config.productionTip = false
// set default config
window.events = new Vue();

// Element Ui
import Element from 'element-ui'
import locale from 'element-ui/lib/locale/lang/es'

Vue.use(Element, {
  locale
})

// MomentJs for Vue
const moment = require('moment')
require('moment/locale/es')
Vue.use(require('vue-moment'), {
    moment
})

import VueSweetalert2 from 'vue-sweetalert2'

const options = {
  confirmButtonColor: '#41b882',
  cancelButtonColor: '#ff7674',
  confirmButtonText: "Confirmar",
  cancelButtonText: "Cancelar"
}
 
Vue.use(VueSweetalert2,options)

//volver a validar
store.dispatch('autoLogin')

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: {
    App
  }
})
