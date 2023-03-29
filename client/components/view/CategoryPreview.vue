<script setup lang="ts">
import type { Category } from '@/components/domain/Category'
import { computed } from 'vue'

const props = defineProps<{ category: Category }>()

const primaryTranslation = computed(() => props.category.translations.find((entry) => entry.language === 'de'))
</script>

<template>
  <h2 v-if="primaryTranslation" class="mw-35em">
    <b>{{ primaryTranslation.name }}</b>
    <br />
    {{ primaryTranslation.description }}
  </h2>
</template>
