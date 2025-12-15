<script>
import Auth from '../components/layouts/Auth.vue'
import AuthHeader from '../components/AuthHeader.vue';
import CaloriesProgress from '../components/CaloriesProgress.vue';
import WeightContainer from '../components/WeightContainer.vue';
import Modal from '../components/Modal.vue';
import {checkOutsideElemClick} from '../helpers'
import Chevron from '../components/svg/Chevron.vue';
import Bread from '../components/svg/Bread.vue';
import Apple from '../components/svg/Apple.vue';
import RiceBowl from '../components/svg/RiceBowl.vue';
import Burger from '../components/svg/Burger.vue';
import {notifications} from '../store'
import Close from '../components/svg/Close.vue';
import SwitchDateBanner from '../components/SwitchDateBanner.vue';

export default {
    components: {
        AuthHeader,
        AuthLayout: Auth,
        CaloriesProgress,
        WeightContainer,
        Modal,
        Chevron,
        Bread,
        Apple,
        RiceBowl,
        Burger,
        Close,
        SwitchDateBanner
    },
    data() {
        return {
            notifications,
            checkOutsideElemClick,
            calories: {},
            modals: {
                calories: {
                    show: false,
                    range: 500,
                    time: '10:10',
                    meal: {
                        show: false,
                        value: 'snack'
                    }
                },
                calorieEntries: {
                    show: false,
                    calsToRemove: 0
                },
                weight: {
                    show: false
                }
            }
        }
    },
    methods: {
        async retrieveCalories() {
            const response = await fetch('/calories?timestamp=' + this.$page.props.date.timestamp);
            const json = await response.json()
            this.calories = json.data;
        },
        toggleModal(key, state) {
            if (key == 'calories') {
                if (state) {
                    this.modals.calories.range = 500;
                    this.modals.calories.meal.show = false
                    this.modals.calories.meal.value = 'snack'
                    let hours = new Date().getHours();
                    if (hours < 10) {
                        hours = `0${hours}`
                    }
                    this.modals.calories.time = `${hours}:00`
                }
            }
            if (key == 'calorieEntries') {
                if (state) {
                    for (const x of this.calories.entries) {
                        x.toggled = false;
                    }
                }
            }
            this.modals[key].show = state
        },
        async confirmCaloriesClicked() {
            const response = await fetch('/calories?timestamp=' + this.$page.props.date.timestamp, {
                method: 'POST',
                body: JSON.stringify(this.modals.calories),
                headers: {
                    'accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.$page.props.shared_token
                }
            })
            const json = await response.json()
            if (!response.ok) {
                for (const [key, errors] of Object.entries(json.errors)) {
                    for (const err of errors) {
                        const notification = {success: false, message: err, show: true}
                        this.notifications.add(notification)
                    }
                }
                return
            }
            this.modals.calories.show = false;
            this.retrieveCalories()
        },
        async onRemoveEntries() {
            const ids = this.calories.entries.reduce((acc, cur) => {
                if (cur.toggled) acc.push(cur.id)
                return acc;
            }, [])
            const responses = await Promise.all(ids.map((x) => this.deleteEntry(x)))
            for (const x of responses) {
                const notification = {success: x.success, message: x.message, show: true}
                this.notifications.add(notification)
            } 
            await this.retrieveCalories()
        },
        async deleteEntry(id) {
            const response = await fetch('/calories/' + id, {
                method: 'DELETE',
                headers: {
                    'accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.$page.props.shared_token
                }
            })
            const json = await response.json()
            return json;
        }
    },
    computed: {
        deleteSelectedEntries() {
            return this.calories?.entries?.reduce((acc, cur) => cur.toggled ? acc += cur.amount : acc, 0);
        }
    },
    created() {
        Promise.all([this.retrieveCalories()])
        document.addEventListener('click', (e) => {
            const insideTarget = this.checkOutsideElemClick(e, 'meal_dropdown');
            if (!insideTarget) {
                this.modals.calories.meal.show = false;
             }
        })
    }
}
</script>

