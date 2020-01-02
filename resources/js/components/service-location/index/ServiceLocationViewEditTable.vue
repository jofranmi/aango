<template>
    <div>
        <div class="form-row">
            <filter-string v-model="name" :text="'Name'"></filter-string>
            <filter-string v-model="address" :text="'Address'"></filter-string>
            <filter-string v-model="address2" :text="'Address #2'"></filter-string>
            <filter-string v-model="city" :text="'City'"></filter-string>
            <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">State</span>
                </div>
                <select v-model="state" class="custom-select">
                    <option v-for="(state, stt) in states" :value="stt">{{ state }}</option>
                </select>
            </div>
            <filter-string v-model="zip_code" :text="'Zip Code'"></filter-string>
            <filter-string v-model="phone" :text="'Phone'"></filter-string>
            <div class="mb-3 col-sm-1 col-sm-4 col-lg-3">
                <button v-on:click="editServiceLocation" :disabled="buttonDisable" class="btn btn-primary">Edit <i class="fas fa-pen"></i></button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios"

    export default {
        props: {
            states: {type: Object}
        },
        computed: {
            buttonDisable: function () {
                return this.id == '' || this.name == '' || this.address == '' || this.city == '' || this.state == '' || this.zip_code == '' || this.query;
            }
        },
        data: function () {
            return {
                query: false,
                csrf: $('meta[name="csrf-token"]').attr('content'),
                id: '',
                name: '',
                address: '',
                address2: '',
                city: '',
                state: '',
                zip_code: '',
                phone: '',
            };
        },
        methods: {
            editServiceLocation() {
                let vm = this;
                this.query = true;

                axios.post('/request/editServiceLocation', {
                    _token: this.csrf,
                    id: this.id,
                    name: this.name,
                    address: this.address,
                    address2: this.address2,
                    city: this.city,
                    state: this.state,
                    zip_code: this.zip_code,
                    phone: this.phone,
                }).then(function () {
                    vm.query = false;
                });
            },
        },
        created: function() {
            this.$eventHub.$on('viewEditServiceLocation', (data) => {
                this.id = data.id;
                this.name = data.name;
                this.address = data.address;
                this.address2 = data.address2;
                this.city = data.city;
                this.state = data.state;
                this.zip_code = data.zip_code;
                this.phone = data.phone;

                $('#navigation a:last-child').tab('show')
            });
        },
    }
</script>
