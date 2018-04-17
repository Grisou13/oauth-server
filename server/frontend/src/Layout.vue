<template>
    <div id="app" class="container">
        <ul id="slide-out" class="sidenav sidenav-fixed">
            <li class="bold"><a href="#!" v-if="this.depth > 1"><i class="material-icons">chevron_left</i></a></li>
            <li><a class="dropdown-trigger" href="#!" data-target="tutorial-drop-menu">Tutorial<i class="material-icons right">chevron_right</i></a></li>
            <ul id="tutorial-drop-menu" class="dropdown-content">
                <li><a href="/tutorial">Getting started</a></li>
                <li><a href="/tutorial#integration">Integrating to an existing app</a></li>
                <li class="divider"></li>
                <li><a href="/tutorial#create-your-app">Creating your own third party app</a></li>
            </ul>
            <li v-if="!loggedIn"class="bold"><router-link class="waves-effect waves-teal" tag="a" :to="{name: 'login'}">Login</router-link></li>
            <li v-if="!loggedIn"class="bold"><router-link class="waves-effect waves-teal" tag="a" :to="{name: 'register'}">Register</router-link></li>

            <li v-if="loggedIn"class="bold"><router-link class="waves-effect waves-teal" tag="a" :to="{name: 'logout'}">Logout</router-link></li>

            <li v-if="loggedIn"class="bold"><router-link class="waves-effect waves-teal" tag="a" :to="{name: 'clients'}">Manage your api clients</router-link></li>
            <li v-if="loggedIn"class="bold"><router-link class="waves-effect waves-teal" tag="a" :to="{ name:'ask'}">Available apis</router-link></li>
            <li v-if="loggedIn"class="bold"><router-link class="waves-effect waves-teal" tag="a" :to="{ name: 'project-list' }">Manage your apis</router-link></li>
        </ul>

        <!--<a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>-->
        <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>

        <!-- route outlet -->
        <!-- component matched by the route will render here -->
        <main class="app-main-view parent">
            <transition :name="transitionName">
                <router-view class="container child-view"></router-view>
            </transition>
        </main>

    </div>
</template>
<script>
    import EventBus from './EventBus'
    export default {
        data () {
            return {
                transitionName: 'fade',
                depth: 1,
                loggedIn: false
            }
        },
        ready() {
            this.prepareComponent();
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },
        methods:{
            prepareComponent(){
                var elem = document.querySelector('.sidenav');
                var instance = M.Sidenav.init(elem, {});
                instance.open()
                EventBus.$on("logged-in", () => {
                    this.loggedIn = true
                })
                EventBus.$on("log-out", () => {
                    this.loggedIn = false
                })
            }
        },
        beforeRouteUpdate (to, from, next) {
            const toDepth = to.path.split('/').length
            const fromDepth = from.path.split('/').length
            this.depth = toDepth
            this.transitionName = toDepth < fromDepth ? 'fade' : 'slide-left'
            next()
        }
    }
</script>
