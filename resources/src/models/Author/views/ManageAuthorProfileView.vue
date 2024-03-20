<template>
    <div class="q-mb-xl">
        <ManageAuthorProfileCard
            v-if="authStore.user && !authStore.loading"
            :author-id="authStore.user.author.data.id"
            secondary
        />
        <ManageAuthorOrcidCard
            v-if="authStore.user && !authStore.loading"
        ></ManageAuthorOrcidCard>
        <ManageAuthorExpertise
            v-if="authStore.user && !authStore.loading"
            :author-id="authStore.user.author.data.id"
        />
    </div>
</template>

<script setup lang="ts">
import ManageAuthorProfileCard from '../components/ManageAuthorProfileCard.vue';
import ManageAuthorExpertise from '../components/ManageAuthorExpertise.vue';
import ManageAuthorOrcidCard from '../components/ManageAuthorOrcidCard.vue';
const authStore = useAuthStore();
</script>

<style scoped></style>
