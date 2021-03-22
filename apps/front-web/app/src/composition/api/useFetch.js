import { reactive, toRefs } from 'vue'
// import store from './store.js'

const baseUrl = process.env.VUE_APP_API_URL

export default (
  method,
  uri,
  queryParams = null,
  formParams = null,
  jwt = null
) => {
  const state = reactive({ response: null, error: null, isLoading: false })

  /**
   * Transform array parameter of URLSearchParams into multiple entries
   * ex: {arrayEx: [a, b, c]} -> arrayEx[]=a&arrayEx[]=b&arrayEx[]=c
   */
  const transformArraySearchParams = (queryParams, searchParams) => {
    Object.keys(queryParams).forEach((queryParamKey) => {
      if (Array.isArray(queryParams[queryParamKey])) {
        queryParams[queryParamKey].forEach((p) => {
          searchParams.append(`${queryParamKey}[]`, p)
        })
        searchParams.delete(queryParamKey)
      }
    })

    return searchParams
  }

  const getSearchParams = (method, queryParams) => {
    const searchParams = queryParams
      ? new URLSearchParams(queryParams)
      : new URLSearchParams()

    if (queryParams) {
      transformArraySearchParams(queryParams, searchParams)
    }

    if (method === 'POST' || method === 'PATCH') {
      searchParams.append('_method', 'POST')
    }

    searchParams.append('_locale', 'fr')

    return searchParams.toString()
  }

  const getUrlWithSearchParams = (method, uri, queryParams) => {
    const url = new URL(`${baseUrl}/${uri}`)

    url.search = getSearchParams(method, queryParams)
    return url
  }

  const getFormData = (formParams) => {
    let formData = null

    if (formParams && Object.keys(formParams).length > 0) {
      formData = new FormData()
      Object.keys(formParams).forEach((formParamKey) => {
        formData.append(formParamKey, formParams[formParamKey])
        console.log(formParamKey, formParams[formParamKey])
      })
    }

    return formData
  }

  const getHeaders = (jwtParam) => {
    return { Authorization: jwtParam ? `Bearer ${jwtParam}` : null }
  }

  const fetchData = async () => {
    state.isLoading = true

    if (!['GET', 'POST', 'PATCH', 'DELETE'].includes(method)) {
      throw new Error('Method invalid', method)
    }

    if (typeof uri !== 'string') {
      throw new Error('Uri must be a string', uri)
    }

    try {
      const res = await fetch(
        getUrlWithSearchParams(method, uri, queryParams),
        {
          method,
          body: getFormData(formParams),
          headers: getHeaders(jwt),
        }
      )
      state.response = await res.json()
    } catch (errors) {
      state.error = errors
    } finally {
      state.isLoading = false
    }
  }

  return { ...toRefs(state), fetchData }
}
