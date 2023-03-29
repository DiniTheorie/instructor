<script setup lang="ts">
import { api } from '@/services/api'
import { computed, ref } from 'vue'
import type { SupportedLanguage } from '@/components/domain/SupportedLanguage'
import { supportedLanguages } from '@/components/domain/SupportedLanguage'
import { truthy } from '@/utils/array'
import TranslatedField from '@/components/shared/Form/TranslatedField.vue'
import TranslatedTextarea from '@/components/shared/Form/TranslatedTextarea.vue'
import TranslationFormModal from '@/components/shared/Modal/TranslationFormModal.vue'
import type { Chapter, ChapterTranslation } from '@/components/domain/theory/Chapter'

const emit = defineEmits<{
  (e: 'updated', chapter: Chapter): void
}>()

const props = defineProps<{ language: SupportedLanguage; template?: ChapterTranslation; chapter: Chapter }>()

const model = ref({ title: props.template?.title ?? '', description: props.template?.description ?? '' })

const store = async () => {
  const translations: ChapterTranslation[] = supportedLanguages
    .map((supportedLanguage) => {
      if (supportedLanguage === props.language) {
        return { ...model.value, language: props.language }
      }
      return props.chapter.translations.find((entry) => entry.language === supportedLanguage)
    })
    .filter(truthy)

  const payload: Chapter = { ...props.chapter, translations }
  const chapter = await api.theory.chapter.put(payload)
  emit('updated', chapter)
}

const remove = async () => {
  const translations = props.chapter.translations.filter((entry) => entry.language !== props.language)
  const payload: Chapter = { ...props.chapter, translations }
  const chapter = await api.theory.chapter.put(payload)
  emit('updated', chapter)
}

const canStore = computed(() => {
  return !!(model.value.title && model.value.description)
})
</script>

<template>
  <TranslationFormModal :can-submit="canStore" :submit="store" :remove="remove" :language="language" :exists="!!template">
    <TranslatedField class="mb-3" field="domain.theory.chapter.title" :language="language" v-model="model.title" />
    <TranslatedTextarea field="domain.theory.chapter.description" :language="language" v-model="model.description" />
  </TranslationFormModal>
</template>
