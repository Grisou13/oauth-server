<template>

    <div class="container">
        <div class="row">
            <form @submit.prevent="login" class="show-password log-in-form" method="post" action="/login">
                <div class="col s12">
                    <div class="input-field col s12">
                        <label for="credential">Username / Email</label>
                        <input id="credential" type="text" name="credential" placeholder="Username / Email" v-model="credential">
                    </div>
                    <div class="input-field col s12">

                        <div class="password-wrapper">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" value="" placeholder="Enter Password" class="password" v-model="password" autocomplete="false">
                            <button class="unmask" type="button" title="Mask/Unmask password to check content">Unmask</button>
                        </div>
                        <label for="password">Password</label>

                    </div>

                    <button  type="submit" class="col s12 waves-effect waves-light btn light-blue darken-1"><span>Log-in<i class="material-icons right">send</i></span></button>

                </div>

            </form>
        </div>

        <div class="row">
            <p>You don't have an acocunt? We'll then just <router-link :to="{ name:'register'}" class="waves-effect waves-light btn cyan lighten-3"><span>Register</span></router-link> one</p>
        </div>
    </div>
</template>
<script>
    import Auth from './auth'
    export default{
        data(){
          return {
              credential: "",
              password: ""
          }
        },
        methods:{
            login(){
              console.log(this)
                Auth.login({credential: this.credential, password: this.password})
            }
        }
    }
</script>
