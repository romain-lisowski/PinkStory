import { library } from '@fortawesome/fontawesome-svg-core'
import {
  faTimes,
  faBars,
  faVenusMars,
  faHeart,
  faChevronLeft,
  faChevronRight,
} from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import sanitizeHTML from 'sanitize-html'
import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './assets/css/style.css'
import i18n from './i18n'

library.add(faTimes)
library.add(faBars)
library.add(faVenusMars)
library.add(faHeart)
library.add(faChevronLeft)
library.add(faChevronRight)

Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.prototype.$sanitize = sanitizeHTML
Vue.config.productionTip = false

new Vue({
  router,
  store,
  i18n,
  render: (h) => h(App),
}).$mount('#app')
