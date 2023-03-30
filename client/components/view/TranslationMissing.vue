<script setup lang="ts">
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
</script>

<template>
  <div class="alert alert-info">
    {{ t('components.shared.preview_impossible_no_translation') }}
  </div>
</template>
