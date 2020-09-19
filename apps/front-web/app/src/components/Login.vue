<template>
  <form @submit.prevent="processForm">
    <div>
      <h2>Login :</h2>
      <div>
        <label>
          Email :
          <input type="email" name="email" v-model="email" />
        </label>
      </div>
      <div>
        <label>
          Password :
          <input type="password" name="password" v-model="password" :autocomplete="'off'"/>
        </label>
      </div>
    </div>
    <button type="submit">Connexion</button>
  </form>
</template>

<script>
export default {
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
    }
  },
  methods: {
    processForm() {
      this.$store.dispatch('login', { email: this.email, password: this.password })
    },
  },
}
</script>
