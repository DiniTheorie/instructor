<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { api } from '@/services/api'
import FormModal from '@/components/shared/Modal/FormModal.vue'
import { computed, ref } from 'vue'
import ImageField from '@/components/shared/Form/ImageField.vue'
import type { ArticleWithUrls } from '@/components/domain/theory/Article'

const emit = defineEmits<{
  (e: 'updated', article: ArticleWithUrls): void
}>()

const props = defineProps<{ chapterId: string; sectionId: string; article: ArticleWithUrls }>()

const image = ref<File>()

const storeImage = async () => {
  let imageValue = image.value
  if (!imageValue) {
    return
  }

  await api.theory.chapter.section.article.postImage(props.chapterId, props.sectionId, props.article.id, imageValue)

  const article: ArticleWithUrls = { ...props.article, imageUrls: props.article.imageUrls.concat([imageValue.name]).sort() }
  emit('updated', article)
}
const canSubmit = computed(() => {
  return !!image.value
})

const { t } = useI18n()
</script>

<template>
  <FormModal :submit="storeImage" :can-submit="canSubmit" :title="t('components.action.article_image_add.title')">
    <ImageField class="mb-3" field="domain.theory.article.image" @change="image = $event" />
  </FormModal>
</template>
