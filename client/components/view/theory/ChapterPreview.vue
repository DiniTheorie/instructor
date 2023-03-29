<script setup lang="ts">
import { computed } from 'vue'
import type { Chapter } from '@/components/domain/theory/Chapter'

const props = defineProps<{ chapter: Chapter }>()

const primaryTranslation = computed(() => props.chapter.translations.find((entry) => entry.language === 'de'))
</script>

<template>
  <h2 v-if="primaryTranslation" class="mw-35em">
    <b>{{ primaryTranslation.title }}</b>
    <br />
    {{ primaryTranslation.description }}
  </h2>
</template>
