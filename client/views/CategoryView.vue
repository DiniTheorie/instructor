<script setup lang="ts">
import { ref } from 'vue'
import { api } from '@/services/api'
import { useRoute, useRouter } from 'vue-router'
import type { ExamCategory } from '@/components/domain/Category'
import CategoryEdit from '@/components/action/CategoryEdit.vue'
import { routes } from '@/router'
import BackButton from '@/components/layout/BackButton.vue'
import QuestionLinkList from '@/components/view/QuestionIdList.vue'
import { useI18n } from 'vue-i18n'

const params = useRoute()
const router = useRouter()
const categoryId = params.params.id as string

const category = ref<ExamCategory>()
api.exam.category.get(categoryId).then((result) => (category.value = result))

const storeCategory = async (changedCategory: ExamCategory) => {
  category.value = await api.exam.category.put(changedCategory)
}
const removeCategory = async () => {
  await api.exam.category.delete(categoryId)
  await router.push({ name: routes.home })
}

const toQuestion = (id: string) => {
  router.push({ name: routes.categoryQuestion, params: { categoryId: categoryId, id } })
}

const questionIds = ref<string[]>()
api.exam.category.question.getIds(categoryId).then((result) => (questionIds.value = result))

const { t } = useI18n()
</script>

<template>
  <BackButton />
  <h2>{{ categoryId }}</h2>
  <CategoryEdit v-if="category" :category="category" :store="storeCategory" :remove="removeCategory" />

  <h3 class="mt-5">{{ t('domain.exam.category.questions') }}</h3>
  <QuestionLinkList :question-ids="questionIds" @click="toQuestion" />
</template>
