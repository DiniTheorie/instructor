<script setup lang="ts">
import type { Question, QuestionTranslation, QuestionWithUrls } from '@/components/domain/Question'
import { api } from '@/services/api'
import { computed, ref } from 'vue'
import type { SupportedLanguage } from '@/components/domain/SupportedLanguage'
import { supportedLanguages } from '@/components/domain/SupportedLanguage'
import { truthy } from '@/utils/array'
import TranslatedTextarea from '@/components/shared/Form/TranslatedTextarea.vue'
import TranslationFormModal from '@/components/shared/Modal/TranslationFormModal.vue'

const emit = defineEmits<{
  (e: 'updated', question: QuestionWithUrls): void
}>()

const props = defineProps<{ language: SupportedLanguage; template?: QuestionTranslation; question: Question; categoryId: string }>()

const model = ref({
  question: props.template?.question ?? '',
  answer_1: props.template?.answer_1 ?? '',
  answer_2: props.template?.answer_2 ?? '',
  answer_3: props.template?.answer_3 ?? '',
  explanation: props.template?.explanation ?? ''
})

const store = async () => {
  const translations: QuestionTranslation[] = supportedLanguages
    .map((supportedLanguage) => {
      if (supportedLanguage === props.language) {
        return { ...model.value, language: props.language }
      }
      return props.question.translations.find((entry) => entry.language === supportedLanguage)
    })
    .filter(truthy)

  const payload: Question = { ...props.question, translations }
  const question = await api.exam.category.question.put(props.categoryId, payload)
  emit('updated', question)
}

const remove = async () => {
  const translations = props.question.translations.filter((entry) => entry.language !== props.language)
  const payload: Question = { ...props.question, translations }
  const question = await api.exam.category.question.put(props.categoryId, payload)
  emit('updated', question)
}

const canStore = computed(() => {
  return !!(model.value.question && model.value.answer_1 && model.value.answer_2 && model.value.answer_3)
})
</script>

<template>
  <TranslationFormModal :can-submit="canStore" :submit="store" :remove="remove" :language="language" :exists="!!template">
    <TranslatedTextarea class="mb-3" field="domain.exam.question.question" :language="language" v-model="model.question" />
    <TranslatedTextarea class="mb-3" field="domain.exam.question.answer_1" :language="language" v-model="model.answer_1" />
    <TranslatedTextarea class="mb-3" field="domain.exam.question.answer_2" :language="language" v-model="model.answer_2" />
    <TranslatedTextarea class="mb-3" field="domain.exam.question.answer_3" :language="language" v-model="model.answer_3" />
    <TranslatedTextarea field="domain.exam.question.explanation" :language="language" v-model="model.explanation" />
  </TranslationFormModal>
</template>
