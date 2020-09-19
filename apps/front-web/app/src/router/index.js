import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store'
import Auth from '@/views/Auth.vue'
import User from '@/views/User.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Auth',
    component: Auth,
  },
  {
    path: '/user',
    name: 'User',
    component: User,
    meta: {
      requiresAuth: true,
    },
  },
]

const router = new VueRouter({
  mode: 'history',
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
