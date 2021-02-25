import { watch } from 'vue'

export default (store, isLoading) => {
  watch(isLoading, (value) => {
    if (value) {
      store.dispatch('site/showLoadingOverlay')
    } else {
      store.dispatch('site/hideLoadingOverlay')
    }
  })

  return {}
}
