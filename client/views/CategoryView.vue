<script setup lang="ts">
import { computed, ref, watchEffect } from 'vue'
import { api } from '@/services/api'
import { useRoute, useRouter } from 'vue-router'
import type { ExamCategory } from '@/components/domain/Category'
import CategoryPreview from '@/components/view/CategoryPreview.vue'
import { routes } from '@/router'
import BackButton from '@/components/layout/HierarchicNav.vue'
import { useI18n } from 'vue-i18n'
import CategoryRemove from '@/components/action/CategoryRemove.vue'
import IdList from '@/components/view/IdList.vue'
import QuestionCreate from '@/components/action/QuestionCreate.vue'
import { supportedLanguages } from '@/components/domain/SupportedLanguage'
import CategoryTranslationEdit from '@/components/action/CategoryTranslationEdit.vue'

const route = useRoute()
const router = useRouter()
const categoryId = computed(() => route.params.id as string)

const category = ref<ExamCategory>()
const questionIds = ref<string[]>()
watchEffect(() => {
  api.exam.category.get(categoryId.value).then((result) => (category.value = result))
  api.exam.category.question.getIds(categoryId.value).then((result) => (questionIds.value = result))
})

const toQuestion = (id: string) => {
  router.push({ name: routes.categoryQuestion, params: { categoryId: categoryId.value, id } })
}

const categoryIds = ref<string[]>()
api.exam.category.getIds().then((result) => (categoryIds.value = result))

const changeCategory = (id: string) => {
  router.replace({ name: routes.category, params: { id } })
}

const { t } = useI18n()
</script>

<template>
  <BackButton :can-go-back="true" :siblings="categoryIds" :current="categoryId" @change-sibling="changeCategory" />
  <CategoryPreview v-if="category" :category="category" />
  <p v-if="category">
    <CategoryTranslationEdit
      class="me-1 d-inline-block"
      v-for="supportedLanguage in supportedLanguages"
      :key="supportedLanguage"
      :language="supportedLanguage"
      :category="category"
      :template="category.translations.find((entry) => entry.language === supportedLanguage)"
      @updated="category = $event"
    />
  </p>

  <h3 class="mt-5">{{ t('domain.exam.category.questions') }}</h3>
  <QuestionCreate :category-id="categoryId" @created="toQuestion($event.id)" />
  <IdList class="mt-1" v-if="questionIds" size="2" :ids="questionIds" @click="toQuestion" />

  <div class="mt-5 mb-5">
    <CategoryRemove v-if="category" :category="category" @removed="router.back()" />
  </div>
</template>
