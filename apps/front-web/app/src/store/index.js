import Vuex from 'vuex'
import ApiUsers from '@/api/ApiUsers'

const user = JSON.parse(localStorage.getItem('user'))
const jwt = JSON.parse(localStorage.getItem('jwt'))
const isAdult = JSON.parse(localStorage.getItem('isAdult'))
const theme = JSON.parse(localStorage.getItem('theme'))
  ? JSON.parse(localStorage.getItem('theme'))
  : 'auto'

export default Vuex.createStore({
  state: {
    user,
    jwt,
    theme,
    isAdult,
    categoryFilters: [],
    storyOrder: 'rate',
  },
  getters: {
    isLoggedIn: (state) => {
      return state.user && state.jwt
    },
    getUserName: (state) => {
      return state.user.name
    },
    getUserProfilePicture: (state, getters) => {
      return getters.isLoggedIn && state.user.profile_picture
        ? `${
            process.env.VUE_APP_PROJECT_FILE_MANAGER_DSN +
            process.env.VUE_APP_PROJECT_FILE_MANAGER_IMAGE_DIR +
            process.env.VUE_APP_PROJECT_FILE_MANAGER_IMAGE_USER_DIR
          }/${state.user.profile_picture}`
        : null
    },
  },
  actions: {
    async fetchCurrentUser({ commit }) {
      const responseCurrent = await ApiUsers.current(jwt)
      const { user } = responseCurrent
      commit('SET_USER', user)
      localStorage.setItem('user', JSON.stringify(user))

      return user
    },
    async login({ commit, dispatch }, { email, password }) {
      // get jwt
      const responseLogin = await ApiUsers.login(email, password)
      if (responseLogin.ok) {
        const jwt = responseLogin.token
        localStorage.setItem('jwt', JSON.stringify(jwt))
        // get user informations
        dispatch('fetchCurrentUser')
        commit('LOGIN_SUCCESS', { user, jwt })
      } else {
        commit('LOGIN_FAILURE')
      }
    },
    logout({ commit }) {
      localStorage.removeItem('user')
      localStorage.removeItem('jwt')
      commit('LOGOUT')
    },
    updateTheme({ commit }, { theme }) {
      commit('SET_THEME', theme)
      localStorage.setItem('theme', JSON.stringify(theme))
    },
    isAdult({ commit }) {
      commit('IS_ADULT')
      localStorage.setItem('isAdult', true)
    },
    toggleFilter({ state, commit }, { category }) {
      if (!state.categoryFilters.includes(category)) {
        commit('ADD_CATEGORY_FILTER', category)
      } else {
        commit('REMOVE_CATEGORY_FILTER', category)
      }
    },
    updateStoryOrder({ commit }, { storyOrder }) {
      commit('SET_STORY_ORDER', storyOrder)
    },
  },
  mutations: {
    SET_USER(state, user) {
      state.user = user
    },
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
    ADD_CATEGORY_FILTER(state, category) {
      state.categoryFilters.push(category)
    },
    REMOVE_CATEGORY_FILTER(state, category) {
      const index = state.categoryFilters.indexOf(category)
      if (index > -1) {
        state.categoryFilters.splice(index, 1)
      }
    },
    SET_STORY_ORDER(state, storyOrder) {
      state.storyOrder = storyOrder
    },
  },
})
