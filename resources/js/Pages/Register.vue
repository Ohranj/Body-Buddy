<script>
import {notifications} from '../store'
import Guest from '../components/layouts/Guest.vue';
import { router } from '@inertiajs/vue3'
import Spinner from '../components/svg/Spinner.vue';

export default {
    components: {
        GuestLayout: Guest,
        Spinner
    },
    data() {
        return {
            notifications,
            showMainSpinner: false,
            popups: {
                passwordRules: false
            },
            forms: {
                register: {
                    forename: '',
                    surname: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                }
            }
        }
    },
    methods: {
        async onRegisterSubmit() {
            const errors = [];
            for (const [key, value] of Object.entries(this.forms.register)) {
                if (!value.trim().length) {
                    errors.push(`The ${key} field is required`)
                }
            }
            if (errors.length) {
                for (const x of errors) {
                    const notification = {success: false, message: x, show: true}
                    this.notifications.add(notification)
                }
                return
            }
            if (this.forms.register.password != this.forms.register.password_confirmation) {
                const notification = {success: false, message: 'Your password confirmation does not match', show: true}
                this.notifications.add(notification) 
                return
            }

            const response = await fetch('/register', {
                method: 'POST',
                body: JSON.stringify(this.forms.register),
                headers: {
                    'accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.$page.props.shared_token
                }
            })
            const json = await response.json()
            if (!response.ok) {
                const notification = {success: false, message: json.message, show: true}
                this.notifications.add(notification)
                return
            }
            const notification = {success: true, message: json.message, show: true}
            this.notifications.add(notification)
            this.showMainSpinner = true;
            this.resetRegistrationForm()
            await new Promise((res) => setTimeout(() => res(), 2500))
            router.visit('/dashboard');
        },
        resetRegistrationForm() {
            for (const key of Object.keys(this.forms.register)) {
                this.forms.register[key] = ''
            }
        }
    },
    mounted() {
        window.addEventListener('keypress', (e) => {
            if (e.key != 'Enter') return;
            const formHasValues = Object.values(this.forms.register).some((x) => x.trim().length);
            if (!formHasValues) return
            this.onRegisterSubmit()
        })
    }
}
</script>

<template>
    <GuestLayout>
        <template v-slot:main>
            <div v-if="!showMainSpinner" class="h-full flex items-center w-full justify-center">
                <div class="min-h-[200px] p-5 max-w-[650px] w-full rounded-md transition-opacity opacity-100 duration-1000 starting:opacity-0 space-y-16">
                    <h1 class="text-center font-semibold text-2xl underline underline-offset-4">Account Registration
                    </h1>
                    <div class="flex flex-col gap-y-5 text-sm font-semibold max-w-[500px] mx-auto">
                        <div class="gap-y-5 sm:gap-y-0 flex flex-col sm:flex-row sm:items-center sm:gap-x-5">
                            <div class="flex flex-col gap-y-1 grow">
                                <label class="text-xs">Forename <sup class="text-red-500">*</sup></label>
                                <input type="text" class="rounded-md shadow border p-2.5" placeholder="..."
                                    v-model="forms.register.forename" />
                            </div>
                            <div class="flex flex-col gap-y-1 grow">
                                <label class="text-xs">Surname <sup class="text-red-500">*</sup></label>
                                <input type="text" class="rounded-md shadow border p-2.5" placeholder="..."
                                    v-model="forms.register.surname" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-y-1">
                            <label class="text-xs">Email address <sup class="text-red-500">*</sup></label>
                            <input class="rounded-md shadow border p-2.5" placeholder="..." type="text"
                                v-model="forms.register.email" />
                        </div>
                        <div class="flex flex-col gap-y-1 relative">
                            <label class="text-xs">Password <sup class="text-red-500">*</sup></label>
                            <input class="rounded-md shadow border p-2.5" @focus="popups.passwordRules = true"
                                @focusout="popups.passwordRules = false" placeholder="..." type="password"
                                v-model="forms.register.password" />
                        </div>
                        <Transition name="fade">
                            <div v-show="popups.passwordRules" class="border rounded-md bg-zinc-800 p-2">
                                <small>Passwords must contain:</small>
                                <ul class="text-[11px] list-disc pl-4">
                                    <li :class="{ 'text-lime-600': forms.register.password.length >= 8 }">At least 8
                                        characters</li>
                                    <li :class="{ 'text-lime-600': /\d/.test(forms.register.password) }">At least 1 number
                                    </li>
                                    <li :class="{ 'text-lime-600': /[A-Z]/.test(forms.register.password) }">At least 1
                                        uppercase letter</li>
                                </ul>
                            </div>
                        </Transition>
                        <div class="flex flex-col gap-y-1">
                            <label class="text-xs">Confirm your Password <sup class="text-red-500">*</sup></label>
                            <input class="rounded-md shadow border p-2.5" placeholder="..." type="password"
                                v-model="forms.register.password_confirmation" />
                        </div>
                    </div>
                    <button class="bg-lime-600 rounded-md p-2 min-w-[125px] block mx-auto font-semibold text-white text-sm cursor-pointer hover:bg-lime-700 shadow shadow-black" @click="onRegisterSubmit">Register</button>
                    <small class="text-xs text-center block">Already have an account? <Link href="/" class="text-lime-600 hover:underline">Sign in here</Link></small>
                </div>
            </div>
            <div v-if="showMainSpinner" class="grid place-content-center h-full gap-y-2">
                <Spinner class="text-white w-12 h-12 animate-spin mx-auto" fill="green" />
                <small class="text-center mx-auto block font-semibold">Loading resources...</small>
            </div>
        </template>
    </GuestLayout>
</template>