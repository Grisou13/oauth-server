<template>
    <div class="container">
        <form method="post" action="/register" @submit.prevent="register">
            <div class="row col s12">
                <label for="credential">Email</label>
                <input type="text" name="credential" v-model="credential">
            </div>
            <div class="row input-field col s12">
                <label for="password">Password</label>
                <input type="password" name="password" v-model="password">
            </div>
            <div class="row input-field col s12">
                <label for="password_confirm" v-model="password_confirm">Confirm password</label>
                <input type="password" name="password_confirm" >
            </div>
            <div class="row">
                <button type="submit" class="col s12 waves-effect waves-light btn light-blue darken-1" ><span>Register</span></button>
            </div>
        </form>
    </div>
</template>

<script>
    import Auth from './auth'
    export default {
        data(){
          return {
            credential: "",
            password : "",
            password_confirm: ""
          }
        },
        methods:{
            register(){
                Auth.register({
                    credential: this.credential,
                    password: this.password
                })
            }
        }
    }
</script>
