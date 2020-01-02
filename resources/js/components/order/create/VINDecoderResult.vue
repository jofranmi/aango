<template>
    <div>
        <hr>
        <div v-if="error" class="alert alert-danger fade show" role="alert">
            <strong>Error!</strong>
            <hr>
            <p class="mb-0">{{ errorMessage }}</p>
        </div>
        <div v-if="!error && vehicle && key" class="card text-white bg-secondary">
            <div class="card-body">
                <h5 class="card-title">{{ vehicle.year + ' ' + vehicle.make + ' ' + vehicle.model }}</h5>
                <h6 class="card-subtitle mb-2">{{ key.item.name }}</h6>
                <p class="card-text">{{ key.price | money }}</p>
            </div>
        </div>
        <hr>
        <div v-if="!error && vehicle && key" class="card text-white bg-secondary">
            <div class="card-body">
                <div class="form-row">
                    <div class="input-group mb-3 col-sm-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Service Locations</span>
                        </div>
                        <select class="custom-select">
                            <option v-for="location in service_locations">{{ location.name }}</option>
                        </select>
                    </div>
                    <div class="input-group mb-3 col-sm-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Service Location Name</span>
                        </div>
                        <input v-model="service_name" type="text" class="form-control">
                    </div>
                    <div class="input-group mb-3 col-sm-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Address</span>
                        </div>
                        <input v-model="service_address" type="text" class="form-control">
                    </div>
                    <div class="input-group mb-3 col-sm-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Address #2</span>
                        </div>
                        <input v-model="service_address2" type="text" class="form-control">
                    </div>
                    <div class="input-group mb-3 col-sm-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text">City</span>
                        </div>
                        <input v-model="service_city" type="text" class="form-control">
                    </div>
                    <div class="input-group mb-3 col-sm-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text">State</span>
                        </div>
                        <select v-model="service_state" class="custom-select">
                            <option v-for="(state, stt) in states" :value="stt">{{ state }}</option>
                        </select>
                    </div>
                    <div class="input-group mb-3 col-sm-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Zip</span>
                        </div>
                        <input v-model="service_zip_code" type="text" maxlength="5" class="form-control">
                    </div>
                    <div class="input-group mb-3 col-sm-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Phone</span>
                        </div>
                        <input v-model="service_phone" type="tel" maxlength="10" class="form-control">
                    </div>
                </div>
                <button :disabled="buttonDisable | query" v-on:click="createOrder" type="button" class="btn btn-primary">Create order</button>
            </div>
        </div>
    </div>
</template>

<script>
	import axios from "axios";

    export default {
        data: function () {
            return {
                query: false,
                error: false,
                errorMessage: '',
                key: null,
                csrf: $('meta[name="csrf-token"]').attr('content'),
                vehicle: null,
                vin: '',

                states: [],
                service_locations: [],
                service_name: '',
                service_address: '',
                service_address2: '',
                service_city: '',
                service_state: '',
                service_zip_code: '',
                service_phone: '',
            };
        },
        created: function() {
            this.$eventHub.$on('decodedVINWithKey', (data) => {
                this.error = data.request.errorCode != '0';
                this.errorMessage = data.request.errorResponse;
                this.key = data.key;
                this.vehicle = data.request.vehicle;
                this.vin = data.vin;
                this.states = data.states;
                this.service_locations = data.service_locations;
            });
        },
        methods: {
            createOrder: function () {
                let eventHub = this.$eventHub;
                let vm = this;
                let service_location = {
                	name: this.service_name,
                	address: this.service_address,
                	address2: this.service_address2,
                	city: this.service_city,
                	state: this.service_state,
                	zip_code: this.service_zip_code,
                	phone: this.service_phone,
                }
                vm.query = true;
                eventHub.$emit('createOrder')

                axios.post('/request/createOrder', {
                    _token: this.csrf,
                    key: this.key,
                    service_location: service_location,
                    vehicle: this.vehicle,
                    vin: this.vin,
                }).then(function () {
                    eventHub.$emit('orderCreated');
                    vm.query = false;
                    vm.key = null;
                    vm.vehicle = null;
                    vm.vin = null;

                    vm.service_locations = [];
                    vm.service_name = null;
                    vm.service_address = null;
                    vm.service_address2 = null;
                    vm.service_city = null;
                    vm.service_state = null;
                    vm.service_zip_code = null;
                    vm.service_phone = null;
                });
            },
        },
		computed: {
			buttonDisable: function () {
				return this.service_name == '' || this.service_address == '' || this.service_city == '' || this.service_state == '' || this.service_zip_code == '' || this.query;
			},
		},
    }
</script>
