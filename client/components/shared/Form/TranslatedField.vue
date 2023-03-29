<script setup lang="ts">
import type { SupportedLanguage } from '@/components/domain/SupportedLanguage'
import { useI18n } from 'vue-i18n'

defineProps<{
  field: string
  language: SupportedLanguage
  modelValue: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

const handleInput = (event: Event) => {
  emit('update:modelValue', (event.target as HTMLInputElement).value)
}

const { t } = useI18n()
const uniqueId = String(Math.random())
</script>

<template>
  <div>
    <label class="form-label" :for="uniqueId">{{ t(field) }}</label>
    <div class="input-group mb-1">
      <input type="text" class="form-control" :id="uniqueId" :value="modelValue" @input="handleInput" />
      <span class="input-group-text">{{ t('domain.supported_language.' + language) }}</span>
    </div>
  </div>
</template>
