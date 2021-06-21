import { ref } from 'vue'

const baseUrl = process.env.VUE_APP_API_URL

export default (method, uri, params = null, jwt = null) => {
  const ok = ref(null)
  const response = ref(null)
  const error = ref(null)
  const loading = ref(false)

  /**
   * Add params to urlSearchParams.
   * Arrays are transform into query string parameters like :
   * {arr: [a, b, c]} -> arr[]=a&arr[]=b&arr[]=c
   */
  const addParamsToUrlSearchParams = (urlSearchParams, params) => {
    Object.keys(params).forEach((paramKey) => {
      if (Array.isArray(params[paramKey])) {
        params[paramKey].forEach((param) => {
          urlSearchParams.append(`${paramKey}[]`, param)
        })
      } else {
        urlSearchParams.append(paramKey, params[paramKey])
      }
    })

    return urlSearchParams
  }

  /**
   * GET parameters (query string params)
   */
  const getUrlSearchParams = () => {
    const urlSearchParams = new URLSearchParams()

    // default GET parameter
    urlSearchParams.append('_locale', 'fr')

    // On a GET request, params are query string params
    if (method === 'GET' && params) {
      addParamsToUrlSearchParams(urlSearchParams, params)
    }

    return urlSearchParams
  }

  const getUrl = () => {
    const url = new URL(`${baseUrl}/${uri}?_locale=fr`)
    url.search = getUrlSearchParams()
    return url
  }

  const getOptions = () => {
    const options = {
      method,
      headers: { Authorization: jwt ? `Bearer ${jwt}` : null },
    }

    // GET request can't have a body
    if (method !== 'GET' && params !== null) {
      options.body = JSON.stringify(params)
    }

    return options
  }

  // add array of form field violations
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
