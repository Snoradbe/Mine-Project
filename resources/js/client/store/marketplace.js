Vue.component('marketplace', require('./components/store/MarketPlaceComponent').default)

import infiniteScroll from 'vue-infinite-scroll'

const marketplace = new Vue({
    el: '#store',
    directives: {infiniteScroll}
});
