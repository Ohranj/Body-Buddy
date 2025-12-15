<script>
import Auth from '../components/layouts/Auth.vue'
import AuthHeader from '../components/AuthHeader.vue';
import Chevron from '../components/svg/Chevron.vue';
import Profile from '../components/svg/Profile.vue';
import {notifications} from '../store'

export default {
    components: {
        AuthHeader,
        AuthLayout: Auth,
        Chevron,
        Profile
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
    created() {
        this.calorieTarget = this.$page.props.calorieTarget
        this.calorieTarget.date = this.$page.props.date
    }
}
</script>

<template>
    <AuthLayout>
        <template v-slot:header>
            <AuthHeader :user="$page.props.user" />
        </template>
        <template v-slot:main>
            <Link href="/dashboard" class="w-fit text-xs font-semibold hover:underline underline-offset-2 decoration-lime-600 flex items-center gap-x-1 text-lime-600"><Chevron class="w-3.5 h-3.5 rotate-90" stroke="#FFFFFF" fill="none" />Return to Dashboard</Link>
            <div class="space-y-16 mt-20">
                <div>
                    <h1 class="font-semibold tracking-wide flex items-center text-lg gap-x-1"><Profile class="w-8 h-8" stroke="#FFFFFF" fill="none" />My Profile.</h1>
                    <small>Manage your profile, such as metrics, to improve your experience and tailor your progress.</small>
                </div>
                <div class="flex items-end justify-between gap-x-4 hover:bg-zinc-800 p-2 sm:p-4 rounded-md hover:shadow shadow-black">
                    <div class="flex flex-col text-xs space-y-1 font-semibold">
                        <label>Amend your daily calorie target. This change takes effect going forward from the date specified.</label>
                        <div class="flex items-center gap-x-2">
                            <input type="number" class="border w-[75px] text-center p-1 rounded-md cursor-pointer" v-model="calorieTarget.target" @click="(e) => e.target.select()" />
                            <input type="date" class="border w-[115px] scheme-dark text-center p-1 rounded-md cursor-pointer" v-model="calorieTarget.date" />
                        </div>
                        <small>Last updated: <span v-text="calorieTarget?.human_created"></span></small>
                        <button class="mr-auto mt-1 cursor-pointer bg-blue-500 hover:bg-blue-600 text-sm px-2 py-1 rounded-md font-semibold shadow shadow-black min-w-[95px]">My Targets</button>
                    </div>
                    <button @click="onCalorieTargetConfirm()" class="cursor-pointer bg-lime-600 text-sm px-2 py-1 rounded-md font-semibold shadow shadow-black min-w-[95px] hover:bg-lime-700">Confirm</button>
                </div>
                <hr class="my-2 border border-zinc-700">
            </div>
        </template>
    </AuthLayout>
</template>


<!-- Add calorie targets modal to allow delete -->