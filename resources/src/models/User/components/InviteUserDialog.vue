<template>
    <BaseDialog title="Invite a User">
        <q-card-section>
            <p class="q-ma-md">
                Invite a new user to the portal. The invite email will be sent
                to the user's email address and you will be able to whether they
                have accepted the invitation under the "Sent Invitation" tab in
                the "Settings" menu.
            </p>
        </q-card-section>
        <q-banner v-if="errorMessage" dark class="bg-negative">
            <template #avatar
                ><q-icon name="mdi-alert-circle-outline"
            /></template>
            <div>{{ errorMessage.message }}</div>
        </q-banner>
        <q-card-section class="q-mx-md">
            <q-form @submit.prevent="onSubmit">
                <q-input
                    v-model="firstName"
                    label="First Name"
                    outlined
                    :rules="nameRules"
                />
                <q-input
                    v-model="lastName"
                    label="Last Name"
                    outlined
                    :rules="nameRules"
                />
                <q-input
                    v-model="email"
                    label="Email"
                    type="email"
                    outlined
                    :rules="emailRules"
                />
                <LocaleSelect
                    v-model="locale"
                    hint="The invitation email will be sent using the chosen language."
                />
                <q-card-actions align="right" class="q-mt-md">
                    <q-btn v-close-popup label="Cancel" color="primary" flat />
                    <q-btn
                        type="submit"
                        color="primary"
                        label="Invite"
                        :loading="loading"
                        :disable="loading"
                    />
                </q-card-actions>
            </q-form>
        </q-card-section>
    </BaseDialog>
</template>

<script setup lang="ts">
import { ErrorResponse, extractErrorMessages } from '@/api/errors';
import BaseDialog from '@/components/BaseDialog.vue';
import LocaleSelect from '@/components/LocaleSelect.vue';
import { Locale } from '@/stores/LocaleStore';
import { UserResource, UserService } from '../User';

const localStore = useLocaleStore();

const emits = defineEmits<{
    (event: 'created', user: UserResource): void;
}>();

// data
const firstName = ref('');
const lastName = ref('');
const email = ref('');
const locale = ref<Locale>(localStore.locale);

// invitation logic
const loading = ref(false);
const errorMessage = ref<ErrorResponse | null>(null);

function onSubmit() {
    loading.value = true;
    UserService.invite({
        first_name: firstName.value,
        last_name: lastName.value,
        email: email.value,
        locale: locale.value,
    })
        .then((user) => {
            emits('created', user);
        })
        .catch((error) => {
            errorMessage.value = extractErrorMessages(error);
        })
        .finally(() => {
            loading.value = false;
        });
}

// rules
const nameRules = [(val: string) => !!val || 'Required'];

const emailRules = [
    (val: string) => !!val || 'Email is required',
    (val: string) => /.+@.+\..+/.test(val) || 'Email must be valid',
];
</script>

<style scoped></style>
