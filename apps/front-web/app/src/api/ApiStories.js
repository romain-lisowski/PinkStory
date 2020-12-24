import { useStore } from 'vuex'

const baseUrl = process.env.VUE_APP_API_URL

export default {
  async search(params = {}) {
    const store = useStore()
    const url = new URL(`${baseUrl}/story/search`)
    const queryParams = params
    queryParams._locale = 'fr'
    url.search = new URLSearchParams(queryParams).toString()

    const response = await fetch(url, {
      headers: { Authorization: `Bearer ${store.state.jwt}` },
    })

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },
}
