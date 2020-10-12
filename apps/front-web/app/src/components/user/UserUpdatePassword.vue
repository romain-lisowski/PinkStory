<template>
  <div>
    <h3 class="font-bold text-2xl sm:text-3xl lg:text-4xl text-accent">
      {{ $t('update-password') }}
    </h3>
    <form class="flex flex-col" @submit.prevent="processForm">
      <input
        v-model="passwordOld"
        :placeholder="$t('current-password')"
        type="password"
        name="password-old"
        :autocomplete="'current-password'"
        class="mt-5 p-3 text-primary-inverse bg-primary-inverse rounded-md"
      />
      <input
        v-model="passwordNew"
        :placeholder="$t('new-password')"
        type="password"
        name="password-new"
        :autocomplete="'new-password'"
        class="mt-5 p-3 text-primary-inverse bg-primary-inverse ounded-md"
      />
      <input
        v-model="passwordNewConfirm"
        :placeholder="$t('confirm-new-password')"
        type="password"
        name="password-new-confirm"
        :autocomplete="'new-password'"
        class="mt-5 p-3 text-primary-inverse bg-primary-inverse ounded-md"
      />
      <button
        class="mt-8 py-4 text-lg font-light tracking-wide text-primary bg-accent rounded-lg"
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
  name: 'UserUpdatePassword',
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
        this.passwordNewConfirm
      )
    },
  },
}
</script>

<i18n>
{
  "fr": {
    "update-password": "Mot de passe",
    "current-password": "Mot de passe actuel",
    "new-password": "Nouveau mot de passe",
    "confirm-new-password": "Confirmer nouveau mot de passe",
    "update": "Modifier"
  }
}
</i18n>
