<script setup lang="ts">
import { computed } from 'vue'
import type { QuestionWithUrls } from '@/components/domain/exam/Question'
import CorrectIndicator from '@/components/view/CorrectIndicator.vue'
import GreenRedBadge from '@/components/shared/GreenRedBadge.vue'
import { useI18n } from 'vue-i18n'
import { api } from '@/services/api'
import ImageWithFilename from '@/components/shared/ImageWithFilename.vue'
import RemovableImageWithFilename from '@/components/shared/RemovableImageWithFilename.vue'
import { useSettingsStore } from '@/stores/settingsStore'
import { storeToRefs } from 'pinia'
import TranslationMissing from '@/components/view/TranslationMissing.vue'

const props = defineProps<{ question: QuestionWithUrls; categoryId: string }>()

const emit = defineEmits<{
  (e: 'updated', question: QuestionWithUrls): void
}>()

const getImageUrl = (image: string) => api.exam.category.question.getImageUrl(props.categoryId, props.question.id, image)
const { previewLanguage } = storeToRefs(useSettingsStore())
const primaryTranslation = computed(() => props.question.translations.find((entry) => entry.language === previewLanguage.value))
const { t } = useI18n()

const removeImage = async (image: string) => {
  await api.exam.category.question.deleteImage(props.categoryId, props.question.id, image)
  const newQuestion: QuestionWithUrls = { ...props.question, imageUrls: props.question.imageUrls.filter((url) => url !== image) }
  emit('updated', newQuestion)
}
</script>

<template>
  <div class="row">
    <div class="col-sm-9 col-md-6 col-xxl-4">
      <ImageWithFilename :url="getImageUrl(question.examImageUrl)" :filename="question.examImageUrl" />
      <div class="row mt-1 g-1">
        <div v-for="imageUrl in question.imageUrls" :key="imageUrl" class="col-6">
          <RemovableImageWithFilename :filename="imageUrl" :url="getImageUrl(imageUrl)" :remove="() => removeImage(imageUrl)" />
        </div>
      </div>
    </div>
    <div class="col-sm-9 col-md-6 col-xxl-4">
      <div v-if="primaryTranslation">
        <p v-if="primaryTranslation.question">
          <b>{{ primaryTranslation.question }}</b>
        </p>
        <p>
          <CorrectIndicator class="me-2" :correct="question.meta.answer_1_correct" />
          {{ primaryTranslation.answer_1 }}
        </p>
        <p>
          <CorrectIndicator class="me-2" :correct="question.meta.answer_2_correct" />
          {{ primaryTranslation.answer_2 }}
        </p>
        <p>
          <CorrectIndicator class="me-2" :correct="question.meta.answer_3_correct" />
          {{ primaryTranslation.answer_3 }}
        </p>
        <p class="mt-4">
          <b>{{ t('domain.exam.question.explanation') }}: </b>
          {{ primaryTranslation.explanation }}
        </p>
      </div>
      <TranslationMissing v-else />
      <p class="mt-5">
        {{ t('domain.exam.question.source') }}:
        <GreenRedBadge class="me-1" :title="t('domain.exam.question.meta.text_asa')" :active="question.meta.text_asa" />
        <GreenRedBadge :title="t('domain.exam.question.meta.image_asa')" :active="question.meta.image_asa" />
      </p>
      <p>
        {{ t('domain.exam.question.classification') }}:
        <GreenRedBadge class="me-1" :title="t('domain.exam.question.meta.important')" :active="question.meta.important" />
        <GreenRedBadge :title="t('domain.exam.question.meta.exam')" :active="question.meta.exam" />
      </p>
    </div>
  </div>
</template>
