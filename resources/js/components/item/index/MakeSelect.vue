<template>
    <select v-model="make" class="custom-select" :size="makes.length + 1" :disabled="query" style="overflow-y: auto;">
        <option>All</option>
        <option v-for="make in makes">{{ make.make }}</option>
    </select>
</template>

<script>
    import axios from "axios"

    export default {
        props: {
            makes: {type: Array}
        },
        data: function () {
            return {
                query: false,
                make: '',
            };
        },
        watch: {
            make: function (val) {
                let eventHub = this.$eventHub;
                let vm = this;
                this.query = true;

                axios.post('/request/getKeysForMake', {
                    _token: this.csrf,
                    make: this.make,
                }).then(function (data) {
                    vm.query = false;
                    eventHub.$emit('updateSearchSourceData', data.data);
                });
            },
        },
    }
</script>
