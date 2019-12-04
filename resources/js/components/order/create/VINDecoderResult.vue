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
                <button :disabled="buttonDisable" v-on:click="createOrder" type="button" class="btn btn-primary">Create order</button>
            </div>
        </div>
    </div>
</template>

<script>
	import axios from "axios";

    export default {
        data: function () {
            return {
                buttonDisable: false,
                error: false,
                errorMessage: '',
                key: null,
                csrf: $('meta[name="csrf-token"]').attr('content'),
                vehicle: null,
                vin: ''
            };
        },
        created: function() {
            this.$eventHub.$on('decodedVINWithKey', (data) => {
                this.error = data.request.errorCode != '0';
                this.errorMessage = data.request.errorResponse;
                this.key = data.key;
                this.vehicle = data.request.vehicle;
                this.vin = data.vin;
            });
        },
        methods: {
            createOrder: function () {
                let eventHub = this.$eventHub;
                let vm = this;
                vm.buttonDisable = true;
                eventHub.$emit('createOrder')

                axios.post('/request/createOrder', {
                    _token: this.csrf,
                    key: this.key,
                    vehicle: this.vehicle,
                    vin: this.vin,
                }).then(function () {
                    eventHub.$emit('orderCreated');
                    vm.buttonDisable = false;
                    vm.key = null;
                    vm.vehicle = null;
                    vm.vin = null;
                });
            },
        }
    }
</script>
