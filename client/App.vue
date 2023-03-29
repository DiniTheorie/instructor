<script setup lang="ts">
import { RouterView } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { api } from './services/api.js'
import NavBar from '@/components/layout/NavBar.vue'

const { t } = useI18n()

api.setup(t)
</script>

<template>
  <NavBar />

  <div class="container mb-5">
    <RouterView />
  </div>
</template>
