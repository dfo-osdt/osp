<script setup lang="ts">
import type { AuthorResource, AuthorResourceList } from '../Author'
import { QSelect } from 'quasar'
import { AuthorService } from '../Author'
import CreateAuthorDialog from './CreateAuthorDialog.vue'

const props = withDefaults(
  defineProps<{
    modelValue: number | null
    disabledAuthorIds?: number[]
    hideCreateAuthorDialog?: boolean
  }>(),
  {
    disabledAuthorIds: () => [],
  },
)

const emit = defineEmits(['update:modelValue'])

const authorSelect = ref<QSelect | null>(null)

const authors = ref<AuthorResourceList>({ data: [] })
const selectedAuthor = ref<AuthorResource | null>(null)
const lastSearchTerm = ref('')
const authorsLoading = ref(false)
const showCreateAuthorDialog = ref(false)

watch(selectedAuthor, (author) => {
  if (author) {
    emit('update:modelValue', author.data.id)
  }
  else {
    emit('update:modelValue', null)
  }
})

async function filterAuthors(val: string, update: (arg0: () => Promise<void>) => void) {
  lastSearchTerm.value = val
  update(async () => {
    if (val !== '') {
      const needle = val.toLowerCase()
      authorsLoading.value = true
      await AuthorService.list(
        `limit=10&include=organization&filter[search]=${needle}`,
      ).then((response) => {
        authors.value = response
      })
      authorsLoading.value = false
    }
  })
}

function createdAuthor(item: AuthorResource) {
  authorSelect.value?.updateInputValue('', true)
  selectedAuthor.value = item
  showCreateAuthorDialog.value = false
}

function optionValue(item: AuthorResource) {
  return item.data.id
}
function optionLabel(item: AuthorResource) {
  const { first_name, last_name, email } = item.data
  return `${first_name} ${last_name} (${email})`
}
function optionDisable(item: AuthorResource) {
  return props.disabledAuthorIds.includes(item.data.id)
}

defineExpose({
  selectedAuthor,
})
</script>

<template>
  <QSelect
    ref="authorSelect"
    v-model="selectedAuthor"
    :options="authors.data"
    :option-value="optionValue"
    :option-disable="optionDisable"
    :option-label="optionLabel"
    clearable
    :label="$t('common.author', 1)"
    :loading="authorsLoading"
    use-input
    stack-label
    outlined
    :hint="$t('author-select.hint')"
    @filter="filterAuthors"
  >
    <template #no-option>
      <q-item>
        <q-item-section class="text-grey">
          <template v-if="lastSearchTerm === ''">
            {{ $t('author-select.empty-search-hint') }}
          </template>
          <template v-else>
            {{ $t('common.no-results-found-for') }}
            <strong>{{ lastSearchTerm }}</strong>
          </template>
        </q-item-section>
      </q-item>
      <q-separator />
      <q-item
        v-if="!hideCreateAuthorDialog"
        clickable
        @click="showCreateAuthorDialog = true"
      >
        <q-item-section>
          {{ $t('author-select.cant-find-text') }}
        </q-item-section>
        <q-item-section side>
          <q-btn
            icon="mdi-plus"
            color="primary"
            size="sm"
            round
            @click="showCreateAuthorDialog = true"
          >
            <q-tooltip class="text-body2">
              {{
                $t('author-select.add-a-new-author')
              }}
            </q-tooltip>
          </q-btn>
        </q-item-section>
      </q-item>
    </template>
    <template #option="scope">
      <q-item v-bind="scope.itemProps">
        <q-item-section>
          <q-item-label>
            {{ scope.opt.data.first_name }}
            {{ scope.opt.data.last_name }}
          </q-item-label>
          <q-item-label caption>
            {{ scope.opt.data.email }}
          </q-item-label>
        </q-item-section>
        <q-item-section side>
          {{
            scope.opt.data.organization.data[`name_${$i18n.locale}`]
          }}
        </q-item-section>
      </q-item>
    </template>
    <template v-if="!hideCreateAuthorDialog" #after-options>
      <q-separator />
      <q-item clickable @click="showCreateAuthorDialog = true">
        <q-item-section>
          {{ $t('author-select.not-the-author-youre-looking-for') }}
        </q-item-section>
        <q-item-section side>
          <q-btn
            icon="mdi-plus"
            color="primary"
            size="sm"
            round
            @click="showCreateAuthorDialog = true"
          >
            <q-tooltip class="text-body2">
              {{
                $t('author-select.add-a-new-author')
              }}
            </q-tooltip>
          </q-btn>
        </q-item-section>
      </q-item>
    </template>
    <CreateAuthorDialog
      v-if="showCreateAuthorDialog"
      v-model="showCreateAuthorDialog"
      @created="createdAuthor"
    />
  </QSelect>
</template>

<style scoped></style>
