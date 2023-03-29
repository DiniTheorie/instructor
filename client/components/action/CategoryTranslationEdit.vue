<script setup lang="ts">
import type { Category, CategoryTranslation } from '@/components/domain/Category'
import { api } from '@/services/api'
import { computed, ref } from 'vue'
import type { SupportedLanguage } from '@/components/domain/SupportedLanguage'
import { supportedLanguages } from '@/components/domain/SupportedLanguage'
import { truthy } from '@/utils/array'
import TranslatedField from '@/components/shared/Form/TranslatedField.vue'
import TranslatedTextarea from '@/components/shared/Form/TranslatedTextarea.vue'
import TranslationFormModal from '@/components/shared/Modal/TranslationFormModal.vue'

const emit = defineEmits<{
  (e: 'updated', category: Category): void
}>()

const props = defineProps<{ language: SupportedLanguage; template?: CategoryTranslation; category: Category }>()

const model = ref({ name: props.template?.name ?? '', description: props.template?.description ?? '' })

const store = async () => {
  const translations: CategoryTranslation[] = supportedLanguages
    .map((supportedLanguage) => {
      if (supportedLanguage === props.language) {
        return { ...model.value, language: props.language }
      }
      return props.category.translations.find((entry) => entry.language === supportedLanguage)
    })
    .filter(truthy)

  const payload: Category = { ...props.category, translations }
  const category = await api.exam.category.put(payload)
  emit('updated', category)
}

const remove = async () => {
  const translations = props.category.translations.filter((entry) => entry.language !== props.language)
  const payload: Category = { ...props.category, translations }
  const category = await api.exam.category.put(payload)
  emit('updated', category)
}

const canStore = computed(() => {
  return !!(model.value.name && model.value.description)
})
</script>

<template>
  <TranslationFormModal :can-submit="canStore" :submit="store" :remove="remove" :language="language" :exists="!!template">
    <TranslatedField class="mb-3" field="domain.exam.category.name" :language="language" v-model="model.name" />
    <TranslatedTextarea field="domain.exam.category.description" :language="language" v-model="model.description" />
  </TranslationFormModal>
</template>
