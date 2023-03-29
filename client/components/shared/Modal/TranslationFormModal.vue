<script setup lang="ts">
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import RawModal from '@/components/shared/Modal/RawModal.vue'
import type { SupportedLanguage } from '@/components/domain/SupportedLanguage'

const { t } = useI18n()

const loading = ref(false)
const showModal = ref(false)

const props = defineProps<{
  language: SupportedLanguage
  exists: boolean
  canSubmit: boolean
  submit: () => Promise<void>
  remove: () => Promise<void>
}>()

const handleSubmit = async () => {
  if (props.canSubmit) {
    loading.value = true
    try {
      await props.submit()
      showModal.value = false
    } finally {
      loading.value = false
    }
  }
}

const handleRemove = async () => {
  loading.value = true
  try {
    await props.remove()
    showModal.value = false
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div>
    <button class="btn" :class="{ 'btn-success': exists, 'btn-secondary': !exists }" @click="showModal = true">
      {{ t('domain.supported_language.' + props.language) }}
    </button>
    <form @submit.prevent="handleSubmit" class="position-absolute">
      <RawModal v-if="showModal" @hide="showModal = false" :title="t('domain.supported_language.' + props.language)">
        <template v-slot:body>
          <slot></slot>
        </template>
        <template v-slot:footer>
          <button v-if="exists" class="btn btn-outline-danger" @click.prevent="handleRemove" :disabled="loading">
            {{ t('components.shared.remove') }}
          </button>
          <button type="submit" class="ms-auto btn btn-outline-primary" :disabled="loading || !canSubmit">
            {{ t('components.shared.store') }}
          </button>
        </template>
      </RawModal>
    </form>
  </div>
</template>
