import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store'
import HomePage from '@/pages/HomePage/HomePage.vue'
import UserPage from '@/pages/UserPage/UserPage.vue'
import StoryPage from '@/pages/StoryPage/StoryPage.vue'
import WritePage from '@/pages/WritePage/WritePage.vue'
import SearchPage from '@/pages/SearchPage/SearchPage.vue'
import NotFound from '@/pages/NotFoundPage/NotFoundPage.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomePage,
  },
  {
    path: '/story',
    name: 'Story',
    component: StoryPage,
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
    path: '/404',
    component: NotFound,
  },
  {
    path: '*',
    redirect: '/404',
  },
]

const router = new VueRouter({
  mode: 'history',
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) {
      return { selector: to.hash }
    }
    if (savedPosition) {
      return savedPosition
    }
    return { x: 0, y: 0 }
  },
  base: process.env.BASE_URL,
  routes,
})

router.beforeEach((to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!store.state.jwt || !store.state.user) {
      next({ name: 'Auth' })
    } else {
      next()
    }
  } else {
    next() // does not require auth
  }
})

export default router
