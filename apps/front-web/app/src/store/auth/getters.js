export default {
  getJwt: ({ state }) => {
    return state.jwt
  },

  getCurrentUser: ({ state }) => {
    return state.currentUser
  },

  isSignedIn: ({ state }) => {
    return (
      typeof state.currentUser === 'object' &&
      state.currentUser !== null &&
      state.jwt !== 'undefined'
    )
  },
}
