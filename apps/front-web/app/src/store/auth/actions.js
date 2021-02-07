import ApiUsers from '@/api/ApiUsers'

export default {
  async login({ state, commit, dispatch }, { email, password }) {
    const responseLogin = await ApiUsers.login(email, password)

    if (responseLogin.ok) {
      const jwt = responseLogin.token

      dispatch('fetchCurrentUser', jwt)
      if (state.userLoggedIn) {
        localStorage.setItem('jwt', JSON.stringify(jwt))
        commit('SET_JWT', jwt)
      }
    } else {
      commit('SET_JWT', null)
    }
  },
  logout({ commit }) {
    localStorage.removeItem('userLoggedIn')
    localStorage.removeItem('jwt')
    commit('LOGOUT')
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
}
