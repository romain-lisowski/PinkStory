import { useStore } from 'vuex'

const baseUrl = process.env.VUE_APP_API_URL

export default {
  async search(order, sort) {
    const store = useStore()
    const { jwt } = store.state
    const params = {
      _locale: 'fr',
      order,
      sort,
    }

    const url = new URL(`${baseUrl}/story/search`)
    url.search = new URLSearchParams(params).toString()

    const response = await fetch(url, {
      headers: { Authorization: `Bearer ${jwt}` },
    })

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },
}
