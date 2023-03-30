<script setup lang="ts">
import type { Category } from '@/components/domain/exam/Category'
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useSettingsStore } from '@/stores/settingsStore'
import TranslationMissing from '@/components/view/TranslationMissing.vue'

const props = defineProps<{ category: Category }>()

const { previewLanguage } = storeToRefs(useSettingsStore())
const primaryTranslation = computed(() => props.category.translations.find((entry) => entry.language === previewLanguage.value))
</script>

<template>
  <div class="mw-35em">
    <h2 v-if="primaryTranslation">
      <b>{{ primaryTranslation.name }}</b>
      <br />
      {{ primaryTranslation.description }}
    </h2>
    <TranslationMissing v-else />
  </div>
</template>
