<script setup lang="ts">
import type { Category } from '@/components/domain/Category'
import { useI18n } from 'vue-i18n'
import { api } from '@/services/api'
import FormModal from '@/components/shared/Modal/FormModal.vue'
import { computed, ref } from 'vue'
import TranslatedField from '@/components/shared/Form/TranslatedField.vue'
import FormField from '@/components/shared/Form/TextField.vue'
import TranslatedTextarea from '@/components/shared/Form/TranslatedTextarea.vue'

const emit = defineEmits<{
  (e: 'created', category: Category): void
}>()

const model = ref({ id: '', name: '', description: '' })

const storeCategory = async () => {
  const payload: Category = {
    id: model.value.id,
    translations: [{ language: 'de', name: model.value.name, description: model.value.description }]
  }
  const category = await api.exam.category.post(payload)
  emit('created', category)
}
const canSubmit = computed(() => {
  return !!(model.value.id && model.value.name && model.value.description)
})

const { t } = useI18n()
</script>

<template>
  <FormModal :submit="storeCategory" :can-submit="canSubmit" :title="t('components.action.category_create.title')">
    <FormField class="mb-5" field="domain.folder.id" help="domain.folder.id_help" v-model="model.id" />
    <TranslatedField class="mb-3" field="domain.exam.category.name" language="de" v-model="model.name" />
    <TranslatedTextarea field="domain.exam.category.description" language="de" v-model="model.description" />
  </FormModal>
</template>
