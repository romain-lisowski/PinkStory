<template>
  <div>
    <h2 class="text-psblack my-5 font-bold text-3xl">
      {{ $t('authentification') }}
    </h2>
    <div class="flex flex-row justify-center ">
      <Login class="w-1/3 shadow-xs border rounded-md border-gray-400 mr-4 p-2" />
      <SignUp class="w-1/3 shadow-xs border rounded-md border-gray-400 ml-4 p-2" />
    </div>
  </div>
</template>

<script>
import Login from '@/components/Login.vue'
import SignUp from '@/components/SignUp.vue'

export default {
  name: 'Auth',
  components: {
    Login,
    SignUp,
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
