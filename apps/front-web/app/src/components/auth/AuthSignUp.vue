<template>
  <form @submit.prevent="processForm">
    <h3 class="font-bold text-4xl sm:text-5xl lg:text-6xl text-accent">
      {{ $t('sign-up') }}
    </h3>
    <label class="flex flex-1 justify-end m-5">
      <input
        v-model="name"
        :placeholder="$t('pseudo')"
        type="text"
        name="name"
        class="flex-1 p-2 text-primary-inverse border border-gray-400 rounded-md"
      />
    </label>
    <label class="flex flex-1 justify-end m-5">
      <input
        v-model="email"
        :placeholder="$t('email')"
        type="email"
        name="email"
        class="flex-1 p-2 text-primary-inverse border border-gray-400 rounded-md"
      />
    </label>
    <label class="flex flex-1 justify-end m-5">
      <input
        v-model="password"
        :placeholder="$t('password')"
        type="password"
        name="password"
        autocomplete="'autocomplete'"
        class="flex-1 p-2 text-primary-inverse border border-gray-400 rounded-md"
      />
    </label>
    <label class="flex flex-1 justify-end m-5">
      <input
        v-model="passwordConfirm"
        :placeholder="$t('confirm')"
        type="password"
        name="passwordConfirm"
        autocomplete="'autocomplete'"
        class="flex-1 p-2 text-primary-inverse border border-gray-400 rounded-md"
      />
    </label>
    <button
      class="m-1 py-4 px-8 text-lg text-primary font-light tracking-wide bg-accent rounded-lg"
      type="submit"
    >
      {{ $t('submit') }}
    </button>
    <a
      class="block mt-8 text-xl hover:underline cursor-pointer"
      @click="onDisplayLogin"
    >
      {{ $t('sign-in') }}
    </a>
  </form>
</template>

<script>
import ApiUsers from '@/api/ApiUsers'

export default {
  name: 'AuthSignUp',
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
      this.$emit('onDisplayLogin')
    },
  },
}
</script>

<i18n>
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
</i18n>
