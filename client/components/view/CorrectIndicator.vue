<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

defineProps<{ correct: boolean }>()
</script>

<template>
  <span>
    <FontAwesomeIcon class="text-success" :icon="['fal', 'circle-check']" v-if="correct" />
    <FontAwesomeIcon class="text-danger" :icon="['fal', 'circle-xmark']" v-else />
  </span>
</template>
