// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);

import '../static/template/font-awesome/css/font-awesome.min.css'
import '../static/template/dist/css/adminlte.min.css'

import '../static/template/jquery/jquery.min.js'
import '../static/template/bootstrap/js/bootstrap.bundle.min.js'
import '../static/template/dist/js/adminlte.min.js'

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
