<template>
    <tr>
        <th scope="col">{{ order.id }}</th>
        <th scope="col">{{ order.vin }}</th>
        <th scope="col">{{ order.year + ' ' + order.make + ' ' + order.model }}</th>
        <th scope="col">{{ order.items[0].item.name }}</th>
        <th scope="col">{{ order.total | money }}</th>
        <th scope="col">{{ order.user.name }}</th>
        <th scope="col">{{ order.created_at | date }}</th>
        <th v-if="authorized" scope="col">
            <select v-model="status" class="custom-select custom-select-sm">
                <option v-for="status in statuses" :value="status.id">{{ status.name }}</option>
            </select>
        </th>
        <th v-if="authorized" scope="col">
            <button :disabled="query" @click="updateOrder" type="button" class="btn btn-sm btn-primary text-light">Update</button>
        </th>
        <th v-else scope="col">{{ order.status.name }}</th>
    </tr>
</template>

<script>
    import axios from "axios"

    export default {
        props: {
            order: {type: Object},
            statuses: {type: Array},
            authorized: {type: Boolean}
        },
        data: function () {
            return {
                query: false,
                csrf: $('meta[name="csrf-token"]').attr('content'),
                status: this.order.status_id,
            };
        },
        methods: {
            updateOrder(id) {
                let vm = this;
                this.queue = true;

                axios.post('/request/updateOrderStatus', {
                    _token: this.csrf,
                    id: vm.order.id,
                    status: vm.status
                }).then(function(data) {
                    vm.query = false;
                });
            }
        },
    }
</script>
