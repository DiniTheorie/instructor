<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { api } from '@/services/api'
import FormModal from '@/components/shared/Modal/FormModal.vue'
import { computed, ref } from 'vue'
import FormField from '@/components/shared/Form/TextField.vue'
import type { Article } from '@/components/domain/theory/Article'
import TranslatedMarkdown from '@/components/shared/Form/TranslatedMarkdown.vue'
import { exampleMarkdown } from '@/utils/markdown'

const emit = defineEmits<{
  (e: 'created', article: Article): void
}>()

const props = defineProps<{ chapterId: string; sectionId: string }>()

const model = ref({
  id: '',
  markdown: exampleMarkdown
})

const storeArticle = async () => {
  let modelValue = model.value
  if (!modelValue) {
    return
  }

  const payload: Article = {
    id: modelValue.id,
    translations: [{ language: 'de', markdown: modelValue.markdown }]
  }
  const article = await api.theory.chapter.section.article.post(props.chapterId, props.sectionId, payload)
  emit('created', article)
}

const canSubmit = computed(() => {
  return !!(model.value.id && model.value.markdown)
})

const { t } = useI18n()
</script>

<template>
  <FormModal :submit="storeArticle" :can-submit="canSubmit" :title="t('components.action.article_create.title')" size="lg">
    <FormField class="mb-5" field="domain.folder.id" help="domain.folder.id_help" v-model="model.id" />
    <TranslatedMarkdown field="domain.theory.article.markdown" help="domain.theory.article.markdown_help" language="de" v-model="model.markdown" />
  </FormModal>
</template>
