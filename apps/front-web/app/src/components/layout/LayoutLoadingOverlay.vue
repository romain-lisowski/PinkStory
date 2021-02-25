<template>
  <div
    v-show="show"
    class="w-full h-screen fixed bg-primary bg-opacity-100 flex items-center justify-center z-50"
  >
    <svg
      viewBox="0 0 120 30"
      xmlns="http://www.w3.org/2000/svg"
      :fill="svgAttrs.color"
      :width="svgAttrs.width"
      :height="svgAttrs.height"
    >
      <circle cx="15" cy="15" r="15">
        <animate
          attributeName="r"
          from="15"
          to="15"
          begin="0s"
          dur="0.8s"
          values="15;9;15"
          calcMode="linear"
          repeatCount="indefinite"
        />
        <animate
          attributeName="fill-opacity"
          from="1"
          to="1"
          begin="0s"
          dur="0.8s"
          values="1;.5;1"
          calcMode="linear"
          repeatCount="indefinite"
        />
      </circle>
      <circle cx="60" cy="15" r="9" fill-opacity="0.3">
        <animate
          attributeName="r"
          from="9"
          to="9"
          begin="0s"
          dur="0.8s"
          values="9;15;9"
          calcMode="linear"
          repeatCount="indefinite"
        />
        <animate
          attributeName="fill-opacity"
          from="0.5"
          to="0.5"
          begin="0s"
          dur="0.8s"
          values=".5;1;.5"
          calcMode="linear"
          repeatCount="indefinite"
        />
      </circle>
      <circle cx="105" cy="15" r="15">
        <animate
          attributeName="r"
          from="15"
          to="15"
          begin="0s"
          dur="0.8s"
          values="15;9;15"
          calcMode="linear"
          repeatCount="indefinite"
        />
        <animate
          attributeName="fill-opacity"
          from="1"
          to="1"
          begin="0s"
          dur="0.8s"
          values="1;.5;1"
          calcMode="linear"
          repeatCount="indefinite"
        />
      </circle>
    </svg>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useStore } from 'vuex'

export default {
  setup() {
    const store = useStore()
    const svgAttrs = {
      height: 240,
      width: 60,
      color: '#e66872',
    }

    const show = computed(() => {
      return store.getters['site/showingLoadingOverlay']
    })

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          loading: 'Chargement...',
        },
      },
    })

    return { show, svgAttrs, t }
  },
}
</script>
