export default {
  SET_JWT({ state }, jwt) {
    state.jwt = jwt
    localStorage.setItem('jwt', JSON.stringify(jwt))
  },
  SET_CURRENT_USER({ state }, currentUser) {
    state.currentUser = currentUser
    localStorage.setItem('currentUser', JSON.stringify(currentUser))
  },
  SIGN_OUT({ state }) {
    state.currentUser = null
    state.jwt = null
    localStorage.removeItem('currentUser')
    localStorage.removeItem('jwt')
  },
}
