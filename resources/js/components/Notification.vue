<script>
import {notifications} from '../store'

export default {
    data() {
        return {
            notifications
        }
    },
    methods: {
        notificationClicked(index) {
            this.notifications.hide(index)
        }
    }
}
</script>

<template>
    <div class="fixed space-y-1.5 w-full flex items-center flex-col justify-center">
        <template v-for="(x, i) in notifications.items">
            <div v-show="x.show" class="flex flex-col w-5/6 sm:w-[375px] cursor-pointer shadow shadow-zinc-700 rounded-md p-1.5 relative" :class="x.success ? 'bg-lime-700' : 'bg-red-500 hover:bg-red-600'" @click="notificationClicked(i)">
                <small class="font-semibold" v-text="x.success ? 'Success!' : 'Error!'"></small>
                <small class="text-xs" v-text="x.message"></small>
                <span class="text-lg absolute top-0 right-2">×</span>
            </div>
        </template>
    </div>
</template>