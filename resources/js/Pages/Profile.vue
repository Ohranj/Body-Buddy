<script>
import Auth from '../components/layouts/Auth.vue'
import AuthHeader from '../components/AuthHeader.vue';
import Chevron from '../components/svg/Chevron.vue';

export default {
    components: {
        AuthHeader,
        AuthLayout: Auth,
        Chevron
    },
    data() {
        return {
        }
    },
    methods: {
    }
}
</script>

<template>
    <AuthLayout>
        <template v-slot:header>
            <AuthHeader :user="$page.props.user" />
        </template>
        <template v-slot:main>
            <div>
                <Link href="/dashboard" class="text-xs font-semibold hover:underline underline-offset-2 decoration-lime-600 flex items-center gap-x-1"><Chevron class="w-3.5 h-3.5 rotate-90" stroke="#FFFFFF" fill="none" />Return to Dashboard</Link>
            </div>
        </template>
    </AuthLayout>
</template>