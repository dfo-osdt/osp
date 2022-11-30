<template>
    <MainPageLayout title="My Manuscripts">
        <div class="row q-gutter-lg q-col-gutter-lg flex">
            <div class="cols-2">
                <ContentCard secondary>
                    <template #title>Filters</template>
                </ContentCard>
            </div>
            <div class="col q-pr-lg">
                <ContentCard secondary>
                    <template #title>My Manuscripts</template>
                    <ManuscriptList :manuscripts="manuscripts" />
                </ContentCard>
            </div>
        </div>
    </MainPageLayout>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import ManuscriptList from '../components/ManuscriptList.vue';
import {
    ManuscriptRecordResource,
    ManuscriptRecordService,
} from '../ManuscriptRecord';

const manuscripts = ref<ManuscriptRecordResource[]>([]);

onMounted(() => {
    console.log('mounted');
    getManuscripts();
});

async function getManuscripts() {
    manuscripts.value = await ManuscriptRecordService.getMyManuscripts();
}
</script>

<style scoped></style>
