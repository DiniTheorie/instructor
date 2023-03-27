import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './assets/main.scss'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { createPinia } from 'pinia'
import { i18n } from '@/build/vue-i18n'
import './build/fontawesome'

const pinia = createPinia()

const app = createApp(App)

app.use(router)
app.use(i18n)
app.use(pinia)

app.component('FontAwesomeIcon', FontAwesomeIcon)

app.mount('#app')
