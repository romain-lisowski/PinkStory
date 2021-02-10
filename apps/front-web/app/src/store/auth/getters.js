export default {
  getJwt: (state) => {
    return state.jwt
  },

  isLoggedIn: (state) => {
    return (
      typeof state.userLoggedIn === 'object' &&
      state.userLoggedIn !== null &&
      state.jwt !== 'undefined'
    )
  },
}
