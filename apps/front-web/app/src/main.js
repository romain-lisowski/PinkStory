import { UiFontAwesomeIcon } from '@/plugins/font-awesome'
import {
  FontAwesomeLayers,
  FontAwesomeLayersText,
} from '@fortawesome/vue-fontawesome'
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

createApp(App)
  .use(router)
  .use(store)
  .use(i18n)
  .component('ui-font-awesome-icon', UiFontAwesomeIcon)
  .component('font-awesome-layers', FontAwesomeLayers)
  .component('font-awesome-layers-text', FontAwesomeLayersText)
  .mount('#app')
