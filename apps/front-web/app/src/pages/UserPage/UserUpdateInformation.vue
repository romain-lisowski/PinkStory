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
        class="mt-5 p-4 rounded-md bg-primary bg-opacity-100 opacity-100"
      />
      <select
        v-model="gender"
        class="mt-5 p-4 text-primary rounded-md bg-primary bg-opacity-100 opacity-100"
      >
        <option
          v-for="(gender, index) in genderTypes"
          :key="index"
          :value="gender"
        >
          {{ t(gender) }}
        </option>
      </select>
      <select
        v-model="language"
        class="mt-5 p-4 text-primary rounded-md bg-primary bg-opacity-100 opacity-100"
      >
        <option
          v-for="language in languages"
          :key="language.id"
          :value="language"
        >
          {{ language.title }}
        </option>
      </select>
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
import useApiUserUpdateInformation from '@/composition/api/user/useApiUserUpdateInformation'
import useApiLanguageSearch from '@/composition/api/language/useApiLanguageSearch'
import genderTypes from '@/enums/genderTypes'
import { useStore } from 'vuex'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

export default {
  async setup() {
    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'update-informations': 'Informations',
          name: 'Pseudo',
          update: 'Modifier',
          gender: 'Genre',
          UNDEFINED: 'Non défini',
          MALE: 'Homme',
          FEMALE: 'Femme',
          OTHER: 'Autre',
        },
      },
    })

    const store = useStore()
    const name = ref(store.state.auth.state.currentUser.name)
    const gender = ref(genderTypes.UNDEFINED)
    const language = ref(store.state.auth.state.currentUser.language)
    const readingLanguages = ref(
      store.state.auth.state.currentUser.reading_languages
    )

    let languages = null
    const apiLanguageSearchFetch = await useApiLanguageSearch(store)
    if (apiLanguageSearchFetch.ok.value) {
      languages = apiLanguageSearchFetch.response.value.languages
    }

    const processForm = async () => {
      const jwt = store.getters['auth/getJwt']
      await useApiUserUpdateInformation(store, {
        jwt,
        name: name.value,
        language: language.value,
      })
      store.dispatch('auth/signIn', jwt)
    }

    return {
      name,
      gender,
      genderTypes,
      language,
      languages,
      readingLanguages,
      processForm,
      t,
    }
  },
}
</script>
