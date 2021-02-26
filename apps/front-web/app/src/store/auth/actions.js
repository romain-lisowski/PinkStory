import useApiUserLogin from '@/composition/api/user/useApiUserLogin'
import useApiUserCurrent from '@/composition/api/user/useApiUserCurrent'

export default {
  async login({ state, commit, dispatch }, { email, password }) {
    const { response, error } = await useApiUserLogin(this, { email, password })

    if (!error.value) {
      const jwt = response.value.token

      dispatch('fetchCurrentUser', jwt)
      if (state.state.userLoggedIn) {
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
    const { response, error } = await useApiUserCurrent(this, jwt)

    if (!error.value) {
      const userLoggedIn = response.value.user
      commit('SET_USER_LOGGED_IN', userLoggedIn)
      localStorage.setItem('userLoggedIn', JSON.stringify(userLoggedIn))
    } else {
      commit('SET_USER_LOGGED_IN', null)
    }
  },
}
