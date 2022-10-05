<template>
    <MainPageLayout title="My Profile">
        <ManageUserProfileCard class="q-ma-md" />
        <ManageAuthorProfileCard class="q-ma-md" />
    </MainPageLayout>
</template>

<script setup lang="ts">
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import ManageUserProfileCard from '../components/ManageUserProfileCard.vue';
import ManageAuthorProfileCard from '@/models/Author/components/ManageAuthorProfileCard.vue';



</script>

<style scoped>

</style>