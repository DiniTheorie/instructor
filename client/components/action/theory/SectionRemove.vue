<script setup lang="ts">
import { api } from '@/services/api'
import { useI18n } from 'vue-i18n'
import RemoveButton from '@/components/shared/RemoveButton.vue'
import type { Section } from '@/components/domain/theory/Section'

const props = defineProps<{ chapterId: string; section: Section }>()

const emit = defineEmits<{
  (e: 'removed'): void
}>()

const { t } = useI18n()

const remove = async () => {
  await api.theory.chapter.section.delete(props.chapterId, props.section.id)
  emit('removed')
}
</script>

<template>
  <RemoveButton :title="t('components.action.section_remove.remove_all')" :remove="remove" />
</template>
