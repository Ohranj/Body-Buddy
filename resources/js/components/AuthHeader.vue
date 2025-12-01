<script>
import Chevron from './svg/Chevron.vue';

export default {
    components: {
        Chevron
    },
    props: {
        user: Object
    },
    data() {
        return {
            show: false
        }
    },
    methods: {
        //Move to a global
        listenForClickOutside(targetId) {
            document.addEventListener('click', (e) => {
                let currentElem = e.target
                let clickedInTarget = false;

                const checkIsTarget = () => {
                    if (currentElem.hasAttribute('id')) {
                        if (currentElem.id == targetId) {
                            clickedInTarget = true;
                        }
                    }
                }

                checkIsTarget()

                while (currentElem.tagName != 'BODY' && !clickedInTarget) {
                    currentElem = currentElem.parentElement;
                    checkIsTarget()
                }
                if (!clickedInTarget) {
                    this.show = false;
                }
            })
        }
    },
    mounted() {
        this.listenForClickOutside('dropdown')
    }
}
</script>

<!-- CSRF ISSUE - Check its updating when changed backend-->

<template>
    <div class="h-[75px] flex items-center justify-end">
        <div class="relative" id="dropdown">
            <button @click="show = !show" class="rounded-md cursor-pointer p-2 text-sm min-w-[95px] flex items-center gap-x-1"><span v-text="user.forename + ' ' + user.surname"></span>
                <Chevron class="w-4 h-4" :class="{'rotate-180' : show}" stroke="#FFFFFF" fill="none" />
            </button>
                <div class="font-semibold min-w-[200px] absolute top-full z-10 right-0 flex flex-col bg-white shadow shadow-black rounded-md p-0 overflow-hidden text-black transition-[max-height] duration-750 linear" :class="show ? 'max-h-[300px]' : 'max-h-0'">
                    <div class="flex flex-col">
                        <div class="hover:bg-slate-300 p-1.5 cursor-pointer">
                            <small>My Profile</small>
                        </div>
                        <div class="hover:bg-slate-300 p-1.5 cursor-pointer">
                            <small>Settings & Security</small>
                        </div>
                    </div>
                    <hr class="border-zinc-700">
                    <div>
                        <div class="hover:bg-slate-300 cursor-pointer">
                            <Form method="POST" action="/logout">
                                <input type="hidden" name="_token" :value="$globals.csrfToken" />
                                <button class="text-xs cursor-pointer w-full text-left p-1.5" type="submit">Logout</button>
                            </Form>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</template>