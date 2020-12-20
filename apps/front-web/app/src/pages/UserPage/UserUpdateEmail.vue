<template>
  <div>
    <p class="font-bold text-2xl sm:text-3xl lg:text-4xl text-accent">
      {{ t('update-email') }}
    </p>
    <form class="flex flex-col" @submit.prevent="processForm">
      <input
        v-model="email"
        :placeholder="t('new-email')"
        type="email"
        name="email"
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
import { reactive } from 'vue'
import { useI18n } from 'vue-i18n'
import { useStore } from 'vuex'

export default {
  setup() {
    const store = useStore()

    const data = reactive({
      email: store.state.user.email,
    })

    const processForm = async () => {
      await ApiUsers.updateEmail(store.state.jwt, data.email)
      store.dispatch('fetchCurrentUser')
    }

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'update-email': 'Email',
          'new-email': 'Nouvel email',
          update: 'Modifier',
        },
      },
    })

    return { ...data, processForm, t }
  },
}
</script>
