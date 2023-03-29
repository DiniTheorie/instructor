<script setup lang="ts">
defineProps<{
  title: string
  active: boolean
}>()
</script>

<template>
  <span class="badge" :class="{ 'text-bg-success': active, 'text-bg-secondary': !active }">
    {{ title }}
  </span>
</template>
