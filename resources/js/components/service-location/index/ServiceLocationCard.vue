<template>
    <div @click="viewEdit" class="card service-location-card bg-secondary text-light">
        <div class="card-body">
            <p class="card-text text-center">{{ service_location.name }}</p>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        props: {
            service_location: {type: Object}
        },
        methods: {
            viewEdit() {
                let eventHub = this.$eventHub;

                axios.post('/request/getServiceLocation', {
                    _token: this.csrf,
                    id: this.service_location.id,
                }).then(function (data) {
                    eventHub.$emit('viewEditServiceLocation', data.data);
                });
            },
        },
    }
</script>

<style>
    .service-location-card:hover {
        background-color: #576068 !important;
        cursor: pointer;
    }
</style>
