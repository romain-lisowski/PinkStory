<template>
  <footer
    class="bg-secondary h-10 py-8 sm:py-10 flex flex-row items-center justify-between"
  >
    <div
      class="text-primary dark:text-psblack-lighter font-extrabold text-xl pl-12"
    >
      PinkStory
    </div>
    <div class="text-primary text-sm font-light pr-12">
      <span class="mx-1 text-sm font-normal">{{ $t('theme') }} : </span>
      <span
        class="mx-1 text-secondary rounded-md bg-primary px-2 py-1 font-normal cursor-pointer"
        @click="setThemeAuto"
        >{{ $t('auto') }}</span
      >
      /
      <span class="mx-1 cursor-pointer" @click="setThemeLight">{{
        $t('light')
      }}</span>
      /
      <span class="mx-1 cursor-pointer" @click="setThemeDark">{{
        $t('dark')
      }}</span>
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
    setThemeLight() {
      document.documentElement.classList.remove('theme-dark')
    },
    setThemeDark() {
      document.documentElement.classList.add('theme-dark')
    },
    setThemeAuto() {
      if (
        window.matchMedia &&
        window.matchMedia('(prefers-color-scheme: dark)').matches
      ) {
        this.setThemeDark()
      } else {
        this.setThemeLight()
      }
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
    "auto": "Auto",
    "light": "Claire",
    "dark": "Sombre"
  }
}
</i18n>
