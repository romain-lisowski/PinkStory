<template>
  <footer class="bg-psred h-10 mt-8  py-10 flex flex-row items-center justify-between">
    <div class="text-white font-extrabold text-xl pl-12">
      PinkStory
    </div>
    <div class="text-white text-xs font-light pr-12">
      <span class="mr-2 text-psred rounded-md bg-white px-2  font-normal">Auto</span>
      <span class="mr-2">Light</span>
      <span class="mr-2">Dark</span>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'Footer',
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

<i18n>
{
  "fr": {
    "discover": "Découvrir",
    "categories": "Catégories",
    "write": "Ecrire une histoire",
    "authentification": "Authentification",
    "profile": "Profile",
    "logout": "Deconnexion"
  }
}
</i18n>
