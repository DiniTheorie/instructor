<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { computed, ref, watch } from 'vue'

defineProps<{
  field: string
}>()

const emit = defineEmits<{
  (e: 'change', value?: File): void
}>()

const image = ref<File>()

const imageUrl = computed(() => (image.value ? URL.createObjectURL(image.value) : undefined))
watch(image, () => {
  emit('change', image.value)
})

const handleChange = (event: Event) => {
  let firstFile = (event.target as HTMLInputElement).files?.item(0)
  image.value = firstFile ?? undefined
}

const uniqueId = String(Math.random())
const { t } = useI18n()
</script>

<template>
  <div>
    <label class="form-label" :for="uniqueId">{{ t(field) }}</label>
    <input class="form-control" type="file" accept="image/*" :id="uniqueId" @change="handleChange" />
    <img class="img-fluid mt-2" v-if="imageUrl" :src="imageUrl" />
  </div>
</template>
