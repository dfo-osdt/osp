<script setup lang="ts">
import type { PreprintResource } from '../Preprint'
import DoiLink from '@/models/Publication/components/DoiLink.vue'

defineProps<{
  preprints: PreprintResource[]
}>()

const { t } = useI18n()

function publishedYear(preprint: PreprintResource) {
  if (preprint.data.accepted_on) {
    return new Date(preprint.data.accepted_on).getFullYear()
  }
  return t('common.pending-publication')
}
</script>

<template>
  <q-list separator role="list">
    <q-item
      v-for="preprint in preprints"
      :key="preprint.data.manuscript_record_id"
    >
      <q-item-section>
        <q-item-label
          class="text-body1 text-weight-medium text-primary"
        >
          {{ preprint.data.title }}
        </q-item-label>
        <q-item-label caption lines="2">
          <template v-if="preprint.data.authors">
            <template
              v-if="
                preprint.data.authors.length > 0
              "
            >
              <span
                v-for="(item, index) in preprint.data
                  .authors"
                :key="item.data.id"
              >
                <q-img
                  v-if="item.data.author?.data.orcid"
                  src="/assets/orcid/orcid.logo.icon.svg"
                  width="15px"
                />
                {{ item.data.author?.data.last_name }},
                {{ item.data.author?.data.first_name }}
                <span
                  v-if="
                    index
                      < preprint.data.authors
                        ?.length
                      - 1
                  "
                >;
                </span>
              </span>
            </template>
            <template v-else>
              <span>{{ $t('common.no-authors') }}</span>
            </template>
          </template>
        </q-item-label>
        <q-item-label caption class="text-uppercase">
          <div class="q-pt-sm flex">
            <div>
              {{ publishedYear(preprint) }}
            </div>
            <div class="q-mx-sm">
              |
            </div>
            <div>URL/DOI: <DoiLink :doi="preprint.data.preprint_url" /></div>
          </div>
        </q-item-label>
      </q-item-section>
    </q-item>
  </q-list>
</template>

<style scoped></style>
