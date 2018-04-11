export default {
    token: null,
    user: null,
    get loggedIn(){
        return !!this.token
    },
    login(credentials){
        return axios.post("/login",{...credentials})
            .then(resp => this.token = resp.data)
            .then(()=>{
                window.axios.defaults.headers.common['Authorization'] = "Baerer "+this.token;
            })
            .then(()=>{
                axios.get("/api/profile")
                    .then(resp => this.user = resp.data)
            })
    },
    register(credentials){
        return axios.post("/register",{...credentials}).then(resp => this.login(credentials))
    },
    logout(){
        
    }
}