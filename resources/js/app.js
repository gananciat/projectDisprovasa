/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap.js')
window.Vue = require('vue');
window.vuetify = require('vuetify');
window.VeeValidate = require('vee-validate');
window.Validator = require('vee-validate');
window.es = require('vee-validate/dist/locale/es');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);


Vue.component('base-component', require('./components/layout/baseComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.config.productionTip = false
Vue.use(vuetify)
Vue.use(VeeValidate, {events: "change|blur|keyup"});
Validator.localize("es", es);
export default new vuetify(
    {   icons: {iconfont: 'mdi'},
        breakpoint: {
            thresholds: {
            xs: 340,
            sm: 540,
            md: 800,
            lg: 1280,
            },
            scrollBarWidth: 24,
        },  
    })

/* eslint-disable no-new */
new Vue({
  el: '#app',
  vuetify: new vuetify(),
})
