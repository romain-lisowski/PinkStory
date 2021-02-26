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
      ><font-awesome-icon icon="times" class="h-8"
    /></a>

    <div
      :class="!displaySignUp ? '' : 'translate-y-full'"
      class="flex items-center justify-center absolute w-full h-screen transform transition-transform duration-300 ease-out"
    >
      <AuthLogin
        class="p-8 w-4/5 sm:w-2/3 lg:3/4 xl:w-1/3 bg-primary-inverse bg-opacity-5 rounded-xl"
        @display-sign-up-block="displaySignUp = true"
      />
    </div>
    <div
      :class="displaySignUp ? 'translate-y-full' : ''"
      class="flex items-center justify-center absolute bottom-100 w-full h-screen transform transition-transform duration-300 ease-out"
    >
      <AuthSignUp
        class="p-8 w-4/5 sm:w-2/3 lg:3/4 xl:w-1/3 bg-primary-inverse bg-opacity-5 rounded-xl"
        @display-login-block="displaySignUp = false"
      />
    </div>
  </div>
</template>

<script>
import AuthLogin from '@/components/auth/AuthLogin.vue'
import AuthSignUp from '@/components/auth/AuthSignUp.vue'

export default {
  components: {
    AuthLogin,
    AuthSignUp,
  },
  props: {
    openAuthPanel: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['close-auth-panel'],
  data() {
    return {
      displaySignUp: false,
    }
  },
  methods: {
    onClickCloseAuthPanel() {
      this.displaySignUp = false
      this.$emit('close-auth-panel')
    },
  },
}
</script>
