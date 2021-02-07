let jwt = null
try {
  jwt = JSON.parse(localStorage.getItem('jwt'))
} catch (e) {
  localStorage.removeItem('jwt')
}

let userLoggedIn = null
try {
  userLoggedIn = JSON.parse(localStorage.getItem('userLoggedIn'))
} catch (e) {
  localStorage.removeItem('userLoggedIn')
}

export default {
  state: {
    jwt,
    userLoggedIn,
  },
}
