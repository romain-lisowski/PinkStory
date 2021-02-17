import ApiLanguages from '@/api/ApiLanguages'
import fetchData from '@/composition/api/useFetch'

const baseUrl = process.env.VUE_APP_API_URL

export default {
  async login(email, password) {
    return fetchData('POST', 'account/login', null, {
      email,
      password,
    })
  },

  async signUp(name, gender, email, password, passwordConfirm) {
    return fetchData('POST', 'account/signup', null, {
      name,
      gender,
      email,
      'password[first]': password,
      'password[second]': passwordConfirm,
    })
  },

  async getCurrentUser(jwt = null) {
    return fetchData('GET', 'account', null, null, jwt)
  },

  async updateEmail(jwt, email) {
    const formData = new FormData()
    formData.append('email[first]', email)
    formData.append('email[second]', email)

    return fetchData('PATCH', 'account/update-email', null, {
      "email['first']": email,
      "email['second']": email,
    })
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
    const { languages } = await ApiLanguages.search()

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
