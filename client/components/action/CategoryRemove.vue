<script setup lang="ts">
import type { ExamCategory } from '@/components/domain/Category'
import { api } from '@/services/api'
import { useI18n } from 'vue-i18n'
import RemoveButton from '@/components/shared/RemoveButton.vue'

const props = defineProps<{ category: ExamCategory }>()

const emit = defineEmits<{
  (e: 'removed')
}>()

const { t } = useI18n()

const remove = async () => {
  await api.exam.category.delete(props.category.id)
  emit('removed')
}
</script>

<template>
  <RemoveButton :title="t('components.action.category_remove.remove_all')" :remove="remove" />
</template>
