<script setup lang="ts">
import { ref } from 'vue'
import { api } from '@/services/api'
import { useI18n } from 'vue-i18n'
import { useRoute } from 'vue-router'
import type { ExamCategory } from '@/components/domain/exam/types'
import { useSettingsStore } from '@/stores/localeStore'

const { t } = useI18n()

const category = ref<ExamCategory>()
const params = useRoute()
let categoryId = params.params.id as string
api.getExamCategory(categoryId).then((result) => (category.value = result))

const settings = useSettingsStore()
</script>

<template>
  <FontAwesomeIcon :icon="['fal', 'pencil']" />
  <h2>{{ categoryId }}</h2>
  {{ settings.translationLanguage }}
  <RouterLink to="/">{{ t('page.home.title') }}</RouterLink>
</template>
