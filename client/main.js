import {createApp} from 'vue'
import App from './App.vue'
import router from './router'
import de from './locales/de.json'
import {library as FontawesomeLibrary} from '@fortawesome/fontawesome-svg-core'
import {faPencil, faPlus, faTrash,} from '@fortawesome/pro-light-svg-icons'
import './assets/main.scss'
import {createI18n} from "vue-i18n";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

FontawesomeLibrary.add(
  faPlus, faPencil, faTrash
)

// configure locale
const locale = document.documentElement.lang.substr(0, 2)
const i18n = createI18n({
  locale,
  fallbackLocale: 'de',
  messages: {
    de
  }
})

const app = createApp(App)

app.config.productionTip = false

app.use(router)
app.use(i18n)

app.component('FontAwesomeIcon', FontAwesomeIcon)

app.mount('#app')
