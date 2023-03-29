<script setup lang="ts">
import { ref } from 'vue'
import { api } from '@/services/api'
import { useRoute, useRouter } from 'vue-router'
import BackButton from '@/components/layout/BackButton.vue'
import type { QuestionWithUrls } from '@/components/domain/Question'
import QuestionPreview from '@/components/view/QuestionPreview.vue'
import QuestionTranslationEdit from '@/components/action/QuestionTranslationEdit.vue'
import { supportedLanguages } from '@/components/domain/SupportedLanguage'
import QuestionMetaEdit from '@/components/action/QuestionMetaEdit.vue'
import QuestionRemove from '@/components/action/QuestionRemove.vue'
import QuestionImageAdd from '@/components/action/QuestionImageAdd.vue'

const question = ref<QuestionWithUrls>()
const params = useRoute()
const router = useRouter()
const categoryId = params.params.categoryId as string
const questionId = params.params.id as string
api.exam.category.question.get(categoryId, questionId).then((result) => (question.value = result))
</script>

<template>
  <BackButton />
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
