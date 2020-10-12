import Vue from 'vue'
import Vuex from 'vuex'
import ApiUsers from '@/api/ApiUsers'

Vue.use(Vuex)

const user = JSON.parse(localStorage.getItem('user'))
const jwt = JSON.parse(localStorage.getItem('jwt'))
const isAdult = JSON.parse(localStorage.getItem('isAdult'))
const theme = JSON.parse(localStorage.getItem('theme'))

export default new Vuex.Store({
  state: {
    user,
    jwt,
    theme,
    isAdult,
  },
  actions: {
    async login({ commit }, { email, password }) {
      // get jwt
      const responseLogin = await ApiUsers.login(email, password)
      if (responseLogin.ok) {
        const jwt = responseLogin.token
        // get user
        const responseCurrent = await ApiUsers.current(jwt)
        const { name } = responseCurrent.user
        localStorage.setItem('user', JSON.stringify({ email, name }))
        localStorage.setItem('jwt', JSON.stringify(jwt))
        commit('loginSuccess', { email, name, jwt })
      } else {
        commit('loginFailure')
      }
    },
    logout({ commit }) {
      localStorage.removeItem('user')
      localStorage.removeItem('jwt')
      commit('logout')
    },
    changeTheme({ commit }, { theme }) {
      commit('setTheme', theme)
      localStorage.setItem('theme', JSON.stringify(theme))
    },
    isAdult({ commit }) {
      commit('setIsAdult')
      localStorage.setItem('isAdult', true)
    },
  },
  mutations: {
    loginSuccess(state, { email, name, jwt }) {
      state.user = { email, name }
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
    setTheme(state, theme) {
      state.theme = theme
    },
    setIsAdult(state) {
      state.isAdult = true
    },
  },
})
