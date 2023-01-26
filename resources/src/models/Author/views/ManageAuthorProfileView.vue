<template>
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
                <q-btn outline color="primary" :href="orcidAuthUrl">
                    <OrcidAvatar size="md" /><span class="q-ml-md text-grey-8"
                        >{{ $t('ocrid.verify-btn-text') }}
                    </span></q-btn
                >
            </div>
        </q-card-section>
        <q-card-section v-else>
            <div class="column flex flex-center">
                <div class="q-ma-md text-h5 text-grey-8">
                    Verify your ORCID iD (or register for an iD)
                </div>
                <div>
                    <q-btn outline color="primary" :href="orcidAuthUrl">
                        <OrcidAvatar size="md" /><span
                            class="q-ml-md text-grey-8"
                            >{{ $t('ocrid.verify-btn-text') }}
                        </span></q-btn
                    >
                </div>
            </div>
        </q-card-section>
    </ContentCard>
    <ContentCard secondary class="q-mt-lg">
        <template #title>{{ $t('manage-author-profile-view.title') }}</template>
    </ContentCard>
</template>

<script setup lang="ts">
import ManageAuthorProfileCard from '../components/ManageAuthorProfileCard.vue';
import ContentCard from '@/components/ContentCard.vue';
import OrcidAvatar from '@/components/OrcidAvatar.vue';
const authStore = useAuthStore();

// get VITE environment variables
const client_id = import.meta.env.VITE_ORCID_CLIENT_ID;
const redirect_uri = import.meta.env.VITE_ORCID_REDIRECT_URI;

// encode redirect url
const redirectUriEncoded = encodeURIComponent(redirect_uri);
const orcidAuthUrl = `https://orcid.org/oauth/authorize?client_id=${client_id}&response_type=token&scope=openid&redirect_uri=${redirectUriEncoded}`;
</script>

<style scoped></style>
