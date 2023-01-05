<template>
    <q-card>
        <q-card-section class="bg-primary text-white">
            <div class="text-h5">{{ t('common.logged-out') }}</div>
        </q-card-section>
        <q-card-section class="q-mt-md flex justify-around">
            <p>You've been logged out from the portal.</p>
        </q-card-section>
        <q-card-actions class="flex justify-around">
            <q-btn
                label="Login"
                type="submit"
                color="primary"
                data-cy="login"
                :to="{ name: 'login' }"
                icon="mdi-login"
            />
        </q-card-actions>
    </q-card>
</template>

<script setup lang="ts">
const authStore = useAuthStore();

const { t } = useI18n();

// UI related data
const loading = ref(false);

async function logout() {
    loading.value = true;
    await authStore.logout();
    loading.value = false;
}

onMounted(async () => {
    logout();
});
</script>

<style scoped></style>
