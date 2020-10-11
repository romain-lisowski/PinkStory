<template>
  <footer
    class="bg-accent h-10 py-8 sm:py-10 flex flex-row items-center justify-between z-10"
  >
    <router-link
      :to="{ name: 'Home' }"
      class="text-primary font-extrabold text-2xl pl-12"
      >PinkStory</router-link
    >

    <div class="text-primary text-sm font-light pr-12">
      <span class="mx-1 text-sm font-normal">{{ $t('theme') }} : </span>
      <span
        class="mx-1 cursor-pointer transition-all duration-300 ease-in-out"
        :class="activeTheme === 'auto' ? activeThemeClasses : ''"
        @click="setThemeAuto"
        >{{ $t('auto') }}</span
      >
      |
      <span
        class="mx-1 cursor-pointer transition-all duration-300 ease-in-out"
        :class="activeTheme === 'light' ? activeThemeClasses : ''"
        @click="setThemeLight"
        >{{ $t('light') }}</span
      >
      |
      <span
        class="mx-1 cursor-pointer transition-all duration-300 ease-in-out"
        :class="activeTheme === 'dark' ? activeThemeClasses : ''"
        @click="setThemeDark"
        >{{ $t('dark') }}</span
      >
    </div>
  </footer>
</template>

<script>
export default {
  name: 'LayoutFooter',
  data() {
    return {
      activeTheme: this.$store.state.theme ? this.$store.state.theme : 'auto',
      activeThemeClasses: [
        'text-accent',
        'rounded-md',
        'bg-primary',
        'px-2',
        'py-1',
      ],
    }
  },
  computed: {
    userLoggedIn() {
      return this.$store.state.user
    },
    loggedIn() {
      return this.userLoggedIn && this.$store.state.jwt
    },
  },
  watch: {
    activeTheme(theme) {
      console.log(theme)
      this.$store.dispatch('changeTheme', { theme })
    },
  },
  created() {
    if (this.activeTheme === 'light') {
      this.setThemeLight()
    } else if (this.activeTheme === 'dark') {
      this.setThemeDark()
    } else {
      this.setThemeAuto()
    }
  },
  methods: {
    logout() {
      this.$store.dispatch('logout')
      this.$router.push({ name: 'Auth' })
    },
    setThemeLight() {
      this.activeTheme = 'light'
      document.documentElement.classList.add('theme-light')
      document.documentElement.classList.remove('theme-dark')
    },
    setThemeDark() {
      this.activeTheme = 'dark'
      document.documentElement.classList.add('theme-dark')
      document.documentElement.classList.remove('theme-light')
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
      this.activeTheme = 'auto'
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
