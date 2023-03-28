<script setup lang="ts">
import type { SupportedLanguage } from '@/components/domain/SupportedLanguage'
import { useI18n } from 'vue-i18n'

defineProps<{
  field: string
  language: SupportedLanguage
  modelValue: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string)
}>()

const { t } = useI18n()
</script>

<template>
  <div>
    <label class="form-label">{{ t(field) }}</label>
    <div class="input-group mb-1">
      <input type="text" class="form-control" :value="modelValue" @input="emit('update:modelValue', $event.target.value)" />
      <span class="input-group-text">{{ t('domain.supported_language.' + language) }}</span>
    </div>
  </div>
</template>
