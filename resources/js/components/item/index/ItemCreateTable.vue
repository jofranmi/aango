<template>
    <div class="row">
        <div class="mb-3 col-lg-6 col-xl-3">
            <select v-model="make" class="custom-select" :size="makes.length <= 1 ? 2 : makes.length" :disabled="query" style="overflow-y: auto;">
                <option v-for="make in makes" @click="getModelsFromMake(make.make)">{{ make.make }}</option>
            </select>
        </div>
        <div class="mb-3 col-lg-6 col-xl-3">
            <select v-model="model" class="custom-select" :size="models.length <= 1 ? 2 : models.length" :disabled="query" style="overflow-y: auto;">
                <option v-for="model in models">{{ model.model }}</option>
            </select>
        </div>
        <div class="mb-3 col-lg-12 col-xl-6">
            <div class="form-row">
                <div class="input-group mb-3 col-xs-12">
                    <div class="input-group-prepend">
                        <span class="input-group-text">VIN</span>
                    </div>
                    <input v-model="vin" type="text" class="form-control" maxlength="17" placeholder="Search..." style="border: 0px;">
                    <div class="input-group-append">
                        <button :disabled="query || vin == ''" v-on:click="searchVIN" class="btn btn-primary" type="button">Search</button>
                    </div>
                </div>
                <div class="input-group mb-3 col-md-12 col-lg-6">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Year from</span>
                    </div>
                    <datepicker @input="yearFrom = fixDate($event)" ref="date1" :minimum-view="'year'" :wrapper-class="'form-control'" :input-class="'datePicker'" :format="customFormatter"></datepicker>
                </div>
                <div class="input-group mb-3 col-md-12 col-lg-6">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Year to</span>
                    </div>
                    <datepicker @input="yearTo = fixDate($event)" ref="date2" :minimum-view="'year'" :disabled-dates="disabledDates" :wrapper-class="'form-control'" :input-class="'datePicker'" :format="customFormatter"></datepicker>
                </div>
                <filter-string v-model="make" :text="'Make'" :large="true"></filter-string>
                <filter-string v-model="model" :text="'Model'" :large="true"></filter-string>
                <filter-select v-model="type" :data="itemtypes" :text="'Key type'" :large="true"></filter-select>
                <filter-string v-model="price" :text="'Price'" :large="true"></filter-string>
                <div class="mb-3 col-sm-1 col-sm-4 col-lg-3">
                    <button v-on:click="createItemVehicle" :disabled="buttonDisable" class="btn btn-primary">Create <i class="fas fa-plus"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import axios from "axios";
    import Datepicker from "vuejs-datepicker";

    export default {
        props: {
            itemtypes: {type: Array},
            makeslist: {type: Array},
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
                vin: '',
                makes: this.makeslist,
                models: [],
            };
        },
        methods: {
            fixDate(event) {
                return moment(event).format('YYYY');
            },
            customFormatter(date) {
                return moment(date).format('YYYY');
            },
            createItemVehicle() {
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
                }).then(function () {
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
            getModelsFromMake (make) {
                let vm = this;
                this.query = true;
                this.models = [];

                axios.post('/request/getModelsFromMake', {
                    _token: this.csrf,
                    make: make,
                }).then(function (data) {
                    vm.query = false;
                    vm.models = data.data;
                });
            },
            searchVIN: function () {
                let vm = this;
                this.query = true;

                $.post('/request/getVehicleFromVIN', {
                    _token: this.csrf,
                    vin: this.vin,
                }).done(function (data) {
                    vm.getModelsFromMake(data.vehicle.make);
                    vm.make = data.vehicle.make;
                    vm.model = data.vehicle.model;
                    vm.query = false;
                    vm.vin = '';
                });
            },
        },
    }
</script>