<template>
    <AuthLayout>
        <template v-slot:header>
            <AuthHeader :user="$page.props.user" />
        </template>
        <template v-slot:main>
            <h1 class="font-semibold tracking-wide" v-text="$page.props.date.human_day"></h1>
            <ul class="text-xs space-y-1 mt-1.5">
                <li class="text-inherit">Last weigh in: </li>
                <li class="text-inherit">Last workout: </li>
            </ul>

            <SwitchDateBanner :timestamp="$page.props.date.timestamp" />

            <div class="mt-12 flex flex-wrap gap-8">
                <CaloriesProgress 
                    :calories="calories" 
                    @toggle-update="() => toggleModal('calories', true)" 
                    @toggle-entries="() => toggleModal('calorieEntries', true)" />
                <WeightContainer @toggle-modal="() => toggleModal('weight', true)" />
            </div>

            <div class="mt-12">
                <h1 class="text-lg font-semibold tracking-wide">Workouts:</h1>
                <small>Manage your workouts in the following section.</small>
            </div>

            <Modal :show="modals.calories.show" title="Update Calories" @toggle-modal="() => toggleModal('calories', false)">
                <template v-slot:content>
                    <div class="flex flex-col p-0.5 sm:p-4">
                        <div class="flex flex-col">
                            <small class="font-semibold text-[11px] sm:text-sm">Use the slider to append to your daily calories total.</small>
                            <input type="range" min="0" max="2000" step="10" class="accent-zinc-700 appearance-none cursor-pointer my-3" v-model="modals.calories.range" id="i_calories_range" />
                            <small class="font-semibold">Appending: <input type="number" class="px-1 min-w-[25px] rounded cursor-pointer" @click="(e) => e.target.select()" :style="{ width: modals.calories.range.toString().length * 10 + 'px' }" v-model="modals.calories.range" /></small>
                        </div>
                        <div class="mt-8 flex flex-col gap-y-1.5">
                            <small class="font-semibold text-[11px] sm:text-sm">Select a meal time.</small>
                            <input type="time" class="w-fit text-xs font-semibold cursor-pointer" value="22:10" v-model="modals.calories.time" />
                        </div>
                        <div class="flex flex-col gap-y-1.5 mt-8 relative" id="meal_dropdown">
                            <small class="font-semibold text-[11px] sm:text-sm">Select a meal reference.</small>
                            <div class="flex items-center gap-x-2 cursor-pointer" @click="modals.calories.meal.show = true">
                                <input type="text" class="capitalize outline-none cursor-pointer w-[55px] font-semibold tracking-wide rounded text-xs" :value="modals.calories.meal.value" />
                                <Chevron class="w-4 h-4" :class="{'rotate-180' : modals.calories.meal.show}" stroke="#000000" fill="none" />
                            </div>
                            <div class="font-semibold min-w-3/4 absolute top-full z-10 left-0 flex flex-col bg-white rounded-md p-0 overflow-hidden text-black transition-[max-height] duration-750 linear shadow shadow-slate-500" :class="modals.calories.meal.show ? 'max-h-[300px]' : 'max-h-0'">
                                <div class="flex flex-col">
                                    <div class="hover:bg-slate-300 p-1 cursor-pointer flex items-center gap-x-2" @click="modals.calories.meal.value = 'breakfast'; modals.calories.meal.show = false">
                                        <small class="flex items-center gap-x-2"><Bread class="w-5 h-5" fill="none" />Breakfast</small>
                                    </div>
                                    <div class="hover:bg-slate-300 p-1 cursor-pointer flex items-center gap-x-2"  @click="modals.calories.meal.value = 'lunch'; modals.calories.meal.show = false">
                                        <small class="flex items-center gap-x-2"><Burger class="w-5 h-5" fill="none" />Lunch</small>
                                    </div>
                                    <div class="hover:bg-slate-300 p-1 cursor-pointer flex items-center gap-x-2"  @click="modals.calories.meal.value = 'dinner'; modals.calories.meal.show = false">
                                        <small class="flex items-center gap-x-2"><RiceBowl class="w-5 h-5" fill="none" />Dinner</small>
                                    </div>
                                    <div class="hover:bg-slate-300 p-1 cursor-pointer flex items-center gap-x-2"  @click="modals.calories.meal.value = 'snack'; modals.calories.meal.show = false">
                                        <small class="flex items-center gap-x-2"><Apple class="w-5 h-5" fill="none" />Snack</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="bg-blue-500 hover:bg-blue-600 text-sm rounded px-2 py-1 self-end mt-4 cursor-pointer shadow shadow-black font-semibold text-white min-w-[95px]" @click="confirmCaloriesClicked()">Confirm</button>
                        <div class="flex items-center gap-x-4 mt-4">
                            <div class="grow border-b-2 border-dashed text-red-500"></div>
                            <small class="text-red-500 font-semibold">Danger Zone</small>
                            <div class="grow border-b-2 border-dashed text-red-500"></div>
                        </div>
                        <button class="bg-red-500 hover:bg-red-600 text-sm mt-4 rounded px-2 py-1 font-semibold text-white shadow shadow-black self-end cursor-pointer">Reset Calories</button>
                    </div>
                </template>
            </Modal>

            <Modal :show="modals.calorieEntries.show" title="Calorie Entries" @toggle-modal="() => toggleModal('calorieEntries', false)">
                <template v-slot:content>
                    <div class="flex flex-col p-0.5 sm:p-4">
                        <div class="min-h-[75px] overflow-y-scroll hide-scrollbar">
                            <table class="w-full text-black text-xs">
                                <thead>
                                <tr class="font-semibold">
                                    <th class="underline text-left">Meal</th>
                                    <th class="underline">Calories</th>
                                    <th class="hidden md:block underline">Running Total</th>
                                    <th class="underline">Time</th>
                                    <th class="underline">Remove</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(entry, index) of calories.entries" :key="entry.id">
                                        <tr class="hover:bg-zinc-200 border-b border-zinc-300" :class="{'bg-red-200': entry.toggled}">
                                            <td class="py-0.75 capitalize" v-text="entry.meal.toLowerCase()"></td>
                                            <td class="py-0.75 text-center" v-text="entry.amount"></td>
                                            <td class="hidden md:block py-0.75 text-center" v-text="entry.running_total"></td>
                                            <td class="py-0.75 text-center" v-text="entry.human_date"></td>
                                            <td class="py-0.75 text-center">
                                                <button class="cursor-pointer hover:scale-[1.25]" @click="entry.toggled = !entry.toggled"><Close class="w-4 h-4" stroke="red" fill="none" /></button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <Transition name="fade">
                                    <tfoot v-show="deleteSelectedEntries > 0" class="mt-5 pt-5">
                                        <tr class="bg-blue-200">
                                            <td class="uppercase">To remove</td>
                                            <td class="py-0.75 text-center" v-text="deleteSelectedEntries"></td>
                                            <td class="hidden md:block py-0.75 text-center" v-text="calories.total - deleteSelectedEntries"></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </Transition>
                            </table>
                            <Transition name="fade">
                                <button class="bg-blue-500 hover:bg-blue-600 text-sm mx-1 mt-3 mb-1 ml-auto rounded px-2 py-1 font-semibold text-white shadow shadow-black block cursor-pointer" v-show="deleteSelectedEntries > 0" @click="onRemoveEntries">Remove</button>
                            </Transition>
                            <div v-show="calories.entries?.length == 0" class="flex flex-col items-center my-4 justify-center">
                                <Close class="w-6 h-6" stroke="#000000" fill="none" />
                                <small>No entries found</small>
                            </div>
                        </div>
                        <div class="text-sm mt-4">
                            <h2 class="font-semibold text-xs">Overview:</h2>
                            <div class="grid grid-cols-3 font-semibold">
                                <small class="col-start-2 underline">Calories</small>
                                <small class="underline">Percent</small>
                            </div>
                            <div>
                                <div class="border-b border-zinc-300 grid grid-cols-3">
                                    <small>Snacks:</small>
                                    <small v-text="calories.mealTallies?.SNACK.amount"></small>
                                    <small v-text="calories.mealTallies?.SNACK.percent + '%'"></small>
                                </div>
                                <div class="border-b border-zinc-300 grid grid-cols-3">
                                    <small>Breakfast:</small>
                                    <small v-text="calories.mealTallies?.BREAKFAST.amount"></small>
                                    <small v-text="calories.mealTallies?.BREAKFAST.percent + '%'"></small>
                                </div>
                                <div class="border-b border-zinc-300 grid grid-cols-3">
                                    <small>Lunch:</small>
                                    <small v-text="calories.mealTallies?.LUNCH.amount"></small>
                                    <small v-text="calories.mealTallies?.LUNCH.percent + '%'"></small>
                                </div>
                                <div class="border-b border-zinc-300 grid grid-cols-3">
                                    <small>Dinner:</small>
                                    <small v-text="calories.mealTallies?.DINNER.amount"></small>
                                    <small v-text="calories.mealTallies?.DINNER.percent + '%'"></small>
                                </div>
                                <div class="border-b border-zinc-300 grid grid-cols-3 font-semibold">
                                    <small>Total:</small>
                                    <small v-text="calories.total"></small>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-x-4 mt-4">
                            <div class="grow border-b-2 border-dashed text-red-500"></div>
                            <small class="text-red-500 font-semibold">Danger Zone</small>
                            <div class="grow border-b-2 border-dashed text-red-500"></div>
                        </div>
                        <button class="bg-red-500 hover:bg-red-600 text-sm mt-4 rounded px-2 py-1 font-semibold text-white shadow shadow-black self-end" :class="{'cursor-pointer': calories.entries?.length >= 1}" :disabled="calories.entries?.length == 0">Remove All</button>
                    </div>
                </template>
            </Modal>

            <Modal :show="modals.weight.show" title="Update Weight" @toggle-modal="() => toggleModal('weight', false)">
                <template v-slot:content>
                    WEight
                </template>
            </Modal>


            <!-- Add a weight table where you log weight updates  -->
            <!-- Add a target weight table where you have a starting weight and target weight - Not sure whether to have a single or multi setup -->
             



            <!-- Add delete of entries - Delete all done on queue with the websocket? -->


            <!-- <small>Track favourite lifts progress</small>
            <small>Body metrics</small>
            <small>Workout</small>
            <small>Timer</small>
            -->
        </template>
    </AuthLayout>
</template>