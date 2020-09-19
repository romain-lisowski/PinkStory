<template>
  <div>
    <h2>Your profile</h2>
    <UserEmail />
    <UserPassword />
    <UserInformation />
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
