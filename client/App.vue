<script setup lang="ts">
import { RouterView } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { api } from './services/api.js'
import NavBar from '@/components/layout/NavBar.vue'
import { ref } from 'vue'

const { t } = useI18n()

const loading = ref(true)

api.addInterceptors(t)
api.setup().then(() => (loading.value = false))
</script>

<template>
  <NavBar />

  <div class="container mb-5">
    <div class="alert alert-info" v-if="loading">
      {{ t('page.home.booting') }}
    </div>
    <RouterView v-else />
  </div>
</template>
