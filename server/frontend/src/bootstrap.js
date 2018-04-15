const axios = require('axios');
window.axios = axios.create({
    baseURL: window.BASE_URL || window.location.url,
    headers: {
        'X-Requested-With': 'XMLHttpRequest'
    }
})