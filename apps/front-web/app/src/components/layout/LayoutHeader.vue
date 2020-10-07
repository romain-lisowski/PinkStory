<template>
  <div>
    <nav
      class="main-menu transform transition-transform duration-300 ease-in-out z-20 lg:hidden absolute top-0 right-100 h-screen w-full overflow-y-scroll bg-primary text-xl"
      :class="!openMenu ? 'translate-x-full' : ''"
    >
      <a
        class="main-menu-close p-4 block text-right"
        href="#"
        @click="toggleMenu"
        ><font-awesome-icon icon="times"
      /></a>

      <ul class="text-center font-bold">
        <li>
          <span class="p-4 block bg-primary-inverse bg-opacity-5">{{
            $t('discover')
          }}</span>
        </li>
        <li>
          <a
            class="p-4 w-full block hover:bg-accent text-accent hover:text-primary-inverse"
            href="#"
            >{{ $t('categories') }}</a
          >
        </li>
        <li>
          <a
            class="p-4 w-full block hover:bg-accent text-accent hover:text-primary-inverse"
            href="#"
            >{{ $t('write') }}</a
          >
        </li>
      </ul>
    </nav>

    <header
      class="z-10 fixed top-0 w-full h-16 lg:h-20 px-2 sm:px-4 md:px-6 lg:px-8 xl:px-12 flex items-center justify-center bg-primary bg-opacity-75 border-b border-primary-inverse border-opacity-5"
    >
      <a
        class="main-menu-toggle lg:hidden text-2xl"
        href="#"
        @click="toggleMenu"
      >
        <font-awesome-icon icon="bars"
      /></a>

      <router-link
        :to="{ name: 'Home' }"
        class="mx-auto lg:ml-0 flex-shrink-0 text-2xl lg:text-3xl xl:text-4xl font-bold text-accent hover:text-accent-highlight tracking-tightest"
        href="#"
      >
        PinkStory
      </router-link>

      <nav v-if="loggedIn" class="hidden lg:block">
        <ul class="flex items-center justify-center tracking-wide">
          <li>
            <span
              class="p-2 px-4 block text-primary font-bold bg-opacity-5 rounded-lg bg-primary-inverse cursor-pointer"
              >{{ $t('discover') }}</span
            >
          </li>
          <li class="pl-2">
            <a
              class="p-2 px-4 block text-accent font-bold bg-opacity-100 hover:bg-accent rounded-lg hover:text-primary-inverse cursor-pointer"
              href="#category"
              >{{ $t('categories') }}</a
            >
          </li>
          <li class="pl-2">
            <a
              class="p-2 px-4 block text-accent font-bold bg-opacity-100 hover:bg-accent rounded-lg hover:text-primary-inverse cursor-pointer"
              href="#"
              >{{ $t('write') }}</a
            >
          </li>
        </ul>
      </nav>

      <button
        v-if="loggedIn"
        class="relative group ml-0 lg:ml-auto flex-shrink-0 flex items-center justify-center bg-opacity-100 border-opacity-50"
        @click="toggleUserMenu"
      >
        <span
          class="absolute top-0 left-0 px-1 md:px-2 bg-accent group-hover:bg-accent-highlight rounded-full leading-snug text-xxs md:text-xs text-primary-inverse font-bold"
          >12</span
        >
        <span
          class="p-1/2 md:p-1 group-hover:bg-accent border-2 border-accent group-hover:border-opacity-0 rounded-2xl md:rounded-3xl"
        >
          <img
            class="w-8 md:w-10 h-8 md:h-10 rounded-xl md:rounded-2xl"
            :src="require('@/assets/images/profil.jpg')"
          />
        </span>
      </button>

      <div v-if="loggedIn" class="flex flex-row">
        <div
          :class="openUserMenu ? 'block' : 'hidden'"
          class="absolute top-0 right-0 mt-20 px-10 py-4 tracking-wide text-sm bg-primary rounded-b-md"
        >
          <router-link
            :to="{ name: 'User' }"
            class="cursor-pointer pt-6 hover:underline"
          >
            {{ $t('settings') }}
          </router-link>
          <div class="cursor-pointer pt-6 hover:underline" @click="logout">
            {{ $t('logout') }}
          </div>
        </div>
      </div>

      <router-link
        v-else
        :to="{ name: 'Auth' }"
        class="p-3 cursor-pointer block mt-4 lg:inline-block lg:mt-0 mr-4 bg-accent rounded-lg"
      >
        {{ $t('connection') }}
      </router-link>
    </header>
  </div>
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
      console.log('toggl')
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
