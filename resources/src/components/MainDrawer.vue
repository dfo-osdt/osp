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
        <q-separator></q-separator>
        <q-list padding class="menu-list">
            <q-item v-ripple clickable to="/dashboard">
                <q-item-section avatar>
                    <q-icon name="mdi-view-dashboard" />
                </q-item-section>

                <q-item-section> Dashboard </q-item-section>
            </q-item>

            <q-item v-ripple clickable>
                <q-item-section avatar>
                    <q-icon name="mdi-file-document-multiple" />
                </q-item-section>

                <q-item-section> My Manuscripts </q-item-section>
            </q-item>

            <q-item v-ripple clickable>
                <q-item-section avatar>
                    <q-icon name="mdi-newspaper-variant-multiple-outline" />
                </q-item-section>

                <q-item-section> My Publications </q-item-section>
            </q-item>

            <q-item v-ripple clickable>
                <q-item-section avatar>
                    <q-icon name="mdi-file-sign" />
                </q-item-section>

                <q-item-section> My Reviews </q-item-section>
            </q-item>

            <q-separator></q-separator>
            <q-item-label header>Explore</q-item-label>

            <q-item v-ripple clickable>
                <q-item-section avatar>
                    <q-icon name="mdi-newspaper-variant-multiple-outline" />
                </q-item-section>

                <q-item-section> Publications </q-item-section>
            </q-item>

            <q-item v-ripple clickable>
                <q-item-section avatar>
                    <q-icon name="mdi-account-group-outline" />
                </q-item-section>

                <q-item-section> Authors </q-item-section>
            </q-item>

            <q-item v-ripple clickable>
                <q-item-section avatar>
                    <q-icon name="mdi-archive-star-outline" />
                </q-item-section>

                <q-item-section> Sensitive Issues </q-item-section>
            </q-item>
        </q-list>
    </q-drawer>
</template>

<script setup lang="ts">
const { t } = useI18n();
</script>

<style lang="sass" scoped></style>
