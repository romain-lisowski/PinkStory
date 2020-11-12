<template>
  <div>
    <nav
      class="lg:hidden absolute right-100 h-screen w-full text-xl bg-primary overflow-y-scroll z-20 transform transition-transform duration-300 ease-in-out"
      :class="!openMenu ? '' : 'translate-x-full'"
    >
      <a class="block py-4 pr-8 text-right cursor-pointer" @click="toggleMenu"
        ><font-awesome-icon icon="times"
      /></a>

      <ul class="text-center font-bold">
        <li @click="toggleMenu">
          <router-link
            :to="{ name: 'Home' }"
            class="p-4 block"
            :class="
              currentPage === '/' ? activeMenuClasses : inactiveMenuClasses
            "
            >{{ $t('discover') }}</router-link
          >
        </li>
        <li @click="toggleMenu">
          <router-link
            :to="{ name: 'Search' }"
            class="p-4 block"
            :class="
              currentPage === '/search'
                ? activeMenuClasses
                : inactiveMenuClasses
            "
            >{{ $t('search') }}</router-link
          >
        </li>
        <li @click="toggleMenu">
          <router-link
            :to="{ name: 'Write' }"
            class="p-4 block"
            :class="
              currentPage === '/write' ? activeMenuClasses : inactiveMenuClasses
            "
            >{{ $t('write') }}</router-link
          >
        </li>
        <li v-show="isLoggedIn" @click="toggleMenu">
          <router-link
            :to="{ name: 'User' }"
            :class="
              currentPage === '/user' ? activeMenuClasses : inactiveMenuClasses
            "
            class="p-4 block"
          >
            {{ $t('settings') }}
          </router-link>
        </li>
        <li v-show="isLoggedIn">
          <a class="p-4 block cursor-pointer" @click="logout">
            {{ $t('logout') }}
          </a>
        </li>
      </ul>
    </nav>

    <header
      class="fixed top-0 w-full h-16 lg:h-20 px-2 sm:px-4 md:px-6 lg:px-8 xl:px-12 flex items-center justify-center bg-primary bg-opacity-75 border-b border-primary-inverse border-opacity-5 z-10"
    >
      <a
        class="ml-6 md:ml-0 lg:hidden text-2xl cursor-pointer"
        @click="toggleMenu"
      >
        <font-awesome-icon icon="bars"
      /></a>

      <router-link
        :to="{ name: 'Home' }"
        class="mx-auto lg:ml-0 flex-shrink-0 text-2xl lg:text-3xl xl:text-4xl font-bold text-accent hover:text-accent-highlight tracking-tighter"
      >
        PinkStory
      </router-link>

      <nav v-show="isLoggedIn" class="hidden lg:block">
        <ul class="flex items-center justify-center tracking-wide">
          <li>
            <router-link
              :to="{ name: 'Home' }"
              class="p-2 px-4 block font-bold rounded-lg cursor-pointer"
              :class="
                currentPage === '/' ? activeMenuClasses : inactiveMenuClasses
              "
              >{{ $t('discover') }}</router-link
            >
          </li>
          <li class="pl-2">
            <router-link
              class="p-2 px-4 block font-bold rounded-lg cursor-pointer"
              :class="
                currentPage.includes('/search')
                  ? activeMenuClasses
                  : inactiveMenuClasses
              "
              :to="{ name: 'Search' }"
              >{{ $t('search') }}</router-link
            >
          </li>
          <li class="pl-2">
            <router-link
              :to="{ name: 'Write' }"
              class="p-2 px-4 block font-bold rounded-lg cursor-pointer"
              :class="
                currentPage.includes('/write')
                  ? activeMenuClasses
                  : inactiveMenuClasses
              "
              >{{ $t('write') }}</router-link
            >
          </li>
          <li class="pl-2">
            <router-link
              :to="{ name: 'User' }"
              class="p-2 px-4 block font-bold rounded-lg cursor-pointer"
              :class="
                currentPage.includes('/user')
                  ? activeMenuClasses
                  : inactiveMenuClasses
              "
            >
              {{ $t('settings') }}
            </router-link>
          </li>
          <li class="pl-2">
            <a
              class="p-2 px-4 block font-bold rounded-lg cursor-pointer"
              @click="logout"
            >
              {{ $t('logout') }}
            </a>
          </li>
        </ul>
      </nav>

      <button
        v-if="isLoggedIn"
        class="relative group mr-6 md:mr-0 lg:ml-auto flex-shrink-0 flex items-center justify-center bg-opacity-100 border-opacity-50"
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
        @close-auth-panel="onCloseAuthPanel"
      />
    </header>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Auth from '@/components/auth/Auth.vue'

export default {
  name: 'LayoutHeader',
  components: {
    Auth,
  },
  data() {
    return {
      openMenu: false,
      openAuthPanel: false,
      activeMenuClasses: [
        'bg-primary-inverse',
        'bg-opacity-5',
        'text-primary',
        'hover:text-primary',
      ],
      inactiveMenuClasses: [
        'text-accent',
        'bg-opacity-100',
        'hover:bg-accent',
        'hover:text-primary-inverse',
      ],
    }
  },
  computed: {
    ...mapGetters(['isLoggedIn']),
    currentPage() {
      return this.$route.path
    },
  },
  watch: {
    isLoggedIn(value) {
      if (value && this.openAuthPanel === true) {
        this.onCloseAuthPanel()
      }
    },
  },
  methods: {
    logout() {
      this.openAuthPanel = false
      this.openMenu = false
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
    "search": "Rechercher",
    "write": "Ecrire une histoire",
    "settings": "Préférences",
    "logout": "Déconnexion"
  }
}
</i18n>
