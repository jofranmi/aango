<template>
    <div @click="viewEdit" class="card item-card bg-secondary text-light">
        <div class="card-body text-center">
            <p class="card-text">{{ item.year_from + '-' + item.year_to + ' ' + item.make + ' ' + item.model }}</p>
            <p class="card-subtitle">{{ item.item.name }} - {{ item.price | money }}</p>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        props: {
            item: {type: Object}
        },
        methods: {
            viewEdit() {
                let eventHub = this.$eventHub;

                axios.post('/request/getItemVehicle', {
                    _token: this.csrf,
                    id: this.item.id,
                }).then(function (data) {
                    eventHub.$emit('viewEditItemVehicle', data.data);
                });
            },
        },
    }
</script>

<style>
    .item-card:hover {
        background-color: #576068 !important;
        cursor: pointer;
    }
</style>
