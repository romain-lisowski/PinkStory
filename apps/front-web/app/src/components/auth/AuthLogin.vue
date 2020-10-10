<template>
  <form @submit.prevent="processForm">
    <h3 class="font-semibold text-2xl">
      {{ $t('login') }}
    </h3>
    <div class="flex justify-end m-5">
      <label class="flex flex-1">
        <input
          v-model="email"
          :placeholder="$t('email')"
          type="email"
          name="email"
          class="flex-1 p-2 border border-gray-400 rounded-md text-accent"
        />
      </label>
    </div>
    <div class="flex justify-end m-5">
      <label class="flex flex-1">
        <input
          v-model="password"
          :placeholder="$t('password')"
          type="password"
          name="password"
          :autocomplete="'off'"
          class="flex-1 p-2 border border-gray-400 rounded-md text-accent"
        />
      </label>
    </div>
    <button
      class="m-1 py-4 px-8 text-primary text-lg font-light bg-accent tracking-wide rounded-lg"
      type="submit"
    >
      {{ $t('connexion') }}
    </button>
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
  },
}
</script>

<i18n>
{
  "fr": {
    "login": "Login",
    "email": "Email",
    "password": "Mot de passe",
    "connexion": "Connexion"
  }
}
</i18n>
