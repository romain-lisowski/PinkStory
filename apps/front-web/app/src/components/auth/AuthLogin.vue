<template>
  <form class="flex flex-col" @submit.prevent="processForm">
    <p class="font-bold text-4xl sm:text-5xl lg:text-6xl text-accent">
      {{ t('login') }}
    </p>

    <input
      v-model="email"
      :placeholder="t('email')"
      type="email"
      name="email"
      class="mt-5 p-4 text-primary bg-primary bg-opacity-100 opacity-100 rounded-md"
    />
    <input
      v-model="password"
      :placeholder="t('password')"
      type="password"
      name="password"
      :autocomplete="'off'"
      class="mt-5 p-4 text-primary bg-primary bg-opacity-100 opacity-100 rounded-md"
    />

    <button
      class="mt-8 py-4 text-primary text-lg font-light bg-accent bg-opacity-100 tracking-wide rounded-lg"
      type="submit"
    >
      {{ t('submit') }}
    </button>
    <a
      class="block mt-8 text-xl hover:underline cursor-pointer"
      @click="onDisplaySignUpBlock"
    >
      {{ t('sign-up') }}
    </a>
  </form>
</template>

<script>
import { ref } from 'vue'
import { useStore } from 'vuex'
import { useI18n } from 'vue-i18n'

export default {
  emits: ['display-sign-up-block'],
  setup(props, context) {
    const store = useStore()
    const email = ref(null)
    const password = ref(null)

    const processForm = () => {
      store.dispatch('login', {
        email: email.value,
        password: password.value,
      })
    }

    const onDisplaySignUpBlock = () => {
      context.emit('display-sign-up-block')
    }

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          login: 'Connexion',
          email: 'Email',
          password: 'Mot de passe',
          submit: "S'identifier",
          'sign-up': 'Pas encore inscrit ?',
        },
      },
    })

    return { email, password, processForm, onDisplaySignUpBlock, t }
  },
}
</script>
