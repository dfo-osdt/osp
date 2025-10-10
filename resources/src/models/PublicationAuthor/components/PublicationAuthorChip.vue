<script setup lang="ts">
import type { PublicationAuthorResource } from '../PublicationAuthor'
import OrcidAvatar from '../../../components/OrcidAvatar.vue'

const props = withDefaults(
  defineProps<{
    publicationAuthor: PublicationAuthorResource
    readonly?: boolean
  }>(),
  {
    readonly: false,
  },
)

const emit = defineEmits([
  'deletePublicationAuthor',
  'editToggleCorrespondingAuthor',
])

const { copy, copied, isSupported } = useClipboard()

const correspondingAuthor = ref(
  props.publicationAuthor.data.is_corresponding_author,
)

const name = computed(() => {
  return `${props.publicationAuthor.data.author?.data.last_name}, ${props.publicationAuthor.data.author?.data.first_name}`
})

const removable = computed(() => {
  if (props.readonly) {
    return false
  }
  return props.publicationAuthor.can?.delete
})
</script>

<template>
  <q-chip
    clickable
    :removable="removable"
    color="teal-2"
    @remove="emit('deletePublicationAuthor')"
  >
    <q-avatar
      v-if="publicationAuthor.data.is_corresponding_author"
      icon="mdi-at"
      color="primary"
      text-color="white"
    />
    {{ name }}
    <q-menu>
      <q-list dense separator>
        <q-item>
          <q-item-section avatar>
            <q-avatar icon="mdi-bank" text-color="primary" />
          </q-item-section>
          <q-item-section>
            <q-item-label>
              {{
                publicationAuthor.data.organization?.data
                  .name_en
              }}
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
                :href="`mailto:${publicationAuthor.data.author?.data.email}`"
              >{{
                publicationAuthor.data.author?.data.email
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
                  publicationAuthor.data.author?.data.email
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
        <q-item
          v-if="publicationAuthor.data.author?.data.orcid"
          clickable
        >
          <q-item-section avatar>
            <OrcidAvatar
              :unauthenticated="
                !publicationAuthor.data.author.data.orcid_verified
              "
            />
          </q-item-section>
          <q-item-section>
            <q-item-label>
              <a
                class="text-primary"
                :href="publicationAuthor.data.author?.data.orcid"
                target="_blank"
              >{{ publicationAuthor.data.author?.data.orcid }}</a>
            </q-item-label>
            <q-item-label
              v-if="!publicationAuthor.data.author.data.orcid_verified"
              caption
            >
              {{ $t('common.unauthenticated-orcid-id') }}
            </q-item-label>
          </q-item-section>
        </q-item>
        <q-item>
          <q-item-section avatar>
            <q-avatar icon="mdi-at" text-color="primary" />
          </q-item-section>
          <q-item-section>
            <q-item-label
              :class="correspondingAuthor ? '' : 'text-grey-5'"
            >
              {{
                $t('common.corresponding-author')
              }}
            </q-item-label>
          </q-item-section>
          <q-item-section v-if="!readonly" side>
            <q-toggle
              v-model="correspondingAuthor"
              @update:model-value="
                $emit(
                  'editToggleCorrespondingAuthor',
                  correspondingAuthor,
                )
              "
            >
              <q-tooltip>
                {{
                  $t('common.corresponding-author-toggle')
                }}
              </q-tooltip>
            </q-toggle>
          </q-item-section>
        </q-item>
        <q-separator />
        <q-item
          clickable
          :to="{
            name: 'author.profile',
            params: { id: publicationAuthor.data.author?.data.id },
          }"
        >
          <q-item-section avatar>
            <q-avatar icon="mdi-account" text-color="primary" />
          </q-item-section>
          <q-item-section>
            <q-item-label>
              {{ $t('author.view-profile') }}
            </q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-menu>
  </q-chip>
</template>

<style scoped></style>
