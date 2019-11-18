/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

let moment = require('moment');
let animate = require('animate.css');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('search-element', require('./components/SearchElement.vue').default);
Vue.component('search-wrapper', require('./components/SearchWrapper.vue').default);
Vue.component('notification-bar', require('./components/NotificationBar.vue').default);

/**
 * Customer
 */
Vue.component(
    'customer-card',
    require('./components/customer/index/CustomerCard.vue').default
);

/**
 * Order
 */
Vue.component(
    'orders-filter',
    require('./components/order/index/OrdersFilter.vue').default
);

Vue.component(
    'orders-table',
    require('./components/order/index/OrdersTable.vue').default
);

Vue.component(
    'search-vin',
    require('./components/order/create/SearchVIN.vue').default
);

Vue.component(
    'vin-decoder-result',
    require('./components/order/create/VINDecoderResult.vue').default
);

/**
 * filters
 */
Vue.component(
    'filter-string',
    require('./components/filters/FilterString.vue').default
);

Vue.component(
    'filter-date',
    require('./components/filters/FilterDate.vue').default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.prototype.$eventHub = new Vue(); // Global event bus

Vue.filter('money', function (value) {
    return '$ ' + value.toFixed(2);
})

Vue.filter('date', function (date) {
    return moment(date).format('MM-DD-YYYY');
})

const app = new Vue({
    el: '#app',
    methods: {
        formatMoney: function (value) {
            return '$ ' + value.toFixed(2);
        },
        formatDate: function (date) {
            return moment(date).format('MM-DD-YYYY');
        }
    },
    mounted() {
        /*Echo.channel('notifications')
            .listen('.my-event', function(data) {
                alert(JSON.stringify(data));
            });*/
    }
});
