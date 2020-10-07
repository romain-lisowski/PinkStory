<template>
  <form @submit.prevent="processForm">
    <div>
      <h3 class="font-semibold text-2xl mt-2">
        {{ $t('sign-up') }}
      </h3>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 font-normal">
          <input
            v-model="name"
            :placeholder="$t('pseudo')"
            type="text"
            name="name"
            class="flex-1 border rounded-md border-gray-400 p-2"
          />
        </label>
      </div>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 font-normal">
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
        <label class="flex flex-1 font-normal">
          <input
            v-model="password"
            :placeholder="$t('password')"
            type="password"
            name="password"
            autocomplete="'autocomplete'"
            class="flex-1 border rounded-md border-gray-400 p-2"
          />
        </label>
      </div>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 font-normal">
          <input
            v-model="passwordConfirm"
            :placeholder="$t('confirm')"
            type="password"
            name="passwordConfirm"
            autocomplete="'autocomplete'"
            class="flex-1 border rounded-md border-gray-400 p-2"
          />
        </label>
      </div>
    </div>
    <button
      class="bg-accent text-primary rounded-lg m-1 py-4 px-8 text-lg font-light tracking-wide"
      type="submit"
    >
      {{ $t('submit') }}
    </button>
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
    },
  },
}
</script>

<i18n>
{
  "fr": {
    "sign-up": "Enregistrement",
    "pseudo": "Login",
    "email": "Email",
    "password": "Mot de passe",
    "confirm": "Confirmer mot de passe",
    "submit": "Envoyer"
  }
}
</i18n>
