<script setup lang="ts">
import { api } from '@/services/api'
import { computed, ref } from 'vue'
import type { SupportedLanguage } from '@/components/domain/SupportedLanguage'
import { supportedLanguages } from '@/components/domain/SupportedLanguage'
import { truthy } from '@/utils/array'
import TranslationFormModal from '@/components/shared/Modal/TranslationFormModal.vue'
import type { Article, ArticleTranslation, ArticleWithUrls } from '@/components/domain/theory/Article'
import TranslatedMarkdown from '@/components/shared/Form/TranslatedMarkdown.vue'

const emit = defineEmits<{
  (e: 'updated', article: ArticleWithUrls): void
}>()

const props = defineProps<{ language: SupportedLanguage; template?: ArticleTranslation; article: ArticleWithUrls; chapterId: string; sectionId: string }>()

const model = ref({
  markdown: props.template?.markdown ?? ''
})

const store = async () => {
  const translations: ArticleTranslation[] = supportedLanguages
    .map((supportedLanguage) => {
      if (supportedLanguage === props.language) {
        return { ...model.value, language: props.language }
      }
      return props.article.translations.find((entry) => entry.language === supportedLanguage)
    })
    .filter(truthy)

  const payload: Article = { id: props.article.id, translations }
  const article = await api.theory.chapter.section.article.put(props.chapterId, props.sectionId, payload)
  emit('updated', article)
}

const remove = async () => {
  const translations = props.article.translations.filter((entry) => entry.language !== props.language)
  const payload: Article = { id: props.article.id, translations }
  const article = await api.theory.chapter.section.article.put(props.chapterId, props.sectionId, payload)
  emit('updated', article)
}

const canStore = computed(() => {
  return !!model.value.markdown
})
</script>

<template>
  <TranslationFormModal :can-submit="canStore" :submit="store" :remove="remove" :language="language" :exists="!!template" size="lg">
    <TranslatedMarkdown field="domain.theory.article.markdown" help="domain.theory.article.markdown_help" :language="language" v-model="model.markdown" />
  </TranslationFormModal>
</template>
