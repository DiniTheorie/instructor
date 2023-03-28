<script setup lang="ts">
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import RawModal from '@/components/shared/Modal/RawModal.vue'

const emit = defineEmits<{
  (e: 'hide'): void
}>()

const { t } = useI18n()

const loading = ref(false)
const showModal = ref(false)

const props = defineProps<{
  title: string
  canSubmit: boolean
  submit: () => Promise<void>
}>()

const handleSubmit = async () => {
  if (props.canSubmit) {
    loading.value = true
    try {
      await props.submit()
      emit('hide')
    } finally {
      loading.value = false
    }
  }
}
</script>

<template>
  <button class="btn btn-outline-secondary" @click="showModal = true">
    {{ title }}
  </button>
  <form @submit.prevent="handleSubmit">
    <RawModal v-if="showModal" @hide="showModal = false" :title="title">
      <template v-slot:body>
        <slot></slot>
      </template>
      <template v-slot:footer>
        <button type="submit" class="btn btn-outline-primary" :disabled="loading || !canSubmit">
          {{ t('components.shared.store') }}
        </button>
      </template>
    </RawModal>
  </form>
</template>
