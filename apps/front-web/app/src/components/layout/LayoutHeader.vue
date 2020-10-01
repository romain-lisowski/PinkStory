<template>
  <header
    :class="openMenu ? 'bg-opacity-100': 'bg-opacity-90'"
    class="flex  items-center shadow-md justify-between flex-wrap bg-white p-4 sticky top-0 z-20"
  >
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
        class="text-psred tracking-tight pl-10"
      >
        PinkStory
      </router-link>
    </div>

    <div class="block lg:hidden">
      <button
        class="flex items-center"
        @click="toggleMenu"
      >
        <svg
          class="fill-current h-3 w-3"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg"
        ><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
      </button>
    </div>

    <div
      :class="openMenu ? 'block': 'hidden'"
      class="w-full block flex-grow lg:flex lg:items-center lg:w-auto"
    >
      <div class="text-xl text-psred lg:flex-grow tracking-wide font-ps">
        <nav v-if="loggedIn">
          <router-link
            :to="{ name: 'Home' }"
            class="p-3 cursor-pointer block mt-4 lg:inline-block lg:mt-0 mr-4 rounded-lg border border-psred"
          >
            {{ $t('discover') }}
          </router-link>
          <div class="p-3 cursor-pointer block mt-4 lg:inline-block lg:mt-0 mr-4">
            {{ $t('categories') }}
          </div>
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
          class="bg-psred rounded-full h-16 w-16 items-center justify-center text-xl text-white
         font-pssemibold cursor-pointer mr-8 hidden lg:flex"
          @click="toggleUserMenu"
        >
          {{ userLoggedIn.name[0] }}
        </div>

        <div
          :class="openUserMenu ? 'block': 'hidden'"
          class="absolute top-0 right-0 mt-24 px-8 py-4 bg-white opacity-90 rounded-md shadow-xl text-psred text-lg"
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
export default {
  name: 'LayoutHeader',
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
    "logout": "Deconnexion"
  }
}
</i18n>
