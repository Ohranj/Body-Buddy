<script>
export default {
    data() {
        return {
            socketId: null,
            message: {}
        }
    },
    async mounted() {
        await new Promise((res) =>
            window.Echo.connector.pusher.connection.bind('connected', () => {
                this.socketId = window.Echo.socketId()
                console.log('Reverb connected...')
                res()
            })
        )

        // window.Echo.channel("test").listen("PodcastProcessed", (e) => {
        //     this.message = e
        //     setTimeout(() => this.message = {}, 5000)
        // });

        window.Echo.channel("test.1").listen(".anon", (e) => {
            console.log(e)
        });
    }
}
</script>

<template>
    <div class="bg-blue-100 h-full p-8">
        <h1>Receiver:</h1>
        <div class="mt-4">
            <template v-for="[key, val] in Object.entries(message)">
                <p v-html="`${key}: ${val}`"></p>
            </template>
        </div>
    </div>
</template>