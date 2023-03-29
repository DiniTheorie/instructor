<script setup lang="ts">
import { ref } from 'vue'
import { api } from '@/services/api'
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'
import { routes } from '@/router'
import BackButton from '@/components/layout/BackButton.vue'
import CategoryCreate from '@/components/action/CategoryCreate.vue'
import IdList from '@/components/view/IdList.vue'

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
  <h3>{{ t('page.home.exam_categories') }}</h3>
  <CategoryCreate @created="toCategory($event.id)" />
  <IdList class="mt-1" v-if="categoryIds" size="4" :ids="categoryIds" @click="toCategory" />
</template>
