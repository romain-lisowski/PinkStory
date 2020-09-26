<template>
  <div>
    <h3 class="text-gray-700 font-semibold text-2xl mt-2">
      {{ $t('password')}}
    </h3>
    <form @submit.prevent="processForm">
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700">
          <input
            v-bind:placeholder="$t('current-password')"
            type="password"
            name="password-old"
            v-model="passwordOld"
            :autocomplete="'current-password'"
            class="flex-1 border rounded-md border-gray-400 placeholder-gray-600 p-2"
          />
       </label>
      </div>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700">
          <input
            v-bind:placeholder="$t('new-password')"
            type="password"
            name="password-new"
            v-model="passwordNew"
            :autocomplete="'new-password'"
            class="flex-1 border rounded-md border-gray-400 placeholder-gray-600 p-2"
          />
        </label>
      </div>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700">
          <input
            v-bind:placeholder="$t('confirm-new-password')"
            type="password"
            name="password-new-confirm"
            v-model="passwordNewConfirm"
            :autocomplete="'new-password'"
            class="flex-1 border rounded-md border-gray-400 placeholder-gray-600 p-2"
          />
        </label>
      </div>
      <button
        type="submit"
        class="m-5 bg-transparent hover:bg-pink-400 text-gray-700 hover:text-white py-2 px-4 border border-pink-400 hover:border-transparent rounded-md"
      >
        {{ $t('update')}}
      </button>
    </form>
  </div>
</template>

<script>
import ApiUsers from '@/api/ApiUsers'

export default {
  name: 'UserPassword',
  data() {
    return {
      passwordOld: '',
      passwordNew: '',
      passwordNewConfirm: '',
    }
  },
  methods: {
    processForm() {
      ApiUsers.updatePassword(
        this.$store.state.jwt,
        this.passwordOld,
        this.passwordNew,
        this.passwordNewConfirm,
      )
    },
  },
}
</script>

<i18n>
{
  "fr": {
    "password": "Mot de passe",
    "current-password": "Mot de passe actuel",
    "new-password": "Nouveau mot de passe",
    "confirm-new-password": "Confirmer nouveau mot de passe",
    "update": "Modifier"
  }
}
</i18n>
