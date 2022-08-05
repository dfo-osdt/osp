<template>
    <q-drawer>
        <div class="flex column q-mb-lg">
            <div class="row fit justify-center">
                <q-img src="/assets/logo.svg" width="40%"></q-img>
            </div>
            <div class="row fit justify-center text-weight-bold text-h6">
                {{ t('common.app-name') }}
            </div>
        </div>
        <q-list>
            <q-item-label header>Essential Links</q-item-label>
        </q-list>
    </q-drawer>
</template>

<script setup lang="ts">
const { t } = useI18n();
</script>

<style scoped></style>
