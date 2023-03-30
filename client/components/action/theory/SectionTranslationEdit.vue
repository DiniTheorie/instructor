<script setup lang="ts">
import { api } from '@/services/api'
import { computed, ref } from 'vue'
import type { SupportedLanguage } from '@/components/domain/SupportedLanguage'
import { supportedLanguages } from '@/components/domain/SupportedLanguage'
import { truthy } from '@/utils/array'
import TranslatedField from '@/components/shared/Form/TranslatedField.vue'
import TranslatedTextarea from '@/components/shared/Form/TranslatedTextarea.vue'
import TranslationFormModal from '@/components/shared/Modal/TranslationFormModal.vue'
import type { Section, SectionTranslation } from '@/components/domain/theory/Section'

const emit = defineEmits<{
  (e: 'updated', section: Section): void
}>()

const props = defineProps<{ language: SupportedLanguage; template?: SectionTranslation; section: Section; chapterId: string }>()

const model = ref({ title: props.template?.title ?? '', description: props.template?.description ?? '' })

const store = async () => {
  const translations: SectionTranslation[] = supportedLanguages
    .map((supportedLanguage) => {
      if (supportedLanguage === props.language) {
        return { ...model.value, language: props.language }
      }
      return props.section.translations.find((entry) => entry.language === supportedLanguage)
    })
    .filter(truthy)

  const payload: Section = { ...props.section, translations }
  const section = await api.theory.chapter.section.put(props.chapterId, payload)
  emit('updated', section)
}

const remove = async () => {
  const translations = props.section.translations.filter((entry) => entry.language !== props.language)
  const payload: Section = { ...props.section, translations }
  const section = await api.theory.chapter.section.put(props.chapterId, payload)
  emit('updated', section)
}

const canStore = computed(() => {
  return !!model.value.title
})
</script>

<template>
  <TranslationFormModal :can-submit="canStore" :submit="store" :remove="remove" :language="language" :exists="!!template">
    <TranslatedField class="mb-3" field="domain.theory.section.title" :language="language" v-model="model.title" />
    <TranslatedTextarea field="domain.theory.section.description" :language="language" v-model="model.description" />
  </TranslationFormModal>
</template>
