<template>
  <div>
    <h2 class="my-5 font-bold text-3xl">
      {{ $t('authentification') }}
    </h2>
    <div class="flex flex-row justify-center">
      <AuthLogin
        class="w-1/3 shadow-xs border rounded-md border-gray-400 mr-4 p-2"
      />
      <AuthSignUp
        class="w-1/3 shadow-xs border rounded-md border-gray-400 ml-4 p-2"
      />
    </div>
  </div>
</template>

<script>
import AuthLogin from '@/components/auth/AuthLogin.vue'
import AuthSignUp from '@/components/auth/AuthSignUp.vue'

export default {
  name: 'Auth',
  components: {
    AuthLogin,
    AuthSignUp,
  },
}
</script>

<i18n>
{
  "fr": {
    "authentification": "Authentification"
  }
}
</i18n>
