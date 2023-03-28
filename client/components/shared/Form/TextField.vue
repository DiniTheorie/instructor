<script setup lang="ts">
import { useI18n } from 'vue-i18n'

defineProps<{
  field: string
  modelValue: string
  help?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string)
}>()
const { t } = useI18n()
</script>

<template>
  <div>
    <label class="form-label">{{ t(field) }}</label>
    <input type="text" class="form-control" :value="modelValue" @input="emit('update:modelValue', $event.target.value)" />
    <div class="form-text">{{ t(help) }}</div>
  </div>
</template>
