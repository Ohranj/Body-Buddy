<script>
import Auth from '../components/layouts/Auth.vue'
import AuthHeader from '../components/AuthHeader.vue';
import Chevron from '../components/svg/Chevron.vue';
import {notifications} from '../store'

export default {
    components: {
        AuthHeader,
        AuthLayout: Auth,
        Chevron
    },
    data() {
        return {
            notifications,
            calorieTarget: {}
        }
    },
    methods: {
        async onCalorieTargetConfirm() {
            const response = await fetch('/calorietarget', {
                method: 'POST',
                body: JSON.stringify(this.calorieTarget),
                headers: {
                    'accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.$page.props.shared_token
                }
            })
            const json = await response.json()
            const notification = {success: json.success, message: json.message, show: true}
            this.notifications.add(notification)
        }
    },
    mounted() {
        this.calorieTarget = this.$page.props.calorieTarget
    }
}
</script>

<template>
    <AuthLayout>
        <template v-slot:header>
            <AuthHeader :user="$page.props.user" />
        </template>
        <template v-slot:main>
            <Link href="/dashboard" class="text-xs font-semibold hover:underline underline-offset-2 decoration-lime-600 flex items-center gap-x-1"><Chevron class="w-3.5 h-3.5 rotate-90" stroke="#FFFFFF" fill="none" />Return to Dashboard</Link>
            <div class="space-y-10 mt-20">
                <div class="flex items-end justify-between gap-x-4">
                    <div class="flex flex-col text-xs space-y-1 font-semibold">
                        <label>Amend your daily calorie target. This change takes effect from today onwards.</label>
                        <input type="number" class="border w-[75px] text-center p-1 rounded-md cursor-pointer" v-model="calorieTarget.target" @click="(e) => e.target.select()" />
                        <small>Last updated: <span v-text="calorieTarget?.human_created"></span></small>
                    </div>
                    <button @click="onCalorieTargetConfirm()" class="cursor-pointer bg-lime-600 text-sm px-2 py-1 rounded-md font-semibold shadow shadow-black min-w-[95px]">Confirm</button>
                </div>
                <hr class="my-2 border border-zinc-700">
            </div>
        </template>
    </AuthLayout>
</template>