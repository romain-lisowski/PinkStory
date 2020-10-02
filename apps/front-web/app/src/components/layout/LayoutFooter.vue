<template>
  <footer class="bg-psred h-10 py-8 sm:py-10 flex flex-row items-center justify-between">
    <div class="text-white dark:text-psblack-lighter font-extrabold text-xl pl-12">
      PinkStory
    </div>
    <div class="text-white dark:text-psblack-lighter text-sm font-light pr-12">
      <span class="mx-1 text-sm font-normal">{{ $t('theme') }} : </span>
      <span class="mx-1 text-psred rounded-md bg-white dark:bg-psblack-lighter px-2 py-1 font-normal">{{ $t('auto') }}</span>
      /
      <span class="mx-1">{{ $t('light') }}</span>
      /
      <span class="mx-1">{{ $t('sombre') }}</span>
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
    "logout": "Deconnexion",
    "theme": "Thème",
    "light": "Claire",
    "dark": "Sombre"
  }
}
</i18n>
