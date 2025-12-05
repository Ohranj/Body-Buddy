<script>
import Chevron from './svg/Chevron.vue';
import Logout from './svg/Logout.vue';
import Cog from './svg/Cog.vue'
import Avatar from './svg/Avatar.vue';
import {checkOutsideElemClick} from '../helpers'

export default {
    components: {
        Chevron, Logout, Cog, Avatar
    },
    props: {
        user: Object
    },
    data() {
        return {
            checkOutsideElemClick,
            show: false
        }
    },
    mounted() {
        document.addEventListener('click', (e) => {
            const insideTarget = this.checkOutsideElemClick(e, 'dropdown');
            if (!insideTarget) {
                this.show = false;
            }
        })
    }
}
</script>

<template>
    <div class="h-[75px] flex items-center justify-end">
        <div class="relative" id="dropdown">
            <button @click.stop="show = !show"
                class="rounded-md cursor-pointer p-2 text-sm min-w-[95px] flex items-center gap-x-1"><span v-text="user.forename + ' ' + user.surname"></span>
                <Chevron class="w-4 h-4" :class="{'rotate-180' : show}" stroke="#FFFFFF" fill="none" />
            </button>
            <div class="font-semibold min-w-[200px] absolute top-full z-10 right-0 flex flex-col bg-white rounded-md p-0 overflow-hidden text-black transition-[max-height] duration-750 linear shadow shadow-slate-500"
                :class="show ? 'max-h-[300px]' : 'max-h-0'">
                <div class="flex flex-col">
                    <div class="hover:bg-slate-300 p-1.5 cursor-pointer flex items-center gap-x-2">
                        <Avatar class="w-4 h-4" stroke="#000000" fill="none" />
                        <small>My Profile</small>
                    </div>
                    <div class="hover:bg-slate-300 p-1.5 cursor-pointer flex items-center gap-x-2">
                        <Cog class="w-4 h-4" stroke="#000000" fill="none" />
                        <small>Settings & Security</small>
                    </div>
                </div>
                <hr class="border-zinc-700">
                <div>
                    <div class="hover:bg-slate-300 cursor-pointer">
                        <Form method="POST" action="/logout">
                            <input type="hidden" name="_token" :value="$page.props.shared_token" />
                            <button class="text-xs cursor-pointer w-full text-left p-1.5 flex items-center gap-x-2"
                                type="submit"><span>
                                    <Logout class="w-4 h-4" stroke="#000000" fill="none" />
                                </span>Logout</button>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>