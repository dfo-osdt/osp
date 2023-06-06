<template>
    <div></div>
</template>

<script setup lang="ts">
import { OhDearStatus } from '@/api/OhDearStatus';
import { useQuasar } from 'quasar';

const localeStore = useLocaleStore();
const { t } = useI18n();

const muted = useLocalStorage('mutedOhDearStatusId', 0);
const ohDearStatus = new OhDearStatus('api/status', localeStore.locale);
const { resume, pause } = useTimeoutPoll(updateStatus, 60000); // 1 minute

onMounted(async () => {
    resume();
});

async function updateStatus() {
    try {
        const response = await ohDearStatus.updateStatus();
        if (isNotificationRequired()) notify();
        console.log(response);
    } catch (err) {
        pause();
    }
}

const $q = useQuasar();

function isNotificationRequired() {
    if (!ohDearStatus.hasPinnedUpdate()) return false;
    if (muted.value === ohDearStatus.getPinnedUpdateId()) return false;

    return true;
}

function notify() {
    const htmlMessage = `
        <div class="text-body1">${
            ohDearStatus.OhDearStatusPage?.pinnedUpdate?.title
        }</div>
        <div class="text-caption">${
            useLocaleDate(ohDearStatus.getPinnedUpdateDate()).value
        }</div>
        <div class="text-body2">${
            ohDearStatus.OhDearStatusPage?.pinnedUpdate?.text
        }</div>`;

    $q.notify({
        multiLine: true,
        html: true,
        icon: 'mdi-progress-wrench',
        iconSize: '4rem',
        message: htmlMessage,
        color: 'secondary',
        position: 'bottom-right',
        timeout: 0,
        actions: [
            {
                label: t('common.dismiss'),
                color: 'white',
                handler() {
                    muted.value = ohDearStatus.getPinnedUpdateId();
                },
            },
        ],
    });
}
</script>

<style scoped></style>
