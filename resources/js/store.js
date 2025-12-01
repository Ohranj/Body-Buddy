import { reactive } from 'vue'

export const notifications = reactive({
    items: [],
    showFor: 7500,
    async add(notification) {
        this.items.push(notification)
        await new Promise((res) => setTimeout(() => res(), this.showFor))
        this.remove()
    },
    remove() {
        this.items.splice(0, 1)
    },
    hide(index) {
        this.items[index].show = false; 
    }
})