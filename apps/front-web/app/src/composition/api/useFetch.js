import { ref } from 'vue'

const baseUrl = process.env.VUE_APP_API_URL

export default (method, uri, body = null, jwt = null) => {
  const ok = ref(null)
  const response = ref(null)
  const error = ref(null)
  const loading = ref(false)

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
      'exception' in response.value &&
      'violations' in response.value.exception
    ) {
      error.value.formViolations = []
      response.value.exception.violations.forEach((violation) => {
        error.value.formViolations.push({
          field: violation.property_path,
          message: violation.message,
        })
      })
    }
  }

  const fetchData = async () => {
    loading.value = true

    try {
      const res = await fetch(getUrl(), getOptions())
      ok.value = res.ok
      response.value = await res.json()

      if (!ok.value) {
        throw new Error('Bad response from server')
      }
    } catch (e) {
      error.value = await e
      addFormViolations()
    } finally {
      loading.value = false
    }
  }

  return { ok, response, error, loading, fetchData }
}
