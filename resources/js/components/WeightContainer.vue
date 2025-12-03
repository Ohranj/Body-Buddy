<script>
import Pen from './svg/Pen.vue';

export default {
    components: {
        Pen
    }
}
</script>

<template>
    <div class="w-[675px] rounded-md p-2 border text-sm min-h-[100px] flex flex-col relative shadow shadow-black">
        <h1 class="text-base font-semibold tracking-wide">Weight:</h1>
        <div class="grow">
            <div class="flex flex-col">
                <small>Your starting weight was: <span class="font-semibold underline underline-offset-2">90kg</span>.</small>
                <small>Your target weight is: <span class="font-semibold underline underline-offset-2">95kg </span><span>(+ 5kg)</span>.</small>
            </div>
            <div class="w-full h-[35px] border mt-4 rounded-md">
                <div class="bg-lime-600 w-[75%] h-full rounded-l-md flex items-center justify-end pr-6 font-semibold text-xl">
                    <small>90kg</small></div>
            </div>
            <small class="block text-right relative right-2"><span>5kg</span> remaining.</small>
            <button class="rounded mt-4 p-1.5 font-semibold min-w-[75px] bg-zinc-400 text-black shadow shadow-black cursor-pointer hover:bg-zinc-500 text-sm flex items-center gap-x-2"><Pen class="w-5 h-5" stroke="#000000" fill="none" />Update</button>
        </div>
        <small class="text-lime-600 absolute right-1.5 top-1.5"><sup>*</sup>Target updated: today</small>
    </div>
</template>