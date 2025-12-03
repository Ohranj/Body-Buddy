<script>
import Google from '../components/svg/Google.vue';
import Guest from '../components/layouts/Guest.vue';
import {notifications} from '../store'
import { router } from '@inertiajs/vue3'
import Spinner from '../components/svg/Spinner.vue';

export default {
    components: {
        GuestLayout: Guest,
        GoogleIcon: Google,
        Spinner
    },
    data() {
        return {
            notifications,
            showMainSpinner: false,
            forms: {
                login: {
                    email: '',
                    password: '',
                    remember: false
                }
            }
        }
    },
    methods: {
        async onLoginSubmit() {
            const response = await fetch('/login', {
                method: 'POST',
                body: JSON.stringify(this.forms.login),
                headers: {
                    'acccept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.$page.props.shared_token
                }
            })
            const json = await response.json()

            if (!response.ok) {
                const notification = {success: false, message: json.message, show: true}
                this.notifications.add(notification)
                return;
            }
            const notification = {success: true, message: json.message, show: true}
            this.notifications.add(notification)
            this.showMainSpinner = true;
            await new Promise((res) => setTimeout(() => res(), 2500))
            router.visit('/dashboard');
        }
    },
    mounted() {
        console.log(this.$page.props)
    }
}
</script>

<template>
    <GuestLayout>
        <template v-slot:main>
            <div v-if="!showMainSpinner" class="h-full flex items-center w-full justify-center">
                <div class="min-h-[200px] p-5 max-w-[650px] w-full rounded-md transition-opacity opacity-100 duration-1000 starting:opacity-0 space-y-16">
                    <a href="/" class="w-fit mx-auto bg-[#131314] hover:bg-zinc-700 rounded-md px-2.5 py-0.5 shadow shadow-black border text-sm cursor-pointer font-semibold flex items-center gap-x-1"><span><GoogleIcon class="h-8 w-8" /></span>Sign in with Google</a>
                    <div class="flex items-center gap-x-3">
                        <div class="border-b border-zinc-400 grow"></div>
                        <small>or</small>
                        <div class="border-b border-zinc-400 grow"></div>
                    </div>
                    <div class="flex flex-col gap-y-5 text-sm font-semibold max-w-[500px] mx-auto">
                        <div class="flex flex-col gap-y-1">
                            <label class="text-xs">Email address</label>
                            <input class="rounded-md shadow border p-2.5" placeholder="..." type="text" v-model="forms.login.email" />
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <label class="text-xs">Password</label>
                            <input class="rounded-md shadow border p-2.5" placeholder="..." type="password" v-model="forms.login.password" />
                        </div>
                        <div class="flex items-center gap-x-1">
                            <input type="checkbox" class="shadow cursor-pointer accent-lime-600" />
                            <label class="text-xs">Remember me</label>
                        </div>
                    </div>
                    <button class="bg-lime-600 rounded-md p-2 min-w-[125px] block mx-auto font-semibold text-white text-sm cursor-pointer hover:bg-lime-700 shadow shadow-black" @click="onLoginSubmit">Sign in</button>
                    <a href="/" class="text-xs text-center hover:underline block w-fit mx-auto py-6">Forgot your password</a>
                    <small class="text-xs text-center block">Dont have an account? <Link href="/register"class="text-lime-600 hover:underline">Register here</Link></small>
                </div>
            </div>
            <div v-if="showMainSpinner" class="grid place-content-center h-full gap-y-2">
                <Spinner class="text-white w-12 h-12 animate-spin mx-auto" fill="green" />
                <small class="text-center mx-auto block font-semibold">Loading resources...</small>
            </div>
        </template>
    </GuestLAyout>
</template>