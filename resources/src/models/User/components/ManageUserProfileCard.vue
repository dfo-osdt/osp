<template>
    <ContentCard>
        <template #title>Manage User Profile</template>
        <q-skeleton v-if="!user" height="150px" />
        <q-form v-else @submit="save()">
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
            </div>
        </q-form>
        <q-card-actions align="right">
            <q-btn v-if="user" color="primary" label="Save" type="submit" />
        </q-card-actions>
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import { Ref } from 'vue';
import { User, UserService } from '../User';

const props = defineProps<{
    userId: number;
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
});

async function save() {
    if (!user.value) return;
    loading.value = true;
    const userResource = await UserService.update(
        user.value.id,
        user.value?.first_name,
        user.value?.last_name
    );
    user.value = userResource.data;
    loading.value = false;
}
</script>

<style scoped></style>
