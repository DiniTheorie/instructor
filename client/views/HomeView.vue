<script setup lang="ts">
import { ref } from 'vue'
import { api } from '@/services/api'
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'
import { routes } from '@/router'
import CategoryLinkList from '@/components/domain/exam/category/CategoryLinkList.vue'
import BackButton from '@/components/layout/BackButton.vue'

const { t } = useI18n()

const router = useRouter()
const toCategory = (id: string) => {
  router.push({ name: routes.category, params: { id } })
}

const categoryIds = ref<string[]>()
api.exam.category.getIds().then((result) => (categoryIds.value = result))
</script>

<template>
  <BackButton empty />
  <h2>{{ t('page.home.exam_categories') }}</h2>
  <CategoryLinkList v-if="categoryIds" :category-ids="categoryIds" @click="toCategory" />
</template>
