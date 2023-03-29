<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { api } from '@/services/api'
import FormModal from '@/components/shared/Modal/FormModal.vue'
import { computed, ref } from 'vue'
import FormField from '@/components/shared/Form/TextField.vue'
import TranslatedTextarea from '@/components/shared/Form/TranslatedTextarea.vue'
import type { Question } from '@/components/domain/Question'
import TranslatedAnswer from '@/components/shared/Form/TranslatedAnswer.vue'
import ImageField from '@/components/shared/Form/ImageField.vue'
import CheckField from '@/components/shared/Form/CheckField.vue'

const emit = defineEmits<{
  (e: 'created', question: Question): void
}>()

const props = defineProps<{ categoryId: string }>()

const model = ref({
  id: '',
  meta: {
    exam: true,
    important: false,
    text_asa: true,
    image_asa: true,
    answer_1_correct: false,
    answer_2_correct: false,
    answer_3_correct: false
  },
  question: '',
  answer_1: '',
  answer_2: '',
  answer_3: '',
  explanation: ''
})

const examImage = ref<File>()

const storeQuestion = async () => {
  let examImageValue = examImage.value
  let modelValue = model.value
  if (!modelValue || !examImageValue) {
    return
  }

  const payload: Question = {
    id: modelValue.id,
    meta: modelValue.meta,
    translations: [
      {
        language: 'de',
        question: modelValue.question,
        answer_1: modelValue.answer_1,
        answer_2: modelValue.answer_2,
        answer_3: modelValue.answer_3,
        explanation: modelValue.explanation
      }
    ]
  }
  const question = await api.exam.category.question.post(props.categoryId, payload)
  await api.exam.category.question.postImage(props.categoryId, question.id, examImageValue, 'exam')
  emit('created', question)
}
const canSubmit = computed(() => {
  return !!(model.value.id && model.value.question && model.value.answer_1 && model.value.answer_2 && model.value.answer_3 && examImage.value)
})

const { t } = useI18n()
</script>

<template>
  <FormModal :submit="storeQuestion" :can-submit="canSubmit" :title="t('components.action.question_create.title')">
    <FormField class="mb-5" field="domain.folder.id" help="domain.folder.id_help" v-model="model.id" />
    <ImageField class="mb-3" field="domain.exam.question.exam_image" @change="examImage = $event" />
    <TranslatedTextarea class="mb-3" field="domain.exam.question.question" language="de" v-model="model.question" />
    <TranslatedAnswer
      class="mb-3"
      field="domain.exam.question.answer_1"
      :correct="model.meta.answer_1_correct"
      @update:correct="model.meta.answer_1_correct = $event"
      :answer="model.answer_1"
      @update:answer="model.answer_1 = $event"
      language="de"
    />
    <TranslatedAnswer
      class="mb-3"
      field="domain.exam.question.answer_2"
      :correct="model.meta.answer_2_correct"
      @update:correct="model.meta.answer_2_correct = $event"
      :answer="model.answer_2"
      @update:answer="model.answer_2 = $event"
      language="de"
    />
    <TranslatedAnswer
      class="mb-3"
      field="domain.exam.question.answer_3"
      :correct="model.meta.answer_3_correct"
      @update:correct="model.meta.answer_3_correct = $event"
      :answer="model.answer_3"
      @update:answer="model.answer_3 = $event"
      language="de"
    />
    <TranslatedTextarea field="domain.exam.question.explanation" language="de" v-model="model.explanation" />
    <hr />
    <div class="row">
      <div class="col-6">
        <p class="mb-1">{{ t('domain.exam.question.source') }}</p>
        <CheckField field="domain.exam.question.meta.text_asa" v-model="model.meta.text_asa" />
        <CheckField field="domain.exam.question.meta.image_asa" v-model="model.meta.image_asa" />
      </div>
      <div class="col-6">
        <p class="mb-1">{{ t('domain.exam.question.classification') }}</p>
        <CheckField field="domain.exam.question.meta.important" v-model="model.meta.important" />
        <CheckField field="domain.exam.question.meta.exam" v-model="model.meta.exam" />
      </div>
    </div>
  </FormModal>
</template>
