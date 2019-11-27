<template>
    <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{ text }}</span>
        </div>
        <select v-bind:value="value" v-on:input="$emit('input', $event.target.value)" class="custom-select">
            <option v-for="object in data" :value="object[Object.keys(object)[0]]">{{ object[Object.keys(object)[1]] | capitalize }}</option>
        </select>
    </div>
</template>

<script>
    export default {
        props: {
            text: {type: String},
            value: {type: String},
            data: {type: Array},
        },
    }
</script>
