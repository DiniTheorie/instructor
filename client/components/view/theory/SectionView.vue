<script setup lang="ts">
import { ref } from 'vue'
import { api } from '@/services/api'
import type { Section } from '@/components/domain/theory/Section'
import SectionPreview from '@/components/view/theory/SectionPreview.vue'
import { useI18n } from 'vue-i18n'
import SectionRemove from '@/components/action/theory/SectionRemove.vue'
import IdList from '@/components/view/IdList.vue'
import { supportedLanguages } from '@/components/domain/SupportedLanguage'
import SectionTranslationEdit from '@/components/action/theory/SectionTranslationEdit.vue'
import SectionConfigEdit from '@/components/action/theory/SectionConfigEdit.vue'
import ArticleCreate from '@/components/action/theory/ArticleCreate.vue'

const props = defineProps<{ chapterId: string; sectionId: string }>()
const emit = defineEmits<{
  (e: 'goToArticle', id: string): void
  (e: 'removed'): void
}>()

const section = ref<Section>()
const articleIds = ref<string[]>()
api.theory.chapter.section.get(props.chapterId, props.sectionId).then((result) => (section.value = result))
api.theory.chapter.section.article.getIds(props.chapterId, props.sectionId).then((result) => (articleIds.value = result))

const { t } = useI18n()
</script>

<template>
  <SectionPreview v-if="section" :section="section" class="mb-3" />
  <p v-if="section">
    <SectionConfigEdit class="me-3 d-inline-block" :section="section" :chapter-id="chapterId" @updated="section = $event" />
    <SectionTranslationEdit
      class="me-1 d-inline-block"
      v-for="supportedLanguage in supportedLanguages"
      :key="supportedLanguage"
      :language="supportedLanguage"
      :chapter-id="chapterId"
      :section="section"
      :template="section.translations.find((entry) => entry.language === supportedLanguage)"
      @updated="section = $event"
    />
  </p>

  <h3 class="mt-5">{{ t('domain.theory.section.articles') }}</h3>
  <ArticleCreate :chapter-id="chapterId" :section-id="sectionId" @created="emit('goToArticle', $event.id)" />
  <IdList class="mt-1" v-if="articleIds" size="2" :ids="articleIds" @click="emit('goToArticle', $event)" />

  <div class="mt-5">
    <SectionRemove v-if="section" :chapter-id="chapterId" :section="section" @removed="emit('removed')" />
  </div>
</template>
