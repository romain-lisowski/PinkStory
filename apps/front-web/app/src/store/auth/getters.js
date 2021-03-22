export default {
  getJwt: ({ state }) => {
    return state.jwt
  },

  getUserLoggedIn: ({ state }) => {
    return state.userLoggedIn
  },

  isLoggedIn: ({ state }) => {
    return (
      typeof state.userLoggedIn === 'object' &&
      state.userLoggedIn !== null &&
      state.jwt !== 'undefined'
    )
  },
}
