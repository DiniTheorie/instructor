// i18n
import { createI18n } from 'vue-i18n'
import de from '@/locales/de.json'

// the docs do not show how to type this properly
const i18n = createI18n({
  locale: 'de_CH',
  fallbackLocale: 'de_CH',
  messages: {
    de_CH: de
  },
  legacy: false
})

export { i18n }
