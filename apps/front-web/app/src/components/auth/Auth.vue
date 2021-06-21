<template>
  <div
    :class="!openAuthPanel ? '-translate-y-full' : ''"
    class="flex flex-row absolute justify-center items-center inset-0 w-full h-screen p-10 bg-primary bg-opacity-100 transform transition-transform duration-300 ease-in-out z-20"
    tabindex="0"
    @keyup.esc="onClickCloseAuthPanel"
  >
    <a
      class="absolute top-0 right-0 py-8 px-12 z-20 text-3xl cursor-pointer"
      @click="onClickCloseAuthPanel"
      ><ui-font-awesome-icon icon="times" class="h-8"
    /></a>

    <div
      :class="!showSignUp ? '' : 'translate-y-full'"
      class="flex items-center justify-center absolute w-full h-screen transform transition-transform duration-300 ease-out"
    >
      <AuthSignIn
        class="p-8 w-4/5 sm:w-2/3 lg:3/4 xl:w-1/3 bg-primary-inverse bg-opacity-5 rounded-xl"
        @show-sign-up="showSignUp = true"
      />
    </div>
    <div
      :class="showSignUp ? 'translate-y-full' : ''"
      class="flex items-center justify-center absolute bottom-100 w-full h-screen transform transition-transform duration-300 ease-out"
    >
      <AuthSignUp
        class="p-8 w-4/5 sm:w-2/3 lg:3/4 xl:w-1/3 bg-primary-inverse bg-opacity-5 rounded-xl"
        @show-sign-in="showSignUp = false"
      />
    </div>
  </div>
</template>

<script>
import AuthSignIn from '@/components/auth/AuthSignIn.vue'
import AuthSignUp from '@/components/auth/AuthSignUp.vue'
import { ref } from 'vue'

export default {
  components: {
    AuthSignIn,
    AuthSignUp,
  },
  props: {
    openAuthPanel: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['close-auth-panel'],
  setup(props, context) {
    const showSignUp = ref(false)

    const onClickCloseAuthPanel = () => {
      showSignUp.value = false
      context.emit('close-auth-panel')
    }

    return { showSignUp, onClickCloseAuthPanel }
  },
}
</script>
