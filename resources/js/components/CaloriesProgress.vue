<script>
import Pen from './svg/Pen.vue';

export default {
    components: {
        Pen
    },
    props: { calories: Object },
    emits: ['toggleModal'],
    data() {
        return {
            progress: {
                bar: {
                    width: 0,
                },
                count: {
                    show: false,
                }
            }
        }
    },
    methods: {
        async animateProgress() {
            const target = this.calories.percent;
            await new Promise((res) => setTimeout(() => res(), 500))

            while (this.progress.bar.width < target) {
                await new Promise((res) => setTimeout(() => res(), 35))
                this.progress.bar.width += 1;
            }
            this.progress.count.show = true
        }
    },
    watch: {
        calories: {
            handler(val) {
                this.animateProgress()
            },
            deep: true
        }
    },
}
</script>

<template>
    <div class="w-[575px] rounded-lg p-3.5 border text-sm h-[200px] flex flex-col relative shadow shadow-slate-500">
        <h1 class="text-lg font-semibold tracking-wide">Calories:</h1>
        <div class="grow grid grid-cols-1 grid-rows-3">
            <div>
                <small>Your current daily target is: <span class="font-semibold underline underline-offset-2" v-text="calories.dailyTarget?.amount"></span>.</small>
            </div>
            <div>
                <div class="w-full h-[35px] border rounded-2xl bg-zinc-300">
                    <div class="bg-lime-600 h-full rounded-l-2xl flex items-center justify-end font-semibold text-xl"
                        :style="{ width: progress.bar.width + '%' }">
                        <small class="pr-6" v-show="progress.count.show && calories.percent >= 20" v-text="calories.total"></small>
                    </div>
                </div>
                <small class="block text-right relative right-2"><span v-text="calories.remaining"></span> remaining.</small>
            </div>
            <div>
                <button class="mx-auto rounded-md p-1.5 font-semibold min-w-[75px] bg-zinc-300 text-black shadow shadow-slate-500 cursor-pointer hover:bg-zinc-400 text-sm flex items-center gap-x-2" @click="$emit('toggleModal')">
                    <Pen class="w-5 h-5" stroke="#000000" fill="none" />Update
                </button>
            </div>
        </div>
        <small class="text-lime-600 absolute right-2 top-2"><sup>*</sup>Target updated: <span v-text="calories.dailyTarget?.human_created"></span></small>
    </div>
</template>