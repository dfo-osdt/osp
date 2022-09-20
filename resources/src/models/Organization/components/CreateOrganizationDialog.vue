<template>
    <BaseDialog persistent title="Create new Organization">
        <q-card-section>
            <p>
                Once created, this organization will be available to all users.
                Please make sure to use the official name and abbreviation for
                the organization. (E.g.: do not include departments, branches or
                regions.)
            </p>
            <p>
                If the name of the organization does not have an official French
                or English equivalent, please copy the official name in both
                fields.
            </p>
            <q-separator />
        </q-card-section>
        <q-form @submit="createOrganization">
            <q-input
                v-model="name_en"
                outlined
                label="Organization Name
            (English)"
                class="q-ma-md"
                :rules="[(val: string) => val !== '' ||
            'Required']"
            />
            <q-input
                v-model="name_fr"
                outlined
                label="Organization Name (French)"
                class="q-ma-md"
                :rules="[(val: string) => val !== '' || 'Required']"
            />
            <q-input
                v-model="abbr_en"
                outlined
                label="Abbreviation (English)"
                class="q-ma-md"
                hint="Optional"
            />
            <q-input
                v-model="abbr_fr"
                outlined
                label="Abbreviation (French)"
                class="q-ma-md"
                hint="Optional"
            />
            <div class="flex justify-end">
                <q-btn
                    color="primary"
                    label="Create"
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
    console.log('creating organization..');

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
