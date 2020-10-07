<template>
  <form @submit.prevent="processForm">
    <div>
      <h3 class="font-semibold text-2xl mt-2">
        {{ $t('login') }}
      </h3>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1">
          <input
            v-model="email"
            :placeholder="$t('email')"
            type="email"
            name="email"
            class="flex-1 border rounded-md border-gray-400 p-2"
          />
        </label>
      </div>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1">
          <input
            v-model="password"
            :placeholder="$t('password')"
            type="password"
            name="password"
            :autocomplete="'off'"
            class="flex-1 border rounded-md border-gray-400 p-2"
          />
        </label>
      </div>
    </div>
    <button
      class="bg-accent text-primary rounded-lg m-1 py-4 px-8 text-lg font-light tracking-wide"
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
