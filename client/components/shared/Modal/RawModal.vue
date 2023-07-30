<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

defineProps<{ title: string; size?: string }>()
const emit = defineEmits<{
  (e: 'hide'): void
}>()

const showContent = ref(true)
const alignRight = ref(false)
const lastMouseDownEvent = ref<MouseEvent>()

const mouseUpOutside = (event: MouseEvent) => {
  let lastEvent = lastMouseDownEvent.value
  if (!lastEvent) {
    emit('hide')
    return
  }

  const diffX = Math.abs(event.pageX - lastEvent.pageX)
  const diffY = Math.abs(event.pageY - lastEvent.pageY)

  if (diffX < 10 && diffY < 10) {
    emit('hide')
  }
}

const handleEsc = (event: KeyboardEvent) => {
  if (event.key === 'Escape') {
    emit('hide')
  }
}
onMounted(() => document.addEventListener('keyup', handleEsc))
onUnmounted(() => document.removeEventListener('keyup', handleEsc))
</script>

<template>
  <div class="modal-wrapper" @keydown.esc="emit('hide')">
    <div class="modal fade show d-block" :class="size ? 'modal-' + size : undefined" @mousedown="lastMouseDownEvent = $event" @mouseup.self="mouseUpOutside" tabindex="-1">
      <div class="modal-dialog" :class="{ 'me-4': alignRight }">
        <div class="modal-content">
          <div class="modal-header" :class="{ 'border-bottom-0': !showContent }">
            <h5 class="modal-title" id="modal-title">{{ title }}</h5>
            <div class="btn-group ms-auto">
              <button type="button" class="btn btn-outline-secondary" @click="showContent = !showContent">
                <FontAwesomeIcon :icon="['fal', 'eye']" v-if="showContent" />
                <FontAwesomeIcon :icon="['fal', 'eye-slash']" v-else />
              </button>
              <button type="button" class="btn btn-outline-secondary" @click="alignRight = true" v-if="!alignRight">
                <FontAwesomeIcon :icon="['fal', 'right']" />
              </button>
            </div>
            <button type="button" class="btn-close ms-2" @click="emit('hide')"></button>
          </div>
          <div class="modal-body" v-if="showContent">
            <slot name="body"></slot>
          </div>
          <div class="modal-footer" v-if="showContent">
            <slot name="footer"></slot>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-backdrop show" @click="emit('hide')"></div>
  </div>
</template>
