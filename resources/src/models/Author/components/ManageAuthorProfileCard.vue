<template>
    <ContentCard>
        <template #title>Manage your Author Profile</template>
        <template v-if="author">
            <q-form @submit="save()">
                <div class="row q-col-gutter-md">
                    <q-input
                        v-model="author.data.first_name"
                        class="col-12 col-md-6"
                        label="First Name"
                        outlined
                        :disable="hasOwner"
                        :rules="[
                            (val) => val.length > 0 || 'First Name is required',
                        ]"
                        :show-hint="hasOwner"
                        hint="Synced to your user profile."
                    />
                    <q-input
                        v-model="author.data.last_name"
                        class="col-12 col-md-6"
                        label="Last Name"
                        outlined
                        :disable="hasOwner"
                        :rules="[
                            (val) => val.length > 0 || 'Last Name is required',
                        ]"
                        :show-hint="hasOwner"
                        hint="Synced to your user profile."
                    />
                    <q-input
                        v-model="author.data.email"
                        class="col-12 col-md-6"
                        label="Email"
                        outlined
                        :disable="true"
                        :show-hint="hasOwner"
                        hint="Synced to your user profile."
                    />
                    <OrganizationSelect
                        v-model="author.data.organization_id"
                        class="col-12 col-md-6"
                        label="Current Affiliation"
                        outlined
                        hint="This will not affect the affiliation of any of your
                            past publications."
                        clearable
                        :rules="[
                            (val) => val !== null || 'Organization is required',
                        ]"
                    />
                    <OrcidInput
                        v-model="author.data.orcid"
                        class="col-12 col-md-6"
                        label="ORCID"
                        outlined
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
        </template>
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import { Ref } from 'vue';
import { AuthorResource, AuthorService } from '../Author';
import OrganizationSelect from '@/models/Organization/components/OrganizationSelect.vue';
import OrcidInput from '@/components/OrcidInput.vue';

const props = defineProps<{
    authorId: number;
}>();

const loading = ref(true);
const author: Ref<AuthorResource | null> = ref(null);

const hasOwner = computed(() => {
    return author.value?.data.user_id !== null;
});

async function getAuthor() {
    loading.value = true;
    author.value = await AuthorService.find(props.authorId);
    loading.value = false;
}

onMounted(async () => {
    getAuthor();
});

async function save() {
    if (!author.value) return;
    loading.value = true;
    author.value = await AuthorService.update(author.value.data);
    loading.value = false;
}
</script>

<style scoped></style>
