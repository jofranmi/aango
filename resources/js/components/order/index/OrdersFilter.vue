<template>
    <div class="form-row">
        <filter-string v-model="request.filters.vin.value" :text="'VIN'"></filter-string>
        <filter-string v-model="request.filters.year.value" :text="'Year'"></filter-string>
        <filter-string v-model="request.filters.make.value" :text="'Make'"></filter-string>
        <filter-string v-model="request.filters.model.value" :text="'Model'"></filter-string>
        <div v-if="authorized" class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Customer</span>
            </div>
            <select v-model="request.filters.customer.value" class="form-control">
                <option value="" selected>All</option>
                <option v-for="customer in customers" :value="customer['id']">{{ customer['name']}}</option>
            </select>
        </div>
        <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Key type</span>
            </div>
            <select v-model="request.joins.items.value" class="form-control">
                <option value="" selected>All key types</option>
                <option v-for="item in items" :value="item['name']">{{ item['name']}}</option>
            </select>
        </div>
        <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Status</span>
            </div>
            <select v-model="request.joins.status.value" class="form-control">
                <option value="" selected>All</option>
                <option v-for="stat in status" :value="stat['name']">{{ stat['name']}}</option>
            </select>
        </div>
        <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Date from</span>
            </div>
            <datepicker @input="request.filters.dateFrom.value = fixDate($event)" :wrapper-class="'form-control'" :input-class="'datePicker'" :format="customFormatter"></datepicker>
        </div>
        <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Date to</span>
            </div>
            <datepicker @input="request.filters.dateTo.value = fixDate($event)" :wrapper-class="'form-control'" :input-class="'datePicker'" :format="customFormatter"></datepicker>
        </div>
        <div class="mb-3 col-sm-1 col-sm-4 col-lg-3">
            <button v-on:click="search" :disabled="buttonDisable" class="btn btn-primary">Search <i class="fas fa-search"></i></button>
        </div>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from "moment";

    export default {
        mounted() {
            console.log('Orders filters mounted.')
        },
        props: {
            authorized: {type: Boolean},
            customers: {type: Array},
            items: {type: Array},
            status: {type: Array},
        },
        components: {
            Datepicker
        },
        methods: {
            fixDate(event) {
                return moment(event).format('YYYY-MM-DD');
            },
            customFormatter(date) {
                return moment(date).format('MM-DD-YYYY');
            },
            search() {
                let eventHub = this.$eventHub;
                let vm = this;
                this.buttonDisable = true;

                $.post('/orders/filterOrders', {
                    _token: this.csrf,
                    filters: this.request.filters,
                    joins: this.request.joins,
                }).done(function (data) {
                    eventHub.$emit('filterResult', data);
                    vm.buttonDisable = false;
                });
            },
        },
        data: function() {
            return {
                buttonDisable: false,
                csrf: $('meta[name="csrf-token"]').attr('content'),
                pageNumber: 0,
                request: {
                    filters: {
                        vin: {
                            column: 'vin',
                            operator: '=',
                            value: '',
                        },
                        year: {
                            column: 'year',
                            operator: '=',
                            value: '',
                        },
                        make: {
                            column: 'make',
                            operator: '=',
                            value: '',
                        },
                        model: {
                            column: 'model',
                            operator: '=',
                            value: '',
                        },
                        customer: {
                            column: 'customer_id',
                            operator: '=',
                            value: '',
                        },
                        dateFrom: {
                            column: 'created_at',
                            operator: '>=',
                            value: '',
                        },
                        dateTo: {
                            column: 'created_at',
                            operator: '<=',
                            value: '',
                        },
                    },
                    joins: {
                        user: {
                            name: 'user',
                            fields: ['id', 'name']
                        },
                        status: {
                            name: 'status',
                            fields: ['id', 'name'],
                            column: 'name',
                            operator: '=',
                            value: '',
                        },
                        items: {
                            name: 'items.item',
                            fields: '',
                            column: 'name',
                            operator: '=',
                            value: '',
                        },
                    }
                },
            };
        },
        components: {
            Datepicker
        },
    }
</script>
