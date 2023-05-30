<template>
    <div></div>
</template>

<script setup lang="ts">
import { OhDearStatus } from '@/api/OhDearStatus';

const localeStore = useLocaleStore();
const ohDearStatus = new OhDearStatus('api/status', localeStore.locale);
const { isActive, resume, pause } = useTimeoutPoll(updateStatus, 60000); // 1 minute

onMounted(async () => {
    if (!isActive) resume();
});

async function updateStatus() {
    try {
        const response = await ohDearStatus.updateStatus();
        console.log(response);
    } catch (err) {
        if (isActive) pause();
    }
}
</script>

<style scoped></style>
