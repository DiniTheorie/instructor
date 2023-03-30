import { defineStore } from 'pinia'
import type { SupportedLanguage } from '@/components/domain/SupportedLanguage'

export type SettingsStoreState = {
  previewLanguage: SupportedLanguage
}

export const useSettingsStore = defineStore('settings', {
  state: (): SettingsStoreState => ({ previewLanguage: 'de' }),
  actions: {
    setPreviewLanguage(language: SupportedLanguage) {
      this.previewLanguage = language
    }
  }
})
