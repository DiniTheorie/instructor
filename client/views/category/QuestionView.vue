<script setup lang="ts">
import { ref } from 'vue'
import { api } from '@/services/api'
import { useRoute, useRouter } from 'vue-router'
import { routes } from '@/router'
import BackButton from '@/components/layout/BackButton.vue'
import type { Question } from '@/components/domain/Question'
import QuestionPreview from '@/components/view/QuestionPreview.vue'

const question = ref<Question>()
const params = useRoute()
const router = useRouter()
const categoryId = params.params.categoryId as string
const questionId = params.params.id as string
api.exam.category.question.get(categoryId, questionId).then((result) => (question.value = result))

const storeQuestion = async (changedQuestion: Question) => {
  question.value = await api.exam.category.question.put(categoryId, changedQuestion)
}
const removeQuestion = async () => {
  await api.exam.category.question.delete(categoryId, questionId)
  await router.push({ name: routes.category, params: { id: categoryId } })
}
</script>

<template>
  <BackButton />
  <h2>{{ questionId }}</h2>
  <QuestionPreview v-if="question" :question="question" />
</template>
