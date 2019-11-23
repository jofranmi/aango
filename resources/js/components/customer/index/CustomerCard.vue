<template>
    <div @click="viewEdit" class="card customer-card bg-secondary text-light">
        <div class="card-body">
            <p class="card-text text-center">{{ customer.name }}</p>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        mounted() {
            console.log('Customer card mounted.')
        },
        props: {
            customer: {type: Object}
        },
        methods: {
            viewEdit() {
                let eventHub = this.$eventHub;

                axios.post('/request/getCustomerWithUsers', {
                    _token: this.csrf,
                    id: this.customer.id,
                }).then(function (data) {
                    eventHub.$emit('viewEdit', data.data);
                });
            },
        },
    }
</script>

<style>
    .customer-card:hover {
        background-color: #576068 !important;
        cursor: pointer;
    }
</style>
