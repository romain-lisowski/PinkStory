import { useStore } from 'vuex'

const baseUrl = process.env.VUE_APP_API_URL

export default {
  async search(jwt, params = {}) {
    const store = useStore()
    store.dispatch('site/showLoadingOverlay')
    const url = new URL(`${baseUrl}/story/search`)
    const searchParams = new URLSearchParams(params)
    searchParams.append('_locale', 'fr')

    // transform categoryIds into story_theme_ids[]
    if (params.categoryIds && params.categoryIds.length > 0) {
      params.categoryIds.forEach((categoryId) => {
        searchParams.append('story_theme_ids[]', categoryId)
      })
    }
    searchParams.delete('categoryIds')

    url.search = searchParams.toString()
    const response = await fetch(url, {
      headers: {
        Authorization: jwt ? `Bearer ${jwt}` : null,
      },
    })

    const responseJson = await response.json()
    store.dispatch('site/hideLoadingOverlay')
    return { ok: response.ok, status: response.status, ...responseJson }
  },

  async get(jwt, storyId) {
    const store = useStore()
    store.dispatch('site/showLoadingOverlay')
    const url = new URL(`${baseUrl}/story/${storyId}`)
    const searchParams = new URLSearchParams()
    searchParams.append('_locale', 'fr')
    url.search = searchParams.toString()

    const response = await fetch(url, {
      headers: { Authorization: jwt ? `Bearer ${jwt}` : null },
    })

    const responseJson = await response.json()
    store.dispatch('site/hideLoadingOverlay')
    return { ok: response.ok, status: response.status, ...responseJson }
  },
}
