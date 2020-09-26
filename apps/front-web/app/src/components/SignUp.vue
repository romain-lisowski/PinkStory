<template>
  <form @submit.prevent="processForm">
    <div>
      <h3 class="text-gray-700 font-semibold text-2xl mt-2">
        {{ $t('sign-up') }}
      </h3>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700 font-normal">
          <input
            v-bind:placeholder="$t('pseudo')"
            type="text"
            name="name"
            v-model="name"
            class="flex-1 border rounded-md placeholder-gray-600 border-gray-400 p-2"
          />
        </label>
      </div>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700 font-normal">
          <input
            v-bind:placeholder="$t('email')"
            type="email"
            name="email"
            v-model="email"
            class="flex-1 border rounded-md placeholder-gray-600 border-gray-400 p-2"
          />
        </label>
      </div>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700 font-normal">
          <input
            v-bind:placeholder="$t('password')"
            type="password"
            name="password"
            v-model="password" autocomplete="'autocomplete'"
            class="flex-1 border rounded-md placeholder-gray-600 border-gray-400 p-2"
          />
        </label>
      </div>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700 font-normal">
          <input
            v-bind:placeholder="$t('confirm')"
            type="password"
            name="passwordConfirm"
            v-model="passwordConfirm" autocomplete="'autocomplete'"
            class="flex-1 border rounded-md placeholder-gray-600 border-gray-400 p-2"
          />
        </label>
      </div>
    </div>
    <button
     type="submit"
     class="mt-2 mb-4 bg-transparent text-gray-700 hover:text-white py-2 px-4 border border-pink-400 hover:border-transparent hover:bg-pink-400 rounded-md"
    >
      {{ $t('submit') }}
    </button>
  </form>
</template>

<script>
import ApiUsers from '@/api/ApiUsers'

export default {
  name: 'SignUp',
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
      ApiUsers.signUp(this.name, this.email, this.password, this.passwordConfirm)
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
