<script>
import Auth from '../components/layouts/Auth.vue'
import AuthHeader from '../components/AuthHeader.vue';

export default {
    components: {
        AuthHeader,
        AuthLayout: Auth
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







            <small>Calories</small>
            <small>Body metrics</small>
            <small>Workout</small>
        </template>
    </AuthLayout>
</template>