<script setup lang="ts">
import { useI18n } from 'vue-i18n'

defineProps<{
  field: string
  modelValue: string
  options: string[]
  transOption: (option: string) => string
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
    <select class="form-select" :id="uniqueId" aria-label="Default select example" :value="modelValue" @input="handleInput">
      <option v-for="option in options" :value="option" :key="option">{{ transOption(option) }}</option>
    </select>
    <div class="form-text" v-if="help">{{ t(help) }}</div>
  </div>
</template>
