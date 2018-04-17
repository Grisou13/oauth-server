const axios = require('axios');
window.axios = axios.create({
    baseURL: window.BASE_URL || window.location.url,
    mode: 'no-cors',
    headers: {
        'X-Requested-With': 'XMLHttpRequest'
    },
    // withCredentials: true
})
