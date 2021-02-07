<template>
  <div>
    <p class="font-bold text-2xl sm:text-3xl lg:text-4xl text-accent">
      {{ t('update-password') }}
    </p>
    <form class="flex flex-col" @submit.prevent="processForm">
      <input
        v-model="passwordOld"
        :placeholder="t('current-password')"
        type="password"
        name="password-old"
        :autocomplete="'current-password'"
        class="mt-5 p-3 rounded-md bg-primary bg-opacity-100 opacity-100"
      />
      <input
        v-model="passwordNew"
        :placeholder="t('new-password')"
        type="password"
        name="password-new"
        :autocomplete="'new-password'"
        class="mt-5 p-3 rounded-md bg-primary bg-opacity-100 opacity-100"
      />
      <input
        v-model="passwordNewConfirm"
        :placeholder="t('confirm-new-password')"
        type="password"
        name="password-new-confirm"
        :autocomplete="'new-password'"
        class="mt-5 p-3 rounded-md bg-primary bg-opacity-100 opacity-100"
      />
      <button
        class="mt-8 py-4 text-lg font-light tracking-wide text-primary bg-accent bg-opacity-100 rounded-lg"
        type="submit"
      >
        {{ t('update') }}
      </button>
    </form>
  </div>
</template>

<script>
import ApiUsers from '@/api/ApiUsers'
import { useStore } from 'vuex'
import { useI18n } from 'vue-i18n'
import { ref } from 'vue'

export default {
  setup() {
    const store = useStore()
    const passwordOld = ref(null)
    const passwordNew = ref(null)
    const passwordNewConfirm = ref(null)

    const processForm = () => {
      ApiUsers.updatePassword(
        store.state.auth.state.jwt,
        passwordOld.value,
        passwordNew.value,
        passwordNewConfirm.value
      )
    }

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'update-password': 'Mot de passe',
          'current-password': 'Mot de passe actuel',
          'new-password': 'Nouveau mot de passe',
          'confirm-new-password': 'Confirmer nouveau mot de passe',
          update: 'Modifier',
        },
      },
    })

    return { passwordOld, passwordNew, passwordNewConfirm, processForm, t }
  },
}
</script>
