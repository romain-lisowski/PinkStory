import { reactive, toRefs } from 'vue'

const baseUrl = process.env.VUE_APP_API_URL

export default (method, uri, body = null, jwt = null) => {
  const data = reactive({
    ok: null,
    response: null,
    error: null,
    formViolations: [],
    isLoading: false,
  })

  const getUrl = () => {
    return new URL(`${baseUrl}/${uri}?_locale=fr`)
  }

  const getOptions = () => {
    const options = {
      method,
      headers: { Authorization: jwt ? `Bearer ${jwt}` : null },
    }

    // GET request can't have a body
    if (body !== null) {
      options.body = JSON.stringify(body)
    }

    return options
  }

  // format array of form field violations
  const addFormViolations = () => {
    if (
      'exception' in data.response &&
      'violations' in data.response.exception
    ) {
      data.error.formViolations = []
      data.response.exception.violations.forEach((violation) => {
        data.error.formViolations.push({
          field: violation.property_path,
          message: violation.message,
        })
      })
    }
  }

  const fetchData = async () => {
    data.isLoading = true

    try {
      const res = await fetch(getUrl(), getOptions())
      data.ok = res.ok
      data.response = await res.json()

      if (!data.ok) {
        throw new Error('Bad response from server')
      }
    } catch (e) {
      data.error = await e
      addFormViolations()
    } finally {
      data.isLoading = false
    }
  }

  return { ...toRefs(data), fetchData }
}
