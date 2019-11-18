<template>
<!--        <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ text }}</span>
            </div>
            <input v-bind:value="value" v-on:input="$emit('input', $event.target.value)" type="text" class="form-control">
        </div>-->
    <div class="input-group mb-3 col-sm-1 col-sm-4 col-lg-3">
        <div class="input-group-prepend">
            <span class="input-group-text">Date from</span>
        </div>
        <datepicker v-bind:value="value" @input="$emit('input', fixDate($event))" :wrapper-class="'form-control'" :input-class="'datePicker'" :format="customFormatter"></datepicker>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from "moment";

    export default {
        props: {
            text: {type: String},
            value: {type: String},
        },
        methods: {
            fixDate(event) {
                return moment(event).format('YYYY-MM-DD');
            },
            customFormatter(date) {
                return moment(date).format('MM-DD-YYYY');
            },
        },
        watch: {
            value: function () {
                this.$emit('input', this.fixDate(this.value));
            }
        },
        components: {
            Datepicker
        },
    }
</script>

<style>
    .datePicker {
        border: 0px;
        width: 100%;
        height: 100%;
    }
</style>
