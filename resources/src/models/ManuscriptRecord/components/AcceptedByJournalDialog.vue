<template>
    <BaseDialog title="Journal accepted for publication">
        <div class="q-pa-md q">
            <div class="text-primary text-h6 q-mb-sm">
                {{ $t('common.congratulations') }}
            </div>
            <p>
                {{ $t('accepted-by-journal-dialog.text') }}
            </p>
            <q-form class="q-gutter-y-md q-mt-md" @submit="submit">
                <DateInput
                    v-model="submittedOn"
                    class="q-mx-sm"
                    :label="$t('common.submitted-to-journal-on')"
                    required
                    :max-date="acceptedOn"
                />
                <DateInput
                    v-model="acceptedOn"
                    class="q-mx-sm"
                    :label="$t('common.accepted-for-publication-on')"
                    required
                    :min-date="submittedOn"
                />
                <JournalSelect
                    v-model="journalId"
                    class="q-mx-sm"
                    :label="$t('common.journal')"
                    :rules="[(val: number|null) => val !== null || 'Journal is required']"
                />
                <q-card-actions align="right">
                    <q-btn
                        v-close-popup
                        :label="$t('common.cancel')"
                        color="secondary"
                        outline
                    />
                    <q-btn
                        color="primary"
                        :label="$t('common.update')"
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
import JournalSelect from '@/models/Journal/components/JournalSelect.vue';
import { QForm, QCardActions, QBtn } from 'quasar';
import {
    ManuscriptRecord,
    ManuscriptRecordResource,
    ManuscriptRecordService,
} from '../ManuscriptRecord';

const props = defineProps<{
    manuscriptRecord: ManuscriptRecord;
}>();

const emit = defineEmits<{
    (event: 'updated', arg: ManuscriptRecordResource): void;
}>();

const now = new Date().toISOString().substring(0, 10);
const submittedOn = ref(props.manuscriptRecord.submitted_to_journal_on || now);
const acceptedOn = ref(now);
const journalId = ref<number | null>(null);

const loading = ref(false);

async function submit() {
    loading.value = true;
    try {
        const resource = await ManuscriptRecordService.accepted(
            props.manuscriptRecord.id,
            submittedOn.value,
            acceptedOn.value,
            // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
            journalId.value! // should not be null because of validation
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
