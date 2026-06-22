<script setup lang="ts">
import type { ManuscriptRecordSummaryResource } from '../ManuscriptRecord'
import OrcidIcon from '@/components/OrcidIcon.vue'
import CloneManuscriptButton from './CloneManuscriptButton.vue'
import DeleteManuscriptButton from './DeleteManuscriptButton.vue'
import ManuscriptStatusBadge from './ManuscriptStatusBadge.vue'
import ManuscriptTypeBadge from './ManuscriptTypeBadge.vue'

defineProps<{
  manuscripts: ManuscriptRecordSummaryResource[]
}>()

defineEmits<{
  (e: 'deleted', manuscript: ManuscriptRecordSummaryResource): void
}>()

const router = useRouter()

function goToManuscript(manuscript: ManuscriptRecordSummaryResource) {
  router.push({
    name: 'manuscript.form',
    params: { id: manuscript.data.id },
  })
}
</script>

<template>
  <q-list separator role="list">
    <q-item
      v-for="manuscript in manuscripts"
      :key="manuscript.data.id"
      clickable
      @click="goToManuscript(manuscript)"
    >
      <q-item-section>
        <q-item-label
          class="text-body1 text-weight-medium text-primary"
        >
          {{ manuscript.data.title }}
        </q-item-label>
        <q-item-label caption lines="2">
          <template v-if="manuscript.data.manuscript_authors">
            <template
              v-if="manuscript.data.manuscript_authors.length > 0"
            >
              <span
                v-for="(item, index) in manuscript.data
                  .manuscript_authors"
                :key="item.data.id"
              >
                <OrcidIcon
                  v-if="item.data.author?.data.orcid"
                  :unauthenticated="!item.data.author.data.orcid_verified"
                />
                {{ item.data.author?.data.last_name }},
                {{ item.data.author?.data.first_name }}
                <span
                  v-if="
                    index
                      < manuscript.data.manuscript_authors
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
        <q-item-label
          v-if="manuscript.data.active_management_review_step"
          caption
        >
          <q-icon name="mdi-account-clock-outline" size="xs" class="q-mr-xs" />
          {{ manuscript.data.active_management_review_step.user_name }}
          <q-badge
            v-if="manuscript.data.active_management_review_step.is_overdue"
            color="negative"
            class="q-ml-xs"
          >
            {{ $t('common.overdue') }}
          </q-badge>
        </q-item-label>
      </q-item-section>
      <q-item-section side top>
        <span>
          <ManuscriptTypeBadge
            :type="manuscript.data.type"
            class="q-mr-xs"
          />
          <ManuscriptStatusBadge :status="manuscript.data.status" />
          <q-chip
            v-if="manuscript.data.business_days_in_review != null"
            dense
            outline
            :color="manuscript.data.active_management_review_step?.is_overdue ? 'negative' : 'grey'"
            class="q-ml-xs"
            icon="mdi-clock-outline"
          >
            {{ manuscript.data.business_days_in_review }}{{ $t('common.day-abbr') }}
          </q-chip>
          <CloneManuscriptButton
            flat
            icon="mdi-content-duplicate"
            size="sm"
            class="q-ml-sm"
            :manuscript="manuscript"
          />
          <DeleteManuscriptButton
            v-if="manuscript.can?.delete"
            flat
            size="sm"
            color="red"
            dense
            icon="mdi-delete-outline"
            :manuscript="manuscript"
            @deleted="$emit('deleted', manuscript)"
          />
        </span>
      </q-item-section>
    </q-item>
  </q-list>
</template>

<style scoped></style>
