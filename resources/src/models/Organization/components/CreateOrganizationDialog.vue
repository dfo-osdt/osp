<template>
    <BaseDialog persistent :title="$t('create-organization-dialog.title')">
        <q-card-section>
            <p>
                {{ $t('create-organization-dialog.p1') }}
            </p>
            <p>
                {{ $t('create-organization-dialog.p2') }}
            </p>
            <q-separator />
        </q-card-section>
        <q-form @submit="createOrganization">
            <q-input
                v-model="name_en"
                outlined
                :label="
                    $t('create-organization-dialog.organization-name-english')
                "
                class="q-ma-md"
                :rules="[(val: string) => val !== '' ||
            $t('common.required')]"
            />
            <q-input
                v-model="name_fr"
                outlined
                :label="
                    $t('create-organization-dialog.organization-name-french')
                "
                class="q-ma-md"
                :rules="[(val: string) => val !== '' || $t('common.required')]"
            />
            <q-input
                v-model="abbr_en"
                outlined
                :label="$t('create-organization-dialog.abbreviation-english')"
                class="q-ma-md"
                hint="Optional"
            />
            <q-input
                v-model="abbr_fr"
                outlined
                :label="$t('create-organization-dialog.abbreviation-french')"
                class="q-ma-md"
                hint="Optional"
            />
            <div class="flex justify-end">
                <q-btn
                    color="primary"
                    :label="$t('common.create')"
                    type="submit"
                    class="q-ma-md"
                />
            </div>
        </q-form>
    </BaseDialog>
</template>

<script setup lang="ts">
import BaseDialog from '@/components/BaseDialog.vue';
import {
    Organization,
    OrganizationResource,
    OrganizationService,
} from '../Organization';

const emit = defineEmits<{
    (event: 'created', payload: OrganizationResource): void;
}>();

const name_en = ref('');
const name_fr = ref('');
const abbr_en = ref('');
const abbr_fr = ref('');

async function createOrganization() {
    const org: Organization = {
        id: 0,
        name_en: name_en.value,
        name_fr: name_fr.value,
        abbr_en: abbr_en.value,
        abbr_fr: abbr_fr.value,
    };

    const organization = await OrganizationService.create(org);
    emit('created', organization);
}
</script>

<style scoped></style>
