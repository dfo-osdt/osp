<script setup lang="ts">
import type { ManuscriptPeerReviewerResource } from '../ManuscriptPeerReviewer'
import OrcidAvatar from '@/components/OrcidAvatar.vue'
import RORLinkSpan from '@/models/Organization/components/RORLinkSpan.vue'

const props = defineProps<{
  manuscriptPeerReviewer: ManuscriptPeerReviewerResource
  readonly?: boolean
}>()

const emit = defineEmits([
  'delete',
])

const localeStore = useLocaleStore()
const { copy, copied, isSupported } = useClipboard()

const name = computed(() => {
  return `${props.manuscriptPeerReviewer.data.author?.data.last_name}, ${props.manuscriptPeerReviewer.data.author?.data.first_name}`
})

const removable = computed(() => {
  if (props.readonly) {
    return false
  }
  return props.manuscriptPeerReviewer.can?.delete
})
</script>

<template>
  <q-chip
    clickable
    :removable="removable"
    color="teal-2"
    @remove="emit('delete')"
  >
    {{ name }}
    <q-menu>
      <q-list dense separator>
        <q-item class="q-mt-sm">
          <q-item-section avatar>
            <q-avatar icon="mdi-bank" text-color="primary" />
          </q-item-section>
          <q-item-section>
            <q-item-label>
              {{
                manuscriptPeerReviewer.data.author?.data.organization?.data[
                  `name_${localeStore.locale}`
                ]
              }}
            </q-item-label>
            <q-item-label caption>
              <RORLinkSpan
                :organization="
                  manuscriptPeerReviewer.data.author?.data.organization
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
                :href="`mailto:${manuscriptPeerReviewer.data.author?.data.email}`"
              >{{
                manuscriptPeerReviewer.data.author?.data.email
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
                  manuscriptPeerReviewer.data.author?.data.email
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
        <q-item v-if="manuscriptPeerReviewer.data.author?.data.orcid">
          <q-item-section avatar>
            <OrcidAvatar />
          </q-item-section>
          <q-item-section>
            <a
              class="text-primary"
              :href="manuscriptPeerReviewer.data.author?.data.orcid"
              target="_blank"
            >{{ manuscriptPeerReviewer.data.author?.data.orcid }}</a>
          </q-item-section>
        </q-item>
      </q-list>
    </q-menu>
  </q-chip>
</template>

<style scoped>

</style>
