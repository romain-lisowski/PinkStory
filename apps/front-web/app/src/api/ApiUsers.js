import ApiLanguages from '@/api/ApiLanguages'

const baseUrl = process.env.VUE_APP_API_URL

export default {
  async login(email, password) {
    const formData = new FormData()
    formData.append('email', email)
    formData.append('password', password)

    const response = await fetch(`${baseUrl}/account/login?_method=POST`, {
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

    const response = await fetch(`${baseUrl}/account/signup?_method=POST`, {
      method: 'POST',
      body: formData,
    })

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async getCurrentUser(jwt) {
    const response = await fetch(`${baseUrl}/account`, {
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
      `${baseUrl}/account/update-email?_method=PATCH`,
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
      `${baseUrl}/account/update-password?_method=PATCH`,
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
    const { languages } = await ApiLanguages.search(jwt)

    const formData = new FormData()
    formData.append('name', name)
    formData.append('language_id', languages[1].id)

    const response = await fetch(
      `${baseUrl}/account/update-information?_method=PATCH`,
      {
        method: 'POST',
        body: formData,
        headers: { Authorization: `Bearer ${jwt}` },
      }
    )

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async updateImage(jwt, file) {
    const formData = new FormData()
    formData.append('image', file)

    const response = await fetch(
      `${baseUrl}/account/update-image?_method=PATCH`,
      {
        method: 'POST',
        body: formData,
        headers: { Authorization: `Bearer ${jwt}` },
      }
    )

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async deleteImage(jwt) {
    const response = await fetch(`${baseUrl}/account/delete-image`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${jwt}` },
    })

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },
}
