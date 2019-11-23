<template>
    <div class="form-row">
        <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Name</span>
            </div>
            <input v-model="name" type="text" class="form-control">
        </div>
        <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Address</span>
            </div>
            <input v-model="address" type="text" class="form-control">
        </div>
        <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
            <div class="input-group-prepend">
                <span class="input-group-text">City</span>
            </div>
            <input v-model="city" type="text" class="form-control">
        </div>
        <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
            <div class="input-group-prepend">
                <span class="input-group-text">State</span>
            </div>
            <input v-model="state" type="text" class="form-control">
        </div>
        <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Zip</span>
            </div>
            <input v-model="zip_code" type="text" maxlength="5" class="form-control">
        </div>
        <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Phone</span>
            </div>
            <input v-model="phone" type="tel" maxlength="10" class="form-control">
        </div>
        <div class="mb-3 col-sm-1 col-sm-4 col-lg-3">
            <button v-on:click="create" :disabled="buttonDisable" class="btn btn-primary">Create <i class="fas fa-plus"></i></button>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import axios from "axios"

    export default {
        mounted() {
            console.log('Customer create table mounted.')
        },
        props: {
            states: {type: Object}
        },
        computed: {
            buttonDisable: function () {
                return this.name == '' || this.address == '' || this.city == '' || this.state == '' || this.zip_code == '' || this.phone == '' || this.query;
            }
        },
        data: function () {
            return {
                query: false,
                csrf: $('meta[name="csrf-token"]').attr('content'),
                name: '',
                address: '',
                city: '',
                state: '',
                zip_code: '',
                phone: '',
            };
        },
        methods: {
            create() {
                let eventHub = this.$eventHub;
                let vm = this;
                this.query = true;

                axios.post('/request/createCustomer', {
                    _token: this.csrf,
                    name: this.name,
                    address: this.address,
                    city: this.city,
                    state: this.state,
                    zip_code: this.zip_code,
                    phone: this.phone,
                }).then(function (data) {
                    vm.query = false;
                    vm.name = '';
                    vm.address = '';
                    vm.city = '';
                    vm.state = '';
                    vm.zip_code = '';
                    vm.phone = '';
                });
            },
        },
    }
</script>
