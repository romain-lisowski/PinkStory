export default {
  isLoggedIn: (state) => {
    return (
      typeof state.userLoggedIn === 'object' &&
      state.userLoggedIn !== null &&
      state.jwt !== 'undefined'
    )
  },
}
