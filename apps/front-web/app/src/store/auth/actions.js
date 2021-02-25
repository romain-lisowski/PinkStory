import ApiUsers from '@/api/ApiUsers'
import useApiUserLogin from '@/composition/api/user/useApiUserLogin'

export default {
  async login({ context, commit, dispatch }, { email, password }) {
    const responseLogin = await useApiUserLogin(this, email, password)

    if (responseLogin.ok) {
      const jwt = responseLogin.token

      dispatch('fetchCurrentUser', jwt)
      if (context.state.userLoggedIn) {
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
  async fetchCurrentUser({ commit }, jwt = null) {
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
