/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('../../node_modules/bootstrap-sweetalert/dist/sweetalert.min');

window.Vue = require('vue');


var axios = require('axios');
window.axios = axios.create({
    baseURL: window.Laravel.baseUrl,
    /* other custom settings */
});

window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};

import Vue from 'vue';
import Toastr from 'vue-toastr';
import VueEcho from 'vue-echo';
import {Tabs, Tab} from 'vue-tabs-component';


Vue.component('b3playersearch', require('./b3/search.vue'));
Vue.component('b3chat', require('./b3/chat.vue'));
Vue.component('b3setrank', require('./b3/rank.vue'));
Vue.component('b3players', require('./b3/players.vue'));
Vue.component('b3ban', require('./b3/ban.vue'));
Vue.component('vue-toastr',Toastr);
Vue.use(VueEcho, window.pusherinfo);
Vue.component('tabs', Tabs);
Vue.component('tab', Tab);

const app = new Vue({
    el: '#b3app',
    data: {

    }

});



