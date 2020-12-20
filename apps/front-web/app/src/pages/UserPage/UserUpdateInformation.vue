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
import ApiUsers from '@/api/ApiUsers'
import { useStore } from 'vuex'
import { reactive } from 'vue'
import { useI18n } from 'vue-i18n'

export default {
  setup() {
    const store = useStore()

    const data = reactive({
      name: store.state.user.name,
    })

    const processForm = async () => {
      await ApiUsers.updateInformation(this.$store.state.jwt, this.name)
      this.$store.dispatch('fetchCurrentUser')
    }

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'update-informations': 'Informations',
          pseudo: 'Pseudo',
          update: 'Modifier',
        },
      },
    })

    return { data, processForm, t }
  },
}
</script>
