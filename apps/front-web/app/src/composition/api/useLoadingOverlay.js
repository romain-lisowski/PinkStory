import { watch } from 'vue'

export default (store, loading) => {
  watch(loading, (value) => {
    if (value) {
      store.dispatch('site/showLoadingOverlay')
    } else {
      store.dispatch('site/hideLoadingOverlay')
    }
  })

  return {}
}
