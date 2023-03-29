<script setup lang="ts">
import { useI18n } from 'vue-i18n'

defineProps<{
  field: string
  modelValue: boolean
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
}>()
const { t } = useI18n()

const handleChange = (event: Event) => {
  emit('update:modelValue', (event.target as HTMLInputElement).checked)
}

const uniqueId = String(Math.random())
</script>

<template>
  <div class="form-check">
    <input class="form-check-input" type="checkbox" :id="uniqueId" :checked="modelValue" @change="handleChange" />
    <label class="form-check-label" :for="uniqueId">{{ t(field) }}</label>
  </div>
</template>
