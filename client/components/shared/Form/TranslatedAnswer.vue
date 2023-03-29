<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import SwitchField from '@/components/shared/Form/SwitchField.vue'

defineProps<{
  field: string
  correct: boolean
  language: string
  answer: string
}>()

const emit = defineEmits<{
  (e: 'update:answer', value: string): void
  (e: 'update:correct', value: boolean): void
}>()

const handleAnswerInput = (event: Event) => {
  emit('update:answer', (event.target as HTMLInputElement).value)
}

const { t } = useI18n()
const uniqueId = String(Math.random())
</script>

<template>
  <div>
    <label class="form-label ms-5" :for="uniqueId">{{ t(field) }}</label>
    <div class="input-group mb-1">
      <SwitchField class="me-2" :model-value="correct" @update:model-value="emit('update:correct', $event)" />
      <textarea rows="2" class="form-control" :id="uniqueId" :value="answer" @input="handleAnswerInput" />
      <span class="input-group-text">{{ t('domain.supported_language.' + language) }}</span>
    </div>
  </div>
</template>
