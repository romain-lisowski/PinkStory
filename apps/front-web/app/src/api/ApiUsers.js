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

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
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

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async current(jwt) {
    const response = await fetch(`${baseUrl}/users/current`, {
      headers: { ...dataHeaders, Authorization: `Bearer ${jwt}` },
    })

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async updateEmail(jwt, email) {
    const response = await fetch(`${baseUrl}/users/update-email`, {
      method: 'PATCH',
      body: JSON.stringify({
        email: {
          first: email,
          second: email,
        },
      }),
      headers: { ...dataHeaders, Authorization: `Bearer ${jwt}` },
    })

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async updatePassword(jwt, passwordOld, passwordNew, passwordNewConfirm) {
    const response = await fetch(`${baseUrl}/users/update-password`, {
      method: 'PATCH',
      body: JSON.stringify({
        old_password: passwordOld,
        password: {
          first: passwordNew,
          second: passwordNewConfirm,
        },
      }),
      headers: { ...dataHeaders, Authorization: `Bearer ${jwt}` },
    })

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async updateInformation(jwt, name) {
    const response = await fetch(`${baseUrl}/users/update-information`, {
      method: 'PATCH',
      body: JSON.stringify({
        name,
      }),
      headers: { ...dataHeaders, Authorization: `Bearer ${jwt}` },
    })

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },
}
