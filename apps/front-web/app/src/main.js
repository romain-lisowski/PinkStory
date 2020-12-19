import sanitizeHTML from 'sanitize-html'
import { createI18n } from 'vue-i18n'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './assets/css/style.css'

const i18n = createI18n({
  legacy: false,
  locale: 'fr',
  fallbackLocale: 'fr',
})

const app = createApp(App).use(router).use(store).use(i18n).mount('#app')
app.config.globalProperties.$sanitize = sanitizeHTML
