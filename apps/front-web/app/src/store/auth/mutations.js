export default {
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
}
