<script>
import Chevron from './svg/Chevron.vue';

export default {
    components: {
        Chevron
    },
    props: {
        user: Object
    }
}
</script>

<template>
    <div class="h-[75px] flex items-center justify-end">
        <button class="rounded-md cursor-pointer p-2 text-sm min-w-[95px] flex items-center gap-x-1"><span v-text="user.forename + ' ' + user.surname"></span>
            <Chevron class="w-4 h-4" stroke="#FFFFFF" fill="none" />
        </button>
    </div>
</template>