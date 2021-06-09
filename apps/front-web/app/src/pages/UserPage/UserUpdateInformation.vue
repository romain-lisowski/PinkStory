<template>
  <div>
    <p class="font-bold text-2xl sm:text-3xl lg:text-4xl text-accent">
      {{ t('update-informations') }}
    </p>
    <form class="flex flex-col" @submit.prevent="processForm">
      <input
        v-model="name"
        placeholder="Pseudo"
        type="text"
        name="name"
        :autocomplete="'nickname'"
        class="my-5 p-3 rounded-md bg-primary bg-opacity-100 opacity-100"
      />
      <button
        class="mt-3 py-4 text-lg font-light tracking-wide text-primary bg-accent bg-opacity-100 rounded-lg"
        type="submit"
      >
        {{ t('update') }}
      </button>
    </form>
  </div>
</template>

<script>
import useApiUserUpdateInformation from '@/composition/api/user/useApiUserUpdateInformation'
import { useStore } from 'vuex'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

export default {
  setup() {
    const store = useStore()
    const name = ref(store.state.auth.state.userLoggedIn.name)
    const language = ref(store.state.auth.state.userLoggedIn.language)

    const processForm = async () => {
      const jwt = store.getters['auth/getJwt']
      await useApiUserUpdateInformation(store, {
        jwt,
        name: name.value,
        language: language.value,
      })
      store.dispatch('auth/fetchCurrentUser', jwt)
    }

    // TODO: Gender

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'update-informations': 'Informations',
          name: 'Pseudo',
          update: 'Modifier',
        },
      },
    })

    return { name, processForm, t }
  },
}
</script>
