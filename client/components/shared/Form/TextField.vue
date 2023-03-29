<script setup lang="ts">
import { useI18n } from 'vue-i18n'

defineProps<{
  field: string
  modelValue: string
  help?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

const handleInput = (event: Event) => {
  emit('update:modelValue', (event.target as HTMLInputElement).value)
}

const uniqueId = String(Math.random())
const { t } = useI18n()
</script>

<template>
  <div>
    <label class="form-label" :for="uniqueId">{{ t(field) }}</label>
    <input class="form-control" type="text" :id="uniqueId" :value="modelValue" @input="handleInput" />
    <div class="form-text" v-if="help">{{ t(help) }}</div>
  </div>
</template>
