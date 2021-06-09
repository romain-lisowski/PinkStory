import { reactive, toRefs } from 'vue'

const baseUrl = process.env.VUE_APP_API_URL

export default (method, uri, body = null, jwt = null) => {
  const state = reactive({ response: null, error: null, isLoading: false })

  const fetchData = async () => {
    state.isLoading = true

    const url = new URL(`${baseUrl}/${uri}?_locale=fr`)
    const options = {
      method,
      headers: { Authorization: jwt ? `Bearer ${jwt}` : null },
    }

    // GET request can't have a body
    if (body !== null) {
      options.body = JSON.stringify(body)
    }

    try {
      const res = await fetch(url, options)
      state.response = await res.json()
    } catch (errors) {
      state.error = await errors
      console.error(errors)
    } finally {
      state.isLoading = false
    }
  }

  return { ...toRefs(state), fetchData }
}
