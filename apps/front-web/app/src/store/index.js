import Vuex from 'vuex'
import ApiUsers from '@/api/ApiUsers'

let jwt = null
try {
  jwt = JSON.parse(localStorage.getItem('jwt'))
} catch (e) {
  localStorage.removeItem('jwt')
}

let userLoggedIn = null
try {
  userLoggedIn = JSON.parse(localStorage.getItem('userLoggedIn'))
} catch (e) {
  localStorage.removeItem('userLoggedIn')
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
    jwt,
    userLoggedIn,
    theme,
    isAdult,
    searchCategoryIds: [],
    searchOrder: '',
  },
  getters: {
    isLoggedIn: (state) => {
      return (
        typeof state.userLoggedIn === 'object' &&
        state.userLoggedIn !== null &&
        state.jwt !== 'undefined'
      )
    },
  },
  actions: {
    async login({ commit, dispatch }, { email, password }) {
      const responseLogin = await ApiUsers.login(email, password)

      if (responseLogin.ok) {
        const jwt = responseLogin.token

        dispatch('fetchCurrentUser', jwt)
        if (userLoggedIn) {
          localStorage.setItem('jwt', JSON.stringify(jwt))
          commit('SET_JWT', jwt)
        }
      } else {
        commit('SET_JWT', null)
      }
    },
    async fetchCurrentUser({ commit }, jwt) {
      const responseUserLoggedIn = await ApiUsers.getCurrentUser(jwt)

      if (responseUserLoggedIn.ok) {
        const userLoggedIn = responseUserLoggedIn.user
        commit('SET_USER_LOGGED_IN', userLoggedIn)
        localStorage.setItem('userLoggedIn', JSON.stringify(userLoggedIn))
      } else {
        commit('SET_USER_LOGGED_IN', null)
      }
    },
    logout({ commit }) {
      localStorage.removeItem('userLoggedIn')
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
    toggleSearchCategory({ state, commit }, { categoryId }) {
      if (!state.searchCategoryIds.includes(categoryId)) {
        commit('ADD_SEARCH_CATEGORY', categoryId)
      } else {
        commit('REMOVE_SEARCH_CATEGORY', categoryId)
      }
    },
    updateSearchOrder({ commit }, { searchOrder }) {
      commit('SET_SEARCH_ORDER', searchOrder)
    },
  },
  mutations: {
    SET_JWT(state, jwt) {
      state.jwt = jwt
    },
    SET_USER_LOGGED_IN(state, userLoggedIn) {
      state.userLoggedIn = userLoggedIn
    },
    LOGOUT(state) {
      state.userLoggedIn = null
      state.jwt = null
    },
    SET_THEME(state, theme) {
      state.theme = theme
    },
    IS_ADULT(state) {
      state.isAdult = true
    },
    ADD_SEARCH_CATEGORY(state, categoryId) {
      state.searchCategoryIds.push(categoryId)
    },
    REMOVE_SEARCH_CATEGORY(state, categoryId) {
      const index = state.searchCategoryIds.indexOf(categoryId)
      if (index > -1) {
        state.searchCategoryIds.splice(index, 1)
      }
    },
    SET_SEARCH_ORDER(state, searchOrder) {
      state.searchOrder = searchOrder
    },
  },
})
