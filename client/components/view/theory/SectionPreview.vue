<script setup lang="ts">
import { computed } from 'vue'
import type { Section } from '@/components/domain/theory/Section'

const props = defineProps<{ section: Section }>()

const primaryTranslation = computed(() => props.section.translations.find((entry) => entry.language === 'de'))
</script>

<template>
  <h2 v-if="primaryTranslation" class="mw-35em">
    <b>{{ primaryTranslation.title }}</b>
    <br />
    {{ primaryTranslation.description }}
  </h2>
</template>
