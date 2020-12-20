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
              currentPageUri === '/' ? activeMenuClasses : inactiveMenuClasses
            "
            >{{ t('discover') }}</router-link
          >
        </li>
        <li @click="toggleMenu">
          <router-link
            :to="{ name: 'Search' }"
            class="p-4 block"
            :class="
              currentPageUri === '/search'
                ? activeMenuClasses
                : inactiveMenuClasses
            "
            >{{ t('search') }}</router-link
          >
        </li>
        <li @click="toggleMenu">
          <router-link
            :to="{ name: 'Write' }"
            class="p-4 block"
            :class="
              currentPageUri === '/write'
                ? activeMenuClasses
                : inactiveMenuClasses
            "
            >{{ t('write') }}</router-link
          >
        </li>
        <li v-show="loggedIn" @click="toggleMenu">
          <router-link
            :to="{ name: 'User' }"
            :class="
              currentPageUri === '/user'
                ? activeMenuClasses
                : inactiveMenuClasses
            "
            class="p-4 block"
          >
            {{ t('settings') }}
          </router-link>
        </li>
        <li v-show="loggedIn">
          <a class="p-4 block cursor-pointer" @click="logout">
            {{ t('logout') }}
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

      <nav v-show="loggedIn" class="hidden lg:block">
        <ul class="flex items-center justify-center tracking-wide">
          <li>
            <router-link
              :to="{ name: 'Home' }"
              class="p-2 px-4 block font-bold rounded-lg cursor-pointer"
              :class="
                currentPageUri === '/' ? activeMenuClasses : inactiveMenuClasses
              "
              >{{ t('discover') }}</router-link
            >
          </li>
          <li class="pl-2">
            <router-link
              class="p-2 px-4 block font-bold rounded-lg cursor-pointer"
              :class="
                currentPageUri.includes('/search')
                  ? activeMenuClasses
                  : inactiveMenuClasses
              "
              :to="{ name: 'Search' }"
              >{{ t('search') }}</router-link
            >
          </li>
          <li class="pl-2">
            <router-link
              :to="{ name: 'Write' }"
              class="p-2 px-4 block font-bold rounded-lg cursor-pointer"
              :class="
                currentPageUri.includes('/write')
                  ? activeMenuClasses
                  : inactiveMenuClasses
              "
              >{{ t('write') }}</router-link
            >
          </li>
          <li class="pl-2">
            <router-link
              :to="{ name: 'User' }"
              class="p-2 px-4 block font-bold rounded-lg cursor-pointer"
              :class="
                currentPageUri.includes('/user')
                  ? activeMenuClasses
                  : inactiveMenuClasses
              "
            >
              {{ t('settings') }}
            </router-link>
          </li>
          <li class="pl-2">
            <a
              class="p-2 px-4 block font-bold rounded-lg cursor-pointer"
              @click="logout"
            >
              {{ t('logout') }}
            </a>
          </li>
        </ul>
      </nav>

      <button
        v-if="loggedIn"
        class="relative group mr-6 md:mr-0 lg:ml-auto flex-shrink-0 flex items-center justify-center bg-opacity-100 border-opacity-50"
      >
        <span
          class="absolute top-0 right-0 px-1 md:px-1 bg-primary-inverse group-hover:bg-accent-highlight rounded-full leading-snug text-xxs md:text-xs text-primary-inverse font-bold"
          >8</span
        >
        <span
          v-if="userProfilePicture"
          class="p-1/2 md:p-1 group-hover:bg-accent border-2 border-accent group-hover:border-opacity-0 rounded-2xl md:rounded-3xl"
        >
          <img
            class="w-8 md:w-12 h-8 md:h-12 rounded-xl md:rounded-2xl"
            :src="userProfilePicture"
          />
        </span>
        <span
          v-else
          class="w-8 md:w-12 h-8 md:h-12 flex items-center justify-center font-bold bg-accent bg-opacity-100 rounded-full"
          >{{ userName[0].toUpperCase() }}</span
        >
      </button>

      <a
        v-else
        class="p-3 cursor-pointer block mt-4 lg:inline-block lg:mt-0 rounded-lg"
        @click="onOpenAuthPanel"
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
import { reactive, computed, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useStore } from 'vuex'
import { useI18n } from 'vue-i18n'
import Auth from '@/components/auth/Auth.vue'

export default {
  components: {
    Auth,
  },
  // eslint-disable-next-line no-unused-vars
  setup(props, context) {
    const store = useStore()
    const route = useRoute()
    const router = useRouter()

    const data = reactive({
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
    })

    const loggedIn = computed(() => {
      return store.state.loggedIn
    })

    const userName = computed(() => {
      return store.state.userName
    })

    const userProfilePicture = computed(() => {
      return store.state.userProfilePicture
    })

    const currentPageUri = computed(() => {
      return route.path
    })

    watch(loggedIn, (value) => {
      if (value && data.openAuthPanel === true) {
        data.onCloseAuthPanel()
      }
    })

    const logout = () => {
      data.openAuthPanel = false
      data.openMenu = false
      store.dispatch('logout')
      if (route.path !== '/') {
        router.push({ name: 'Home' })
      }
    }

    const toggleMenu = () => {
      data.openMenu = !data.openMenu
    }

    const onOpenAuthPanel = () => {
      data.openAuthPanel = true
    }

    const onCloseAuthPanel = () => {
      data.openAuthPanel = false
    }

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          discover: 'Découvrir',
          search: 'Rechercher',
          write: 'Ecrire une histoire',
          settings: 'Préférences',
          logout: 'Déconnexion',
        },
      },
    })

    return {
      ...data,
      loggedIn,
      userName,
      userProfilePicture,
      currentPageUri,
      logout,
      toggleMenu,
      onOpenAuthPanel,
      onCloseAuthPanel,
      t,
    }
  },
  methods: {},
}
</script>
