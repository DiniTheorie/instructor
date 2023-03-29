<script setup lang="ts">
import { computed } from 'vue'
import type { QuestionWithUrls } from '@/components/domain/Question'
import CorrectIndicator from '@/components/view/CorrectIndicator.vue'
import GreenRedBadge from '@/components/shared/GreenRedBadge.vue'
import { useI18n } from 'vue-i18n'
import { api } from '@/services/api'
import ImageWithFilename from '@/components/shared/ImageWithFilename.vue'

const props = defineProps<{ question: QuestionWithUrls; categoryId: string }>()

const getImageUrl = (image: string) => api.exam.category.question.getImageUrl(props.categoryId, props.question.id, image)
const primaryTranslation = computed(() => props.question.translations.find((entry) => entry.language === 'de'))
const { t } = useI18n()
</script>

<template>
  <div class="row">
    <div class="col-sm-9 col-md-6 col-xxl-4">
      <ImageWithFilename :url="getImageUrl(question.examImageUrl)" :filename="question.examImageUrl" />
    </div>
    <div class="col-sm-9 col-md-6 col-xxl-4">
      <div v-if="primaryTranslation">
        <p>
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
        <p class="alert alert-info mt-4">
          <span>{{ t('domain.exam.question.explanation') }}: </span>
          {{ primaryTranslation.explanation }}
        </p>
      </div>
      <p>
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
