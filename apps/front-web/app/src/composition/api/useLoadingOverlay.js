import { watch } from 'vue'
import { useStore } from 'vuex'

export default (isLoading) => {
  const store = useStore()

  watch(isLoading, (value) => {
    if (value) {
      store.dispatch('site/showLoadingOverlay')
    } else {
      store.dispatch('site/hideLoadingOverlay')
    }
  })

  return {}
}
