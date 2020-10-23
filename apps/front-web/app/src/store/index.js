import Vue from 'vue'
import Vuex from 'vuex'
import ApiUsers from '@/api/ApiUsers'

Vue.use(Vuex)

const user = JSON.parse(localStorage.getItem('user'))
const jwt = JSON.parse(localStorage.getItem('jwt'))
const isAdult = JSON.parse(localStorage.getItem('isAdult'))
const theme = JSON.parse(localStorage.getItem('theme'))
  ? JSON.parse(localStorage.getItem('theme'))
  : 'auto'

export default new Vuex.Store({
  state: {
    user,
    jwt,
    theme,
    isAdult,
  },
  getters: {
    isLoggedIn: (state) => {
      return state.user && state.jwt
    },
    getTheme: (state) => {
      return state.theme ? state.theme : 'auto'
    },
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
        commit('LOGIN_SUCCESS', { email, name, jwt })
      } else {
        commit('LOGIN_FAILURE')
      }
    },
    logout({ commit }) {
      localStorage.removeItem('user')
      localStorage.removeItem('jwt')
      commit('LOGOUT')
    },
    changeTheme({ commit }, { theme }) {
      commit('SET_THEME', theme)
      localStorage.setItem('theme', JSON.stringify(theme))
    },
    isAdult({ commit }) {
      commit('IS_ADULT')
      localStorage.setItem('isAdult', true)
    },
  },
  mutations: {
    LOGIN_SUCCESS(state, { email, name, jwt }) {
      state.user = { email, name }
      state.jwt = jwt
    },
    LOGIN_FAILURE(state) {
      state.user = null
      state.jwt = null
    },
    LOGOUT(state) {
      state.user = null
      state.jwt = null
    },
    SET_THEME(state, theme) {
      state.theme = theme
    },
    IS_ADULT(state) {
      state.isAdult = true
    },
  },
})
