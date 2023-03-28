<script setup lang="ts">
const emit = defineEmits<{
  (e: 'click', id: string): void
}>()

defineProps<{ questionIds: string[] }>()
</script>

<template>
  <div class="row g-2">
    <div v-for="questionId in questionIds" :key="questionId" class="col-md-3">
      <div role="button" class="card shadow-sm bg-body-tertiary" @click="emit('click', questionId)">
        <div class="card-body">
          <h3>{{ questionId }}</h3>
        </div>
      </div>
    </div>
  </div>
</template>
