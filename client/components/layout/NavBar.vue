<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { api } from '@/services/api'
import SubmitButton from '@/components/shared/LoadingButton.vue'

const publishChanges = async () => {
  await api.store()
}
const discardChanges = async () => {
  if (window.confirm(t('layout.nav_bar.discard_changes_confirm'))) {
    await api.discard()
    window.location.reload()
  }
}

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
        <div class="btn-group ms-auto">
          <SubmitButton :submit="publishChanges">{{ t('layout.nav_bar.publish_changes') }}</SubmitButton>
          <SubmitButton class="btn-danger" :submit="discardChanges">{{ t('layout.nav_bar.discard_changes') }}</SubmitButton>
        </div>
      </div>
    </div>
  </nav>
</template>
