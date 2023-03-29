<script setup lang="ts">
import { useRouter } from 'vue-router'
import { computed } from 'vue'

const props = withDefaults(defineProps<{ canGoBack?: boolean; siblings?: string[]; current?: string }>(), { canGoBack: false })
const emit = defineEmits<{
  (e: 'changeSibling', target: string): void
}>()

const router = useRouter()

const currentIndex = computed(() => {
  if (!props.siblings || !props.current) {
    return undefined
  }

  return props.siblings.indexOf(props.current)
})

const nextSibling = computed(() => {
  let currentIndexValue = currentIndex.value
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

const changeSibling = (target?: string) => {
  if (target) {
    emit('changeSibling', target)
  }
}
</script>

<template>
  <p class="mb-4 mt-4 lead">
    <span>&nbsp;</span>
    <a v-if="canGoBack" href="#" @click.prevent="router.back()">
      <FontAwesomeIcon :icon="['far', 'chevron-double-left']" />
    </a>
    <span class="ms-5">
      <a v-if="previousSibling" href="#" @click.prevent="changeSibling(previousSibling)">
        <FontAwesomeIcon :icon="['far', 'chevron-left']" />
      </a>
      <span class="me-2" v-if="!previousSibling">&nbsp;</span>
    </span>
    <span class="ms-5">
      <a v-if="nextSibling" href="#" @click.prevent="changeSibling(nextSibling)">
        <FontAwesomeIcon :icon="['far', 'chevron-right']" />
      </a>
      <span class="me-2" v-if="!nextSibling">&nbsp;</span>
    </span>
    <span class="ms-5" v-if="currentIndex !== undefined && siblings"> {{ currentIndex + 1 }} / {{ siblings.length }} </span>
  </p>
</template>
