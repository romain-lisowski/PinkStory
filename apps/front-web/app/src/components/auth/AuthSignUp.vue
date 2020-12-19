<template>
  <form class="flex flex-col" @submit.prevent="processForm">
    <p class="font-bold text-4xl sm:text-5xl lg:text-6xl text-accent">
      {{ t('sign-up') }}
    </p>

    <input
      v-model="name"
      :placeholder="t('pseudo')"
      type="text"
      name="name"
      class="mt-5 p-4 text-primary rounded-md bg-primary bg-opacity-100 opacity-100"
    />
    <input
      v-model="email"
      :placeholder="t('email')"
      type="email"
      name="email"
      class="mt-5 p-4 text-primary rounded-md bg-primary bg-opacity-100 opacity-100"
    />
    <input
      v-model="password"
      :placeholder="t('password')"
      type="password"
      name="password"
      autocomplete="'autocomplete'"
      class="mt-5 p-4 text-primary rounded-md bg-primary bg-opacity-100 opacity-100"
    />
    <input
      v-model="passwordConfirm"
      :placeholder="t('confirm')"
      type="password"
      name="passwordConfirm"
      autocomplete="'autocomplete'"
      class="mt-5 p-4 text-primary rounded-md bg-primary bg-opacity-100 opacity-100"
    />

    <button
      class="mt-8 py-4 text-lg text-primary font-light tracking-wide bg-accent bg-opacity-100 rounded-lg"
      type="submit"
    >
      {{ t('submit') }}
    </button>
    <a
      class="block mt-8 text-xl hover:underline cursor-pointer"
      @click="onDisplayLogin"
    >
      {{ t('sign-in') }}
    </a>
  </form>
</template>

<script>
import ApiUsers from '@/api/ApiUsers'

export default {
  name: 'AuthSignUp',
  emits: ['display-login'],
  data() {
    return {
      name: '',
      email: '',
      password: '',
      passwordConfirm: '',
    }
  },
  methods: {
    processForm() {
      ApiUsers.signUp(
        this.name,
        this.email,
        this.password,
        this.passwordConfirm
      )
      this.onDisplayLogin()
    },
    onDisplayLogin() {
      this.$emit('display-login')
    },
  },
}
</script>

<!-- <i18n>
{
  "fr": {
    "sign-up": "Inscription",
    "pseudo": "Login",
    "email": "Email",
    "password": "Mot de passe",
    "confirm": "Confirmer votre mot de passe",
    "submit": "S'inscrire",
    "sign-in": "Déjà inscrit ?"
  }
}
</i18n> -->
