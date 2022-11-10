<template>
    <BaseDialog title="Update the manuscript status as submitted">
        <div class="q-pa-md text-body1">
            <p>
                Mark this manuscript as having been submitted to a journal. This
                step is optional and allows you to let all users involved in the
                management review that you submitted your manuscript.
            </p>
            <q-form @submit="submit">
                <DateInput
                    v-model="submittedOn"
                    class="q-mx-sm"
                    label="Submitted to Journal On"
                    required
                />
                <q-card-actions align="right">
                    <q-btn
                        v-close-popup
                        label="Cancel"
                        color="secondary"
                        outline
                    />
                    <q-btn
                        color="primary"
                        label="Update"
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
import DateInput from '@/components/DateInput.vue';
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

const now = new Date().toISOString().substring(0, 10);
const submittedOn = ref(now);

const loading = ref(false);

async function submit() {
    loading.value = true;
    try {
        const resource = await ManuscriptRecordService.submitted(
            props.manuscriptRecordId,
            submittedOn.value
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
