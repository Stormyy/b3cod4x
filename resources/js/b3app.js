/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('../../node_modules/bootstrap-sweetalert/dist/sweetalert.min');
require('vue-select-image/dist/vue-select-image.css');

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

import b3playersearch from './b3/search.vue';
import b3chat from './b3/chat.vue';
import b3setrank from './b3/rank.vue';
import b3players from './b3/players.vue';
import b3ban from './b3/ban.vue';


Vue.component('b3playersearch', b3playersearch);
Vue.component('b3chat', b3chat);
Vue.component('b3setrank', b3setrank);
Vue.component('b3players', b3players);
Vue.component('b3ban', b3ban);
Vue.component('vue-toastr',Toastr);
Vue.use(VueEcho, window.pusherinfo);
Vue.component('tabs', Tabs);
Vue.component('tab', Tab);

const app = new Vue({
    el: '#b3app',
    data: {

    }

});

window.b3app = app;



