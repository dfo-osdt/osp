import { defineStore } from 'pinia';
import { ref } from 'vue';

export const userUserStore = defineStore('user', () => {
    const user = ref<object>();
});
