const baseUrl = process.env.VUE_APP_API_URL

export default {
  async login(email, password) {
    const formData = new FormData()
    formData.append('email', email)
    formData.append('password', password)

    const response = await fetch(`${baseUrl}/users/login?_method=POST`, {
      method: 'POST',
      body: formData,
    })

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async signUp(name, email, password, passwordConfirm) {
    const formData = new FormData()
    formData.append('name', name)
    formData.append('email', email)
    formData.append('password[first]', password)
    formData.append('password[second]', passwordConfirm)

    const response = await fetch(`${baseUrl}/users/signup?_method=POST`, {
      method: 'POST',
      body: formData,
    })

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async current(jwt) {
    const response = await fetch(`${baseUrl}/users/current`, {
      headers: { Authorization: `Bearer ${jwt}` },
    })

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async updateEmail(jwt, email) {
    const formData = new FormData()
    formData.append('email[first]', email)
    formData.append('email[second]', email)

    const response = await fetch(
      `${baseUrl}/users/update-email?_method=PATCH`,
      {
        method: 'POST',
        body: formData,
        headers: { Authorization: `Bearer ${jwt}` },
      }
    )

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async updatePassword(jwt, passwordOld, passwordNew, passwordNewConfirm) {
    const formData = new FormData()
    formData.append('old_password', passwordOld)
    formData.append('password[first]', passwordNew)
    formData.append('password[second]', passwordNewConfirm)

    const response = await fetch(
      `${baseUrl}/users/update-password?_method=PATCH`,
      {
        method: 'POST',
        body: formData,
        headers: { Authorization: `Bearer ${jwt}` },
      }
    )

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async updateInformation(jwt, name) {
    const formData = new FormData()
    formData.append('name', name)

    const response = await fetch(
      `${baseUrl}/users/update-information?_method=PATCH`,
      {
        method: 'POST',
        body: formData,
        headers: { Authorization: `Bearer ${jwt}` },
      }
    )

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async updateProfilePicture(jwt, file) {
    const formData = new FormData()
    formData.append('profile_picture', file)

    const response = await fetch(
      `${baseUrl}/users/update-profile-picture?_method=PATCH`,
      {
        method: 'POST',
        body: formData,
        headers: { Authorization: `Bearer ${jwt}` },
      }
    )

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async deleteProfilePicture(jwt) {
    const response = await fetch(`${baseUrl}/users/remove-profile-picture`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${jwt}` },
    })

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },
}
