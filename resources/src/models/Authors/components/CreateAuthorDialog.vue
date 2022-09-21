<template>
    <BaseDialog persistent title="Create a new Author">
        <q-form @submit="createAuthor">
            <q-input
                v-model="firstName"
                outlined
                label="First Name"
                class="q-ma-md"
                :rules="[(val: string) => val !== '' || 'Required']"
            />
            <q-input
                v-model="lastName"
                outlined
                label="Last Name"
                class="q-ma-md"
                :rules="[(val: string) => val !== '' || 'Required']"
            />
            <OrganizationSelect
                v-model="organizationId"
                label="Affiliation"
                class="q-ma-md"
                :rules="[(val: number|null) => val !== null || 'Organization is required']"
            />
            <q-input
                v-model="email"
                outlined
                label="Email"
                class="q-ma-md"
                :rules="[
                        (val: string) => val !== '' || 'Required',
                        (val: string) => /^\S+@\S+\.\S+$/.test(val) || 'Email is invalid']"
            />
            <OrcidInput v-model="orcId" class="q-ma-md" />
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
import { AuthorResource, AuthorService, Author } from '../Author';
import OrganizationSelect from '@/models/Organization/components/OrganizationSelect.vue';
import BaseDialog from '@/components/BaseDialog.vue';
import OrcidInput from '@/components/OrcidInput.vue';

const emit = defineEmits<{
    (event: 'created', payload: AuthorResource): void;
}>();

const firstName = ref('');
const lastName = ref('');
const email = ref('');
const organizationId = ref<number | null>(null);
const orcId = ref('');

const createAuthor = async () => {
    if (organizationId.value === null) {
        return;
    }

    const data: Author = {
        id: 0,
        first_name: firstName.value,
        last_name: lastName.value,
        email: email.value,
        organization_id: organizationId.value,
        orcid: orcId.value,
    };

    const author = await AuthorService.create(data);
    emit('created', author);
};
</script>

<style scoped></style>
