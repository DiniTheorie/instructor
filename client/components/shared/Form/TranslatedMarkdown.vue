<script setup lang="ts">
import type { SupportedLanguage } from '@/components/domain/SupportedLanguage'
import { useI18n } from 'vue-i18n'
import { ref } from 'vue'
import { marked } from 'marked'
import DOMPurify from 'dompurify'

const props = defineProps<{
  field: string
  language: SupportedLanguage
  modelValue: string
  help?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

const handleInput = (event: Event) => {
  let markdown = (event.target as HTMLInputElement).value
  emit('update:modelValue', markdown)

  refreshSanitizedMarkdown(markdown)
}

const { t } = useI18n()
const uniqueId = String(Math.random())

const sanitizedMarkdown = ref('')
const refreshSanitizedMarkdown = (markdown: string) => {
  let parsedMarkdown = marked.parse(markdown)
  sanitizedMarkdown.value = DOMPurify.sanitize(parsedMarkdown)
}

refreshSanitizedMarkdown(props.modelValue)
</script>

<template>
  <div class="row">
    <div class="col-md-6">
      <label class="form-label" :for="uniqueId">{{ t(field) }} ({{ t('domain.supported_language.' + language) }})</label>
      <textarea rows="14" class="form-control" :id="uniqueId" :value="modelValue" @input="handleInput" />
      <div class="form-text" v-if="help">{{ t(help) }}</div>
    </div>
    <div class="col-md-6">
      <label class="form-label" :for="uniqueId">Vorschau</label>
      <div v-html="sanitizedMarkdown" class="bg-light p-2 border border-1 border-secondary border-opacity-25" />
    </div>
  </div>
</template>
