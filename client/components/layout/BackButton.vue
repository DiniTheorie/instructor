<script setup lang="ts">
import { useI18n } from 'vue-i18n'

defineProps<{ empty?: boolean }>()

const { t } = useI18n()
</script>

<template>
  <p class="mb-0 mt-4">
    <RouterLink v-if="!empty" to="/">
      <FontAwesomeIcon size="2xs" :icon="['fal', 'chevron-left']" />
      {{ t('layout.back_button.go_back') }}
    </RouterLink>
    <span v-else>&nbsp;</span>
  </p>
</template>
