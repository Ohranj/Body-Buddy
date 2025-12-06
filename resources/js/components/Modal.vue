<script>
import Close from '../components/svg/Close.vue';

export default {
    components: {
        Close
    },
    props: {
        show: Boolean,
        title: String
    },
    emits: ['toggleModal']
}
</script>

<template>
    <div v-show="show" class="fixed inset-0 z-50 backdrop-blur-sm bg-[#D3D3D340]">
        <div class="flex min-h-screen items-center justify-center px-2">
            <div class="min-h-[375px] relative min-w-[300px] w-[475px] flex flex-col rounded-md shadow-md shadow-black bg-zinc-100 text-black">
                <div class="p-4 flex items-center justify-between">
                    <h1 class="font-semibold tracking-wide text-lg" v-text="title"></h1>
                    <button @click="$emit('toggleModal')" class="hover:scale-[1.2]">
                        <Close class="w-7 h-7 cursor-pointer" stroke="#000000" fill="none"/>
                    </button>
                </div>
                <hr>
                <div class="p-4">
                    <slot name="content" />
                </div>
            </div>
        </div>
    </div>
</template>