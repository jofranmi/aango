<template>
    <div>
        <div class="row">
            <div class="input-group mb-3 col-sm-12 col-sm-5 col-md-5 col-lg-4 col-xl-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Show</span>
                </div>
                <select v-model="size" class="form-control" style="border: 0px;">
                    <option value="1000" selected>All</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <div class="input-group-append">
                    <button :disabled="pageNumber == 0" @click="prevPage" class="btn btn-primary"><i class="fas fa-angle-double-left"></i></button>
                    <button :disabled="pageNumber >= pageCount -1" @click="nextPage" class="btn btn-primary"><i class="fas fa-angle-double-right"></i></button>
                </div>
            </div>
        </div>
        <table class="table table-hover table-dark text-center">
            <thead>
            <tr>
                <th scope="col">Order #</th>
                <th scope="col">VIN</th>
                <th scope="col">Vehicle</th>
                <th scope="col">Item</th>
                <th scope="col">Total</th>
                <th scope="col">Placed by</th>
                <th scope="col">Placed on</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="order in Porders">
                <th scope="col">{{ order.id }}</th>
                <th scope="col">{{ order.vin }}</th>
                <th scope="col">{{ order.year + ' ' + order.make + ' ' + order.model }}</th>
                <th scope="col">{{ order.items[0].item.name }}</th>
                <th scope="col">{{ order.total | money }}</th>
                <th scope="col">{{ order.user.name }}</th>
                <th scope="col">{{ order.created_at | date }}</th>
                <th scope="col">{{ order.status.name }}</th>
                <th scope="col"><button typeof="button" class="btn btn-sm btn-danger" href="">Cancel</button></th>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import moment from "moment";

    export default {
        mounted() {
            console.log('Orders table mounted.')
        },
        props: {
            data: {type: Array},
            authorized: {type: Boolean}
        },
        data: function () {
            return {
                orders: this.data,
                pageNumber: 0,
                size: 25,
            };
        },
        methods: {
            nextPage() {
                this.pageNumber++;
            },
            prevPage() {
                this.pageNumber--;
            },
        },
        computed: {
            pageCount() {
                let length = this.orders.length;
                let size = this.size;

                return Math.ceil(length/size);
            },
                Porders() {
                const start = this.pageNumber * this.size,
                    end = start + this.size;
                return this.orders.slice(start, end);
            }
        },
        created: function() {
            this.$eventHub.$on('filterResult', (data) => {
                this.orders = data;
            });
        },
    }
</script>
