<script setup lang="ts">
defineProps<{
  url: string
  filename: string
}>()
</script>

<template>
  <div class="position-relative">
    <img class="img-fluid" :src="url" />
    <span class="position-absolute end-0 bottom-0 p-1 bg-light opacity-75">{{ filename }}</span>
  </div>
</template>

<style scoped></style>
