<template>
  <div
    ref="auth"
    :class="!openAuthPanel ? '-translate-y-full' : ''"
    class="flex flex-row absolute justify-center items-center inset-0 w-full h-screen p-10 bg-fixed bg-primary bg-cover bg-opacity-75 transform transition-transform duration-300 ease-in-out z-20"
    :style="{
      'background-image': `url(${require('@/assets/images/auth2.jpg')})`,
    }"
    tabindex="0"
    @keyup.esc="onCloseAuthPanel"
  >
    <a
      class="absolute top-0 right-0 py-6 px-16 z-20 text-3xl cursor-pointer"
      @click="onCloseAuthPanel"
      ><font-awesome-icon icon="times"
    /></a>

    <div class="bg-radial-gradient-center absolute inset-0"></div>

    <div
      :class="!displaySignUp ? '' : 'translate-y-full'"
      class="flex items-center justify-center absolute w-full h-screen transform transition-transform duration-300 ease-out"
    >
      <AuthLogin
        class="-mt-48 p-8 w-4/5 sm:w-2/3 lg:3/4 xl:w-1/3 bg-primary rounded-xl"
        @display-sign-up="onDisplaySignUp"
      />
    </div>
    <div
      :class="displaySignUp ? 'translate-y-full' : ''"
      class="flex items-center justify-center absolute bottom-100 w-full h-screen transform transition-transform duration-300 ease-out"
    >
      <AuthSignUp
        class="-mt-48 p-8 w-4/5 sm:w-2/3 lg:3/4 xl:w-1/3 bg-primary rounded-xl"
        @display-login="onDisplayLogin"
      />
    </div>
  </div>
</template>

<script>
import AuthLogin from '@/components/auth/AuthLogin.vue'
import AuthSignUp from '@/components/auth/AuthSignUp.vue'

export default {
  name: 'Auth',
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
  data() {
    return {
      displaySignUp: false,
    }
  },
  watch: {
    openAuthPanel(value) {
      if (value === true) {
        this.$refs.auth.focus()
      }
    },
  },
  methods: {
    onCloseAuthPanel() {
      this.displaySignUp = false
      this.$emit('close-auth-panel')
    },
    onDisplaySignUp() {
      this.displaySignUp = true
    },
    onDisplayLogin() {
      this.displaySignUp = false
    },
  },
}
</script>
