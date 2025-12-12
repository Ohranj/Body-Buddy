<script>
import Pen from './svg/Pen.vue';
import Clipboard from './svg/Clipboard.vue'

export default {
    components: {
        Pen, Clipboard
    },
    emits: ['toggleModal'],
    data() {
        return {
            progress: {
                bar: {
                    width: 0
                },
                count: {
                    show: false,
                    value: 92
                }
            }
        }
    },
    async mounted() {
        const target = 40;
        await new Promise((res) => setTimeout(() => res(), 500))

        while (this.progress.bar.width < target) {
            await new Promise((res) => setTimeout(() => res(), 20))
            this.progress.bar.width += 1;
        }
        this.progress.count.show = true
    }
}
</script>

<template>
    <div class="w-[575px] rounded-lg p-3.5 border text-sm h-[200px] flex flex-col relative shadow shadow-slate-500">
        <h1 class="text-lg font-semibold tracking-wide">Weight:</h1>
        <div class="grow grid grid-cols-1 grid-rows-3">
            <div class="flex flex-col">
                <small>Your starting weight was: <span class="font-semibold underline underline-offset-2">90kg</span>.</small>
                <small>Your target weight is: <span class="font-semibold underline underline-offset-2">95kg</span><span>(+ 5kg)</span>.</small>
            </div>
            <div>
                <div class="w-full h-[35px] border rounded-2xl bg-zinc-300">
                    <div class="bg-lime-600 h-full rounded-l-2xl flex items-center justify-end font-semibold text-xl"
                        :style="{ width: progress.bar.width + '%' }">
                        <small class="pr-6" v-show="progress.count.show" v-text="progress.count.value + 'kg'"></small>
                    </div>
                </div>
                <small class="block text-right relative right-2"><span>3kg</span> remaining.</small>
            </div>
            <div class="flex items-center gap-x-4 justify-center">
                <button class="rounded-md p-1.5 font-semibold min-w-[75px] bg-zinc-300 text-black shadow shadow-slate-500 cursor-pointer hover:bg-zinc-400 text-sm flex items-center gap-x-1.5" @click="$emit('toggleModal')">
                    <Clipboard class="w-5 h-5" stroke="#000000" fill="none" />History
                </button>
                <button class="rounded-md p-1.5 font-semibold min-w-[75px] bg-zinc-300 text-black shadow shadow-slate-500 cursor-pointer hover:bg-zinc-400 text-sm flex items-center gap-x-1.5" @click="$emit('toggleModal')">
                    <Pen class="w-5 h-5" stroke="#000000" fill="none" />Update
                </button>
            </div>
        </div>
        <small class="text-lime-600 absolute right-2 top-2"><sup>*</sup>Target updated: today</small>
    </div>
</template>