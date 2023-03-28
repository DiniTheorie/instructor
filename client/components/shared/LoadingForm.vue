<script setup lang="ts">
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const loading = ref(false)

const props = defineProps<{
  submit: () => Promise<void>
}>()

const handleSubmit = async () => {
  loading.value = true
  await props.submit()
  loading.value = false
}

const { t } = useI18n()
</script>

<template>
  <form @submit.prevent="handleSubmit">
    <slot></slot>
    <button type="submit" class="btn btn-outline-primary" :disabled="loading">
      {{ t('components.shared.store') }}
    </button>
  </form>
</template>
