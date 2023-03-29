<template>
    <div v-if="steps.loading">
        <q-spinner-dots size="50px" color="primary" />
    </div>
    <div v-else>
        <NoManagementStepExistsDiv v-if="steps.empty" />
        <div v-else>
            <ManagementReviewStepList :management-review-steps="steps.recent" />
        </div>
    </div>
</template>

<script setup lang="ts">
import ManagementReviewStepList from '../components/ManagementReviewStepList.vue';
import NoManagementStepExistsDiv from '../components/NoManagementStepExistsDiv.vue';

const steps = useManagementReviewStepStore();

onMounted(async () => {
    steps.getMyManagementReviewSteps();
});
</script>

<style scoped></style>
