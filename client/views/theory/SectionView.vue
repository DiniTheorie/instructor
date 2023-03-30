<script setup lang="ts">
import { computed, ref, watchEffect } from 'vue'
import { api } from '@/services/api'
import { useRoute, useRouter } from 'vue-router'
import type { Section } from '@/components/domain/theory/Section'
import SectionPreview from '@/components/view/theory/SectionPreview.vue'
import { routes } from '@/router'
import BackButton from '@/components/layout/HierarchicNav.vue'
import { useI18n } from 'vue-i18n'
import SectionRemove from '@/components/action/theory/SectionRemove.vue'
import IdList from '@/components/view/IdList.vue'
import { supportedLanguages } from '@/components/domain/SupportedLanguage'
import SectionTranslationEdit from '@/components/action/theory/SectionTranslationEdit.vue'
import SectionConfigEdit from '@/components/action/theory/SectionConfigEdit.vue'
import ArticleCreate from '@/components/action/theory/ArticleCreate.vue'

const route = useRoute()
const router = useRouter()
const chapterId = computed(() => route.params.chapterId as string)
const sectionId = computed(() => route.params.id as string)

const section = ref<Section>()
const articleIds = ref<string[]>()
watchEffect(() => {
  api.theory.chapter.section.get(chapterId.value, sectionId.value).then((result) => (section.value = result))
  api.theory.chapter.section.article.getIds(chapterId.value, sectionId.value).then((result) => (articleIds.value = result))
})

const toArticle = (id: string) => {
  router.push({ name: routes.chapterSectionArticle, params: { chapterId: chapterId.value, sectionId: sectionId.value, id } })
}

const sectionIds = ref<string[]>()
watchEffect(() => {
  api.theory.chapter.section.getIds(chapterId.value).then((result) => (sectionIds.value = result))
})

const changeSection = (id: string) => {
  router.replace({ name: routes.chapterSection, params: { chapterId: chapterId.value, id } })
}

const { t } = useI18n()
</script>

<template>
  <BackButton :can-go-back="true" :siblings="sectionIds" :current="sectionId" @change-sibling="changeSection" />
  <SectionPreview v-if="section" :section="section" />
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
  <ArticleCreate :chapter-id="chapterId" :section-id="sectionId" @created="toArticle($event.id)" />
  <IdList class="mt-1" v-if="articleIds" size="2" :ids="articleIds" @click="toArticle" />

  <div class="mt-5">
    <SectionRemove v-if="section" :chapter-id="chapterId" :section="section" @removed="router.back()" />
  </div>
</template>
