<script>
import Auth from '../components/layouts/Auth.vue'
import AuthHeader from '../components/AuthHeader.vue';
import CaloriesContainer from '../components/CaloriesContainer.vue';
import WeightContainer from '../components/WeightContainer.vue';
import Modal from '../components/Modal.vue';
import {checkOutsideElemClick} from '../helpers'
import Chevron from '../components/svg/Chevron.vue';

export default {
    components: {
        AuthHeader,
        AuthLayout: Auth,
        CaloriesContainer,
        WeightContainer,
        Modal,
        Chevron
    },
    data() {
        return {
            checkOutsideElemClick,
            modals: {
                calories: {
                    show: false,
                    range: 500,
                    meal: {
                        show: false,
                        value: 'breakfast'
                    }
                },
                weight: {
                    show: false
                }
            }
        }
    },
    methods: {
        toggleModal(key, val) {
            this.modals[key].show = val
        }
    },
    mounted() {

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

            <div class="mt-12 flex flex-wrap gap-8">
                <CaloriesContainer @toggle-modal="() => toggleModal('calories', true)" />
                <WeightContainer @toggle-modal="() => toggleModal('weight', true)" />
            </div>

            <div class="mt-12">
                <h1 class="text-lg font-semibold tracking-wide">Workouts:</h1>
                <small>Manage your workouts in the following section.</small>
            </div>

            <Modal :show="modals.calories.show" title="Update Calories" @toggle-modal="() => toggleModal('calories', false)">
                <template v-slot:content>
                    <div class="flex flex-col p-4">
                        <div class="flex flex-col">
                            <small class="font-semibold text-sm">Use the slider to append to your daily calories total.</small>
                            <input type="range" min="0" max="2000" step="10" class="accent-zinc-700 appearance-none cursor-pointer my-3.5" v-model="modals.calories.range" id="i_calories_range" />
                            <small class="font-semibold">Append: <span v-text="modals.calories.range"></span></small>
                        </div>
                        <div class="flex flex-col gap-y-1.5 mt-8 relative" id="meal_dropdown">
                            <small class="font-semibold text-sm">Select a meal reference.</small>
                            <div class="flex items-center gap-x-2 cursor-pointer" @click="modals.calories.meal.show = true">
                                <input type="text" class="capitalize outline-none cursor-pointer w-[75px] font-semibold tracking-wide rounded text-xs" :value="modals.calories.meal.value" />
                                <Chevron class="w-4 h-4" :class="{'rotate-180' : modals.calories.meal.show}" stroke="#000000" fill="none" />
                            </div>
                            <div class="font-semibold min-w-3/4 absolute top-full z-10 left-0 flex flex-col bg-white rounded-md p-0 overflow-hidden text-black transition-[max-height] duration-750 linear shadow shadow-slate-500" :class="modals.calories.meal.show ? 'max-h-[300px]' : 'max-h-0'">
                                <div class="flex flex-col">
                                    <div class="hover:bg-slate-300 p-1.5 cursor-pointer flex items-center gap-x-2" @click="modals.calories.meal.value = 'breakfast'; modals.calories.meal.show = false">
                                        <Avatar class="w-4 h-4" stroke="#000000" fill="none" />
                                        <small>Breakfast</small>
                                    </div>
                                    <div class="hover:bg-slate-300 p-1.5 cursor-pointer flex items-center gap-x-2"  @click="modals.calories.meal.value = 'lunch'; modals.calories.meal.show = false">
                                        <Avatar class="w-4 h-4" stroke="#000000" fill="none" />
                                        <small>Lunch</small>
                                    </div>
                                    <div class="hover:bg-slate-300 p-1.5 cursor-pointer flex items-center gap-x-2"  @click="modals.calories.meal.value = 'dinner'; modals.calories.meal.show = false">
                                        <Avatar class="w-4 h-4" stroke="#000000" fill="none" />
                                        <small>Dinner</small>
                                    </div>
                                    <div class="hover:bg-slate-300 p-1.5 cursor-pointer flex items-center gap-x-2"  @click="modals.calories.meal.value = 'snack'; modals.calories.meal.show = false">
                                        <Avatar class="w-4 h-4" stroke="#000000" fill="none" />
                                        <small>Snack</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="bg-blue-500 hover:bg-blue-600 text-sm rounded-md px-2 py-1 self-end mt-4 cursor-pointer shadow shadow-black font-semibold text-white min-w-[95px]">Confirm</button>
                        <div class="flex items-center gap-x-4 mt-12">
                            <div class="grow border-b-2 border-dashed text-red-500"></div>
                            <small class="text-red-500 font-semibold">Danger Zone</small>
                            <div class="grow border-b-2 border-dashed text-red-500"></div>
                        </div>
                        <button class="bg-red-500 hover:bg-red-600 text-sm mt-4 rounded-md px-2 py-1 font-semibold text-white shadow shadow-black self-end cursor-pointer">Reset Calories</button>
                    </div>
                </template>
            </Modal>

            <Modal :show="modals.weight.show" title="Update Weight" @toggle-modal="() => toggleModal('weight', false)">
                <template v-slot:content>
                    WEight
                </template>
            </Modal>



            <!-- <small>Track favourite lifts progress</small> -->
            <!-- <small>Calories</small>
            <small>Body metrics</small>
            <small>Workout</small>
            <small>Timer</small>
            <small>Calendar - can view old pages</small> -->
        </template>
    </AuthLayout>
</template>