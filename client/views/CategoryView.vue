<script setup lang="ts">
import { ref } from 'vue'
import { api } from '@/services/api'
import { useRoute, useRouter } from 'vue-router'
import type { ExamCategory } from '@/components/domain/Category'
import CategoryEdit from '@/components/action/CategoryEdit.vue'
import { routes } from '@/router'
import BackButton from '@/components/layout/BackButton.vue'
import { useI18n } from 'vue-i18n'
import CategoryRemove from '@/components/action/CategoryRemove.vue'
import IdList from '@/components/view/IdList.vue'
import QuestionCreate from '@/components/action/QuestionCreate.vue'

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
  <CategoryEdit v-if="category" :category="category" @updated="category = $event" />

  <h3 class="mt-5">{{ t('domain.exam.category.questions') }}</h3>
  <QuestionCreate :category-id="categoryId" @created="toQuestion($event.id)" />
  <IdList class="mt-1" v-if="questionIds" size="2" :ids="questionIds" @click="toQuestion" />

  <div class="mt-5 mb-5">
    <CategoryRemove v-if="category" :category="category" @removed="router.push({ name: routes.home })" />
  </div>
</template>
