<script setup lang="ts">
import { ref } from 'vue'
import { api } from '@/services/api'
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'
import { routes } from '@/router'
import BackButton from '@/components/layout/HierarchicNav.vue'
import CategoryCreate from '@/components/action/exam/CategoryCreate.vue'
import IdList from '@/components/view/IdList.vue'
import ChapterCreate from '@/components/action/theory/ChapterCreate.vue'

const { t } = useI18n()

const router = useRouter()
const toCategory = (id: string) => {
  router.push({ name: routes.category, params: { id } })
}
const toChapter = (id: string) => {
  router.push({ name: routes.chapter, params: { id } })
}

const categoryIds = ref<string[]>()
api.exam.category.getIds().then((result) => (categoryIds.value = result))

const chapterIds = ref<string[]>()
api.theory.chapter.getIds().then((result) => (chapterIds.value = result))
</script>

<template>
  <BackButton />
  <h3 class="mb-2">{{ t('page.home.exam_categories') }}</h3>
  <CategoryCreate @created="toCategory($event.id)" />
  <IdList class="mt-1" v-if="categoryIds" size="4" :ids="categoryIds" @click="toCategory" />

  <h3 class="mt-5 mb-2">{{ t('page.home.theory_chapters') }}</h3>
  <ChapterCreate @created="toChapter($event.id)" />
  <IdList class="mt-1" v-if="chapterIds" size="4" :ids="chapterIds" @click="toChapter" />
</template>
