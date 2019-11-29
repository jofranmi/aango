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

Vue.component(
    'customer-create-table',
    require('./components/customer/index/CustomerCreateTable.vue').default
);

Vue.component(
    'customer-view-edit-table',
    require('./components/customer/index/CustomerViewEditTable.vue').default
);

Vue.component(
    'customer-remove-modal',
    require('./components/customer/index/CustomerRemoveModal.vue').default
);

/**
 * User
 */
Vue.component(
    'user-card',
    require('./components/user/index/UserCard.vue').default
);

Vue.component(
    'user-create-table',
    require('./components/user/index/UserCreateTable.vue').default
);

Vue.component(
    'user-view-edit-table',
    require('./components/user/index/UserViewEditTable.vue').default
);

Vue.component(
    'user-remove-modal',
    require('./components/user/index/UserRemoveModal.vue').default
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
    'orders-table-line',
    require('./components/order/index/OrdersTableLine.vue').default
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
 * Items
 */
Vue.component(
    'item-card',
    require('./components/item/index/ItemCard.vue').default
);

Vue.component(
    'make-select',
    require('./components/item/index/MakeSelect.vue').default
);

Vue.component(
    'item-create-table',
    require('./components/item/index/ItemCreateTable.vue').default
);

/**
 * Filters
 */
Vue.component(
    'filter-string',
    require('./components/filters/FilterString.vue').default
);

Vue.component(
    'filter-date',
    require('./components/filters/FilterDate.vue').default
);

Vue.component(
    'filter-select',
    require('./components/filters/FilterSelect.vue').default
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

Vue.filter('capitalize', function (value) {
    return value.charAt(0).toUpperCase() + value.slice(1);
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
});
