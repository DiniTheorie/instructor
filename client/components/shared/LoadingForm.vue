<script setup lang="ts">
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const loading = ref(false)

const props = defineProps<{
  submit: () => Promise<void>
  remove: () => Promise<void>
}>()

const handleSubmit = async () => {
  loading.value = true
  await props.submit()
  loading.value = false
}

const handleRemove = async () => {
  loading.value = true
  if (window.confirm(t('components.shared.remove_confirm'))) {
    await props.remove()
  }
  loading.value = false
}
</script>

<template>
  <form @submit.prevent="handleSubmit">
    <slot></slot>
    <button type="submit" class="btn btn-outline-primary" :disabled="loading">
      {{ t('components.shared.store') }}
    </button>
    <button class="btn btn-outline-danger ms-2" :disabled="loading" @click.prevent="handleRemove">
      {{ t('components.shared.remove') }}
    </button>
  </form>
</template>
