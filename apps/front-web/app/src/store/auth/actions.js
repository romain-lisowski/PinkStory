import useApiUserCurrent from '@/composition/api/user/useApiUserCurrent'

export default {
  async signIn({ commit, dispatch }, jwt) {
    const apiUserCurrentFetch = await useApiUserCurrent(this, jwt)

    if (apiUserCurrentFetch.ok.value) {
      commit('SET_CURRENT_USER', apiUserCurrentFetch.response.value.user)
      commit('SET_JWT', jwt)
    } else {
      dispatch('signOut')
    }
  },
  signOut({ commit }) {
    commit('SIGN_OUT')
  },
}
