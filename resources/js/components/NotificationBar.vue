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
            Echo.channel('notifications')
                .listen('.my-event', function(event) {
                    console.log('event');
                    vm.notifications.push(event.notification);
                });

            Echo.private('user-' + this.user_id)
                .listen('.my-event', function(event) {
                    vm.notifications.push(event.notification);
                });

            Echo.private('admin')
                .listen('.my-event', function(event) {
                    vm.notifications.push(event.notification);
                });

            Echo.private('office')
                .listen('.my-event', function(event) {
                    vm.notifications.push(event.notification);
                });
        },
        props: {
            user_id: {type: Number},
        },
        data: function () {
            return {
                notifications: [],
            };
        },
        created: function() {
            this.$eventHub.$on('localNotification', (data) => {
                this.notifications.push(data);
            });
        },
    }
</script>

<style>
    .notificationBar {
        width: 250px;
        height: 100vh;
        overflow: hidden;
        position: fixed;
        z-index: 1500;
        left: calc(100% - 250px);
    }
</style>
