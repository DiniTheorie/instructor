<script setup lang="ts">
import { RouterView } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { ref } from 'vue'
import { api } from './services/api.js'
import NavBar from '@/components/layout/NavBar.vue'

const { t } = useI18n()

api.setup(t)

const language = ref('de')
</script>

<template>
  <NavBar language="de" @language-changed="language = $event" />

  <div class="container">
    <RouterView />
  </div>
</template>
