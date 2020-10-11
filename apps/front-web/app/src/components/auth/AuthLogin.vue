<template>
  <form @submit.prevent="processForm">
    <h3 class="font-bold text-4xl sm:text-5xl lg:text-6xl text-accent">
      {{ $t('login') }}
    </h3>
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
        :autocomplete="'off'"
        class="flex-1 p-2 text-primary-inverse border border-gray-400 rounded-md"
      />
    </label>
    <button
      class="m-1 py-4 px-8 text-primary text-lg font-light bg-accent tracking-wide rounded-lg"
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
