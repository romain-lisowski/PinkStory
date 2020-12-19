<template>
  <footer
    class="flex flex-row py-8 sm:py-10 h-10 bg-accent items-center justify-between"
  >
    <router-link
      :to="{ name: 'Home' }"
      class="text-primary font-extrabold text-lg sm:text-2xl pl-12 tracking-tighter"
      >PinkStory</router-link
    >

    <div class="pr-8 sm:pr-12 pt-2 sm:pt-0 text-primary text-xs font-light">
      <span class="mx-1 text-xs sm:text-sm sm:font-thin font-normal"
        >{{ t('theme') }} :
      </span>
      <span
        class="sm:mx-1 cursor-pointer transition-all duration-300 ease-in-out"
        :class="activeTheme === 'auto' ? activeThemeClasses : ''"
        @click="setThemeAuto"
        >{{ t('auto') }}</span
      >
      |
      <span
        class="sm:mx-1 cursor-pointer transition-all duration-300 ease-in-out"
        :class="activeTheme === 'light' ? activeThemeClasses : ''"
        @click="setThemeLight"
        >{{ t('light') }}</span
      >
      |
      <span
        class="sm:mx-1 cursor-pointer transition-all duration-300 ease-in-out"
        :class="activeTheme === 'dark' ? activeThemeClasses : ''"
        @click="setThemeDark"
        >{{ t('dark') }}</span
      >
    </div>
  </footer>
</template>

<script>
export default {
  name: 'LayoutFooter',
  data() {
    return {
      activeTheme: this.$store.state.theme,
      activeThemeClasses: [
        'text-accent',
        'rounded-md',
        'bg-primary',
        'px-2',
        'py-1',
      ],
    }
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
      this.$store.dispatch('updateTheme', { theme: 'light' })
      document.documentElement.classList.add('theme-light')
      document.documentElement.classList.remove('theme-dark')
    },
    setThemeDark() {
      this.activeTheme = 'dark'
      this.$store.dispatch('updateTheme', { theme: 'dark' })
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
      this.$store.dispatch('updateTheme', { theme: 'auto' })
    },
  },
}
</script>
<!-- <i18n>
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
</i18n> -->
