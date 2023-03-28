<script setup lang="ts">
import { ref } from 'vue'
import { api } from '@/services/api'
import { useI18n } from 'vue-i18n'
import { useRoute } from 'vue-router'
import type { ExamCategory } from '@/components/domain/exam/types'
import CategoryEdit from '@/components/domain/exam/CategoryEdit.vue'

const { t } = useI18n()

const category = ref<ExamCategory>()
const params = useRoute()
let categoryId = params.params.id as string
api.getExamCategory(categoryId).then((result) => (category.value = result))

const storeCategory = async (changedCategory: ExamCategory) => {
  category.value = await api.putExamCategory(changedCategory)
}
</script>

<template>
  <FontAwesomeIcon :icon="['fal', 'pencil']" />
  <RouterLink to="/">{{ t('page.home.title') }}</RouterLink>
  <h2>{{ categoryId }}</h2>
  <CategoryEdit v-if="category" :category="category" :store="storeCategory" />
</template>
