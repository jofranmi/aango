<template>
    <div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Remove</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Remove {{ user.name }} from this customer?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button @click="remove" :disabled="query" type="button" class="btn btn-primary">Remove</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        data: function () {
            return {
                query: false,
                csrf: $('meta[name="csrf-token"]').attr('content'),
                user: {},
            };
        },
        methods: {
            remove() {
                let eventHub = this.$eventHub;
                let user = this.user;
                let vm = this;
                this.query = true;

                axios.post('/request/removeUserFromCustomer', {
                    _token: this.csrf,
                    id: this.user.id,
                }).then(function () {
                    $('#removeModal').modal('hide')
                    vm.user = {};
                    vm.query = true;
                    eventHub.$emit('userRemoved', user);
                });
            },
        },
        created: function() {
            this.$eventHub.$on('setUserToRemoveFromCustomer', (user) => {
                this.user = user;

                $('#removeModal').modal('show')
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
