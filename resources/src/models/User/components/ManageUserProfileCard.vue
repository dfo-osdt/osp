<template>
    <ContentCard secondary>
        <template #title>User Profile</template>
        <q-form v-if="user" @submit="save()">
            <div class="row q-col-gutter-md">
                <q-input
                    v-model="user.first_name"
                    class="col-12 col-md-6"
                    label="First Name"
                    outlined
                    :rules="[
                        (val) => val.length > 0 || 'First Name is required',
                    ]"
                />
                <q-input
                    v-model="user.last_name"
                    class="col-12 col-md-6"
                    label="Last Name"
                    outlined
                    :rules="[
                        (val) => val.length > 0 || 'Last Name is required',
                    ]"
                />
                <q-input
                    v-model="user.email"
                    class="col-12 col-md-6"
                    label="Email"
                    outlined
                    :disable="true"
                    hint="Your verified email address cannot be changed manually."
                />
                <q-select
                    v-model="user.locale"
                    emit-value
                    map-options
                    class="col-12 col-md-6"
                    label="Language Preference"
                    outlined
                    :options="[
                        { label: 'English', value: 'en' },
                        { label: 'French', value: 'fr' },
                    ]"
                />
            </div>
            <q-card-actions align="right">
                <q-btn
                    color="primary"
                    label="Save"
                    type="submit"
                    :loading="loading"
                />
            </q-card-actions>
        </q-form>
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import { Ref } from 'vue';
import { User, UserService } from '../User';
import { useQuasar } from 'quasar';

const $q = useQuasar();

const props = defineProps<{
    userId: number;
}>();

const emit = defineEmits<{
    (event: 'saved'): void;
}>();

const loading = ref(true);
const user: Ref<User | null> = ref(null);

async function getUser() {
    loading.value = true;
    user.value = (await UserService.get(props.userId)).data;
    loading.value = false;
}

onMounted(async () => {
    getUser();
    console.log('get user');
});

async function save() {
    console.log('save');
    if (!user.value) return;
    loading.value = true;
    const userResource = await UserService.update(
        user.value.id,
        user.value?.first_name,
        user.value?.last_name,
        user.value?.locale
    );
    user.value = userResource.data;
    loading.value = false;
    emit('saved');
    // notify
    $q.notify({
        message: 'User profile saved',
        type: 'positive',
        position: 'top',
    });
}
</script>

<style scoped></style>
