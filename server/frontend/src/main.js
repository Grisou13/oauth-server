import Vue from 'vue'
import Dashboard from './Dashboard/Dashboard.vue'
import Clients from './Dashboard/Clients.vue'
import AuthorizedClients from './Dashboard/AuthorizedClients.vue'
import PersonalAccessToken from './Dashboard/PersonalAccessTokens.vue'

import Project from './Project/ProjectTable'
import ProjectDetail from './Project/ProjectDetail'
import ScopesTable from './Project/ScopeTable'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

window._ = require('lodash');
window.Popper = require('popper.js').default;
window.Vue = require('vue');
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    //require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
const axios = require('axios');
window.axios = axios.create({
  baseURL: window.BASE_URL || window.location.url,
  headers: {
    'X-Requested-With' : 'XMLHttpRequest'
  }
})

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

let auth = document.head.querySelector('meta[name="auth-token"]');

if (auth) {
    window.axios.defaults.headers.common['Authorization'] = "Baerer "+auth.content;
} else {
    console.error('Auth token not found');
}
console.log($.fn.modal)
Vue.component("authorized-clients", AuthorizedClients)
Vue.component("clients", Clients)
Vue.component("personal-access-token", PersonalAccessToken)

// 2. Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// `Vue.extend()`, or just a component options object.
// We'll talk about nested routes later.
const routes = [
  { path: '/clients', component: Dashboard },
  { path: '/projects', component: Project},
  { path: '/projects/:id', component: ProjectDetail}

  // { path: '/clients', component: Clients }
]

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = new VueRouter({
  routes // short for `routes: routes`
})

// 4. Create and mount the root instance.
// Make sure to inject the router with the router option to make the
// whole app router-aware.

const app = new Vue({
  router
}).$mount('#app')
