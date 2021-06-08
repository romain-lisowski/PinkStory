import useApiUserLogin from '@/composition/api/user/useApiUserLogin'
import useApiUserCurrent from '@/composition/api/user/useApiUserCurrent'

export default {
  async login({ commit, dispatch }, { email, password }) {
    const { response, error } = await useApiUserLogin(this, { email, password })

    if (!error.value) {
      const jwt = response.value.access_token.id
      dispatch('fetchCurrentUser', jwt)
    } else {
      commit('SET_JWT', null)
    }
  },
  logout({ commit }) {
    localStorage.removeItem('userLoggedIn')
    localStorage.removeItem('jwt')
    commit('LOGOUT')
  },
  async fetchCurrentUser({ commit, dispatch }, jwt) {
    const { response, error } = await useApiUserCurrent(this, jwt)

    if (!error.value) {
      const userLoggedIn = response.value.user
      localStorage.setItem('userLoggedIn', JSON.stringify(userLoggedIn))
      commit('SET_USER_LOGGED_IN', userLoggedIn)
      localStorage.setItem('jwt', JSON.stringify(jwt))
      commit('SET_JWT', jwt)
    } else {
      dispatch('logout')
    }
  },
}
