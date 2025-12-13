<script>
    import Chevron from './svg/Chevron.vue';

    export default {
        props: {
            timestamp: Number
        },
        components: {
            Chevron
        }
    }
</script>

<template>
    <div class="w-fit mx-auto mt-12">
        <div class="flex items-center justify-center md:justify-start gap-x-4">
            <Link :href="'/dashboard?timestamp=' + (this.timestamp - 86400)" class="cursor-pointer hover:bg-zinc-800 rounded-md">
                <Chevron class="w-24 h-24 rotate-90" stroke="#FFFFFF" fill="none" />
            </Link>
            <Link :href="'/dashboard?timestamp=' + (this.timestamp + 86400)" class="cursor-pointer hover:bg-zinc-800 rounded-md">
                <Chevron class="w-24 h-24 -rotate-90" stroke="#FFFFFF" fill="none" />
            </Link>
        </div>
        <small class="text-center block tracking-wide font-semibold mt-1 md:underline underline-offset-4">MOVE BETWEEN DAYS</small>
    </div>
</template>