<template>
  <div
    v-show="show"
    class="w-full h-screen fixed bg-primary bg-opacity-100 flex items-center justify-center z-50"
  >
    <span class="text-accent text-5xl">{{ t('loading') }}</span>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useStore } from 'vuex'

export default {
  setup() {
    const store = useStore()

    const show = computed(() => {
      return store.getters['site/showLoadingOverlay']
    })

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          loading: 'Chargement...',
        },
      },
    })

    return { show, t }
  },
}
</script>
