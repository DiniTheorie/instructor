<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { api } from '@/services/api'
import FormModal from '@/components/shared/Modal/FormModal.vue'
import { ref } from 'vue'
import type { Section } from '@/components/domain/theory/Section'
import { sectionViews } from '@/components/domain/theory/Section'
import SelectField from '@/components/shared/Form/SelectField.vue'

const emit = defineEmits<{
  (e: 'updated', section: Section): void
}>()

const props = defineProps<{ chapterId: string; section: Section }>()

const model = ref(props.section.config)

const storeSection = async () => {
  let modelValue = model.value
  if (!modelValue) {
    return
  }

  const payload: Section = {
    id: props.section.id,
    translations: props.section.translations,
    config: modelValue
  }
  const section = await api.theory.chapter.section.put(props.chapterId, payload)

  emit('updated', section)
}

const { t } = useI18n()
</script>

<template>
  <FormModal :submit="storeSection" :can-submit="true" :title="t('components.action.section_config_edit.title')">
    <SelectField
      field="domain.theory.section.view"
      help="domain.theory.section.view_help"
      v-model="model.view"
      :options="sectionViews"
      :trans-option="(entry) => t('domain.theory.section.views.' + entry)"
    />
  </FormModal>
</template>
