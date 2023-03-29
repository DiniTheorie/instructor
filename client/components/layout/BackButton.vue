<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'

defineProps<{ empty?: boolean }>()

const router = useRouter()

const { t } = useI18n()
</script>

<template>
  <p class="mb-4 mt-4">
    <a v-if="!empty" href="#" @click.prevent="router.back()">
      <FontAwesomeIcon size="2xs" :icon="['fal', 'chevron-left']" />
      {{ t('layout.back_button.go_back') }}
    </a>
    <span v-else>&nbsp;</span>
  </p>
</template>
