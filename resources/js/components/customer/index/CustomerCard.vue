<template>
    <div class="card customer-card">
        <div class="card-body">
            <p class="card-text text-center">{{ customer.name }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Customer card mounted.')
        },
        props: {
            customer: {type: Object}
        }
    }
</script>

<style>
    .customer-card:hover {
        background: #cdcdcd;
        cursor: pointer;
    }
</style>
