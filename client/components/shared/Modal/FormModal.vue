<script setup lang="ts">
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import RawModal from '@/components/shared/Modal/RawModal.vue'

const { t } = useI18n()

const loading = ref(false)
const showModal = ref(false)

const props = defineProps<{
  title: string
  canSubmit: boolean
  submit: () => Promise<void>
  size?: string
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
</script>

<template>
  <div>
    <button class="btn btn-outline-secondary" @click="showModal = true">
      {{ title }}
    </button>
    <form @submit.prevent="handleSubmit" class="position-absolute">
      <RawModal v-if="showModal" @hide="showModal = false" :title="title" :size="size">
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
  </div>
</template>
