<template>
  <div>
    <h2 class="text-gray-700 my-5 font-bold text-3xl">
      {{ $t('update-profile') }}
    </h2>
    <div class="flex flex-col items-center justify-center">
      <UserEmail class="w-1/3 shadow-xs border rounded-md border-gray-400 mb-4 p-2" />
      <UserPassword class="w-1/3 shadow-xs border rounded-md border-gray-400 mb-4 p-2" />
      <UserInformation class="w-1/3 shadow-xs border rounded-md border-gray-400 mb-4 p-2" />
    </div>
  </div>
</template>

<script>
import UserEmail from '@/components/UserEmail.vue'
import UserPassword from '@/components/UserPassword.vue'
import UserInformation from '@/components/UserInformation.vue'

export default {
  name: 'User',
  components: {
    UserEmail,
    UserPassword,
    UserInformation,
  },
}
</script>

<i18n>
{
  "fr": {
    "update-profile": "Modifier mon profil"
  }
}
</i18n>
