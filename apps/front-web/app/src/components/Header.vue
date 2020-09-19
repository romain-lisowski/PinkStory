<template>
  <div>
    <div v-if="loggedIn">
      Logged as : {{ userLoggedIn.email }}
      <button type="button" @click="logout">Logout</button>
      <nav>
        Nav :
        <router-link :to="{ name: 'Auth' }">Auth</router-link> |
        <router-link :to="{ name: 'User' }">Update profile</router-link>
      </nav>
    </div>
    <div v-else>
      Not connected
    </div>
    <h1>PinkStory</h1>
    <hr/>
  </div>
</template>

<script>
export default {
  name: 'Header',
  computed: {
    userLoggedIn() {
      return this.$store.state.user
    },
    loggedIn() {
      return this.userLoggedIn && this.$store.state.jwt
    },
  },
  methods: {
    logout() {
      this.$store.dispatch('logout')
      this.$router.push({ name: 'Auth' })
    },
  },
}
</script>

<style>

</style>
