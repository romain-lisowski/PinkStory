<template>
  <header
    :class="openMenu ? 'bg-opacity-100': 'bg-opacity-90'"
    class="flex  items-center shadow-md justify-start flex-wrap bg-white dark:bg-psblackop90 px-4 py-2 sm:py-4 sticky top-0 z-20"
  >
    <div class="block lg:hidden">
      <button
        class="flex items-center px-5 py-4"
        @click="toggleMenu"
      >
        <svg
          class="fill-white dark:fill-psblack h-3 w-3"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg"
        ><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
      </button>
    </div>

    <div
      class="
      flex
      items-center
      flex-shrink-0
      mr-6
      font-extrabold
      text-4xl"
    >
      <router-link
        :to="{ name: 'Home' }"
        class="text-psred tracking-tight pl-6 sm:pl-10 text-3xl sm:text-4xl"
      >
        PinkStory
      </router-link>
    </div>

    <div
      v-closable="{handler: 'onCloseMenu'}"
      :class="openMenu ? 'block': 'hidden'"
      class="w-full block flex-grow lg:flex lg:items-center lg:w-auto"
    >
      <div class="text-xl text-psred lg:flex-grow tracking-wide font-ps">
        <nav v-if="loggedIn">
          <router-link
            :to="{ name: 'Home', hash: '#main' }"
            class="p-3 cursor-pointer block mt-4 lg:inline-block lg:mt-0 mr-4 border-b-2 border-psred"
          >
            {{ $t('discover') }}
          </router-link>
          <a
            class="p-3 cursor-pointer block mt-4 lg:inline-block lg:mt-0 mr-4"
            href="#category"
          >
            {{ $t('categories') }}
          </a>
          <div class="p-3 cursor-pointer block mt-4 lg:inline-block lg:mt-0 mr-4">
            {{ $t('write') }}
          </div>
          <router-link
            :to="{ name: 'User' }"
            class="p-3 cursor-pointer mt-4 block lg:mt-0 mr-4 lg:hidden"
          >
            {{ $t('settings') }}
          </router-link>
          <div
            class="p-3 cursor-pointer mt-4 block lg:mt-0 mr-4 lg:hidden"
            @click="logout"
          >
            {{ $t('logout') }}
          </div>
        </nav>
      </div>

      <div
        v-if="loggedIn"
        class="flex flex-row"
      >
        <div
          v-closable="{handler: 'onCloseUserMenu'}"
          class="bg-psred rounded-full h-16 w-16 items-center justify-center text-xl text-white dark:text-psblack
         font-pssemibold cursor-pointer mr-8 hidden lg:flex"
          @click="toggleUserMenu"
        >
          {{ userLoggedIn.name[0] }}
        </div>

        <div
          :class="openUserMenu ? 'block': 'hidden'"
          class="absolute top-0 right-0 mt-24 px-12 py-6 bg-white bg-psblackop90 tracking-wide shadow-xl text-psred text-lg"
        >
          <router-link
            :to="{ name: 'User' }"
            class="cursor-pointer hover:underline pt-6"
          >
            {{ $t('settings') }}
          </router-link>
          <div
            class="cursor-pointer hover:underline pt-6"
            @click="logout"
          >
            {{ $t('logout') }}
          </div>
        </div>
      </div>
      <div v-else>
        <router-link
          :to="{ name: 'Auth' }"
          class="p-3 cursor-pointer block mt-4 lg:inline-block lg:mt-0 mr-4 border border-psred rounded-lg text-psred"
        >
          {{ $t('connection') }}
        </router-link>
      </div>
    </div>
  </header>
</template>

<script>
import { Closable } from '../../directives/Closable'

export default {
  name: 'LayoutHeader',
  directives: {
    Closable,
  },
  data() {
    return {
      openMenu: false,
      openUserMenu: false,
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
  methods: {
    logout() {
      this.$store.dispatch('logout')
      this.$router.push({ name: 'Home' })
    },
    toggleMenu() {
      this.openMenu = !this.openMenu
    },
    toggleUserMenu() {
      this.openUserMenu = !this.openUserMenu
    },
    onCloseMenu() {
      this.openUserMenu = false
    },
    onCloseUserMenu() {
      this.openUserMenu = false
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
    "connection": "Connexion",
    "settings": "Préférences",
    "logout": "Déconnexion"
  }
}
</i18n>
