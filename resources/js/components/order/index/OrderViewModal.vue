<template>
    <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Order #{{ order.id }} details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <b>Vehicle:</b> {{ order.year + ' ' + order.make + ' ' + order.model }} <br>
                    <b>VIN:</b> {{ order.vin }} <br>
                    <b>Item:</b> {{ 'items' in order ? order.items[0].item.name : ''}} <br>
                    <b>Price:</b> {{ 'items' in order ? price : ''}} <br>
                    <b>Status:</b> {{ 'status' in order ? order.status.name : '' }} <br>
                    <b>Placed on:</b> {{ order.created_at | datetimeVerbose }} <br>
                    <b>Placed by:</b> {{ 'user' in order ? order.user.name : '' }} <br>
                </div>
                <div v-if="authorized" class="container-fluid">
                    <div class="form-group">
                        <textarea v-model="comment" class="form-control" rows="3"></textarea>
                    </div>
                    <button :disabled="query" @click="createOrderComment" type="button" class="btn btn-primary">Add comment</button>
                    <hr>
                </div>
                <div v-for="comment in order.comments" class="container-fluid mb-3">
                    <div style="border: 1px solid #1b2026; border-radius: 3px; padding: 5px;">
                        <p>{{ comment.comment }}</p>
                        <h6 class="card-subtitle text-muted">{{ comment.user.name }} at {{ comment.created_at | datetime }}</h6>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
		props: {
			authorized: {type: Boolean}
		},
        data: function () {
            return {
                query: false,
                csrf: $('meta[name="csrf-token"]').attr('content'),
                order: {},
                comment: '',
            };
        },
		computed: {
			price: function () {
				return this.$options.filters.money(this.order.items[0].price);
			},
		},
        methods: {
			createOrderComment() {
                let vm = this;
                this.query = true;

                axios.post('/request/createOrderComment', {
                    _token: this.csrf,
                    id: this.order.id,
                    comment: this.comment,
                }).then(function (data) {
                    vm.comment = '';
                    vm.query = false;
                    vm.order.comments.unshift(data.data);
                });
            },
        },
        created: function() {
            this.$eventHub.$on('setOrderToView', (order) => {
                this.order = order;

                $('#orderModal').modal('show')
            });
        },
    }
</script>

<style>
    .modal-header {
        border-bottom: 1px solid #1b2026;
    }

    .modal-footer {
        border-top: 1px solid #1b2026;
    }
</style>
