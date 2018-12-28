/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import "babel-polyfill";
require('../../node_modules/bootstrap-sweetalert/dist/sweetalert.min');
require('vue-select-image/dist/vue-select-image.css');

var axios = require('axios');
window.axios = axios.create({
    baseURL: window.Laravel.baseUrl,
    /* other custom settings */
});

window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};

import Toastr from 'vue-toastr';
import b3playersearch from './b3/pages/search.vue';
import b3chat from './b3/pages/chat.vue';
import b3setrank from './b3/rank.vue';
import b3players from './b3/pages/players.vue';
import b3banlist from './b3/pages/bans.vue';
import b3ban from './b3/ban.vue';
import b3servers from './b3/pages/servers.vue';
import b3admins from './b3/pages/admins.vue';


window.b3routes = [
	{path: '/b3/:serverid/players', component: b3players, props: true},
	{path: '/b3/:serverid/search', component: b3playersearch, props:true},
	{path: '/b3/:serverid/chat', component: b3chat, props: true},
	{path: '/b3/:serverid/players', component: b3players, props: true},
	{path: '/b3/:serverid/admins', component: b3admins, props: true},
	{path: '/b3/:serverid/bans', component: b3banlist, props: true},
	{path: '/b3', component: b3servers, props: true},
];

window.b3components = [
	{'name': 'b3setrank', 'component': b3setrank},
	{'name': 'b3ban', 'component': b3ban},
	{'name': 'vue-toastr', 'component': Toastr}
]








