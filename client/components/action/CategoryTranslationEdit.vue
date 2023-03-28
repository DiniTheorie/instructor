<script setup lang="ts">
import type { ExamCategory, ExamCategoryTranslation } from '@/components/domain/Category'
import { useI18n } from 'vue-i18n'
import { api } from '@/services/api'
import FormModal from '@/components/shared/Modal/FormModal.vue'
import { computed, ref } from 'vue'
import { SupportedLanguage, supportedLanguages } from '@/components/domain/SupportedLanguage'
import { truthy } from '@/utils/array'
import TranslatedField from '@/components/shared/Form/TranslatedField.vue'
import TranslatedTextarea from '@/components/shared/Form/TranslatedTextarea.vue'

const emit = defineEmits<{
  (e: 'updated', category: ExamCategory): void
}>()

const props = defineProps<{ language: SupportedLanguage; template?: ExamCategoryTranslation; category: ExamCategory }>()

const model = ref({ name: props.template?.name, description: props.template?.description })

const storeCategoryTranslation = async () => {
  const translations: ExamCategoryTranslation[] = supportedLanguages
    .map((supportedLanguage) => {
      if (supportedLanguage === props.language) {
        return { ...model.value, language: props.language }
      }
      return category.translations.find((entry) => entry.language === supportedLanguage)
    })
    .filter(truthy)

  const payload: ExamCategory = { id: props.category.id, translations }
  const category = await api.exam.category.post(payload)
  emit('updated', category)
}
const canSubmit = computed(() => {
  return !!(model.value.name && model.value.description)
})

const { t } = useI18n()
</script>

<template>
  <FormModal :submit="storeCategoryTranslation" :can-submit="canSubmit" :title="t('components.exam.category.create')">
    <TranslatedField class="mb-3" field="domain.exam.category.name" language="de" v-model="model.name" />
    <TranslatedTextarea field="domain.exam.category.description" language="de" v-model="model.description" />
  </FormModal>
</template>
