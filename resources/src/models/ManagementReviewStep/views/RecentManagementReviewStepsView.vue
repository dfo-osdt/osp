<template>
    <div v-if="steps.loading">
        <q-spinner-dots size="50px" color="primary" />
    </div>
    <div v-else>
        <div v-if="steps.empty" class="flex row justify-center">
            <div class="col-12 col-lg-10 flex column text-center">
                <div>
                    <q-img
                        src="/assets/splash_images/undraw_done_checking_re_6vyx.svg"
                        width="250px"
                        class="q-mb-sm"
                    />
                </div>
                <div class="text-h5 text-weight-light text-accent q-mb-sm">
                    You have nothing to review
                </div>
                <div class="text-body1 text-grey-8">
                    <p>
                        Manuscripts you've recently been asked to review will
                        appear here.
                    </p>
                </div>
            </div>
        </div>
        <div v-else>
            <ManagementReviewStepList :manuscripts="steps.recent" />
        </div>
    </div>
</template>

<script setup lang="ts">
import ManagementReviewStepList from '../components/ManagementReviewStepList.vue';

const steps = useManagementReviewStepStore();

onMounted(async () => {
    steps.getMyManagementReviewSteps();
});
</script>

<style scoped></style>
