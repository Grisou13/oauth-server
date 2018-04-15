<templat>
    <div class="container">
        <form method="post" action="/register?{{ \Illuminate\Support\Facades\Request::getQueryString() }}" >
            <div class="row col s12">
                <label for="credential">Email</label>
                <input type="text" name="credential" >
            </div>
            <div class="row input-field col s12">
                <label for="password">Password</label>
                <input type="password" name="password" >
            </div>
            <div class="row input-field col s12">
                <label for="password_confirm">Confirm password</label>
                <input type="password" name="password_confirm" >
            </div>
            <div class="row">
                <button type="submit" class="col s12 waves-effect waves-light btn light-blue darken-1"><span>Register</span></button>
            </div>
        </form>
    </div>
</templat>

<script>
    import Auth from './auth'
    export default {
        data(){

        },
        methods:{
            register(){
                Auth.register({
                    ...this
                })
            }
        }
    }
</script>