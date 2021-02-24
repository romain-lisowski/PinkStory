import { watch } from 'vue'
import store from '@/store/index'

export default (isLoading) => {
  watch(isLoading, (value) => {
    if (value) {
      store.dispatch('site/showLoadingOverlay')
    } else {
      store.dispatch('site/hideLoadingOverlay')
    }
  })

  return {}
}
