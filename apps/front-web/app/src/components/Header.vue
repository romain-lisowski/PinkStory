<template>
  <nav class="flex shadow-xs items-center justify-between flex-wrap bg-white p-3">

    <div class="flex items-center flex-shrink-0 mr-6 font-bold text-3xl">
      <span class="text-gray-700 tracking-tight mr-1">Pink</span>
      <span class="border rounded-lg bg-pink-400 border-pink-400 px-1 text-white">Story</span>
    </div>

    <div class="block lg:hidden">
      <button class="flex items-center px-3 py-2 border rounded text-gray-700 border-white hover:text-white hover:border-white">
        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
      </button>
    </div>

    <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
      <div class="text-lg lg:flex-grow">
        <div v-if="loggedIn">
          <div class="p-3 cursor-pointer hover:underline block mt-4 lg:inline-block lg:mt-0 text-gray-700 mr-4 line-through">
            {{ $t('top-rated') }}
          </div>
          <div class="p-3 cursor-pointer hover:underline block mt-4 lg:inline-block lg:mt-0 text-gray-700 mr-4 line-through">
            {{ $t('last-stories') }}
          </div>
          <div class="p-3 cursor-pointer hover:underline block mt-4 lg:inline-block lg:mt-0 text-gray-700 mr-4 line-through">
            {{ $t('categories') }}
          </div>
          <div class="p-3 cursor-pointer hover:underline block mt-4 lg:inline-block lg:mt-0 text-gray-700 mr-4 line-through">
            {{ $t('authors') }}
          </div>
          <router-link
            :to="{ name: 'Auth' }"
            class="p-3 cursor-pointer hover:underline block mt-4 lg:inline-block lg:mt-0 text-gray-700 mr-4"
          >
            {{ $t('authentification') }}
          </router-link>
          <router-link
            :to="{ name: 'User' }"
            class="p-3 cursor-pointer hover:underline block mt-4 lg:inline-block lg:mt-0 text-gray-700 mr-4"
          >
            {{ $t('profile') }}
          </router-link>
        </div>
      </div>

      <div v-if="loggedIn" class="flex flex-row">
          <span class="text-gray-700 mr-4 rounded-full border border-gray-500 h-12 w-12 flex items-center justify-center">
            {{ userLoggedIn.name[0] }}
          </span>
          <button
            type="button"
            @click="logout"
            class="flex items-center justify-center text-sm px-4 py-3 leading-none border rounded-md text-gray-700 border-pink-400 hover:border-transparent hover:text-white hover:bg-pink-400 mt-4 lg:mt-0"
          >
            {{ $t('logout') }}
          </button>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Header',
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
    "top-rated": "Mieux notées",
    "last-stories": "Dernières histoires",
    "categories": "Catégories",
    "authors": "Auteurs",
    "authentification": "Authentification",
    "profile": "Profile",
    "logout": "Deconnexion"
  }
}
</i18n>
