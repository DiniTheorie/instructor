<script setup lang="ts">
import { api } from '@/services/api'
import { useI18n } from 'vue-i18n'
import RemoveButton from '@/components/shared/RemoveButton.vue'
import type { Chapter } from '@/components/domain/theory/Chapter'

const props = defineProps<{ chapter: Chapter }>()

const emit = defineEmits<{
  (e: 'removed'): void
}>()

const { t } = useI18n()

const remove = async () => {
  await api.theory.chapter.delete(props.chapter.id)
  emit('removed')
}
</script>

<template>
  <RemoveButton :title="t('components.action.chapter_remove.remove_all')" :remove="remove" />
</template>
