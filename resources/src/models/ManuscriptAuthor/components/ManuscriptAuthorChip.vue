<script setup lang="ts">
import type { ManuscriptAuthorResource } from '../ManuscriptAuthor'
import OrcidAvatar from '@/components/OrcidAvatar.vue'
import RORLinkSpan from '@/models/Organization/components/RORLinkSpan.vue'

const props = withDefaults(
  defineProps<{
    manuscriptAuthor: ManuscriptAuthorResource
    readonly?: boolean
  }>(),
  {
    readonly: false,
  },
)

const emit = defineEmits([
  'delete:manuscriptAuthor',
  'edit:toggleCorrespondingAuthor',
])

const localeStore = useLocaleStore()
const { t } = useI18n()

const { copy, copied, isSupported } = useClipboard()

const correspondingAuthor = computed({
  get() {
    return props.manuscriptAuthor.data.is_corresponding_author
  },
  set(value) {
    emit('edit:toggleCorrespondingAuthor', value)
  },
})

const name = computed(() => {
  return `${props.manuscriptAuthor.data.author?.data.last_name}, ${props.manuscriptAuthor.data.author?.data.first_name}`
})

const removable = computed(() => {
  if (props.readonly) {
    return false
  }
  return props.manuscriptAuthor.can?.delete
})
</script>

<template>
  <q-chip
    clickable
    :removable="removable"
    color="teal-2"
    @remove="emit('delete:manuscriptAuthor')"
  >
    <q-avatar
      v-if="manuscriptAuthor.data.is_corresponding_author"
      icon="mdi-at"
      color="primary"
      text-color="white"
    />
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
                manuscriptAuthor.data.organization?.data[
                  `name_${localeStore.locale}`
                ]
              }}
            </q-item-label>
            <q-item-label caption>
              <RORLinkSpan
                :organization="
                  manuscriptAuthor.data.organization
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
                :href="`mailto:${manuscriptAuthor.data.author?.data.email}`"
              >{{
                manuscriptAuthor.data.author?.data.email
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
                  manuscriptAuthor.data.author?.data.email
                    ?? '',
                )
              "
            >
              <q-tooltip>
                {{
                  t('common.copy-to-clipboard')
                }}
              </q-tooltip>
            </q-btn>
            <div v-else class="text-caption">
              {{ t('common.copied') }}
            </div>
          </q-item-section>
        </q-item>
        <q-item v-if="manuscriptAuthor.data.author?.data.orcid">
          <q-item-section avatar>
            <OrcidAvatar :unauthenticated="!manuscriptAuthor.data.author.data.orcid_verified" />
          </q-item-section>
          <q-item-section>
            <q-item-label>
              <a
                class="text-primary"
                :href="manuscriptAuthor.data.author?.data.orcid"
                target="_blank"
              >{{ manuscriptAuthor.data.author?.data.orcid }}</a>
            </q-item-label>
            <q-item-label v-if="!manuscriptAuthor.data.author.data.orcid_verified" caption>
              {{ t('common.unauthenticated-orcid-id') }}
            </q-item-label>
          </q-item-section>
        </q-item>
        <q-item v-if="manuscriptAuthor.can?.update || manuscriptAuthor.data.is_corresponding_author">
          <q-item-section avatar>
            <q-avatar icon="mdi-at" text-color="primary" />
          </q-item-section>
          <q-item-section>
            <q-item-label
              :class="correspondingAuthor ? '' : 'text-grey-5'"
            >
              {{
                t('common.corresponding-author')
              }}
            </q-item-label>
          </q-item-section>
          <q-item-section v-if="!readonly" side>
            <q-toggle v-model="correspondingAuthor">
              <q-tooltip>
                {{
                  t('common.corresponding-author-toggle')
                }}
              </q-tooltip>
            </q-toggle>
          </q-item-section>
        </q-item>
      </q-list>
    </q-menu>
  </q-chip>
</template>

<style scoped></style>
