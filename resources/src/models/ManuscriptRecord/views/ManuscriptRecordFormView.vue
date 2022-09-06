<template>
    <MainPageLayout icon="mdi-file-document" title="Manuscript">
        test
    </MainPageLayout>
</template>

<script setup lang="ts">
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import { ManuscriptRecordService, ManuscriptRecordResource } from '../ManuscriptRecord';

const props = defineProps<{
    id: number
}>();

const loading = ref(true);
let manuscriptResource: ManuscriptRecordResource | null = null;


onMounted(async () => {
    await ManuscriptRecordService.find(props.id).then(
        (response) => {
            manuscriptResource = reactive(response);
        }
    ).catch((error) => {
        console.log(error);
    }).finally(() => {
        loading.value = false;
    });
    loading.value = false;

    console.log(manuscriptResource?.data.title);
});



</script>

<style scoped>

</style>