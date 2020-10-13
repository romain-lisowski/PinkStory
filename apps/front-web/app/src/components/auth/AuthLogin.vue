<template>
  <form class="flex flex-col" @submit.prevent="processForm">
    <p class="font-bold text-4xl sm:text-5xl lg:text-6xl text-accent">
      {{ $t('login') }}
    </p>

    <input
      v-model="email"
      :placeholder="$t('email')"
      type="email"
      name="email"
      class="mt-5 p-2 text-primary-inverse rounded-md"
    />
    <input
      v-model="password"
      :placeholder="$t('password')"
      type="password"
      name="password"
      :autocomplete="'off'"
      class="mt-5 p-2 text-primary-inverse rounded-md"
    />

    <button
      class="mt-8 py-4 text-primary text-lg font-light bg-accent tracking-wide rounded-lg"
      type="submit"
    >
      {{ $t('submit') }}
    </button>
    <a
      class="block mt-8 text-xl hover:underline cursor-pointer"
      @click="onDisplaySignUp"
    >
      {{ $t('sign-up') }}
    </a>
  </form>
</template>

<script>
export default {
  name: 'AuthLogin',
  data() {
    return {
      email: '',
      password: '',
    }
  },
  methods: {
    processForm() {
      this.$store.dispatch('login', {
        email: this.email,
        password: this.password,
      })
    },
    onDisplaySignUp() {
      this.$emit('onDisplaySignUp')
    },
  },
}
</script>

<i18n>
{
  "fr": {
    "login": "Connexion",
    "email": "Email",
    "password": "Mot de passe",
    "submit": "S'identifier",
    "sign-up": "Pas encore inscrit ?"
  }
}
</i18n>
