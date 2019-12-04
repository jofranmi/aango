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
                <orders-table-line v-for="(order, key) in paginatedOrders" :order="order" :statuses="statuses" :authorized="authorized" :key="key"></orders-table-line>
            </tbody>
        </table>
    </div>
</template>

<script>
    import axios from "axios"

    export default {
        props: {
            data: {type: Array},
            statuses: {type: Array},
            authorized: {type: Boolean}
        },
        data: function () {
            return {
                query: false,
                csrf: $('meta[name="csrf-token"]').attr('content'),
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
            updateOrder(id) {
                let vm = this;
                this.queue = true;

                axios.post('/request/updateOrder', {
                    _token: this.csrf,
                    id: id,
                }).then(function() {
                    vm.query = false;
                });
            }
        },
        computed: {
            pageCount() {
                let length = this.orders.length;
                let size = this.size;

                return Math.ceil(length/size);
            },
                paginatedOrders() {
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
