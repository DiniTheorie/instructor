<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { api } from '@/services/api'
import FormModal from '@/components/shared/Modal/FormModal.vue'
import { computed, ref } from 'vue'
import TranslatedField from '@/components/shared/Form/TranslatedField.vue'
import FormField from '@/components/shared/Form/TextField.vue'
import TranslatedTextarea from '@/components/shared/Form/TranslatedTextarea.vue'
import type { Section } from '@/components/domain/theory/Section'

const emit = defineEmits<{
  (e: 'created', section: Section): void
}>()

const props = defineProps<{ chapterId: string }>()

const model = ref({ id: '', title: '', description: '' })

const storeSection = async () => {
  const payload: Section = {
    id: model.value.id,
    translations: [{ language: 'de', title: model.value.title, description: model.value.description }],
    config: { view: 'list' }
  }
  const section = await api.theory.chapter.section.post(props.chapterId, payload)
  emit('created', section)
}
const canSubmit = computed(() => {
  return !!(model.value.id && model.value.title && model.value.description)
})

const { t } = useI18n()
</script>

<template>
  <FormModal :submit="storeSection" :can-submit="canSubmit" :title="t('components.action.section_create.title')">
    <FormField class="mb-5" field="domain.folder.id" help="domain.folder.id_help" v-model="model.id" />
    <TranslatedField class="mb-3" field="domain.theory.section.title" language="de" v-model="model.title" />
    <TranslatedTextarea field="domain.theory.section.description" language="de" v-model="model.description" />
  </FormModal>
</template>
