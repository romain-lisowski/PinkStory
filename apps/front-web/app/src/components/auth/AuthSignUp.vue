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

    <FormErrorList v-if="showFormError" :form-violations="formViolations" />

    <button
      class="mt-8 py-4 text-lg text-primary font-light tracking-wide bg-accent bg-opacity-100 rounded-lg"
      :class="activeSubmit ? activeSubmitClasses : inactiveSubmitClasses"
      type="submit"
    >
      {{ t('submit') }}
    </button>

    <a
      class="block mt-8 text-xl hover:underline cursor-pointer"
      @click="showSignIn"
    >
      {{ t('sign-in') }}
    </a>
  </form>
</template>

<script>
import FormErrorList from '@/components/form/FormErrorList.vue'
import useApiUserSignUp from '@/composition/api/user/useApiUserSignUp'
import { ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import genderTypes from '@/enums/genderTypes'
import { useStore } from 'vuex'

export default {
  components: {
    FormErrorList,
  },
  emits: ['show-sign-in'],
  setup(props, context) {
    const store = useStore()
    const genders = genderTypes
    const name = ref(null)
    const gender = ref(genderTypes.UNDEFINED)
    const email = ref(null)
    const password = ref(null)
    const passwordConfirm = ref(null)
    const activeSubmit = ref(false)
    const showFormError = ref(false)
    const formViolations = ref([])

    const showSignIn = () => {
      context.emit('show-sign-in')
    }

    const processForm = async () => {
      const apiUserSignUpFetch = await useApiUserSignUp(store, {
        name: name.value,
        gender: gender.value,
        email: email.value,
        password: password.value,
      })

      if (apiUserSignUpFetch.ok.value) {
        showFormError.value = false
        showSignIn()
      } else {
        showFormError.value = true
        formViolations.value = apiUserSignUpFetch.error.value.formViolations
      }
    }

    watch(
      () => [password.value, passwordConfirm.value],
      () => {
        activeSubmit.value = password.value === passwordConfirm.value
      }
    )

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
      genders,
      activeSubmit,
      name,
      gender,
      email,
      password,
      passwordConfirm,
      showFormError,
      formViolations,
      activeSubmitClasses,
      inactiveSubmitClasses,
      processForm,
      showSignIn,
      t,
    }
  },
}
</script>
