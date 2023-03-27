<script setup lang="ts">
const emit = defineEmits<{
  (e: 'click', id: string): void
}>()

defineProps<{ categoryIds: string[] }>()
</script>

<template>
  <div
    v-for="categoryId in categoryIds"
    :key="categoryId"
    role="button"
    class="card"
    @click="emit('click', categoryId)"
  >
    <div class="card-body">
      <h3>{{ categoryId }}</h3>
    </div>
  </div>
</template>
