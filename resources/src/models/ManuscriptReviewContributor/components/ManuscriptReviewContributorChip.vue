<script setup lang="ts">
import type { ManuscriptReviewContributorResource } from '../ManuscriptReviewContributor'
import type { ReviewContributorRole } from '../reviewContributorRoleOptions'
import OrcidAvatar from '@/components/OrcidAvatar.vue'
import { useDefaultOrganizationId } from '@/composables/useDefaultOrganizationId'
import RORLinkSpan from '@/models/Organization/components/RORLinkSpan.vue'
import { useReviewContributorRoleOptions } from '../reviewContributorRoleOptions'
import ReviewContributorRoleSelect from './ReviewContributorRoleSelect.vue'

const props = defineProps<{
  manuscriptReviewContributor: ManuscriptReviewContributorResource
  readonly?: boolean
}>()

const emit = defineEmits<{
  delete: [value: ManuscriptReviewContributorResource]
  updateRole: [value: ReviewContributorRole]
}>()

const localeStore = useLocaleStore()
const { copy, copied, isSupported } = useClipboard()
const { t } = useI18n()
const { roleLabel } = useReviewContributorRoleOptions()

const name = computed(() => {
  return `${props.manuscriptReviewContributor.data.author?.data.last_name}, ${props.manuscriptReviewContributor.data.author?.data.first_name}`
})

const label = computed(() => t('manage-review-contributors.chip-label', {
  role: roleLabel(props.manuscriptReviewContributor.data.role),
  name: name.value,
}))

const role = computed({
  get() {
    return props.manuscriptReviewContributor.data.role
  },
  set(value) {
    emit('updateRole', value)
  },
})

const defaultOrganizationId = useDefaultOrganizationId()
const isInternal = computed(() => props.manuscriptReviewContributor.data.author?.data.organization_id === defaultOrganizationId)

const removable = computed(() => {
  if (props.readonly) {
    return false
  }
  return props.manuscriptReviewContributor.can?.delete
})

const canChangeRole = computed(() => !props.readonly && props.manuscriptReviewContributor.can?.update)
</script>

<template>
  <q-chip
    clickable
    :removable="removable"
    color="teal-2"
    @remove="emit('delete', props.manuscriptReviewContributor)"
  >
    {{ label }}
    <q-icon
      :name="isInternal ? 'mdi-fish' : 'mdi-earth'"
      :color="isInternal ? 'primary' : 'grey-7'"
      :aria-label="isInternal ? t('common.internal-author') : t('common.external-author')"
      size="18px"
      class="q-ml-xs"
    >
      <q-tooltip>
        {{ isInternal ? t('common.internal-author') : t('common.external-author') }}
      </q-tooltip>
    </q-icon>
    <q-icon
      v-if="manuscriptReviewContributor.data.orcid_synced"
      name="img:/assets/orcid/orcid.logo.icon.svg"
      :aria-label="t('manage-review-contributors.on-orcid')"
      size="18px"
      class="q-ml-xs"
    >
      <q-tooltip>
        {{ t('manage-review-contributors.on-orcid') }}
      </q-tooltip>
    </q-icon>
    <q-menu>
      <q-list dense separator>
        <q-item class="q-mt-sm">
          <q-item-section avatar>
            <q-avatar icon="mdi-account-check" text-color="primary" />
          </q-item-section>
          <q-item-section>
            <ReviewContributorRoleSelect
              v-if="canChangeRole"
              v-model="role"
              dense
              class="q-my-xs"
            />
            <q-item-label v-else>
              {{ roleLabel(role) }}
            </q-item-label>
          </q-item-section>
        </q-item>
        <q-item>
          <q-item-section avatar>
            <q-avatar icon="mdi-bank" text-color="primary" />
          </q-item-section>
          <q-item-section>
            <q-item-label>
              {{
                manuscriptReviewContributor.data.author?.data.organization?.data[
                  `name_${localeStore.locale}`
                ]
              }}
            </q-item-label>
            <q-item-label caption>
              <RORLinkSpan
                :organization="
                  manuscriptReviewContributor.data.author?.data.organization
                "
              />
            </q-item-label>
          </q-item-section>
        </q-item>
        <q-item>
          <q-item-section avatar>
            <q-avatar icon="mdi-email" text-color="primary" />
          </q-item-section>
          <q-item-section>
            <q-item-label>
              <a
                class="text-primary"
                :href="`mailto:${manuscriptReviewContributor.data.author?.data.email}`"
              >{{
                manuscriptReviewContributor.data.author?.data.email
              }}</a>
            </q-item-label>
          </q-item-section>
          <q-item-section v-if="isSupported" side>
            <q-btn
              v-if="!copied"
              icon="mdi-content-copy"
              round
              flat
              size="sm"
              @click="
                copy(
                  manuscriptReviewContributor.data.author?.data.email
                    ?? '',
                )
              "
            >
              <q-tooltip>
                {{
                  $t('common.copy-to-clipboard')
                }}
              </q-tooltip>
            </q-btn>
            <div v-else class="text-caption">
              {{ $t('common.copied') }}
            </div>
          </q-item-section>
        </q-item>
        <q-item v-if="manuscriptReviewContributor.data.author?.data.orcid">
          <q-item-section avatar>
            <OrcidAvatar />
          </q-item-section>
          <q-item-section>
            <a
              class="text-primary"
              :href="manuscriptReviewContributor.data.author?.data.orcid"
              target="_blank"
            >{{ manuscriptReviewContributor.data.author?.data.orcid }}</a>
          </q-item-section>
        </q-item>
      </q-list>
    </q-menu>
  </q-chip>
</template>

<style scoped>

</style>
