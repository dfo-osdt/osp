<template>
    <MainPageLayout title="Settings">
        <div class="row q-gutter-lg q-col-gutter-lg flex">
            <div class="cols-2">
                <ContentCard secondary>
                    <template #title>Settings</template>
                    <q-list>
                        <q-item clickable to="/settings/profile">
                            <q-item-section avatar>
                                <q-icon name="mdi-account-outline" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>User Profile</q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item clickable to="/settings/author">
                            <q-item-section avatar>
                                <q-icon name="mdi-badge-account-outline" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>Author Profile</q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item clickable to="/settings/password">
                            <q-item-section avatar>
                                <q-icon name="mdi-shield-lock-outline" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>Password</q-item-label>
                                <q-item-label caption>
                                    Change your password.
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item clickable to="/settings/invitations">
                            <q-item-section avatar>
                                <q-icon name="mdi-email-outline" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>Sent Invitations</q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </ContentCard>
            </div>
            <div class="col q-pr-lg">
                <router-view />
            </div>
        </div>
    </MainPageLayout>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import MainPageLayout from '@/layouts/MainPageLayout.vue';
</script>

<style scoped></style>
