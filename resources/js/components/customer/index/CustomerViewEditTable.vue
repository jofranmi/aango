<template>
    <div>
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
                <select v-model="state" class="custom-select">
                    <option v-for="(state, stt) in states" :value="stt">{{ state }}</option>
                </select>
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
                <button v-on:click="editCustomer" :disabled="buttonDisable || customer == ''" class="btn btn-primary">Edit <i class="fas fa-pen"></i></button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="input-group mb-3">
                    <select v-model="user_id" class="custom-select">
                        <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                    </select>
                    <div class="input-group-append">
                        <button @click="addUserToCustomer" :disabled="query || user_id == ''" class="btn btn-primary" type="button">Add <i class="fas fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-columns">
            <div v-for="user in customer.users" class="card bg-secondary text-light">
                <div class="card-body">
                    <span class="card-text">{{ user.name }}</span>
                    <button @click="setCustomerToRemove(user)" :disabled="query" class="btn btn-sm btn-danger float-right my-auto" data-toggle="modal" data-target="#removeModal">Remove</button>
                </div>
            </div>
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
                customer: '',
                users: '',
                user_id: '',
                name: '',
                address: '',
                city: '',
                state: '',
                zip_code: '',
                phone: '',
            };
        },
        methods: {
            editCustomer() {
                let vm = this;
                this.query = true;

                axios.post('/request/editCustomer', {
                    _token: this.csrf,
                    id: this.customer.id,
                    name: this.name,
                    address: this.address,
                    city: this.city,
                    state: this.state,
                    zip_code: this.zip_code,
                    phone: this.phone,
                }).then(function(data) {
                    vm.query = false;
                });
            },
            addUserToCustomer() {
                let vm = this;
                this.query = true;
                let usersArray = this.users;

                axios.post('/request/addUserToCustomer', {
                    _token: this.csrf,
                    customer_id: this.customer.id,
                    user_id: this.user_id,
                }).then(function(user) {
                    vm.query = false;
                    vm.users = usersArray.filter(function(oldUser) {
                        return oldUser.id != user.data.id && oldUser.name != user.data.name;
                    });
                    vm.user_id = '';
                    vm.customer.users.push(user.data);
                });
            },
            setCustomerToRemove(user) {
                this.$eventHub.$emit('setUserToRemoveFromCustomer', user);
            },
        },
        created: function() {
            this.$eventHub.$on('viewEditCustomer', (data) => {
                this.customer = data.customer;
                this.name = data.customer.name;
                this.address = data.customer.address;
                this.city = data.customer.city;
                this.state = data.customer.state;
                this.zip_code = data.customer.zip_code;
                this.phone = data.customer.phone;
                this.users = data.users;

                $('#navigation a:last-child').tab('show')
            });

            this.$eventHub.$on('userRemoved', (user) => {
                let usersArray = this.customer.users;

                this.customer.users = usersArray.filter(function(oldUser) {
                    return oldUser.id != user.id && oldUser.name != user.name;
                });
            });
        },
    }
</script>
