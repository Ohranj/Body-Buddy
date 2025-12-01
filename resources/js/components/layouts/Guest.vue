<script>
import Notification from '../Notification.vue'

export default {
    components: {
        Notification
    },
}
</script>

<template>
    <Notification />
    <slot name="main" />
</template>