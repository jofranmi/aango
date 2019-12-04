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
        <th v-else scope="col">{{ order.status.name }}</th>
        <th  scope="col">
            <button v-if="authorized" :disabled="query" @click="updateOrder" type="button" class="btn btn-sm btn-primary text-light">Update</button>
            <button :disabled="query" @click="getOrderDetailsFromVIN" type="button" class="btn btn-sm btn-secondary text-light">View</button>
        </th>
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
            updateOrder() {
                let vm = this;
                this.query = true;

                axios.post('/request/updateOrderStatus', {
                    _token: this.csrf,
                    id: vm.order.id,
                    status: vm.status
                }).then(function() {
                    vm.query = false;
                });
            },
			getOrderDetailsFromVIN() {
				let eventHub = this.$eventHub;
				let vm = this;
				this.query = true;

				axios.post('/request/getOrderDetailsFromVIN', {
					_token: this.csrf,
					id: this.order.id
				}).then(function (data) {
					vm.query = false;
					eventHub.$emit('setOrderToView', data.data);
				});
			}
        },
    }
</script>
