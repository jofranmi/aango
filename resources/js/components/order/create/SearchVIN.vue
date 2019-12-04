<template>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">VIN</span>
        </div>
        <input v-model="vin" type="text" class="form-control" maxlength="17" placeholder="Search..." style="border: 0px;">
        <div class="input-group-append">
            <button :disabled="buttonDisable" v-on:click="searchVIN" class="btn btn-primary" type="button">Search</button>
        </div>
    </div>
</template>

<script>
	import axios from "axios";

    export default {
        data: function () {
            return {
                buttonDisable: true,
                csrf: $('meta[name="csrf-token"]').attr('content'),
                vin: '',
            };
        },
        watch: {
            vin: function (val) {
                if(val.length != 17) {
                    this.buttonDisable = true;
                } else {
                    this.buttonDisable = false;
                };
            },
        },
        methods: {
            searchVIN: function () {
                let eventHub = this.$eventHub;
                let vm = this;
                this.buttonDisable = true;

                axios.post('/request/getItemsFromVIN', {
                    _token: this.csrf,
                    vin: this.vin,
                }).then(function (data) {
                    eventHub.$emit('decodedVINWithKey', data.data);
                    vm.buttonDisable = false;
                });
            },
        },
        created: function() {
            this.$eventHub.$on('createOrder', () => {
                this.buttonDisable = true;
            });

            this.$eventHub.$on('orderCreated', () => {
                this.buttonDisable = false;
            });
        }
    }
</script>
