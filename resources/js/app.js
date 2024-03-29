require('./bootstrap');

window.Vue = require('vue');
import swal from 'sweetalert';
import vSelect from 'vue-select'

Vue.component('v-select', vSelect)

window.moment = require('moment');

var store = require('./global/store.js').default;

var router = require('./global/router.js').default;


/**
 * CAPA PRINCIPAL
 */
import Dashboard from './App.vue';
Vue.component('empty',require("./layouts/empty.vue").default);
Vue.component('panel',require("./layouts/panel.vue").default);

const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(Dashboard)
});
