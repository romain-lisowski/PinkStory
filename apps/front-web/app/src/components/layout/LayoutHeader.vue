<template>
  <div>
    <nav
      class="lg:hidden absolute h-screen w-full text-xl bg-primary overflow-y-scroll z-20 transform transition-transform duration-300 ease-in-out"
      :class="!openMenu ? 'translate-x-full' : ''"
    >
      <a class="block p-4 text-right" @click="toggleMenu"
        ><font-awesome-icon icon="times"
      /></a>

      <ul class="text-center font-bold">
        <li>
          <router-link
            :to="{ name: 'Home' }"
            class="p-4 block bg-primary-inverse bg-opacity-5"
            >{{ $t('discover') }}</router-link
          >
        </li>
        <li>
          <a
            class="p-4 w-full block hover:bg-accent text-accent hover:text-primary-inverse"
            >{{ $t('categories') }}</a
          >
        </li>
        <li>
          <a
            class="p-4 w-full block hover:bg-accent text-accent hover:text-primary-inverse"
            >{{ $t('write') }}</a
          >
        </li>
        <li v-if="loggedIn">
          <router-link
            :to="{ name: 'User' }"
            class="p-4 w-full block hover:bg-accent text-primary hover:text-primary-inverse"
          >
            {{ $t('settings') }}
          </router-link>
        </li>
        <li v-if="loggedIn">
          <a
            class="p-4 w-full block hover:bg-accent text-primary hover:text-primary-inverse"
            @click="logout"
          >
            {{ $t('logout') }}
          </a>
        </li>
      </ul>
    </nav>

    <header
      class="z-10 fixed top-0 w-full h-16 lg:h-20 px-2 sm:px-4 md:px-6 lg:px-8 xl:px-12 flex items-center justify-center bg-primary bg-opacity-75 border-b border-primary-inverse border-opacity-5"
    >
      <a
        class="ml-12 sm:ml-8 lg:hidden text-2xl cursor-pointer"
        @click="toggleMenu"
      >
        <font-awesome-icon icon="bars"
      /></a>

      <router-link
        :to="{ name: 'Home' }"
        class="mx-auto lg:ml-0 flex-shrink-0 text-2xl lg:text-3xl xl:text-4xl font-bold text-accent hover:text-accent-highlight tracking-tightest"
      >
        PinkStory
      </router-link>

      <nav v-if="loggedIn" class="hidden lg:block">
        <ul class="flex items-center justify-center tracking-wide">
          <li>
            <router-link
              :to="{ name: 'Home' }"
              class="p-2 px-4 block text-primary font-bold bg-opacity-5 rounded-lg bg-primary-inverse cursor-pointer"
              >{{ $t('discover') }}</router-link
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
              >{{ $t('write') }}</a
            >
          </li>
          <li v-if="loggedIn">
            <router-link
              :to="{ name: 'User' }"
              class="p-2 px-4 block text-accent font-bold bg-opacity-100 hover:bg-accent rounded-lg hover:text-primary-inverse cursor-pointer"
            >
              {{ $t('settings') }}
            </router-link>
          </li>
          <li v-if="loggedIn">
            <a
              class="p-2 px-4 block text-accent font-bold bg-opacity-100 hover:bg-accent rounded-lg hover:text-primary-inverse cursor-pointer"
              @click="logout"
            >
              {{ $t('logout') }}
            </a>
          </li>
        </ul>
      </nav>

      <button
        v-if="loggedIn"
        class="relative group ml-0 lg:ml-auto flex-shrink-0 flex items-center justify-center bg-opacity-100 border-opacity-50"
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

      <a
        v-else
        class="p-3 cursor-pointer block mt-4 lg:inline-block lg:mt-0 rounded-lg"
        @click="onOpenCloseAuth"
      >
        <font-awesome-icon
          icon="venus-mars"
          class="-mt-2 lg:mt-2 mr-8 sm:mr-6 lg:mr-0 text-4xl text-accent rounded-full"
        />
      </a>
      <Auth
        :open-auth-panel="openAuthPanel"
        @onCloseAuthPanel="onCloseAuthPanel"
      />
    </header>
  </div>
</template>

<script>
import Auth from '@/components/auth/Auth.vue'
import { Closable } from '../../directives/Closable'

export default {
  name: 'LayoutHeader',
  components: {
    Auth,
  },
  directives: {
    Closable,
  },
  data() {
    return {
      openMenu: false,
      openAuthPanel: false,
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
    loggedIn(loggedIn) {
      if (loggedIn && this.openAuthPanel === true) {
        this.onCloseAuthPanel()
      }
    },
  },
  methods: {
    logout() {
      this.openAuthPanel = false
      this.$store.dispatch('logout')
      if (this.$route.path !== '/') {
        this.$router.push({ name: 'Home' })
      }
    },
    toggleMenu() {
      this.openMenu = !this.openMenu
    },
    onOpenCloseAuth() {
      this.openAuthPanel = true
    },
    onCloseAuthPanel() {
      this.openAuthPanel = false
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
    "settings": "Préférences",
    "logout": "Déconnexion"
  }
}
</i18n>
