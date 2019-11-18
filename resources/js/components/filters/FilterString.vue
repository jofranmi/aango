<template>
    <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
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
            value: {type: String},
        },
    }
</script>
