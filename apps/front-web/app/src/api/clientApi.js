import store from '@/store'

const baseUrl = process.env.VUE_APP_API_URL

export default {
  async fetch(uri, searchParams = {}) {
    store.dispatch('site/showLoadingOverlay')

    const response = await fetch(
      this.getUrlWithSearchParams(uri, searchParams),
      {
        headers: this.getHeaders(),
      }
    )

    store.dispatch('site/hideLoadingOverlay')
    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  getUrlWithSearchParams(uri, searchParams) {
    const url = new URL(`${baseUrl}/${uri}`)
    searchParams.append('_locale', 'fr')
    url.search = searchParams.toString()
    return url
  },

  getHeaders() {
    const { jwt } = store.state.auth.state
    return { Authorization: jwt ? `Bearer ${jwt}` : null }
  },
}
