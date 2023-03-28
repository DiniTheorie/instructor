<script setup lang="ts">
import { ref } from 'vue'

const loading = ref(false)

const props = defineProps<{
  submit: () => Promise<void>
}>()

const handleSubmit = async () => {
  loading.value = true
  await props.submit()
  loading.value = false
}
</script>

<template>
  <button class="btn btn-primary" @click="handleSubmit" :disabled="loading">
    <slot></slot>
  </button>
</template>
