<script setup lang="ts">
import { api } from '@/services/api'
import { useI18n } from 'vue-i18n'
import RemoveButton from '@/components/shared/RemoveButton.vue'
import type { Question } from '@/components/domain/Question'

const props = defineProps<{ categoryId: string; question: Question }>()

const emit = defineEmits<{
  (e: 'removed'): void
}>()

const { t } = useI18n()

const remove = async () => {
  await api.exam.category.question.delete(props.categoryId, props.question.id)
  emit('removed')
}
</script>

<template>
  <RemoveButton :title="t('components.action.question_remove.remove_all')" :remove="remove" />
</template>
