<template>
    <div class="form-row">
        <filter-string v-model="name" :text="'Name'"></filter-string>
        <filter-string v-model="email" :text="'Email'"></filter-string>
        <filter-string v-model="password" :text="'Password'"></filter-string>
        <filter-select v-model="type" :data="usertypes" :text="'User type'"></filter-select>
        <filter-select v-model="customer" :data="customers" :text="'Customer'" v-if="type == 3"></filter-select>
        <div class="mb-3 col-sm-1 col-sm-4 col-lg-3">
            <button v-on:click="createUser" :disabled="buttonDisable" class="btn btn-primary">Create <i class="fas fa-plus"></i></button>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import axios from "axios"

    export default {
        mounted() {
            console.log('User create table mounted')
        },
        props: {
            customers: {type: Array},
            usertypes: {type: Array},
        },
        computed: {
            buttonDisable: function () {
                return this.name == '' || this.email == '' || this.password == '' || this.type == '' || (this.type == '3' && this.customer == '') || this.query;
            }
        },
        data: function () {
            return {
                query: false,
                csrf: $('meta[name="csrf-token"]').attr('content'),
                name: '',
                email: '',
                password: '',
                type: '',
                customer: '',
            };
        },
        methods: {
            createUser() {
                let vm = this;
                this.query = true;

                axios.post('/request/createUser', {
                    _token: this.csrf,
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    type: this.type,
                    customer: this.customer,
                }).then(function (data) {
                    vm.query = false;
                    vm.name = '';
                    vm.email = '';
                    vm.password = '';
                    vm.type = '';
                    vm.customer = '';
                });
            },
        },
    }
</script>
