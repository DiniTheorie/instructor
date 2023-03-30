<script setup lang="ts">
import { computed, ref, watchEffect } from 'vue'
import { api } from '@/services/api'
import { useRoute, useRouter } from 'vue-router'
import type { Chapter } from '@/components/domain/theory/Chapter'
import ChapterPreview from '@/components/view/theory/ChapterPreview.vue'
import { routes } from '@/router'
import BackButton from '@/components/layout/HierarchicNav.vue'
import ChapterRemove from '@/components/action/theory/ChapterRemove.vue'
import { supportedLanguages } from '@/components/domain/SupportedLanguage'
import ChapterTranslationEdit from '@/components/action/theory/ChapterTranslationEdit.vue'
import SectionCreate from '@/components/action/theory/SectionCreate.vue'
import SectionView from '@/components/view/theory/SectionView.vue'

const route = useRoute()
const router = useRouter()
const chapterId = computed(() => route.params.id as string)

const chapter = ref<Chapter>()
const sectionIds = ref<string[]>()
watchEffect(() => {
  api.theory.chapter.get(chapterId.value).then((result) => (chapter.value = result))
  api.theory.chapter.section.getIds(chapterId.value).then((result) => (sectionIds.value = result))
})

const toSection = (id: string) => {
  router.push({ name: routes.chapterSection, params: { chapterId: chapterId.value, id } })
}

const chapterIds = ref<string[]>()
api.theory.chapter.getIds().then((result) => (chapterIds.value = result))

const changeChapter = (id: string) => {
  router.replace({ name: routes.chapter, params: { id } })
}
</script>

<template>
  <BackButton :can-go-back="true" :siblings="chapterIds" :current="chapterId" @change-sibling="changeChapter" />
  <ChapterPreview v-if="chapter" :chapter="chapter" />
  <p v-if="chapter">
    <ChapterTranslationEdit
      class="me-1 d-inline-block"
      v-for="supportedLanguage in supportedLanguages"
      :key="supportedLanguage"
      :language="supportedLanguage"
      :chapter="chapter"
      :template="chapter.translations.find((entry) => entry.language === supportedLanguage)"
      @updated="chapter = $event"
    />
  </p>

  <div class="mt-5 mb-2">
    <SectionCreate :chapter-id="chapterId" @created="toSection($event.id)" />
  </div>
  <div class="bg-light p-5 mb-5" v-for="sectionId in sectionIds" :key="sectionId">
    <p class="mb-4 lead">{{ sectionId }}</p>
    <SectionView :chapter-id="chapterId" :section-id="sectionId" />
  </div>

  <div class="mt-5">
    <ChapterRemove v-if="chapter" :chapter="chapter" @removed="router.back()" />
  </div>
</template>
