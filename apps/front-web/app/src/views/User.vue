<template>
  <div>
    <h2 class="my-5 font-bold text-3xl">
      {{ $t('update-profile') }}
    </h2>
    <div class="flex flex-col items-center justify-center">
      <UserUpdateEmail
        class="w-1/3 shadow-xs border rounded-md border-gray-400 mb-4 p-2"
      />
      <UserUpdatePassword
        class="w-1/3 shadow-xs border rounded-md border-gray-400 mb-4 p-2"
      />
      <UserUpdateInformation
        class="w-1/3 shadow-xs border rounded-md border-gray-400 mb-4 p-2"
      />
    </div>
  </div>
</template>

<script>
import UserUpdateEmail from '@/components/user/UserUpdateEmail.vue'
import UserUpdatePassword from '@/components/user/UserUpdatePassword.vue'
import UserUpdateInformation from '@/components/user/UserUpdateInformation.vue'

export default {
  name: 'User',
  components: {
    UserUpdateEmail,
    UserUpdatePassword,
    UserUpdateInformation,
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
