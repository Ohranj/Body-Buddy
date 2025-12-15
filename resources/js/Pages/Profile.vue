<script>
import Auth from '../components/layouts/Auth.vue'
import AuthHeader from '../components/AuthHeader.vue';
import Chevron from '../components/svg/Chevron.vue';
import Profile from '../components/svg/Profile.vue';
import {notifications} from '../store'
import Modal from '../components/Modal.vue';
import Close from '../components/svg/Close.vue';

export default {
    components: {
        AuthHeader,
        AuthLayout: Auth,
        Chevron,
        Profile,
        Modal,
        Close
    },
    data() {
        return {
            notifications,
            calorieTarget: {
                current: null
            },
            modals: {
                calorieTargets: {
                    show: false,
                    list: []
                }
            }
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
        },
        async retrieveCalorieTargets() {
            const response = await fetch('/calorietarget');
            const json = await response.json()
            this.modals.calorieTargets.list = json.data.targets;
            console.log(json)
        }
    },
    created() {
        this.calorieTarget.current = this.$page.props.calorieTarget
        this.calorieTarget.current.date = this.$page.props.date
        Promise.all([this.retrieveCalorieTargets()])
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
                            <input type="number" class="border w-[75px] text-center p-1 rounded-md cursor-pointer" v-model="calorieTarget.current.target" @click="(e) => e.target.select()" />
                            <input type="date" class="border w-[115px] scheme-dark text-center p-1 rounded-md cursor-pointer" v-model="calorieTarget.current.date" />
                        </div>
                        <small>Last updated: <span v-text="calorieTarget.current?.human_created"></span></small>
                        <button class="mr-auto mt-1 cursor-pointer bg-blue-500 hover:bg-blue-600 text-sm px-2 py-1 rounded-md font-semibold shadow shadow-black min-w-[95px]" @click="modals.calorieTargets.show=true">My Targets</button>
                    </div>
                    <button @click="onCalorieTargetConfirm()" class="cursor-pointer bg-lime-600 text-sm px-2 py-1 rounded-md font-semibold shadow shadow-black min-w-[95px] hover:bg-lime-700">Confirm</button>
                </div>
                <hr class="my-2 border border-zinc-700">
            </div>





            <Modal :show="modals.calorieTargets.show" title="Calorie Targets" @toggle-modal="this.modals.calorieTargets.show=false">
                <template v-slot:content>
                    <div>
                        <table class="text-black w-full text-xs">
                            <thead>
                                <tr class="font-semibold">
                                    <th class="underline">Target</th>
                                    <th class="underline">Take Effect</th>
                                    <th class="underline">Created</th>
                                    <th class="underline">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(entry, index) of modals.calorieTargets.list" :key="entry.id">
                                    <tr class="hover:bg-zinc-200 border-b border-zinc-300" :class="{'bg-red-200': entry.toggled}">
                                        <td class="py-0.75 text-center" v-text="entry.target"></td>
                                        <td class="py-0.75 text-center" v-text="entry.human_take_effect"></td>
                                        <td class="py-0.75 text-center" v-text="entry.human_created"></td>
                                        <td class="py-0.75 text-center">
                                            <button class="cursor-pointer hover:scale-[1.25]" @click="entry.toggled = !entry.toggled"><Close class="w-4 h-4" stroke="red" fill="none" /></button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <Transition name="fade">
                            <div class="flex items-start justify-between mt-3.5">
                                <small class="text-xs font-semibold inline-block">6 entries to remove</small>
                                <button class="bg-blue-500 hover:bg-blue-600 text-sm mx-1 rounded px-2 py-1 font-semibold text-white shadow shadow-black block cursor-pointer">Remove</button>
                            </div>
                        </Transition>
                    </div>
                </template>
            </Modal>

        </template>
    </AuthLayout>
</template>