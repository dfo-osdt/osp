<template>
    <MainPageLayout icon="mdi-file-document" title="Manuscript">
        {{ manuscriptResource?.data.title }}
    </MainPageLayout>
</template>

<script setup lang="ts">
import { Ref } from 'vue';
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import {
    ManuscriptRecordService,
    ManuscriptRecordResource,
} from '../ManuscriptRecord';

const props = defineProps<{
    id: number;
}>();

const loading = ref(true);
let manuscriptResource: Ref<ManuscriptRecordResource | null> = ref(null);

onMounted(async () => {
    await ManuscriptRecordService.find(props.id)
        .then((response) => {
            manuscriptResource.value = response;
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            loading.value = false;
        });
    loading.value = false;

    // change title value in 1 second
    setTimeout(() => {
        manuscriptResource.value
            ? (manuscriptResource.value.data.title = 'New title')
            : null;
    }, 1000);

    console.log(manuscriptResource.value?.data.title);
});
</script>

<style scoped></style>
