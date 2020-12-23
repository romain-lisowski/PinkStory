import Vuex from 'vuex'
import ApiUsers from '@/api/ApiUsers'

let jwt = null
try {
  jwt = JSON.parse(localStorage.getItem('jwt'))
} catch (e) {
  localStorage.removeItem('jwt')
}

let user = null
try {
  user = JSON.parse(localStorage.getItem('user'))
} catch (e) {
  localStorage.removeItem('user')
}

let isAdult = null
try {
  isAdult = JSON.parse(localStorage.getItem('isAdult'))
} catch (e) {
  localStorage.removeItem('isAdult')
}

let theme = null
try {
  theme = JSON.parse(localStorage.getItem('theme'))
} catch (e) {
  localStorage.removeItem('theme')
}

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
    userName: (state) => {
      return state.user.name
    },
    userImage: (state, getters) => {
      return getters.isLoggedIn && state.user.image_url
        ? state.user.image_url
        : null
    },
  },
  actions: {
    async login({ commit, dispatch }, { email, password }) {
      const responseLogin = await ApiUsers.login(email, password)

      if (responseLogin.ok) {
        const jwt = responseLogin.token
        localStorage.setItem('jwt', JSON.stringify(jwt))

        dispatch('fetchCurrentUser')
        commit('LOGIN_SUCCESS', { user, jwt })
      } else {
        commit('LOGIN_FAILURE')
      }
    },
    async fetchCurrentUser({ commit }) {
      const { user } = await ApiUsers.getCurrentUser(jwt)
      commit('SET_USER', user)
      localStorage.setItem('user', JSON.stringify(user))

      return user
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
