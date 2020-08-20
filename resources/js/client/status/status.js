window._ = require('lodash');

window.axios = require('axios');

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found!');
}

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;
window.axios.need_close_loading = true

window.io = require('socket.io-client');

window.Vue = require('vue');
Vue.mixin({
    data() {
        return {
            Lang: window.Lang,
            Types: window.Types,
        }
    },
    methods: {
        declOfNum(number, titles) {
            number = Math.abs(number) % 100
            if (number > 4 && number < 21) return titles[2] ? titles[2] : titles[1]

            number = number % 10
            if (number > 1 && number < 5) return titles[1]

            if (number === 1) return titles[0]

            return titles[2] ? titles[2] : titles[1]
        },
    }
})

Vue.component('status', require('./components/StatusComponent').default)

const marketplace = new Vue({
    el: '#status'
});

axios.interceptors.request.use(config => {
    try {
        loading(true)
    } catch (e) {
        //do nothing
    }

    return config;
});

axios.interceptors.response.use(function (response) {
    try {
        if (axios.need_close_loading) {
            loading(false)
        }
    } catch (e) {
        //do nothing
    }

    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    return response;
}, function (error) {
    let msg
    if (typeof error === 'string') {
        msg = error
    } else if (error.response.status === 422) {
        for(let e in error.response.data.errors)
        {
            msg = error.response.data.errors[e][0];
            break;
        }
    } else {
        msg = error.response.data.message
    }

    alert(msg)
    try {
        loading(false)
    } catch (e) {
        //do nothing
    }

    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    return Promise.reject(error);
});
