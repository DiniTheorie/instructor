<script setup lang="ts">
import { computed, ref, watchEffect } from 'vue'
import { api } from '@/services/api'
import { useRoute, useRouter } from 'vue-router'
import BackButton from '@/components/layout/HierarchicNav.vue'
import type { QuestionWithUrls } from '@/components/domain/Question'
import QuestionPreview from '@/components/view/QuestionPreview.vue'
import QuestionTranslationEdit from '@/components/action/QuestionTranslationEdit.vue'
import { supportedLanguages } from '@/components/domain/SupportedLanguage'
import QuestionMetaEdit from '@/components/action/QuestionMetaEdit.vue'
import QuestionRemove from '@/components/action/QuestionRemove.vue'
import QuestionImageAdd from '@/components/action/QuestionImageAdd.vue'
import { routes } from '@/router'

const question = ref<QuestionWithUrls>()
const route = useRoute()
const router = useRouter()
const categoryId = computed(() => route.params.categoryId as string)
const questionId = computed(() => route.params.id as string)
watchEffect(() => {
  api.exam.category.question.get(categoryId.value, questionId.value).then((result) => (question.value = result))
})

const questionIds = ref<string[]>()
api.exam.category.question.getIds(categoryId.value).then((result) => (questionIds.value = result))

const changeQuestion = (id: number) => {
  router.replace({ name: routes.categoryQuestion, params: { categoryId: categoryId.value, id } })
}
</script>

<template>
  <BackButton :can-go-back="true" :current="questionId" :siblings="questionIds" @change-sibling="changeQuestion" />
  <h2>{{ questionId }}</h2>
  <QuestionPreview v-if="question" :question="question" :category-id="categoryId" @updated="question = $event" />
  <p v-if="question" class="mt-5">
    <QuestionMetaEdit class="me-1 d-inline-block" :question="question" :category-id="categoryId" @updated="question = $event" />
    <QuestionImageAdd class="me-1 d-inline-block" :question="question" :category-id="categoryId" @updated="question = $event" />
    <QuestionTranslationEdit
      class="d-inline-block me-1"
      v-for="supportedLanguage in supportedLanguages"
      :key="supportedLanguage"
      :language="supportedLanguage"
      :category-id="categoryId"
      :question="question"
      :template="question.translations.find((entry) => entry.language === supportedLanguage)"
      @updated="question = $event"
    />
  </p>
  <p v-if="question" class="mt-5">
    <QuestionRemove :category-id="categoryId" :question="question" @removed="router.back()" />
  </p>
</template>
