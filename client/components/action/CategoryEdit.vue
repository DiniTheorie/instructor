<script setup lang="ts">
import type { ExamCategory } from '@/components/domain/Category'
import { supportedLanguages } from '@/components/domain/SupportedLanguage'
import CategoryTranslationEdit from '@/components/action/CategoryTranslationEdit.vue'
import { api } from '@/services/api'
import { computed } from 'vue'

const props = defineProps<{ category: ExamCategory }>()

const emit = defineEmits<{
  (e: 'removed')
  (e: 'updated', category: ExamCategory)
}>()

const removeCategory = async () => {
  await api.exam.category.delete(props.category.id)
  emit('removed')
}

const primaryTranslation = computed(() => props.category.translations.find((entry) => entry.language === 'de'))
</script>

<template>
  <h2>
    <b>{{ primaryTranslation.name }}</b>
    <br />
    {{ primaryTranslation.description }}
  </h2>
  <p>
    <CategoryTranslationEdit
      v-for="supportedLanguage in supportedLanguages"
      :key="supportedLanguage"
      :language="supportedLanguage"
      :category="category"
      :template="category.translations.find((entry) => entry.language === supportedLanguage)"
      @updated="emit('updated', $event)"
    />
  </p>
</template>
