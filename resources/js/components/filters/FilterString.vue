<template>
    <div class="input-group mb-3" :class="cols">
        <div class="input-group-prepend">
            <span class="input-group-text">{{ text }}</span>
        </div>
        <input v-bind:value="value" v-on:input="$emit('input', $event.target.value)" type="text" class="form-control">
    </div>
</template>

<script>
    export default {
        props: {
            text: {type: String},
            value: {type: [String, Number]},
            large: {type: Boolean},
        },
        computed: {
            cols: function () {
                if (this.large == true) {
                    return 'col-md-12 col-lg-6';
                }

                return 'col-sm-4 col-lg-3';
            },
        },
    }
</script>
