<script>
import Auth from '../components/layouts/Auth.vue'
import AuthHeader from '../components/AuthHeader.vue';
import CaloriesContainer from '../components/CaloriesContainer.vue';
import WeightContainer from '../components/WeightContainer.vue';

export default {
    components: {
        AuthHeader,
        AuthLayout: Auth,
        CaloriesContainer,
        WeightContainer
    },
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

            <div class="mt-10 flex flex-wrap gap-8">
                <CaloriesContainer />
                <WeightContainer />
            </div>


            <!-- design needs work -->





            <small>Calories</small>
            <small>Body metrics</small>
            <small>Workout</small>
            <small>Timer</small>
            <small>Calendar - can view old pages</small>
        </template>
    </AuthLayout>
</template>