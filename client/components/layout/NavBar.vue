<script setup lang="ts">
import { ref, watchEffect } from 'vue'
import { useI18n } from 'vue-i18n'

const emit = defineEmits<{
  (e: 'languageChanged', language: string): void
}>()

const props = defineProps<{ language: string }>()
const language = ref(props.language)
const supportedLanguages: string[] = ['de', 'en', 'fr', 'it']

watchEffect(() => {
  emit('languageChanged', language.value)
})

const { t } = useI18n()
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light mt-0">
    <div class="container">
      <div class="navbar-brand">
        <img alt="DiniTheorie logo" class="me-2" src="@/assets/logo.png" width="25" height="25" />
        <RouterLink to="/">{{ t('page.home.title') }}</RouterLink>
      </div>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <form class="d-flex ms-auto">
          <label class="col-form-label me-2" for="language-select">{{
            t('layout.nav_bar.language')
          }}</label>
          <select
            class="form-select"
            v-model="language"
            id="language-select"
            aria-label="Default select example"
          >
            <option v-for="language in supportedLanguages" :key="language" :value="language">
              {{ t('domain.supported_language.' + language) }}
            </option>
          </select>
        </form>
      </div>
    </div>
  </nav>
</template>
