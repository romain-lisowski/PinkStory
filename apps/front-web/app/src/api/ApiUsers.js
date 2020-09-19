const baseUrl = process.env.VUE_APP_API_URL
const dataHeaders = { 'Content-type': 'application/json; charset=UTF-8' }

export default {
  async login(email, password) {
    const response = await fetch(`${baseUrl}/users/login`, {
      method: 'POST',
      body: JSON.stringify({
        email,
        password,
      }),
      headers: dataHeaders,
    })

    return response.json()
  },

  async signUp(name, email, password, passwordConfirm) {
    const response = await fetch(`${baseUrl}/users/signup`, {
      method: 'POST',
      body: JSON.stringify({
        name,
        email,
        password: {
          first: password,
          second: passwordConfirm,
        },
      }),
      headers: dataHeaders,
    })

    return response.json()
  },

  async current() {
    const response = await fetch(`${baseUrl}/users/current`)
    return response.json()
  },

  async updateEmail(email) {
    const response = await fetch(`${baseUrl}/users/update-email`, {
      method: 'PATCH',
      body: JSON.stringify({
        email: {
          first: email,
          second: email,
        },
      }),
      headers: dataHeaders,
    })

    return response.json()
  },

  async updatePassword(passwordOld, passwordNew, passwordNewConfirm) {
    const response = await fetch(`${baseUrl}/users/update-password`, {
      method: 'PATCH',
      body: JSON.stringify({
        old_password: passwordOld,
        password: {
          first: passwordNew,
          second: passwordNewConfirm,
        },
      }),
      headers: dataHeaders,
    })

    return response.json()
  },

  async updateInformation(name) {
    const response = await fetch(`${baseUrl}/users/update-information`, {
      method: 'PATCH',
      body: JSON.stringify({
        name,
      }),
      headers: dataHeaders,
    })

    return response.json()
  },
}
