<!--
<search-wrapper :resources="{{ $var->toJson() }}" :filter="'filter by property'">
    <template v-slot:default="slotProps">
    </template>
</search-wrapper>
-->
<template>
    <div>
        <slot v-bind:results="results"></slot>
    </div>
</template>

<script>
    export default {
        props: {
            resources: {type: Array},
        },
        data: function() {
            return {
                filter: '',
                query: '',
                unfilteredResults: this.resources,
                results: this.resources,
            }
        },
        methods: {
            updateQuery(query) {
                this.query = query;
            }
        },
        created: function() {
            this.$eventHub.$on('query', (data) => {
                this.query = data;

                if(this.query) {
                    this.results = this.unfilteredResults.filter((item) => {
                        if (typeof this.filter === 'object') {
                            let key = Object.keys(this.filter);
                            let value = [this.filter[key[0]]];

                            if (typeof item[key][value] == 'string') {
                                return item[key][value].toLowerCase().includes(this.query.toLowerCase());
                            } else if (typeof item[key][value] == 'number') {
                                return item[key][value] == this.query;
                            }
                        } else {
                            if (typeof item[this.filter] == 'string') {
                                return item[this.filter].toLowerCase().includes(this.query.toLowerCase());
                            } else if (typeof item[this.filter] == 'number') {
                                return item[this.filter] == this.query;
                            }
                        }
                    });
                } else {
                    this.results = this.unfilteredResults;
                }
            });

            this.$eventHub.$on('filter', (data) => {
                this.filter = data;
            });

            this.$eventHub.$on('updateSearchSourceData', (data) => {
                this.unfilteredResults = data;
                this.results = data;
            });
        },
    }
</script>
