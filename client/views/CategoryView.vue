<script setup lang="ts">
import { ref } from 'vue'
import { api } from '@/services/api'
import { useI18n } from 'vue-i18n'
import { useRoute, useRouter } from 'vue-router'
import type { ExamCategory } from '@/components/domain/exam/types'
import CategoryEdit from '@/components/domain/exam/CategoryEdit.vue'
import { routes } from '@/router'
import BackButton from '@/components/layout/BackButton.vue'

const { t } = useI18n()

const category = ref<ExamCategory>()
const params = useRoute()
const router = useRouter()
let categoryId = params.params.id as string
api.exam.category.get(categoryId).then((result) => (category.value = result))

const storeCategory = async (changedCategory: ExamCategory) => {
  category.value = await api.exam.category.put(changedCategory)
}
const removeCategory = async () => {
  await api.exam.category.delete(categoryId)
  await router.push({ name: routes.home })
}
</script>

<template>
  <BackButton />
  <h2>{{ categoryId }}</h2>
  <CategoryEdit v-if="category" :category="category" :store="storeCategory" :remove="removeCategory" />
</template>
