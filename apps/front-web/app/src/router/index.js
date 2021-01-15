import { createRouter, createWebHistory } from 'vue-router'
import store from '@/store'
import HomePage from '@/pages/HomePage/HomePage.vue'
import UserPage from '@/pages/UserPage/UserPage.vue'
import StoryPage from '@/pages/StoryPage/StoryPage.vue'
import WritePage from '@/pages/WritePage/WritePage.vue'
import SearchPage from '@/pages/SearchPage/SearchPage.vue'
import NotFound from '@/pages/NotFoundPage/NotFoundPage.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomePage,
  },
  {
    path: '/story/:storyId',
    name: 'Story',
    component: StoryPage,
    props: true,
  },
  {
    path: '/search',
    name: 'Search',
    component: SearchPage,
  },
  {
    path: '/user',
    name: 'User',
    component: UserPage,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/write',
    name: 'Write',
    component: WritePage,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/:catchAll(.*)',
    component: NotFound,
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),

  // scroll on last position
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) {
      return { selector: to.hash }
    }
    if (savedPosition) {
      return savedPosition
    }
    return { x: 0, y: 0 }
  },

  routes,
})

router.beforeEach((to, from, next) => {
  // Check if route requires authentification
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!store.getters.isLoggedIn) {
      next({ name: 'Home' })
    } else {
      next()
    }
  } else {
    next()
  }
})

export default router
