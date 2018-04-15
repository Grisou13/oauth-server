import Vue from 'vue'
import VueRouter from 'vue-router'
import "./bootstrap"

import Dashboard from './Oauth/Dashboard.vue'

import Project from './Project/ProjectTable'
import ProjectDetail from './Project/ProjectDetail'

import ApprovedProject from './approval/ApprovedProjectList'
import AskableProjectList from './approval/AskableProjectList'
import PendingProjectList from './approval/PendingProjectList'
import Welcome from './Welcome.vue'
import DashboardWelcome from './DashboardWelcome.vue'
import Layout from './Layout'
import Login from './Login'
import Register from './Register'
import Tutorial from './Tutorial'
import "./app.sass"
import Auth from './auth'
import EventBus from './EventBus'

//==============
// Vuejs addons
//==============
Vue.use(VueRouter)

//==============
// Standard libs
//==============
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
} catch (e) {
}


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
    window.axios.defaults.headers.common['Authorization'] = "Baerer " + auth.content;
} else {
    console.error('Auth token not found');
}

//==============
// Routes definition
//==============
const withPrefix = (prefix, routes) =>
    routes.map( (route) => {
        route.path = prefix + route.path;
        return route;
    });
const routes = [
    {path: '', name: "root", meta: {guest: true}, component: Welcome},
    {path: '/login', meta: {guest: true}, name: 'login', component: Login},
    {path: '/register', meta: {guest: true}, name: 'register', component: Register},
    {path: '/tutorial', name: 'tutorial', component: Tutorial},
    {path: '/dashboard', meta: {auth: true}, name: "dashboard", component: DashboardWelcome},
    {path: '/clients', meta: {auth: true}, name: 'clients', component: Dashboard},
    {path: '/approved', meta: {auth: true}, name: 'approved', component: ApprovedProject},
    {path: '/ask', meta: {auth: true}, name: 'ask', component: AskableProjectList},
    {path: '/pending', meta: {auth: true}, name: 'pending', component: PendingProjectList},
    {path: '/projects', meta: {auth: true}, name: 'project-list', component: Project},
    {path: '/projects/:id', meta: {auth: true}, name: 'project-detail', component: ProjectDetail},
    /*{
        path: '/dashboard', meta: {auth: true}, template: `<span>asdasdasd </span><router-view></router-view>`, children: [

        ]
    },*/
]
//==============
// Router configuration
//==============
const router = new VueRouter({
    routes
})

router.beforeEach((to, from, next) => {
    /*if (to.matched.some(record => record.meta.requiresAuth) && !Auth.loggedIn) {
        next({ path: '/login', query: { redirect: to.fullPath }});
    } else {
        next();
    }*/

    // check if the user needs to be authenticated. If the yes, redirect to the
    // login page if the token is null
    if (to.matched.some(record => record.meta.auth) && !Auth.loggedIn) {
        return next({name: 'login', query: {redirect: to.fullPath}})
    }

    // check if a logged user should see this page
    if (to.matched.some(record => record.meta.guest) && Auth.loggedIn) {
        return next({name: 'dashboard', query: {redirect: to.fullPath}})
    }
    return next()
});

// 4. Create and mount the root instance.
// Make sure to inject the router with the router option to make the
// whole app router-aware.


function render() {

    const app = new Vue({
        router,
        template: '<Layout/>',
        components: {Layout}
    }).$mount('#mount')

}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */



if (Auth.isLoggedIn) {
    router.replace({name: "dashboard"})
}


EventBus.$on("logged-in", () => {
    router.push({name: "dashboard"})
})
EventBus.$on("log-out", () => {
    router.push({name: "root"})
})


render()

/*
if(process.env.NODE_ENV === "production"){
    window.axios = axios.create({
        baseURL: window.BASE_URL || window.location.url,
        headers: {
            'X-Requested-With' : 'XMLHttpRequest'
        }
    })
    render()
}
else{


    axios.post("http://localhost:8000/login",{
        "credential":"thomas.ricci@cpnv.ch",
        "password": "toto"
    },{
        headers: {
            'X-Requested-With' : 'XMLHttpRequest'
        }
    }).then(resp => {
        console.log(resp)
        window.axios = axios.create({
            baseURL: 'http://localhost:8000',
            mode: 'no-cors',
            headers: {
                'X-Requested-With' : 'XMLHttpRequest',
                'Authorization': "Baerer "+resp.data,
                "Cookie": "token="+resp.data
            },
            withCredentials: true

        })
        render()
    })

}
*/