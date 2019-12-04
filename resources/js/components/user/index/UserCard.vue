<template>
    <div @click="viewEdit" class="card user-card bg-secondary text-light">
        <div class="card-body">
            <p class="card-text text-center">{{ user.name }}</p>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        props: {
            user: {type: Object}
        },
        methods: {
            viewEdit() {
                let eventHub = this.$eventHub;

                axios.post('/request/getUser', {
                    _token: this.csrf,
                    id: this.user.id,
                }).then(function (data) {
                    eventHub.$emit('viewEditUser', data.data);
                });
            },
        },
    }
</script>

<style>
    .user-card:hover {
        background-color: #576068 !important;
        cursor: pointer;
    }
</style>
