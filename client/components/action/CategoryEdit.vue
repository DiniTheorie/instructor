<script setup lang="ts">
import type { ExamCategory } from '@/components/domain/Category'
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useSettingsStore } from '@/stores/localeStore'
import LoadingForm from '@/components/shared/LoadingForm.vue'
import { storeToRefs } from 'pinia'

const props = defineProps<{ category: ExamCategory; store: (category: ExamCategory) => Promise<void>; remove: () => Promise<void> }>()

const model = ref(props.category)
let fallback = model.value.translations.find((translation) => translation.language === 'de')
if (!fallback) {
  fallback = { language: 'de', name: '', description: '' }
  model.value.translations.push(fallback)
}

const settings = useSettingsStore()
const { translationLanguage } = storeToRefs(settings)

const translation = computed(() => {
  let existing = model.value.translations.find((translation) => translation.language === translationLanguage.value)
  if (!existing) {
    existing = { language: translationLanguage.value, name: '', description: '' }
    // eslint-disable-next-line vue/no-side-effects-in-computed-properties
    model.value.translations.push(existing)
  }

  return existing
})

const storeCategory = () => {
  return props.store(model.value)
}

const { t } = useI18n()
</script>

<template>
  <LoadingForm :submit="storeCategory" :remove="remove">
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label">{{ t('domain.exam.category.name') }}</label>
          <div class="input-group mb-1">
            <input type="text" class="form-control" v-model="fallback.name" />
            <span class="input-group-text">{{ t('domain.supported_language.de') }}</span>
          </div>
          <div class="input-group">
            <input type="text" class="form-control" v-model="translation.name" />
            <span class="input-group-text">{{ t('domain.supported_language.' + settings.translationLanguage) }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label">{{ t('domain.exam.category.description') }}</label>
          <div class="input-group mb-1">
            <textarea rows="2" class="form-control" v-model="fallback.description" />
            <span class="input-group-text">{{ t('domain.supported_language.de') }}</span>
          </div>
          <div class="input-group">
            <textarea rows="2" class="form-control" v-model="translation.description" />
            <span class="input-group-text">{{ t('domain.supported_language.' + settings.translationLanguage) }}</span>
          </div>
        </div>
      </div>
    </div>
  </LoadingForm>
</template>
