import EventBus from './EventBus'
export const LOGIN_EVENT = "logged-in"
export const LOGOUT_EVENT = "log-out"
class Auth {

    constructor(){
        this.data = {}
        if(! Auth.instance){
            this.data.token = window.localStorage.getItem("token") || document.head.querySelector('meta[name="auth-token"]').content || null
            this.data.user = window.localStorage.getItem("user") || null
            if(this.data.token !== null){
                this._login()
            }
            Auth.instance = this;
        }

        return Auth.instance;
    }



    get loggedIn(){
        return !!this.data.token
    }
    _login(){
        window.localStorage.setItem("token",this.data.token)
        window.axios.defaults.headers.common['Authorization'] = "Baerer" + " " + this.data.token;
        window.axios.defaults.withCredentials = true
        console.log(this.data)
        EventBus.$emit(LOGIN_EVENT,this.data.token)
        axios.get("/me")
            .then(resp => {
                this.data.user = resp.data
                window.localStorage.setItem("user",this.data.user)
            })

    }

    login({credential, password}){
        return axios.post("/login",{credential, password/*, scope: "", client_id:"oauth-frontend",client_secret:"",grant_type:"password"*/})
            .then(resp =>{
                this.data.token = resp.data
                this._login()
            })
    }
    register(credentials){
        return axios.post("/register",{...credentials}).then(resp => this.login(credentials))
    }
    logout(){
        window.localStorage.removeItem("user")
        window.localStorage.removeItem("token")
        this.data.token = null
        this.data.user  = null
        EventBus.$emit(LOGOUT_EVENT)
    }
}

const instance = new Auth();
Object.freeze(instance);

export default instance
