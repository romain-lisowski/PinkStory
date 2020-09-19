import Vue from 'vue'
import Vuex from 'vuex'
import ApiUsers from '@/api/ApiUsers'

Vue.use(Vuex)

const user = JSON.parse(localStorage.getItem('user'))
const jwt = JSON.parse(localStorage.getItem('jwt'))

export default new Vuex.Store({
  state: {
    user,
    jwt,
  },
  actions: {
    async login({ commit }, { email, password }) {
      const response = await ApiUsers.login(email, password)
      if (response) {
        const jwt = response.token
        localStorage.setItem('user', JSON.stringify({ email, password }))
        localStorage.setItem('jwt', JSON.stringify(jwt))
        commit('loginSuccess', { email, password, jwt })
      } else {
        commit('loginFailure')
      }
    },
    logout({ commit }) {
      localStorage.removeItem('user')
      localStorage.removeItem('jwt')
      commit('logout')
    },
  },
  mutations: {
    loginSuccess(state, { email, password, jwt }) {
      state.user = { email, password }
      state.jwt = jwt
    },
    loginFailure(state) {
      state.user = null
      state.jwt = null
    },
    logout(state) {
      state.user = null
      state.jwt = null
    },
  },
})
