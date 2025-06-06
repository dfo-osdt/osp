<script setup lang="ts">
import type { ManuscriptRecordResource } from '../ManuscriptRecord'
import LoadingSkeleton from '@/components/LoadingSkeleton.vue'
import ManuscriptRecordProgressPreprint from '../components/ManuscriptRecordProgressPreprint.vue'
import ManuscriptRecordProgressPrimary from '../components/ManuscriptRecordProgressPrimary.vue'
import ManuscriptRecordProgressSecondary from '../components/ManuscriptRecordProgressSecondary.vue'
import { ManuscriptRecordService } from '../ManuscriptRecord'

const props = defineProps<{ id: number }>()

const emit = defineEmits<{
  (event: 'updateManuscript', arg: ManuscriptRecordResource): void
}>()
const loading = ref(true)
const manuscriptRecord = ref<ManuscriptRecordResource | undefined>(undefined)

watch(manuscriptRecord, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    emit('updateManuscript', newValue as ManuscriptRecordResource)
  }
}, { deep: true })

onMounted(() => {
  getManuscriptRecord()
})

async function getManuscriptRecord() {
  await ManuscriptRecordService.find(props.id)
    .then((response) => {
      manuscriptRecord.value = response
      loading.value = false
      emit('updateManuscript', response)
    })
    .catch((error) => {
      console.error(error)
    })
}

const typeComponent = computed(() => {
  if (loading.value || !manuscriptRecord.value) {
    return null
  }
  switch (manuscriptRecord.value.data.type) {
    case 'primary': return ManuscriptRecordProgressPrimary
    case 'secondary': return ManuscriptRecordProgressSecondary
    case 'preprint': return ManuscriptRecordProgressPreprint
    default: return null
  }
})
</script>

<template>
  <LoadingSkeleton
    v-if="loading"
  />
  <component
    :is="typeComponent"
    v-else-if="typeComponent && manuscriptRecord"
    v-model="manuscriptRecord"
  />
</template>
