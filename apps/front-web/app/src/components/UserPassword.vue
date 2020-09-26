<template>
  <div>
    <h3 class="text-psblack font-semibold text-2xl mt-2">
      {{ $t('password') }}
    </h3>
    <form @submit.prevent="processForm">
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-psblack">
          <input
            v-model="passwordOld"
            :placeholder="$t('current-password')"
            type="password"
            name="password-old"
            :autocomplete="'current-password'"
            class="flex-1 border rounded-md border-gray-400 placeholder-psblack p-2"
          >
        </label>
      </div>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-psblack">
          <input
            v-model="passwordNew"
            :placeholder="$t('new-password')"
            type="password"
            name="password-new"
            :autocomplete="'new-password'"
            class="flex-1 border rounded-md border-gray-400 placeholder-psblack p-2"
          >
        </label>
      </div>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-psblack">
          <input
            v-model="passwordNewConfirm"
            :placeholder="$t('confirm-new-password')"
            type="password"
            name="password-new-confirm"
            :autocomplete="'new-password'"
            class="flex-1 border rounded-md border-gray-400 placeholder-psblack p-2"
          >
        </label>
      </div>
      <button
        type="submit"
      >
        {{ $t('update') }}
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
