<template>
  <header
    class="flex  items-center shadow-md justify-between flex-wrap bg-white p-4 bg-opacity-75 sticky top-0"
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
        class="text-psred tracking-tight pl-8"
      >
        PinkStory
      </router-link>
    </div>

    <div class="block lg:hidden">
      <button class="flex items-center">
        <svg
          class="fill-current h-3 w-3"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg"
        ><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
      </button>
    </div>

    <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
      <div class="text-2xl text-psred lg:flex-grow tracking-wide">
        <nav v-if="loggedIn">
          <router-link
            :to="{ name: 'Home' }"
            class="p-3 cursor-pointer block mt-4 lg:inline-block lg:mt-0 mr-4 text-psblack bg-psgray rounded-lg"
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
            :to="{ name: 'Auth' }"
            class="p-3 cursor-pointer block mt-4 lg:inline-block lg:mt-0 mr-4"
          >
            {{ $t('authentification') }}
          </router-link>
          <router-link
            :to="{ name: 'User' }"
            class="p-3 cursor-pointer block mt-4 lg:inline-block lg:mt-0 mr-4"
          >
            {{ $t('profile') }}
          </router-link>
        </nav>
      </div>

      <div
        v-if="loggedIn"
        class="flex flex-row"
      >
        <button
          type="button"
          class="flex items-center justify-center"
          @click="logout"
        >
          {{ $t('logout') }}
        </button>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'LayoutHeader',
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
    "logout": "Deconnexion"
  }
}
</i18n>
