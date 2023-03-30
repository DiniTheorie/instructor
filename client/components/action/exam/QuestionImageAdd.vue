<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { api } from '@/services/api'
import FormModal from '@/components/shared/Modal/FormModal.vue'
import { computed, ref } from 'vue'
import type { QuestionWithUrls } from '@/components/domain/exam/Question'
import ImageField from '@/components/shared/Form/ImageField.vue'

const emit = defineEmits<{
  (e: 'updated', question: QuestionWithUrls): void
}>()

const props = defineProps<{ categoryId: string; question: QuestionWithUrls }>()

const image = ref<File>()

const storeImage = async () => {
  let imageValue = image.value
  if (!imageValue) {
    return
  }

  await api.exam.category.question.postImage(props.categoryId, props.question.id, imageValue)

  const question: QuestionWithUrls = { ...props.question, imageUrls: props.question.imageUrls.concat([imageValue.name]).sort() }
  emit('updated', question)
}
const canSubmit = computed(() => {
  return !!image.value
})

const { t } = useI18n()
</script>

<template>
  <FormModal :submit="storeImage" :can-submit="canSubmit" :title="t('components.action.question_image_add.title')">
    <ImageField class="mb-3" field="domain.exam.question.image" @change="image = $event" />
  </FormModal>
</template>
