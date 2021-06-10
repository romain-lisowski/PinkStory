<template>
  <form class="flex flex-col" @submit.prevent="processForm">
    <p class="font-bold text-4xl sm:text-5xl lg:text-6xl text-accent">
      {{ t('sign-up') }}
    </p>

    <input
      v-model="name"
      :placeholder="t('name')"
      type="text"
      name="name"
      class="mt-5 p-4 text-primary rounded-md bg-primary bg-opacity-100 opacity-100"
    />

    <select
      v-model="gender"
      class="mt-5 p-4 text-primary rounded-md bg-primary bg-opacity-100 opacity-100"
    >
      <option v-for="(gender, index) in genders" :key="index" :value="gender">
        {{ t(gender) }}
      </option>
    </select>

    <input
      v-model="email"
      :placeholder="t('email')"
      type="email"
      name="email"
      class="mt-5 p-4 text-primary rounded-md bg-primary bg-opacity-100 opacity-100"
    />
    <input
      v-model="password"
      :placeholder="t('password')"
      type="password"
      name="password"
      autocomplete="'autocomplete'"
      class="mt-5 p-4 text-primary rounded-md bg-primary bg-opacity-100 opacity-100"
    />
    <input
      v-model="passwordConfirm"
      :placeholder="t('confirm')"
      type="password"
      name="passwordConfirm"
      autocomplete="'autocomplete'"
      class="mt-5 p-4 text-primary rounded-md bg-primary bg-opacity-100 opacity-100"
    />

    <ul
      v-if="formViolations"
      class="mt-5 p-4 text-primary bg-accent bg-opacity-100 rounded-lg"
    >
      <li v-for="formViolation in formViolations" :key="formViolation">
        {{
          formViolation.field.charAt(0).toUpperCase() +
          formViolation.field.slice(1)
        }}
        :
        {{ formViolation.message }}
      </li>
    </ul>

    <button
      class="mt-8 py-4 text-lg text-primary font-light tracking-wide bg-accent bg-opacity-100 rounded-lg"
      :class="activeSubmit ? activeSubmitClasses : inactiveSubmitClasses"
      type="submit"
    >
      {{ t('submit') }}
    </button>

    <a
      class="block mt-8 text-xl hover:underline cursor-pointer"
      @click="displayLoginBlock"
    >
      {{ t('sign-in') }}
    </a>
  </form>
</template>

<script>
import useApiUserSignUp from '@/composition/api/user/useApiUserSignUp'
import { reactive, toRefs, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import genderTypes from '@/enums/genderTypes'
import { useStore } from 'vuex'

export default {
  emits: ['display-login-block'],
  setup(props, context) {
    const store = useStore()

    const data = reactive({
      activeSubmit: false,
      genders: genderTypes,
      name: null,
      gender: genderTypes.UNDEFINED,
      email: null,
      password: null,
      passwordConfirm: null,
      formViolations: null,
    })

    const validatePasswordsMatching = () => {
      data.activeSubmit = data.password === data.passwordConfirm
    }

    watch(
      () => [data.password, data.passwordConfirm],
      () => {
        validatePasswordsMatching()
      }
    )

    const displayLoginBlock = () => {
      context.emit('display-login-block')
    }

    const processForm = async () => {
      const apiUserSignUpData = await useApiUserSignUp(store, {
        name: data.name,
        gender: data.gender,
        email: data.email,
        password: data.password,
      })

      if (apiUserSignUpData.ok.value) {
        displayLoginBlock()
      } else {
        data.formViolations = apiUserSignUpData.formViolations.value
        console.log(data.formViolations)
      }
    }

    const activeSubmitClasses = [
      'cursor-pointer',
      'opacity-100',
      'bg-opacity-100',
    ]

    const inactiveSubmitClasses = [
      'cursor-not-allowed',
      'opacity-50',
      'bg-opacity-50',
    ]

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'sign-up': 'Inscription',
          'sign-in': 'Déjà inscrit ?',
          name: 'Pseudo',
          gender: 'Genre',
          email: 'Email',
          password: 'Mot de passe',
          confirm: 'Confirmer votre mot de passe',
          submit: "S'inscrire",
          UNDEFINED: 'Non défini',
          MALE: 'Homme',
          FEMALE: 'Femme',
          OTHER: 'Autre',
        },
      },
    })

    return {
      ...toRefs(data),
      activeSubmitClasses,
      inactiveSubmitClasses,
      processForm,
      displayLoginBlock,
      t,
    }
  },
}
</script>
