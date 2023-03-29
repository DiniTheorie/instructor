<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { useI18n } from 'vue-i18n'
import { ref } from 'vue'

const props = defineProps<{
  url: string
  filename: string
  remove: () => Promise<void>
}>()

const { t } = useI18n()

const loading = ref(false)

const handleRemove = async () => {
  loading.value = true
  if (window.confirm(t('components.shared.remove_confirm'))) {
    await props.remove()
  }
  loading.value = false
}
</script>

<template>
  <div class="position-relative">
    <img class="img-fluid" :src="url" />
    <span class="position-absolute end-0 bottom-0 p-1 bg-light opacity-75">{{ filename }}</span>
    <span class="position-absolute end-0 top-0 bg-light rounded m-1 opacity-75">
      <button class="btn btn-outline-danger" role="button" @click="handleRemove" :disabled="loading">
        <FontAwesomeIcon :icon="['fal', 'trash']" />
      </button>
    </span>
  </div>
</template>

<style scoped></style>
