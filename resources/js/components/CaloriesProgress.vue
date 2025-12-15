<script>
import Pen from './svg/Pen.vue';
import Clipboard from './svg/Clipboard.vue'

export default {
    components: {
        Pen, Clipboard
    },
    props: { calories: Object },
    emits: ['toggleUpdate', 'toggleEntries'],
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
            this.progress.count.show = false;
            const target = this.calories.percent;
            await new Promise((res) => setTimeout(() => res(), 500))

            if (this.progress.bar.width > target) {
                await this.decrementBar(target)
            } else {
                await this.incrementBar(target)
            }

            this.progress.count.show = true
        },
        async decrementBar(target) {
            while (this.progress.bar.width > target) {
                await new Promise((res) => setTimeout(() => res(), 20))
                this.progress.bar.width -= 1;
            }
        },
        async incrementBar(target) {
            while (this.progress.bar.width < target) {
                await new Promise((res) => setTimeout(() => res(), 20))
                this.progress.bar.width += 1;
            }
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
            <div class="flex flex-col">
                <small>Your daily target is: <Link href="/profile" class="hover:decoration-lime-600 font-semibold underline underline-offset-2" v-text="calories.dailyTarget?.amount"></Link>.</small>
                <small v-show="calories.total == 0" class="text-red-500">You have not added any calories towards today.</small>
            </div>
            <div>
                <div class="w-full h-[35px] border rounded-2xl bg-zinc-300">
                    <div class="h-full flex items-center justify-end font-semibold text-xl" :class="calories.percent < 100 ? 'rounded-l-2xl bg-lime-600' : 'rounded-2xl bg-amber-600'" :style="{ width: progress.bar.width + '%' }">
                        <small class="pr-6" v-show="progress.count.show && calories.percent >= 20" v-text="calories.total"></small>
                    </div>
                </div>
                <small class="block text-right relative right-2"><span v-text="calories.remaining"></span> remaining.</small>
            </div>
            <div class="flex items-center gap-x-4 justify-center">
                <button class="rounded-md p-1.5 font-semibold min-w-[75px] bg-zinc-300 text-black shadow shadow-slate-500 cursor-pointer hover:bg-zinc-400 text-sm flex items-center gap-x-1.5" @click="$emit('toggleEntries')">
                    <Clipboard class="w-5 h-5" stroke="#000000" fill="none" />Entries
                </button>
                <button class="rounded-md p-1.5 font-semibold min-w-[75px] bg-zinc-300 text-black shadow shadow-slate-500 cursor-pointer hover:bg-zinc-400 text-sm flex items-center gap-x-1.5" @click="$emit('toggleUpdate')">
                    <Pen class="w-5 h-5" stroke="#000000" fill="none" />Update
                </button>
            </div>
        </div>
        <Link href="profile" class="text-lime-600 absolute right-2 top-2 text-xs hover:underline underline-offset-2"><sup>*</sup>Target assigned: <span v-text="calories.dailyTarget?.human_created"></span></Link>
    </div>
</template>