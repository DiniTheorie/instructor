<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { api } from '@/services/api'
import FormModal from '@/components/shared/Modal/FormModal.vue'
import { ref } from 'vue'
import type { Question, QuestionWithUrls } from '@/components/domain/Question'
import ImageField from '@/components/shared/Form/ImageField.vue'
import CheckField from '@/components/shared/Form/CheckField.vue'
import SwitchField from '@/components/shared/Form/SwitchField.vue'

const emit = defineEmits<{
  (e: 'updated', question: QuestionWithUrls): void
}>()

const props = defineProps<{ categoryId: string; question: QuestionWithUrls }>()

const model = ref(props.question.meta)

const examImage = ref<File>()

const storeQuestion = async () => {
  let modelValue = model.value
  if (!modelValue) {
    return
  }

  const payload: Question = {
    id: props.question.id,
    translations: props.question.translations,
    meta: modelValue
  }
  const question = await api.exam.category.question.put(props.categoryId, payload)

  let examImageValue = examImage.value
  if (examImageValue) {
    await api.exam.category.question.postImage(props.categoryId, question.id, examImageValue, 'exam')
  }

  emit('updated', question)
}

const { t } = useI18n()
</script>

<template>
  <FormModal :submit="storeQuestion" :can-submit="true" :title="t('components.action.question_meta_edit.title')">
    <ImageField class="mb-3" field="domain.exam.question.exam_image" @change="examImage = $event" />
    <div class="mb-3">
      <p class="mb-1">{{ t('domain.exam.question.answer_correctness') }}</p>
      <SwitchField field="domain.exam.question.meta.answer_1_correct" v-model="model.answer_1_correct" />
      <SwitchField field="domain.exam.question.meta.answer_2_correct" v-model="model.answer_2_correct" />
      <SwitchField field="domain.exam.question.meta.answer_3_correct" v-model="model.answer_3_correct" />
    </div>
    <div class="row">
      <div class="col-6">
        <p class="mb-1">{{ t('domain.exam.question.source') }}</p>
        <CheckField field="domain.exam.question.meta.text_asa" v-model="model.text_asa" />
        <CheckField field="domain.exam.question.meta.image_asa" v-model="model.image_asa" />
      </div>
      <div class="col-6">
        <p class="mb-1">{{ t('domain.exam.question.classification') }}</p>
        <CheckField field="domain.exam.question.meta.important" v-model="model.important" />
        <CheckField field="domain.exam.question.meta.exam" v-model="model.exam" />
      </div>
    </div>
  </FormModal>
</template>
