import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
        
        app.config.globalProperties.$globals = getGlobalDefaults();

        app.use(plugin).component("Link", Link).mount(el)
    },
})

function getGlobalDefaults() {
    return {
        csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';