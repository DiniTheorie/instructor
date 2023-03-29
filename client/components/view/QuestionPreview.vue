<script setup lang="ts">
import { computed } from 'vue'
import type { Question } from '@/components/domain/Question'
import CorrectIndicator from '@/components/view/CorrectIndicator.vue'
import GreenRedBadge from '@/components/shared/GreenRedBadge.vue'
import { useI18n } from 'vue-i18n'

const props = defineProps<{ question: Question }>()

const primaryTranslation = computed(() => props.question.translations.find((entry) => entry.language === 'de'))
const { t } = useI18n()
</script>

<template>
  <div class="row">
    <div class="col-md-6">
      <div v-if="primaryTranslation" class="mw-35em">
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
          <b>{{ primaryTranslation.explanation }}</b>
        </p>
      </div>
    </div>
    <div class="col-md-6">
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
