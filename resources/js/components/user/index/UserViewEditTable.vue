<template>
    <div>
        <div class="form-row">
            <filter-string v-model="name" :text="'Name'"></filter-string>
            <filter-string v-model="email" :text="'Email'"></filter-string>
            <filter-string v-model="password" :text="'Password'"></filter-string>
            <div class="mb-3 col-sm-1 col-sm-4 col-lg-3">
                <button v-on:click="editUser" :disabled="buttonDisable" class="btn btn-primary">Edit <i class="fas fa-pen"></i></button>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import axios from "axios"

    export default {
        mounted() {
            console.log('User view table mounted.')
        },
        computed: {
            buttonDisable: function () {
                return this.name == '' || this.email == '' || this.query;
            }
        },
        data: function () {
            return {
                query: false,
                csrf: $('meta[name="csrf-token"]').attr('content'),
                id: '',
                name: '',
                email: '',
                password: '',
            };
        },
        methods: {
            editUser() {
                let eventHub = this.$eventHub;
                let vm = this;
                this.query = true;

                axios.post('/request/editUser', {
                    _token: this.csrf,
                    id: this.id,
                    name: this.name,
                    email: this.email,
                    password: this.password,
                }).then(function(data) {
                    vm.query = false;
                });
            },
            setCustomerToRemove(user) {
                this.$eventHub.$emit('setUserToRemoveFromCustomer', user);
            },
        },
        created: function() {
            this.$eventHub.$on('viewEditUser', (data) => {
                this.customer = data.customer;
                this.id = data.id;
                this.name = data.name;
                this.email = data.email;

                $('#navigation a:last-child').tab('show')
            });

            this.$eventHub.$on('userDeleted', (user) => {
                let usersArray = this.customer.users;
            });
        },
    }
</script>
