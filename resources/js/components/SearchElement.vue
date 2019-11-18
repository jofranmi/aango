<template>
    <div class="input-group mb-3">
        <input v-model="query" type="text" class="form-control" placeholder="Search...">
        <div class="input-group-append">
            <select v-model="filter" class="form-control" name="filter" id="filter" style="border-radius: 0; border: 0; background-color: #e9ecef;">
                <option v-for="(filterBy, name) in filters" :value="filterBy">{{ name }}</option>
            </select>
            <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Search element mounted.');
            this.$eventHub.$emit('filter', this.filter);
        },
        data: function() {
            return {
                query: '',
                filter: this.filters[Object.keys(this.filters)[0]],
            };
        },
        props: {
            filters: {type: Object},
        },
        watch: {
            query: function (val) {
                this.$eventHub.$emit('query', val);
            },
            filter: function (val) {
                this.$eventHub.$emit('filter', val);
            },
        }
    }
</script>
