// import { useStore } from 'vuex'

const baseUrl = process.env.VUE_APP_API_URL

export default {
  async fetch(method, uri, queryParams = null, formParams = null) {
    // const store = useStore()
    // store.dispatch('site/showLoadingOverlay')

    const response = await fetch(
      this.getUrlWithSearchParams(method, uri, queryParams),
      {
        method,
        body: this.getFormData(formParams),
        headers: this.getHeaders(),
      }
    )

    // store.dispatch('site/hideLoadingOverlay')
    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  getUrlWithSearchParams(method, uri, queryParams) {
    const url = new URL(`${baseUrl}/${uri}`)

    url.search = this.getSearchParams(method, queryParams)
    return url
  },

  getSearchParams(method, queryParams) {
    const searchParams = new URLSearchParams(queryParams)

    if (queryParams) {
      this.transformArraySearchParams(queryParams, searchParams)
    }

    if (method === 'POST' || method === 'PATCH') {
      searchParams.append('_method', 'POST')
    }

    searchParams.append('_locale', 'fr')

    return searchParams.toString()
  },

  /**
   * Transform array parameter of URLSearchParams into multiple entries
   * ex: {arrayEx: [a, b, c]} -> arrayEx[]=a&arrayEx[]=b&arrayEx[]=c
   */
  transformArraySearchParams(queryParams, searchParams) {
    Object.keys(queryParams).forEach((queryParamKey) => {
      if (Array.isArray(queryParams[queryParamKey])) {
        queryParams[queryParamKey].forEach((p) => {
          searchParams.append(`${queryParamKey}[]`, p)
        })
        searchParams.delete(queryParamKey)
      }
    })

    return searchParams
  },

  getFormData(formParams) {
    let formData = null

    if (formParams && Object.keys(formParams).length > 0) {
      formData = new FormData()
      Object.keys(formParams).forEach((formParamKey) => {
        formData.append(formParamKey, formParams[formParamKey])
      })
    }

    return formData
  },

  getHeaders() {
    const jwt = null
    // const store = useStore()
    // const { jwt } = store.state.auth.state
    return { Authorization: jwt ? `Bearer ${jwt}` : null }
  },
}
