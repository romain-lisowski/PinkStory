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
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useStore } from 'vuex'

export default {
  setup() {
    const store = useStore()
    const activeTheme = ref(store.state.theme)
    const activeThemeClasses = [
      'text-accent',
      'rounded-md',
      'bg-primary',
      'px-2',
      'py-1',
    ]

    const setThemeLight = () => {
      activeTheme.value = 'light'
      store.dispatch('updateTheme', { theme: 'light' })
      document.documentElement.classList.add('theme-light')
      document.documentElement.classList.remove('theme-dark')
    }

    const setThemeDark = () => {
      activeTheme.value = 'dark'
      store.dispatch('updateTheme', { theme: 'dark' })
      document.documentElement.classList.add('theme-dark')
      document.documentElement.classList.remove('theme-light')
    }

    const setThemeAuto = () => {
      if (
        window.matchMedia &&
        window.matchMedia('(prefers-color-scheme: dark)').matches
      ) {
        setThemeDark()
      } else {
        setThemeLight()
      }
      activeTheme.value = 'auto'
      store.dispatch('updateTheme', { theme: 'auto' })
    }

    const initTheme = () => {
      if (activeTheme.value === 'light') {
        setThemeLight()
      } else if (activeTheme.value === 'dark') {
        setThemeDark()
      } else {
        setThemeAuto()
      }
    }
    initTheme()

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          discover: 'Découvrir',
          categories: 'Catégories',
          write: 'Ecrire une histoire',
          authentification: 'Authentification',
          profile: 'Profile',
          theme: 'Thème',
          auto: 'Auto',
          light: 'Claire',
          dark: 'Sombre',
        },
      },
    })
    return {
      activeTheme,
      activeThemeClasses,
      setThemeLight,
      setThemeDark,
      setThemeAuto,
      t,
    }
  },
}
</script>
