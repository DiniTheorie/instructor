<script setup lang="ts">
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const loading = ref(false)

const props = defineProps<{
  title: string
  remove: () => Promise<void>
}>()

const handleRemove = async () => {
  loading.value = true
  if (window.confirm(t('components.shared.remove_confirm'))) {
    await props.remove()
  }
  loading.value = false
}
</script>

<template>
  <button class="btn btn-outline-danger" :disabled="loading" @click.prevent="handleRemove">
    {{ title }}
  </button>
</template>
