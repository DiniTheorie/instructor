import { defineStore } from 'pinia'

export const useSettingsStore = defineStore('settings', {
  state: () => ({ translationLanguage: 'en' }),
  actions: {
    setTranslationLanguage(language: string) {
      this.translationLanguage = language
    }
  }
})
