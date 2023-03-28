<script setup lang="ts">
import type { ExamCategory } from '@/components/domain/Category'
import { useI18n } from 'vue-i18n'
import { api } from '@/services/api'
import FormModal from '@/components/shared/Modal/FormModal.vue'
import { computed, ref } from 'vue'

const emit = defineEmits<{
  (e: 'created', category: ExamCategory): void
}>()

const model = ref({ id: '', name: '', description: '' })

const storeCategory = async () => {
  const payload: ExamCategory = {
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
  <FormModal :submit="storeCategory" :can-submit="canSubmit" :title="t('components.exam.category.create')">
    <div class="mb-5">
      <label class="form-label">{{ t('domain.exam.category.id') }}</label>
      <input type="text" class="form-control" v-model="model.id" />
    </div>
    <div class="mb-3">
      <label class="form-label">{{ t('domain.exam.category.name') }}</label>
      <div class="input-group mb-1">
        <input type="text" class="form-control" v-model="model.name" />
        <span class="input-group-text">{{ t('domain.supported_language.de') }}</span>
      </div>
    </div>
    <div>
      <label class="form-label">{{ t('domain.exam.category.description') }}</label>
      <div class="input-group mb-1">
        <textarea rows="2" class="form-control" v-model="model.description" />
        <span class="input-group-text">{{ t('domain.supported_language.de') }}</span>
      </div>
    </div>
  </FormModal>
</template>
