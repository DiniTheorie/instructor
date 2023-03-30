<script setup lang="ts">
import { api } from '@/services/api'
import { useI18n } from 'vue-i18n'
import RemoveButton from '@/components/shared/RemoveButton.vue'
import type { Article } from '@/components/domain/theory/Article'

const props = defineProps<{ chapterId: string; sectionId: string; article: Article }>()

const emit = defineEmits<{
  (e: 'removed'): void
}>()

const { t } = useI18n()

const remove = async () => {
  await api.theory.chapter.section.article.delete(props.chapterId, props.sectionId, props.article.id)
  emit('removed')
}
</script>

<template>
  <RemoveButton :title="t('components.action.article_remove.remove_all')" :remove="remove" />
</template>
