<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { api } from '@/services/api'
import FormModal from '@/components/shared/Modal/FormModal.vue'
import { computed, ref } from 'vue'
import TranslatedField from '@/components/shared/Form/TranslatedField.vue'
import FormField from '@/components/shared/Form/TextField.vue'
import TranslatedTextarea from '@/components/shared/Form/TranslatedTextarea.vue'
import type { Chapter } from '@/components/domain/theory/Chapter'

const emit = defineEmits<{
  (e: 'created', chapter: Chapter): void
}>()

const model = ref({ id: '', title: '', description: '' })

const storeChapter = async () => {
  const payload: Chapter = {
    id: model.value.id,
    translations: [{ language: 'de', title: model.value.title, description: model.value.description }]
  }
  const chapter = await api.theory.chapter.post(payload)
  emit('created', chapter)
}
const canSubmit = computed(() => {
  return !!(model.value.id && model.value.title && model.value.description)
})

const { t } = useI18n()
</script>

<template>
  <FormModal :submit="storeChapter" :can-submit="canSubmit" :title="t('components.action.chapter_create.title')">
    <FormField class="mb-5" field="domain.folder.id" help="domain.folder.id_help" v-model="model.id" />
    <TranslatedField class="mb-3" field="domain.theory.chapter.title" language="de" v-model="model.title" />
    <TranslatedTextarea field="domain.theory.chapter.description" language="de" v-model="model.description" />
  </FormModal>
</template>
