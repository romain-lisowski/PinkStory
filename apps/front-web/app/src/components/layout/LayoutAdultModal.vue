<template>
  <div
    name="adult-modal"
    :class="openAdultModal ? 'fixed' : 'hidden'"
    class="flex justify-center items-center inset-0 fixed w-full h-full bg-primary bg-opacity-90 z-10"
  >
    <div
      class="flex flex-col fixed -mt-48 px-16 pt-8 pb-12 lg:px-20 lg:pt-12 lg:pb-16 w-4/5 sm:w-3/4 lg:2/3 xl:w-1/2 bg-primary bg-opacity-100 border border-primary-inverse rounded-xl"
    >
      <p
        class="text-3xl sm:text-5xl lg:text-7xl font-bold text-accent tracking-tighter"
      >
        PinkStory
      </p>
      <p class="mt-6 text-xl sm:text-2xl lg:text-4xl font-bold">
        {{ t('are-you-an-adult') }}
      </p>
      <p class="mt-4 text-sm sm:text-base lg:text-xl">
        {{ t('adult-speech') }}
      </p>
      <button
        class="block mt-8 sm:py-5 py-4 px-8 text-sm sm:text-lg lg:text-xl font-light tracking-wide bg-accent rounded-lg cursor-pointer"
        @click="onIsAdult"
      >
        {{ t('enter') }}
      </button>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useStore } from 'vuex'

export default {
  setup() {
    const store = useStore()
    const openAdultModal = ref(!store.state.isAdult)

    const onIsAdult = () => {
      store.dispatch('isAdult')
      openAdultModal.value = false
    }

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'are-you-an-adult': 'Avez-vous 18 ans ?',
          'adult-speech':
            'PinkStory est une communauté qui offre du contenu réservé aux adultes. Vous devez avoir 18 ans ou plus pour entrer',
          enter: "J'ai 18 ans ou plus - Entrer",
        },
      },
    })

    return { openAdultModal, onIsAdult, t }
  },
}
</script>
