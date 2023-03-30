<script setup lang="ts">
import { computed } from 'vue'
import { api } from '@/services/api'
import RemovableImageWithFilename from '@/components/shared/RemovableImageWithFilename.vue'
import { useSettingsStore } from '@/stores/settingsStore'
import { storeToRefs } from 'pinia'
import type { ArticleWithUrls } from '@/components/domain/theory/Article'
import { marked } from 'marked'
import DOMPurify from 'dompurify'
import TranslationMissing from '@/components/view/TranslationMissing.vue'

const props = defineProps<{ article: ArticleWithUrls; chapterId: string; sectionId: string }>()

const emit = defineEmits<{
  (e: 'updated', article: ArticleWithUrls): void
}>()

const getImageUrl = (image: string) => api.theory.chapter.section.article.getImageUrl(props.chapterId, props.sectionId, props.article.id, image)
const { previewLanguage } = storeToRefs(useSettingsStore())
const primaryTranslation = computed(() => props.article.translations.find((entry) => entry.language === previewLanguage.value))

const removeImage = async (image: string) => {
  await api.theory.chapter.section.article.deleteImage(props.chapterId, props.sectionId, props.article.id, image)
  const newArticle: ArticleWithUrls = { ...props.article, imageUrls: props.article.imageUrls.filter((url) => url !== image) }
  emit('updated', newArticle)
}

const compiledMarkdown = computed(() => {
  if (primaryTranslation.value) {
    let markdown = marked.parse(primaryTranslation.value.markdown)
    return DOMPurify.sanitize(markdown)
  }
  return null
})
</script>

<template>
  <div class="row">
    <div class="col-sm-9 col-md-6 col-xxl-4">
      <div class="row g-1">
        <div v-for="(imageUrl, index) in article.imageUrls" :key="imageUrl" :class="{ 'col-12': index === 0, 'col-6': index > 0 }">
          <RemovableImageWithFilename :filename="imageUrl" :url="getImageUrl(imageUrl)" :remove="() => removeImage(imageUrl)" />
        </div>
      </div>
      <div class="mt-2" v-if="primaryTranslation">
        <div v-html="compiledMarkdown" />
      </div>
      <TranslationMissing v-else />
    </div>
  </div>
</template>
