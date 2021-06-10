let jwt = null
try {
  jwt = JSON.parse(localStorage.getItem('jwt'))
} catch (e) {
  localStorage.removeItem('jwt')
}

let currentUser = null
try {
  currentUser = JSON.parse(localStorage.getItem('currentUser'))
} catch (e) {
  localStorage.removeItem('currentUser')
}

export default {
  state: {
    jwt,
    currentUser,
  },
}
