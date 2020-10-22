import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store'
import Home from '@/pages/HomePage/HomePage.vue'
import User from '@/pages/UserPage/UserPage.vue'
import Story from '@/pages/StoryPage/StoryPage.vue'
import NotFound from '@/pages/NotFoundPage/NotFoundPage.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },
  {
    path: '/user',
    name: 'User',
    component: User,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/story',
    name: 'Story',
    component: Story,
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
