<script setup lang="ts">
const emit = defineEmits<{
  (e: 'click', id: string): void
}>()

defineProps<{ ids: string[]; size: string }>()
</script>

<template>
  <div class="row g-2">
    <div v-for="id in ids" :key="id" :class="'col-md-' + size">
      <div role="button" class="card shadow-sm bg-body-tertiary" @click="emit('click', id)">
        <div class="card-body">
          <p class="mb-0">
            {{ id }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
