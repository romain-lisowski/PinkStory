import { reactive, toRefs } from 'vue'

const baseUrl = process.env.VUE_APP_API_URL

export default (method, uri, body = null, jwt = null) => {
  const state = reactive({ response: null, error: null, isLoading: false })

  const defaultSearchParams = (method) => {
    const searchParams = new URLSearchParams()

    if (!['GET', 'POST', 'PATCH', 'DELETE'].includes(method)) {
      throw new Error('Method invalid', method)
    }

    if (method === 'POST' || method === 'PATCH') {
      searchParams.append('_method', 'POST')
    } else {
      searchParams.append('_method', 'GET')
    }

    searchParams.append('_locale', 'fr')

    return searchParams.toString()
  }

  // Create URL and add default search params
  const urlWithDefaultSearchParams = (method, uri) => {
    if (typeof uri !== 'string') {
      throw new Error('URI must be a string', uri)
    }

    const url = new URL(`${baseUrl}/${uri}`)
    url.search = defaultSearchParams(method)
    return url
  }

  const fetchData = async () => {
    state.isLoading = true

    const url = urlWithDefaultSearchParams(method, uri)
    const options = {
      method,
      headers: { Authorization: jwt ? `Bearer ${jwt}` : null },
    }

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
