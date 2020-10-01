<template>
  <footer class="bg-psred h-10 py-10 flex flex-row items-center justify-between">
    <div class="text-white font-extrabold text-xl pl-12">
      PinkStory
    </div>
    <div class="text-white text-sm font-light pr-12">
      <span class="mx-1 text-sm font-normal">Thème : </span>
      <span class="mx-1 text-psred rounded-md bg-white px-2 py-1 font-normal">Auto</span>
      /
      <span class="mx-1">Light</span>
      /
      <span class="mx-1">Dark</span>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'LayoutFooter',
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
