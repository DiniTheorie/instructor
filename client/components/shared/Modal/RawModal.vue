<script setup lang="ts">
import { ref } from 'vue'

defineProps<{ title: string }>()
const emit = defineEmits<{
  (e: 'hide'): void
}>()

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
</script>

<template>
  <div class="modal-wrapper">
    <div class="modal fade show d-block" @mousedown="lastMouseDownEvent = $event" @mouseup.self="mouseUpOutside" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal-title">{{ title }}</h5>
            <button type="button" class="btn-close" @click="emit('hide')"></button>
          </div>
          <div class="modal-body">
            <slot name="body"></slot>
          </div>
          <div class="modal-footer">
            <slot name="footer"></slot>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-backdrop show" @click="emit('hide')"></div>
  </div>
</template>
