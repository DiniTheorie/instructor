<script setup lang="ts">
const emit = defineEmits<{
  (e: 'click', id: string): void
}>()

defineProps<{ categoryIds: string[] }>()
</script>

<template>
  <div class="row g-2">
    <div v-for="categoryId in categoryIds" :key="categoryId" class="col-4">
      <div role="button" class="card shadow-sm bg-body-tertiary" @click="emit('click', categoryId)">
        <div class="card-body">
          <h3>{{ categoryId }}</h3>
        </div>
      </div>
    </div>
  </div>
</template>
