<script setup lang="ts">
import { computed } from 'vue'
import type { Chapter } from '@/components/domain/theory/Chapter'
import TranslationMissing from '@/components/view/TranslationMissing.vue'
import { storeToRefs } from 'pinia'
import { useSettingsStore } from '@/stores/settingsStore'

const props = defineProps<{ chapter: Chapter }>()

const { previewLanguage } = storeToRefs(useSettingsStore())
const primaryTranslation = computed(() => props.chapter.translations.find((entry) => entry.language === previewLanguage.value))
</script>

<template>
  <div class="mw-35em">
    <h2 v-if="primaryTranslation">
      <b>{{ primaryTranslation.title }}</b>
      <br />
      {{ primaryTranslation.description }}
    </h2>
    <TranslationMissing v-else />
  </div>
</template>
