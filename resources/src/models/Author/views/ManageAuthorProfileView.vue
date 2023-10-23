<template>
    <div class="q-mb-xl">
        <ManageAuthorProfileCard
            v-if="authStore.user && !authStore.loading"
            :author-id="authStore.user.author.data.id"
            secondary
        />
        <ContentCard secondary class="q-mt-lg">
            <template #title>{{
                $t('manage-author-profile-view.authorize-orcid')
            }}</template>
            <q-card-section v-if="authStore.user?.author.data.orcid_verified">
                <p>
                    {{ $t('orcid.already-verified-text') }}
                </p>
                <div class="text-subtitle1 q-mb-md">
                    <span class="text-primary text-weight-bold">ORCID: </span>
                    <a
                        target="_blank"
                        class="text-primary"
                        :href="`https://orcid.org/${authStore.user.author.data.orcid}`"
                        >{{ authStore.user.author.data.orcid }}</a
                    >
                </div>
                <div class="flex justify-end">
                    <q-btn outline color="primary" :href="url">
                        <OrcidAvatar size="md" /><span
                            class="q-ml-md text-grey-8"
                            >{{ $t('ocrid.verify-btn-text') }}
                        </span></q-btn
                    >
                </div>
            </q-card-section>
            <q-card-section v-else>
                <div class="column flex flex-center">
                    <div class="q-ma-md text-h5 text-grey-8">
                        {{ $t('orcid.verify-header-text') }}
                    </div>
                    <div>
                        <q-btn outline color="primary" :href="url">
                            <OrcidAvatar size="md" /><span
                                class="q-ml-md text-grey-8"
                                >{{ $t('ocrid.verify-btn-text') }}
                            </span></q-btn
                        >
                    </div>
                </div>
            </q-card-section>
        </ContentCard>
        <ManageAuthorExpertise />
    </div>
</template>

<script setup lang="ts">
import ManageAuthorProfileCard from '../components/ManageAuthorProfileCard.vue';
import ContentCard from '@/components/ContentCard.vue';
import OrcidAvatar from '@/components/OrcidAvatar.vue';
import ManageAuthorExpertise from '../components/ManageAuthorExpertise.vue';
const authStore = useAuthStore();

const url = '/api/user/orcid/verify';
</script>

<style scoped></style>
