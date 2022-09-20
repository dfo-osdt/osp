<template>
    <q-select
        ref="organizationSelect"
        v-model="selectedOrganization"
        :options="organizations.data"
        :option-value="optionValue"
        :option-label="optionLabel"
        label="Organization"
        :loading="organizationsLoading"
        use-input
        stack-label
        outlined
        hint="Start typing to search for an Organization (name or abbreviation)"
        @filter="filterOrganizations"
    >
        <template #no-option>
            <template v-if="lastSearchTerm === ''">
                <q-item>
                    <q-item-section class="text-grey">
                        Start typing to search for an organization
                    </q-item-section>
                </q-item>
            </template>
            <template v-else>
                <q-item>
                    <q-item-section class="text-grey">
                        No results found for
                        <strong>{{ lastSearchTerm }}</strong>
                    </q-item-section>
                </q-item>
                <q-separator />
                <q-item clickable @click="showCreateOrganizationDialog = true">
                    <q-item-section>
                        Can't find the organization you're looking for?
                    </q-item-section>
                    <q-item-section side>
                        <q-btn
                            icon="mdi-plus"
                            color="primary"
                            size="sm"
                            round
                            @click="showCreateOrganizationDialog = true"
                        >
                            <q-tooltip class="text-body2"
                                >Add a new author</q-tooltip
                            >
                        </q-btn>
                        <CreateOrganizationDialog
                            v-if="showCreateOrganizationDialog"
                            v-model="showCreateOrganizationDialog"
                            @created="createdOrganization"
                        />
                    </q-item-section>
                </q-item>
            </template>
        </template>
    </q-select>
</template>

<script setup lang="ts">
import { QSelect } from 'quasar';
import {
    OrganizationResource,
    OrganizationResourceList,
    OrganizationService,
} from '../Organization';
import CreateOrganizationDialog from './CreateOrganizationDialog.vue';

const props = defineProps<{
    modelValue: number | null;
    initialSearchTerm?: string;
}>();

const organizationSelect = ref<QSelect | null>(null);

const organizations = ref<OrganizationResourceList>({ data: [] });
const selectedOrganization = ref<OrganizationResource | null>(null);
const lastSearchTerm = ref('');
const organizationsLoading = ref(false);
const showCreateOrganizationDialog = ref(false);

const emit = defineEmits(['update:modelValue']);
watch(selectedOrganization, (organization) => {
    if (organization) {
        emit('update:modelValue', organization.data.id);
    }
});

onMounted(() => {
    if (props.initialSearchTerm) {
        organizationSelect.value?.filter(props.initialSearchTerm);
    }
});

const filterOrganizations = async (val: string, update, abort) => {
    lastSearchTerm.value = val;
    update(async () => {
        if (val !== '') {
            const needle = val.toLowerCase();
            organizationsLoading.value = true;
            await OrganizationService.list(
                `limit=10&filter[search]=${needle}`
            ).then((response) => {
                organizations.value = response;
            });
            organizationsLoading.value = false;
        }
    });
};

function createdOrganization(item: OrganizationResource) {
    organizationSelect.value?.updateInputValue('', true);
    organizations.value.data.push(item);
    selectedOrganization.value = item;
}

function optionValue(item: OrganizationResource) {
    return item.data.id;
}
function optionLabel(item: OrganizationResource) {
    const { name_en, abbr_en } = item.data;
    return `${name_en} (${abbr_en})`;
}
</script>

<style scoped></style>
