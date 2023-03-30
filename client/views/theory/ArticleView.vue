<script setup lang="ts">
import { computed, ref, watchEffect } from 'vue'
import { api } from '@/services/api'
import { useRoute, useRouter } from 'vue-router'
import BackButton from '@/components/layout/HierarchicNav.vue'
import type { ArticleWithUrls } from '@/components/domain/theory/Article'
import ArticlePreview from '@/components/view/theory/ArticlePreview.vue'
import ArticleRemove from '@/components/action/theory/ArticleRemove.vue'
import ArticleImageAdd from '@/components/action/theory/ArticleImageAdd.vue'
import { routes } from '@/router'
import ArticleTranslationEdit from '@/components/action/theory/ArticleTranslationEdit.vue'
import { supportedLanguages } from '@/components/domain/SupportedLanguage'

const article = ref<ArticleWithUrls>()
const route = useRoute()
const router = useRouter()
const chapterId = computed(() => route.params.chapterId as string)
const sectionId = computed(() => route.params.sectionId as string)
const articleId = computed(() => route.params.id as string)
watchEffect(() => {
  api.theory.chapter.section.article.get(chapterId.value, sectionId.value, articleId.value).then((result) => (article.value = result))
})

const articleIds = ref<string[]>()
api.theory.chapter.section.article.getIds(chapterId.value, sectionId.value).then((result) => (articleIds.value = result))

const changeArticle = (id: string) => {
  router.replace({ name: routes.chapterSectionArticle, params: { chapterId: chapterId.value, sectionId: sectionId.value, id } })
}
</script>

<template>
  <BackButton :can-go-back="true" :current="articleId" :siblings="articleIds" @change-sibling="changeArticle" />
  <ArticlePreview v-if="article" :article="article" :chapter-id="chapterId" :section-id="sectionId" @updated="article = $event" />
  <p v-if="article" class="mt-5">
    <ArticleImageAdd class="me-3 d-inline-block" :article="article" :chapter-id="chapterId" :section-id="sectionId" @updated="article = $event" />
    <ArticleTranslationEdit
      class="d-inline-block me-1"
      v-for="supportedLanguage in supportedLanguages"
      :key="supportedLanguage"
      :language="supportedLanguage"
      :chapter-id="chapterId"
      :section-id="sectionId"
      :article="article"
      :template="article.translations.find((entry) => entry.language === supportedLanguage)"
      @updated="article = $event"
    />
  </p>
  <p v-if="article" class="mt-5">
    <ArticleRemove :chapter-id="chapterId" :section-id="sectionId" :article="article" @removed="router.back()" />
  </p>
</template>
