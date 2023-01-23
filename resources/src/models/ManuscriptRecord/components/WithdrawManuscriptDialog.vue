<template>
    <BaseDialog :title="$t('withdraw-manuscript-dialog.title')">
        <div class="q-pa-md text-body1">
            <p>
                {{ $t('withdraw-manuscript-dialog.text') }}
            </p>
            <q-form @submit="submit">
                <q-card-actions align="right">
                    <q-btn
                        v-close-popup
                        :label="$t('common.cancel')"
                        color="secondary"
                        outline
                    />
                    <q-btn
                        color="primary"
                        :label="$t('common.withdraw')"
                        type="submit"
                        :loading="loading"
                    />
                </q-card-actions>
            </q-form>
        </div>
    </BaseDialog>
</template>

<script setup lang="ts">
import BaseDialog from '@/components/BaseDialog.vue';
import { QForm, QCardActions, QBtn } from 'quasar';
import {
    ManuscriptRecordResource,
    ManuscriptRecordService,
} from '../ManuscriptRecord';

const props = defineProps<{
    manuscriptRecordId: number;
}>();

const emit = defineEmits<{
    (event: 'updated', arg: ManuscriptRecordResource): void;
}>();

const loading = ref(false);

async function submit() {
    loading.value = true;
    try {
        const resource = await ManuscriptRecordService.withdraw(
            props.manuscriptRecordId
        );
        emit('updated', resource);
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
}
</script>

<style scoped></style>
