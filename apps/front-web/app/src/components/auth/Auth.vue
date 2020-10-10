<template>
  <div
    :class="!openAuthPanel ? '-translate-y-full' : ''"
    class="flex flex-row absolute justify-center items-center inset-0 w-full h-screen p-10 bg-fixed bg-primary bg-cover bg-opacity-75 transform transition-transform duration-300 ease-in-out z-20"
    :style="{
      'background-image': `url(${require('@/assets/images/auth2.jpg')})`,
    }"
    @keyup.esc="onCloseAuthPanel"
  >
    <a
      class="absolute top-0 right-0 py-6 px-16 z-20 text-3xl cursor-pointer"
      @click="onCloseAuthPanel"
      ><font-awesome-icon icon="times"
    /></a>
    <div class="bg-radial-gradient-center absolute inset-0"></div>
    <div :class="!displaySignUp ? 'block' : 'hidden'" class="w-1/4 z-20">
      <AuthLogin class="-mt-48" />
      <a
        class="block mt-8 hover:underline cursor-pointer"
        @click="toggleSignUp"
      >
        {{ $t('sign-up') }}
      </a>
    </div>
    <div :class="displaySignUp ? 'block' : 'hidden'" class="w-1/4 z-20">
      <AuthSignUp class="-mt-48" />
      <a
        class="block mt-8 hover:underline cursor-pointer"
        @click="toggleSignUp"
      >
        {{ $t('sign-in') }}
      </a>
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
  methods: {
    onCloseAuthPanel() {
      this.openAuthPanel = false
      this.$emit('onCloseAuthPanel')
    },
    toggleSignUp() {
      this.displaySignUp = !this.displaySignUp
    },
  },
}
</script>

<i18n>
{
  "fr": {
    "sign-up": "Pas encore inscrit ?",
    "sign-in": "Déjà inscrit ?"
  }
}
</i18n>
