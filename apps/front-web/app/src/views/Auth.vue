<template>
  <div class="auth">
    <h2>Authentification</h2>
    <Header />
    <Login />
    <SignUp />
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
