<script setup lang="ts">
import { ref } from 'vue'
import { api } from '@/services/api'
import { useRoute, useRouter } from 'vue-router'
import type { ExamCategory } from '@/components/domain/Category'
import CategoryPreview from '@/components/view/CategoryPreview.vue'
import { routes } from '@/router'
import BackButton from '@/components/layout/BackButton.vue'
import { useI18n } from 'vue-i18n'
import CategoryRemove from '@/components/action/CategoryRemove.vue'
import IdList from '@/components/view/IdList.vue'
import QuestionCreate from '@/components/action/QuestionCreate.vue'
import { supportedLanguages } from '@/components/domain/SupportedLanguage'
import CategoryTranslationEdit from '@/components/action/CategoryTranslationEdit.vue'

const params = useRoute()
const router = useRouter()
const categoryId = params.params.id as string

const category = ref<ExamCategory>()
api.exam.category.get(categoryId).then((result) => (category.value = result))

const toQuestion = (id: string) => {
  router.push({ name: routes.categoryQuestion, params: { categoryId: categoryId, id } })
}

const questionIds = ref<string[]>()
api.exam.category.question.getIds(categoryId).then((result) => (questionIds.value = result))

const { t } = useI18n()
</script>

<template>
  <BackButton />
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
