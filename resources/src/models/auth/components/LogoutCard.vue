<template>
    <q-card flat>
        <q-card-section class="q-mt-md flex justify-around">
            <q-img
                src="/assets/splash_images/undraw_adventure_map_hnin.svg"
                width="100%"
            />
        </q-card-section>
        <q-card-section class="flex-center flex">
            <h1 class="text-h2 text-primary q-mb-xs">
                {{ $t('common.logged-out') }}
            </h1>
            <p class="text-body2 text-grey-8">
                {{ $t('logout-card.text') }}
            </p>
        </q-card-section>
        <q-card-actions class="flex justify-around">
            <q-btn
                round
                outline
                color="primary"
                data-cy="login"
                :to="{ name: 'login' }"
                icon="mdi-home"
            >
            </q-btn>
        </q-card-actions>
    </q-card>
</template>

<script setup lang="ts">
const authStore = useAuthStore();

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
