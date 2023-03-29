<script setup lang="ts">
import { useRouter } from 'vue-router'
import { computed } from 'vue'

const props = defineProps<{ canGoBack: boolean; siblings?: string[]; current: string }>()
const emit = defineEmits<{
  (e: 'changeSibling', target: string)
}>()

const router = useRouter()

const currentIndex = computed(() => {
  if (!props.siblings) {
    return undefined
  }

  return props.siblings.indexOf(props.current)
})

const nextSibling = computed(() => {
  let currentIndexValue = currentIndex.value
  console.log('executed', currentIndexValue)
  if (currentIndexValue === undefined || !props.siblings) {
    return undefined
  }

  if (currentIndexValue + 1 < props.siblings.length) {
    return props.siblings[currentIndexValue + 1]
  }

  return undefined
})

const previousSibling = computed(() => {
  let currentIndexValue = currentIndex.value
  if (currentIndexValue === undefined || !props.siblings) {
    return undefined
  }

  if (currentIndexValue > 0) {
    return props.siblings[currentIndexValue - 1]
  }

  return undefined
})
</script>

<template>
  <p class="mb-4 mt-4 lead">
    <span>&nbsp;</span>
    <a v-if="canGoBack" href="#" @click.prevent="router.back()">
      <FontAwesomeIcon :icon="['far', 'chevron-double-left']" />
    </a>
    <a class="ms-5" v-if="previousSibling" href="#" @click.prevent="emit('changeSibling', previousSibling)">
      <FontAwesomeIcon :icon="['far', 'chevron-left']" />
    </a>
    <span class="ms-5 me-2" v-if="!previousSibling">&nbsp;</span>
    <a class="ms-5" v-if="nextSibling" href="#" @click.prevent="emit('changeSibling', nextSibling)">
      <FontAwesomeIcon :icon="['far', 'chevron-right']" />
    </a>
    <span class="ms-5 me-2" v-if="!nextSibling">&nbsp;</span>
    <span class="ms-5" v-if="currentIndex !== undefined"> {{ currentIndex + 1 }} / {{ siblings.length }} </span>
  </p>
</template>
