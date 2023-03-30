<script setup lang="ts">
import { computed } from 'vue'
import type { Section } from '@/components/domain/theory/Section'
import TranslationMissing from '@/components/view/TranslationMissing.vue'
import { storeToRefs } from 'pinia'
import { useSettingsStore } from '@/stores/settingsStore'

const props = defineProps<{ section: Section }>()

const { previewLanguage } = storeToRefs(useSettingsStore())
const primaryTranslation = computed(() => props.section.translations.find((entry) => entry.language === previewLanguage.value))
</script>

<template>
  <div class="mw-35em">
    <h3 v-if="primaryTranslation">
      <b>{{ primaryTranslation.title }}</b>
      <br />
      {{ primaryTranslation.description }}
    </h3>
    <TranslationMissing v-else />
  </div>
</template>
