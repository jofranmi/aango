<template>
    <div class="form-row">
        <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Year from</span>
            </div>
            <datepicker @input="yearFrom = fixDate($event)" ref="date1" :minimum-view="'year'" :wrapper-class="'form-control'" :input-class="'datePicker'" :format="customFormatter"></datepicker>
        </div>
        <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Year to</span>
            </div>
            <datepicker @input="yearTo = fixDate($event)" ref="date2" :minimum-view="'year'" :disabled-dates="disabledDates" :wrapper-class="'form-control'" :input-class="'datePicker'" :format="customFormatter"></datepicker>
        </div>
        <filter-string v-model="make" :text="'Make'"></filter-string>
        <filter-string v-model="model" :text="'Model'"></filter-string>
        <filter-select v-model="type" :data="itemtypes" :text="'Key type'"></filter-select>
        <filter-string v-model="price" :text="'Price'"></filter-string>
        <div class="mb-3 col-sm-1 col-sm-4 col-lg-3">
            <button v-on:click="createItemType" :disabled="buttonDisable" class="btn btn-primary">Create <i class="fas fa-plus"></i></button>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import axios from "axios"
    import Datepicker from "vuejs-datepicker";

    export default {
        mounted() {
            console.log('User create table mounted')
        },
        props: {
            itemtypes: {type: Array},
        },
        components: {
            Datepicker,
        },
        computed: {
            buttonDisable: function () {
                return this.yearFrom == '' || this.yearTo == '' || this.make == '' || this.model == '' || this.type == '' || this.price == '' || this.query;
            },
            disabledDates: function () {
                return {to: new Date((parseInt(this.yearFrom) + 1), 0, 1)};
            }
        },
        data: function () {
            return {
                query: false,
                csrf: $('meta[name="csrf-token"]').attr('content'),
                yearFrom: '',
                yearTo: '',
                make: '',
                model: '',
                type: '',
                price: '',
            };
        },
        methods: {
            fixDate(event) {
                return moment(event).format('YYYY');
            },
            customFormatter(date) {
                return moment(date).format('YYYY');
            },
            createItemType() {
                let vm = this;
                this.query = true;

                axios.post('/request/createItemType', {
                    _token: this.csrf,
                    yearFrom: this.yearFrom,
                    yearTo: this.yearTo,
                    make: this.make,
                    model: this.model,
                    type: this.type,
                    price: this.price,
                }).then(function (data) {
                    vm.query = false;
                    vm.yearFrom = '';
                    vm.yearTo = '';
                    vm.make = '';
                    vm.model = '';
                    vm.type = '';
                    vm.price = '';
                    vm.$refs.date1.clearDate();
                    vm.$refs.date2.clearDate();
                });
            },
        },
    }
</script>
