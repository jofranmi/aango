<template>
    <div class="notificationBar">
        <div v-for="notification in notifications" :class="notification.alert" class="alert alert-dismissible fade show animated fadeInUp" role="alert">
            {{ notification.message }}
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            let vm = this;
            console.log('Notification bar mounted.');
            Echo.channel('notifications')
                .listen('.my-event', function(event) {
                    vm.notifications.push(event.notification);
                });
        },
        data: function () {
            return {
                notifications: [],
            };
        },
    }
</script>

<style>
    .notificationBar {
        width: 250px;
        height: 100vh;
        overflow: hidden;
        position: fixed;
        z-index: 10;
        left: calc(100% - 250px);
    }
</style>
